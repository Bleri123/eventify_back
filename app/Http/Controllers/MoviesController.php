<?php

namespace App\Http\Controllers;

use App\Models\movies;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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

        // Exclude inactive movies by default (unless specifically requested)
        if ($request->query('status') !== 'inactive') {
            $query->where('status', '!=', 'inactive');
        }

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
    // Query param: admin (optional, if 'true' loads all screenings to get base_price)
    public function show(Request $request, $id): JsonResponse
    {
        $movie = movies::with('genres')->findOrFail($id);

        // For admin edit purposes, load the most recent screening to get base_price
        if ($request->query('admin') === 'true') {
            $movie->load(['screenings' => function ($q) {
                $q->orderBy('start_time', 'desc')->limit(1);
            }]);
        } else {
            // For regular users, load screenings for the selected date (or today) with showroom info
            $date = $request->query('date');
            $today = now()->format('Y-m-d');
            $filterDate = $date ?: $today;

            $movie->load(['screenings' => function ($q) use ($filterDate) {
                $q->whereDate('start_time', $filterDate)
                  ->where('status', 'on_sale')
                  ->with('showroom')  // Load showroom info
                  ->orderBy('start_time');
            }]);
        }

        return response()->json($movie);
    }

    // POST /api/movies
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration_minutes' => 'required|integer|min:1',
            'release_date' => 'required|date',
            'movie_language' => 'required|string|max:50',
            'status' => 'required|in:coming_soon,now_showing,inactive',
            'genre_ids' => 'required|array|min:1',
            'genre_ids.*' => 'exists:genres,id',
            'poster' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Handle poster upload
        $posterPath = null;
        if ($request->hasFile('poster')) {
            $file = $request->file('poster');
            $fileName = $file->getClientOriginalName();
            $posterPath = 'posters/' . $fileName;
            $file->storeAs('public', $posterPath);
        }

        // Create the movie
        $movie = movies::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'duration_minutes' => $request->input('duration_minutes'),
            'release_date' => $request->input('release_date'),
            'movie_language' => $request->input('movie_language'),
            'status' => $request->input('status'),
            'poster_url' => $posterPath,
        ]);

        // Attach genres
        $genreIds = $request->input('genre_ids', []);
        $movie->genres()->attach($genreIds);

        return response()->json($movie);
    }

    // PUT /api/movies/{id}
    public function update(Request $request, $id): JsonResponse
    {
        $movie = movies::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'duration_minutes' => 'sometimes|required|integer|min:1',
            'release_date' => 'sometimes|required|date',
            'movie_language' => 'sometimes|required|string|max:50',
            'status' => 'sometimes|required|in:coming_soon,now_showing,inactive',
            'genre_ids' => 'sometimes|required|array|min:1',
            'genre_ids.*' => 'exists:genres,id',
            'poster' => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'ticket_price' => 'sometimes|nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Update movie fields
        $updateData = [];
        $allData = $request->all();

        if ($request->has('title') && $request->filled('title')) $updateData['title'] = $request->input('title');
        if ($request->has('description') && $request->filled('description')) $updateData['description'] = $request->input('description');
        if ($request->has('duration_minutes') && $request->filled('duration_minutes')) $updateData['duration_minutes'] = $request->input('duration_minutes');
        if ($request->has('release_date') && $request->filled('release_date')) $updateData['release_date'] = $request->input('release_date');
        if ($request->has('movie_language') && $request->filled('movie_language')) $updateData['movie_language'] = $request->input('movie_language');

        // Status should always be updated if provided
        // Check multiple methods for FormData compatibility with PUT requests
        $statusValue = $request->input('status') ?? $request->get('status') ?? ($allData['status'] ?? null);
        if ($statusValue !== null && $statusValue !== '' && in_array($statusValue, ['coming_soon', 'now_showing', 'inactive'])) {
            $updateData['status'] = $statusValue;
        }

        // Handle poster upload if provided
        if ($request->hasFile('poster')) {
            $file = $request->file('poster');
            $fileName = $file->getClientOriginalName();
            $posterPath = 'posters/' . $fileName;
            $file->storeAs('public', $posterPath);
            $updateData['poster_url'] = $posterPath;
        }

        // Update movie
        if (!empty($updateData)) {
            $movie->update($updateData);
        }

        // Update genres if provided
        if ($request->has('genre_ids')) {
            $genreIds = $request->input('genre_ids', []);
            $movie->genres()->sync($genreIds);
        }

        // Update screenings base_price if ticket_price is provided
        if ($request->has('ticket_price') && $request->input('ticket_price') !== null) {
            $basePrice = $request->input('ticket_price');
            $movie->screenings()->update(['base_price' => $basePrice]);
        }

        // Load genres for response
        $movie->load('genres');

        return response()->json($movie);
    }

    // DELETE /api/movies/{id}
    public function destroy($id): JsonResponse
    {
        try {
            $movie = movies::findOrFail($id);

            // Mark movie as inactive instead of deleting
            $movie->update([
                'status' => 'inactive'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Movie removed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove movie: ' . $e->getMessage()
            ], 500);
        }
    }
}