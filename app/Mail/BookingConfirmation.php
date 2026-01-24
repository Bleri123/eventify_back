<?php

namespace App\Mail;

use App\Models\bookings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public bookings $booking,
        public string $movieName,
        public string $showroom,
        public string $screeningTime,
        public string $seatsInfo,
        public float $totalPrice
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking Confirmation - Eventify Cinema',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-confirmation',
            with: [
                'booking' => $this->booking,
                'movieName' => $this->movieName,
                'showroom' => $this->showroom,
                'screeningTime' => $this->screeningTime,
                'seatsInfo' => $this->seatsInfo,
                'totalPrice' => $this->totalPrice,
                'userName' => $this->booking->user->first_name ?? 'Guest',
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
