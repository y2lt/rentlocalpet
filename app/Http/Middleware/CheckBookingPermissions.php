<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBookingPermissions
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $booking = $request->route('booking');

        // Check if user is the pet owner for this booking
        if ($booking && $booking->pet->user_id === $user->id) {
            if (!$user->hasPermissionTo('approve bookings')) {
                abort(403, 'You do not have permission to manage bookings');
            }
        }
        // Check if user is the renter for this booking
        else if ($booking && $booking->user_id === $user->id) {
            if (!$user->hasPermissionTo('create bookings')) {
                abort(403, 'You do not have permission to manage your bookings');
            }
        }
        // If neither owner nor renter, must be admin
        else if (!$user->hasRole('admin')) {
            abort(403, 'You do not have permission to access this booking');
        }

        return $next($request);
    }
}
