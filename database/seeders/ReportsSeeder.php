<?php

namespace Database\Seeders;

use App\Models\bookings;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all existing bookings
        $bookings = bookings::all();

        foreach ($bookings as $booking) {
            try {
                // Get user info
                $user = User::find($booking->user_id);
                $firstName = $user?->first_name ?? 'N/A';
                $userEmail = $user?->email ?? 'N/A';

                // Get all seats for this booking
                $seatStrings = [];
                $seatCount = 0;

                $tickets = $booking->tickets()->with('seat')->get();

                if ($tickets && count($tickets) > 0) {
                    foreach ($tickets as $ticket) {
                        if ($ticket && $ticket->seat) {
                            $seatStrings[] = $ticket->seat->row_label . $ticket->seat->seat_number;
                            $seatCount++;
                        }
                    }
                }

                $rowReserved = count($seatStrings) > 0 ? implode(', ', $seatStrings) : null;

                // Create report
                Report::create([
                    'screening_id' => $booking->screening_id,
                    'user_id' => $booking->user_id,
                    'booking_id' => $booking->id,
                    'first_name' => $firstName,
                    'email' => $userEmail,
                    'seats_reserved' => $seatCount,
                    'row_reserved' => $rowReserved,
                    'total_price' => $booking->total_price ?? 0,
                    'status' => $booking->status,
                    'booked_at' => $booking->created_at,
                ]);
            } catch (\Exception $e) {
                \Log::error('Error seeding report: ' . $e->getMessage(), [
                    'booking_id' => $booking->id ?? 'Unknown',
                ]);
            }
        }

        $this->command->info('Reports seeded successfully: ' . Report::count() . ' reports created');
    }
}
