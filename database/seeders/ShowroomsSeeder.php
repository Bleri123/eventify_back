<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ShowroomsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $showrooms = [
            [
              'name' => 'Showroom EV',
              'seat_rows' => 7,
              'seat_cols' => 13,
              'is_active' => true,  
              'created_at' => now(),
            ]
        ];
        DB::table('showrooms')->insert($showrooms);
    }
}
