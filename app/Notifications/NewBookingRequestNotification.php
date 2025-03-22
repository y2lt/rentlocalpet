<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBookingRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private Booking $booking
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Booking Request for ' . $this->booking->pet->name)
            ->line('You have received a new booking request for ' . $this->booking->pet->name)
            ->line('From: ' . $this->booking->start_date->format('M d, Y'))
            ->line('To: ' . $this->booking->end_date->format('M d, Y'))
            ->line('Total Price: $' . $this->booking->total_price)
            ->action('View Request', url('/dashboard/bookings/' . $this->booking->id))
            ->line('Please respond to this request as soon as possible.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'pet_id' => $this->booking->pet_id,
            'pet_name' => $this->booking->pet->name,
            'start_date' => $this->booking->start_date,
            'end_date' => $this->booking->end_date,
            'total_price' => $this->booking->total_price,
        ];
    }
}
