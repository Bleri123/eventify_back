<?php

namespace App\Http\Controllers;

use App\Models\seats;
use App\Models\tickets;
use App\Models\screenings;
use Illuminate\Http\JsonResponse;

class SeatsController extends Controller
{
    // GET /api/screenings/{screeningId}/seats
    // Returns all seats for the showroom + which ones are booked
    public function getSeatsForScreening($screeningId): JsonResponse
    {
        $screening = screenings::with(['showroom', 'movie.genres'])->findOrFail($screeningId);  // Add movie.genres
        $showroomId = $screening->showroom_id;

        // Get all seats for this showroom
        $seats = seats::where('showroom_id', $showroomId)
            ->where('is_active', true)
            ->orderBy('row_label')
            ->orderBy('seat_number')
            ->get();

        // Get booked seat IDs for this screening
        $bookedSeatIds = tickets::where('screening_id', $screeningId)
            ->whereIn('status', ['reserved', 'paid'])
            ->pluck('seat_id')
            ->toArray();

        // Mark seats as booked
        $seats->each(function ($seat) use ($bookedSeatIds) {
            $seat->is_booked = in_array($seat->id, $bookedSeatIds);
        });

        return response()->json([
            'seats' => $seats,
            'screening' => $screening,
        ]);
    }
}