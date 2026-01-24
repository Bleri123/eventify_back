<?php

namespace App\Http\Controllers;

use App\Models\bookings;
use App\Models\screenings;
use App\Models\tickets;
use App\Models\payments;
use App\Mail\BookingConfirmation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentsController extends Controller
{
    public function __construct()
    {
        $stripeKey = config('services.stripe.secret');

        if (!$stripeKey) {
            throw new \Exception('STRIPE_SECRET_KEY is not set in .env file');
        }

        Stripe::setApiKey($stripeKey);
    }

    // POST /api/payments/create-intent
    // Create Stripe payment intent
    public function createIntent(Request $request): JsonResponse
    {
        $request->validate([
            'screening_id' => 'required|exists:screenings,id',
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'required|exists:seats,id',
        ]);

        $screening = screenings::findOrFail($request->screening_id);
        $seatCount = count($request->seat_ids);
        $totalAmount = $screening->base_price * $seatCount;

        // Convert to cents for Stripe
        $amountInCents = (int) ($totalAmount * 100);

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $amountInCents,
                'currency' => 'eur', // or 'usd' depending on your needs
                'metadata' => [
                    'screening_id' => $screening->id,
                    'user_id' => Auth::id(),
                    'seat_ids' => json_encode($request->seat_ids),
                ],
            ]);

            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
                'amount' => $totalAmount,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // POST /api/payments/confirm
    // Confirm payment and create booking
    public function confirm(Request $request): JsonResponse
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
            'screening_id' => 'required|exists:screenings,id',
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'required|exists:seats,id',
        ]);

        try {
            $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);

            if ($paymentIntent->status !== 'succeeded') {
                return response()->json(['error' => 'Payment not succeeded'], 400);
            }

            DB::beginTransaction();

            $screening = screenings::findOrFail($request->screening_id);
            $seatCount = count($request->seat_ids);
            $totalPrice = $screening->base_price * $seatCount;

            // Create booking
            $booking = bookings::create([
                'user_id' => Auth::id(),
                'screening_id' => $request->screening_id,
                'status' => 'paid',
                'total_price' => $totalPrice,
            ]);

            // Create tickets for each seat
            foreach ($request->seat_ids as $seatId) {
                tickets::create([
                    'booking_id' => $booking->id,
                    'screening_id' => $request->screening_id,
                    'seat_id' => $seatId,
                    'total_price' => $screening->base_price,
                    'status' => 'paid',
                ]);
            }

            // Create payment record
            payments::create([
                'booking_id' => $booking->id,
                'total_price' => $totalPrice,
                'status' => 'paid',
                'transaction_ref' => $paymentIntent->id,
            ]);

            // Manually sync to reports after tickets are created
            $booking->load('tickets.seat');
            \App\Observers\BookingObserver::syncBookingToReports($booking);

            DB::commit();

            // Prepare seat information for email
            $seatsInfo = [];
            $screeningTickets = $booking->tickets()->with('seat')->get();
            foreach ($screeningTickets as $ticket) {
                if ($ticket->seat) {
                    $seatsInfo[] = $ticket->seat->row_label . $ticket->seat->seat_number;
                }
            }
            $seatsInfoString = implode(', ', $seatsInfo);

            // Get screening details for email
            $screeningDetails = $screening->load('movie', 'showroom');
            $movieName = $screeningDetails->movie->title ?? 'Movie';
            $showroom = $screeningDetails->showroom->name ?? 'Showroom';
            $startTime = $screeningDetails->start_time;
            if (is_string($startTime)) {
                $startTime = \Carbon\Carbon::parse($startTime);
            }
            $screeningTime = $startTime->format('l, F j, Y \a\t H:i');

            // Send confirmation email
            try {
                $booking->load('user');
                Mail::to($booking->user->email)
                    ->send(new BookingConfirmation(
                        $booking,
                        $movieName,
                        $showroom,
                        $screeningTime,
                        $seatsInfoString,
                        $totalPrice
                    ));
            } catch (\Exception $emailError) {
                \Log::error('Error sending booking confirmation email: ' . $emailError->getMessage(), [
                    'booking_id' => $booking->id,
                ]);
            }

            return response()->json([
                'success' => true,
                'booking_id' => $booking->id,
                'message' => 'Booking confirmed successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}