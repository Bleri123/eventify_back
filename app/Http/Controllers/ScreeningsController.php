<?php

namespace App\Http\Controllers;

use App\Models\screenings;
use Illuminate\Http\JsonResponse;

class ScreeningsController extends Controller
{
    // GET /api/screenings/{id}
    public function show($id): JsonResponse
    {
        $screening = screenings::with(['movie.genres', 'showroom'])
            ->findOrFail($id);

        return response()->json($screening);
    }
}