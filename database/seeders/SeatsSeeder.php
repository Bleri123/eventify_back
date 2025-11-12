<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SeatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $layouts = [
            'A' => 13,
            'B' => 13,
            'C' => 13,
            'D' => 13,
            'E' => 13,
            'F' => 13,
            'H' => 13,
        ];

        $seats = [];
        
        foreach ($layouts as $row => $seatCount) {
            for ($number = 1; $number <= $seatCount; $number++) {
                $seats[] = [
                    'showroom_id' => 1,
                    'row_label' => $row,
                    'seat_number' => $number,
                    'is_active' => true,
                ];
            }
        }

        DB::table('seats')->insert($seats);
    }
}
