<?php

namespace Database\Seeders;

use App\Models\bookings;
use App\Models\tickets;
use Illuminate\Database\Seeder;

class BookingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bookingData = [
            [
                'user_id' => 2,
                'screening_id' => 1,
                'status' => 'pending',
                'total_price' => 5.50,
                'seat_ids' => [1, 2],
                'ticket_status' => 'reserved',
            ],
            [
                'user_id' => 4,
                'screening_id' => 1,
                'status' => 'paid',
                'total_price' => 11.00,
                'seat_ids' => [3, 4],
                'ticket_status' => 'paid',
            ],
            [
                'user_id' => 5,
                'screening_id' => 1,
                'status' => 'cancelled',
                'total_price' => 5.50,
                'seat_ids' => [5, 6],
                'ticket_status' => 'void',
            ],
        ];

        foreach ($bookingData as $data) {
            $seatIds = $data['seat_ids'];
            $ticketStatus = $data['ticket_status'];
            unset($data['seat_ids']);
            unset($data['ticket_status']);

            $booking = bookings::create($data);

            // Create tickets for each seat
            foreach ($seatIds as $seatId) {
                tickets::create([
                    'booking_id' => $booking->id,
                    'screening_id' => $booking->screening_id,
                    'seat_id' => $seatId,
                    'total_price' => $booking->total_price / count($seatIds),
                    'status' => $ticketStatus,
                ]);
            }
        }
    }
}
