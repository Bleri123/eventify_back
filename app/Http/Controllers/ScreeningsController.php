<?php

namespace App\Http\Controllers;

use App\Models\screenings;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ScreeningsController extends Controller
{
    // GET /api/screenings/{id}
    public function show($id): JsonResponse
    {
        $screening = screenings::with(['movie.genres', 'showroom'])
            ->findOrFail($id);

        return response()->json($screening);
    }

    // GET /api/screenings/by-date?date=YYYY-MM-DD
    public function getByDate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $date = $request->query('date');
        $screenings = screenings::whereDate('start_time', $date)
            ->with('movie')
            ->get();

        return response()->json(['screenings' => $screenings]);
    }

    // POST /api/screenings
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'movie_id' => 'required|exists:movies,id',
            'showroom_id' => 'required|exists:showrooms,id',
            'start_time' => 'required|date',
            'base_price' => 'required|numeric|min:0',
            'status' => 'sometimes|in:scheduled,on_sale,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $screening = screenings::create([
            'movie_id' => $request->input('movie_id'),
            'showroom_id' => $request->input('showroom_id'),
            'start_time' => $request->input('start_time'),
            'base_price' => $request->input('base_price'),
            'status' => $request->input('status', 'on_sale'),
        ]);

        $screening->load(['movie', 'showroom']);

        return response()->json($screening, 201);
    }
}