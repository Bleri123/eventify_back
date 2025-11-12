<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = [
            [
                'name' => 'Action',
                'created_at' => now(),
            ],
            [
                'name' => 'Animation',
                'created_at' => now(),
            ],
            [
                'name' => 'Comedy',
                'created_at' => now(),
            ],
            [
                'name' => 'Family',
                'created_at' => now(),
            ],
            [
                'name' => 'Drama',
                'created_at' => now(),
            ],
            [
                'name' => 'Horror',
                'created_at' => now(),
            ],
            [
                'name' => 'History',
                'created_at' => now(),
            ],
        ];

        DB::table('genres')->insert($genres);
    }
}
