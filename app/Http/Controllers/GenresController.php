<?php

namespace App\Http\Controllers;

use App\Models\genres;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GenresController extends Controller
{
    public function index(): JsonResponse
    {
        $genres = genres::orderBy('name')->get();

        return response()->json($genres);
    }
}
