<?php

namespace App\Http\Controllers;

use App\Models\movies;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stats(Request $request): JsonResponse
    {
        $totalMovies = movies::count();
        $totalUsers = User::count();

        return response()->json([
            'total_movies' => $totalMovies,
            'total_users' => $totalUsers,
        ]);
    }
}
