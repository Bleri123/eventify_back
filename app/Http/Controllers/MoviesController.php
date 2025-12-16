<?php

namespace App\Http\Controllers;

use App\Models\movies;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MoviesController extends Controller
{
    // GET /api/movies
    // Query params:
    //  - search: string
    //  - genre_id: int
    //  - date: YYYY-MM-DD
    //  - status: coming_soon|now_showing|inactive (optional)
    //  - page, per_page: pagination
    public function index(Request $request): JsonResponse
    {
        $query = movies::query();

        // Always load genres
        $query->with('genres');

        $date = $request->query('date');

        // If a date is provided, eager-load screenings for that date
        if ($date) {
            $query->with(['screenings' => function ($q) use ($date) {
                $q->whereDate('start_time', $date)
                  ->where('status', 'on_sale')
                  ->orderBy('start_time');
            }]);
        } else {
            // Otherwise load *upcoming* screenings only
            $query->with(['screenings' => function ($q) {
                $q->where('status', 'on_sale')
                  ->orderBy('start_time');
            }]);
        }

        // Text search
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by genre
        if ($genreId = $request->query('genre_id')) {
            $query->whereHas('genres', function ($q) use ($genreId) {
                $q->where('genres.id', $genreId);
            });
        }

        // Filter by date (movies that have screenings on that date)
        if ($date) {
            $query->whereHas('screenings', function ($q) use ($date) {
                $q->whereDate('start_time', $date)
                  ->where('status', 'on_sale');
            });
        }

        // Optional status filter (e.g. only now_showing)
        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $perPage = (int) $request->query('per_page', 8);

        $movies = $query
            ->orderBy('title')
            ->paginate($perPage)
            ->withQueryString(); // keep filters in pagination links

        return response()->json($movies);
    }
    
    // GET /api/movies/{id}
    // Query param: date (optional, defaults to today)
    public function show(Request $request, $id): JsonResponse
    {
        $movie = movies::with('genres')->findOrFail($id);

        $date = $request->query('date');
        $today = now()->format('Y-m-d');
        $filterDate = $date ?: $today;

        // Load screenings for the selected date (or today) with showroom info
        $movie->load(['screenings' => function ($q) use ($filterDate) {
            $q->whereDate('start_time', $filterDate)
              ->where('status', 'on_sale')
              ->with('showroom')  // Load showroom info
              ->orderBy('start_time');
        }]);

        return response()->json($movie);
    }
}