<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BookingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bookings = [
            [
                'user_id' => 2,
                'screening_id' => 1,
                'status' => 'pending',
                'total_price' => 5.50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'screening_id' => 1,
                'status' => 'paid',
                'total_price' => 11.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 5,
                'screening_id' => 1,
                'status' => 'cancelled',
                'total_price' => 5.50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('bookings')->insert($bookings);
    }
}
