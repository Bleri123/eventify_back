<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MoviesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $movies = [
            [
                'title' => 'F1 The Movie',
                'description' => 'A movie about the history of Formula 1 racing.',
                'duration_minutes' => 155,
                'release_date' => '2025-11-12',
                'movie_language' => 'English',
                'status' => 'now_showing',
                'poster_url' => 'posters/f1-the-movie.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'SkyFall',
                'description' => 'A movie about a spy who is trying to stop a terrorist attack.',
                'duration_minutes' => 143,
                'release_date' => '2012-10-26',
                'movie_language' => 'English',
                'status' => 'now_showing',
                'poster_url' => 'posters/skyfall.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Ford v Ferrari',
                'description' => 'A movie about the rivalry between Ford and Ferrari in the 1960s.',
                'duration_minutes' => 152,
                'release_date' => '2019-08-30',
                'movie_language' => 'English',
                'status' => 'now_showing',
                'poster_url' => 'posters/ford-v-ferrari.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'The Dark Knight',
                'description' => 'A movie about a superhero who fights crime and saves the city.',
                'duration_minutes' => 152,
                'release_date' => '2008-07-18',
                'movie_language' => 'English',
                'status' => 'now_showing',
                'poster_url' => 'posters/the-dark-knight.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('movies')->insert($movies);
    }
}
