<?php

namespace App\Http\Controllers;

use App\Models\screenings;
use App\Models\tickets;
use App\Models\bookings;
use App\Models\seats;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    /**
     * Get all screenings with pagination
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
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
                'showroom' => $screening->showroom->name ?? 'N/A',
                'movie_name' => $screening->movie->title ?? 'N/A',
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
    }

    /**
     * Get reserved seats for a specific screening
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, $id): JsonResponse
    {
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);

        $screening = screenings::with(['movie', 'showroom'])->findOrFail($id);

        // Get bookings with their tickets and seats
        $bookings = bookings::where('screening_id', $id)
            ->whereIn('status', ['reserved', 'paid'])
            ->with(['user', 'tickets' => function ($query) {
                $query->with('seat');
            }])
            ->paginate($perPage, ['*'], 'page', $page);

        $offset = ($page - 1) * $perPage;
        $reservedSeats = $bookings->map(function ($booking, $index) use ($offset) {
            $seats = $booking->tickets
                ->filter(function ($ticket) {
                    return $ticket->seat !== null;
                })
                ->map(function ($ticket) {
                    return $ticket->seat->row_label . $ticket->seat->seat_number;
                })
                ->toArray();

            return [
                'id' => $offset + $index + 1, // Sequential ID for display
                'email' => $booking->user->email ?? 'N/A',
                'seats_reserved' => count($seats),
                'row_reserved' => count($seats) > 0 ? implode(', ', $seats) : 'N/A',
            ];
        });

        return response()->json([
            'screening' => [
                'id' => $screening->id,
                'movie_name' => $screening->movie->title ?? 'N/A',
                'showroom' => $screening->showroom->name ?? 'N/A',
                'time' => date('H:i', strtotime($screening->start_time)),
                'date' => date('d/m/Y', strtotime($screening->start_time)),
            ],
            'data' => $reservedSeats,
            'current_page' => $bookings->currentPage(),
            'last_page' => $bookings->lastPage(),
            'per_page' => $bookings->perPage(),
            'total' => $bookings->total(),
        ]);
    }
}
