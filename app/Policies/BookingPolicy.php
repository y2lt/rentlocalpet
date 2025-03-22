<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Users can view their own bookings
    }

    public function view(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id || // Renter
               $user->id === $booking->pet->user_id || // Pet owner
               $user->hasRole('admin'); // Admin
    }

    public function create(User $user): bool
    {
        return $user->hasRole('renter') || $user->hasRole('admin');
    }

    public function update(User $user, Booking $booking): bool
    {
        // Only pet owner can approve/reject bookings
        if ($booking->status === 'pending') {
            return $user->id === $booking->pet->user_id || $user->hasRole('admin');
        }

        // Renter can only cancel their own bookings
        if ($booking->status === 'approved') {
            return $user->id === $booking->user_id || $user->hasRole('admin');
        }

        return $user->hasRole('admin');
    }

    public function delete(User $user, Booking $booking): bool
    {
        return $user->hasRole('admin');
    }
}
