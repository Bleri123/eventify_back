<?php

namespace App\Observers;

use App\Models\bookings;
use App\Models\Report;
use App\Models\User;

class BookingObserver
{
    /**
     * Handle the bookings "created" event.
     */
    public function created(bookings $booking): void
    {
        $this->syncToReports($booking);
    }

    /**
     * Handle the bookings "updated" event.
     */
    public function updated(bookings $booking): void
    {
        // Delete old report and create new one
        Report::where('booking_id', $booking->id)->delete();
        $this->syncToReports($booking);
    }

    /**
     * Handle the bookings "deleted" event.
     */
    public function deleted(bookings $booking): void
    {
        Report::where('booking_id', $booking->id)->delete();
    }

    /**
     * Sync booking data to reports table
     */
    public static function syncBookingToReports(bookings $booking): void
    {
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

            // Create or update report
            Report::updateOrCreate(
                ['booking_id' => $booking->id],
                [
                    'screening_id' => $booking->screening_id,
                    'user_id' => $booking->user_id,
                    'first_name' => $firstName,
                    'email' => $userEmail,
                    'seats_reserved' => $seatCount,
                    'row_reserved' => $rowReserved,
                    'total_price' => $booking->total_price ?? 0,
                    'status' => $booking->status,
                    'booked_at' => $booking->created_at,
                ]
            );
        } catch (\Exception $e) {
            \Log::error('Error syncing booking to reports: ' . $e->getMessage(), [
                'booking_id' => $booking->id ?? 'Unknown',
            ]);
        }
    }

    /**
     * Private wrapper for observer
     */
    private function syncToReports(bookings $booking): void
    {
        self::syncBookingToReports($booking);
    }
}
