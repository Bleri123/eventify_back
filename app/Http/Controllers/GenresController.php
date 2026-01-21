<?php

namespace App\Http\Controllers;

use App\Models\genres;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class GenresController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 10);
        $page = (int) $request->query('page', 1);

        // If per_page is very large (like 1000), return all without pagination
        if ($perPage >= 1000) {
            $genres = genres::orderBy('name')->get();
            return response()->json($genres);
        }

        $genres = genres::orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        return response()->json($genres);
    }

    // POST /api/genres
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:genres,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $genre = genres::create([
            'name' => $request->input('name'),
        ]);

        return response()->json($genre, 201);
    }

    // PUT /api/genres/{id}
    public function update(Request $request, $id): JsonResponse
    {
        $genre = genres::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:genres,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $genre->update([
            'name' => $request->input('name'),
        ]);

        return response()->json($genre);
    }

    // DELETE /api/genres/{id}
    public function destroy($id): JsonResponse
    {
        $genre = genres::findOrFail($id);

        // Delete the genre (cascade will handle related movie_genres)
        $genre->delete();

        return response()->json(['message' => 'Genre deleted successfully']);
    }
}
