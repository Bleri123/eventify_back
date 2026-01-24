<?php

namespace App\Http\Controllers;

use App\Models\screenings;
use App\Models\bookings;
use App\Models\User;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    /**
     * Get all screenings with pagination
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 10);
            $page = $request->get('page', 1);
            $search = $request->get('search', '');

            $query = screenings::with(['movie', 'showroom']);

            // Filter by movie name if search query is provided
            if (!empty($search)) {
                $query->whereHas('movie', function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%');
                });
            }

            $screenings = $query->orderBy('start_time', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            $data = $screenings->map(function ($screening) {
                return [
                    'id' => $screening->id,
                    'showroom' => $screening->showroom?->name ?? 'N/A',
                    'movie_name' => $screening->movie?->title ?? 'N/A',
                    'time' => date('H:i', strtotime($screening->start_time)),
                    'date' => date('d/m/Y', strtotime($screening->start_time)),
                ];
            });

            return response()->json([
                'data' => $data,
                'current_page' => $screenings->currentPage(),
                'last_page' => $screenings->lastPage(),
                'per_page' => $screenings->perPage(),
                'total' => $screenings->total(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error fetching screenings',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get bookings for a specific screening from reports table
     */
    public function show(Request $request, $id): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 10);
            $page = $request->get('page', 1);

            // Find screening
            $screening = screenings::with(['movie', 'showroom'])->findOrFail($id);

            // Get reports for this screening from reports table
            $reportsQuery = Report::where('screening_id', $id)
                ->whereNotIn('status', ['cancelled', 'expired'])
                ->orderBy('booked_at', 'desc');

            $reportsCount = $reportsQuery->count();
            $totalPages = ceil($reportsCount / $perPage);
            $offset = ($page - 1) * $perPage;

            $reportsList = $reportsQuery->skip($offset)
                ->take($perPage)
                ->get();

            // Format the data
            $reservedSeats = $reportsList->map(function ($report, $index) use ($offset) {
                try {
                    return [
                        'id' => $offset + $index + 1,
                        'booking_id' => $report->booking_id,
                        'first_name' => $report->first_name ?? 'N/A',
                        'email' => $report->email ?? 'N/A',
                        'seats_reserved' => $report->seats_reserved ?? 0,
                        'row_reserved' => $report->row_reserved ?? 'N/A',
                        'total_price' => floatval($report->total_price ?? 0),
                        'status' => $report->status,
                        'booked_at' => $report->booked_at ? $report->booked_at->format('d/m/Y H:i') : 'N/A',
                    ];
                } catch (\Exception $e) {
                    \Log::error('Report mapping error: ' . $e->getMessage(), [
                        'report_id' => $report->id ?? 'Unknown',
                    ]);

                    return [
                        'id' => $offset + $index + 1,
                        'booking_id' => $report->booking_id ?? 'Unknown',
                        'first_name' => 'N/A',
                        'email' => 'N/A',
                        'seats_reserved' => 0,
                        'row_reserved' => 'N/A',
                        'total_price' => 0,
                        'status' => 'unknown',
                        'booked_at' => 'N/A',
                    ];
                }
            });

            return response()->json([
                'screening' => [
                    'id' => $screening->id,
                    'movie_name' => $screening->movie?->title ?? 'N/A',
                    'showroom' => $screening->showroom?->name ?? 'N/A',
                    'time' => date('H:i', strtotime($screening->start_time)),
                    'date' => date('d/m/Y', strtotime($screening->start_time)),
                    'base_price' => $screening->base_price ?? 0,
                ],
                'data' => $reservedSeats,
                'current_page' => $page,
                'last_page' => $totalPages,
                'per_page' => $perPage,
                'total' => $reportsCount,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Screening not found',
                'screening_id' => $id
            ], 404);
        } catch (\Exception $e) {
            // Try to at least return the screening info even if reports fail
            try {
                $screening = screenings::with(['movie', 'showroom'])->findOrFail($id);
                return response()->json([
                    'screening' => [
                        'id' => $screening->id,
                        'movie_name' => $screening->movie?->title ?? 'N/A',
                        'showroom' => $screening->showroom?->name ?? 'N/A',
                        'time' => date('H:i', strtotime($screening->start_time)),
                        'date' => date('d/m/Y', strtotime($screening->start_time)),
                        'base_price' => $screening->base_price ?? 0,
                    ],
                    'data' => [],
                    'error' => 'Could not fetch report details. Details: ' . $e->getMessage(),
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => 10,
                    'total' => 0,
                ], 200);
            } catch (\Exception $fallback) {
                return response()->json([
                    'error' => 'Error fetching screening details',
                    'message' => $e->getMessage()
                ], 500);
            }
        }
    }
}
