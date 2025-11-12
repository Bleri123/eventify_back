<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TicketsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tickets = [
            [
                'booking_id' => 1,
                'screening_id' => 1,
                'seat' => ['row_label' => 'A', 'seat_number' => 1],
                'total_price' => 5.50,
                'status' => 'reserved',
            ],
            [
                'booking_id' => 1,
                'screening_id' => 1,
                'seat' => ['row_label' => 'B', 'seat_number' => 5],
                'total_price' => 5.50,
                'status' => 'reserved',
            ],
        ];
        
        foreach ($tickets as $ticket) {
            $seatId = DB::table('seats')
                ->where('showroom_id', 1)
                ->where('row_label', $ticket['seat']['row_label'])
                ->where('seat_number', $ticket['seat']['seat_number'])
                ->value('id');
        
            if (!$seatId) {
                continue;
            }
        
            DB::table('tickets')->insert([
                'booking_id' => $ticket['booking_id'],
                'screening_id' => $ticket['screening_id'],
                'seat_id' => $seatId,
                'total_price' => $ticket['total_price'],
                'status' => $ticket['status'],
                'created_at' => now(),
            ]);
        }
    }
}
