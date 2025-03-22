<?php

namespace App\Listeners\Booking;

use App\Events\Booking\BookingRequestedEvent;
use App\Notifications\NewBookingRequestNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyPetOwnerOfRequest implements ShouldQueue
{
    public function handle(BookingRequestedEvent $event): void
    {
        $petOwner = $event->booking->pet->user;
        
        $petOwner->notify(new NewBookingRequestNotification($event->booking));
    }
}
