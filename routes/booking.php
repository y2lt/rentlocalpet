<?php

use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::prefix('bookings')
    ->middleware('auth')
    ->group(function () {
        // Routes for all authenticated users
        Route::get('/', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('/{booking}', [BookingController::class, 'show'])->name('bookings.show');
        
        // Routes for renters
        Route::middleware('role:renter')->group(function () {
            Route::post('/', [BookingController::class, 'store'])->name('bookings.store');
            Route::post('/{booking}/cancel', [BookingController::class, 'cancel'])
                ->middleware('check.booking')
                ->name('bookings.cancel');
        });
        
        // Routes for pet owners
        Route::middleware('role:pet owner')->group(function () {
            Route::post('/{booking}/approve', [BookingController::class, 'approve'])
                ->middleware('check.booking')
                ->name('bookings.approve');
            Route::post('/{booking}/reject', [BookingController::class, 'reject'])
                ->middleware('check.booking')
                ->name('bookings.reject');
        });
    });
