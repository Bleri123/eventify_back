<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MovieGenresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $movieGenres = [
            [
                'movie_id' => 1,
                'genre_id' => 1,
            ],
            [
                'movie_id' => 2,
                'genre_id' => 1,
            ],
            [
                'movie_id' => 3,
                'genre_id' => 1,
            ],
            [
                'movie_id' => 3,
                'genre_id' => 5,
            ],
            [
                'movie_id' => 3,
                'genre_id' => 7,
            ],
            [
                'movie_id' => 4,
                'genre_id' => 1,
            ],
        ];

        DB::table('movie_genres')->insert($movieGenres);
    }
}
