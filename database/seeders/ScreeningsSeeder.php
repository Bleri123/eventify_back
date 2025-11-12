<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ScreeningsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $screenings = [
            [
                'movie_id' => 1,
                'showroom_id' => 1,
                'start_time' => '2025-11-12 14:30:00',
                'base_price' => 5.50,
                'status' => 'on_sale',
                'created_at' => now(),
            ],
            [
                'movie_id' => 2,
                'showroom_id' => 1,
                'start_time' => '2025-11-12 17:30:00',
                'base_price' => 7.50,
                'status' => 'on_sale',
                'created_at' => now(),
            ],
            [
                'movie_id' => 3,
                'showroom_id' => 1,
                'start_time' => '2025-11-12 20:30:00',
                'base_price' => 5.00,
                'status' => 'on_sale',
                'created_at' => now(),
            ],
            [
                'movie_id' => 4,
                'showroom_id' => 1,
                'start_time' => '2025-11-13 14:30:00',
                'base_price' => 6.50,
                'status' => 'on_sale',
                'created_at' => now(),
            ],
        ];
        DB::table('screenings')->insert($screenings);
    }
}
