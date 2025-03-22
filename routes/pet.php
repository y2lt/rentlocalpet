<?php

use App\Http\Controllers\PetController;
use Illuminate\Support\Facades\Route;

Route::prefix('pets')->group(function () {
    // Public routes
    Route::get('/', [PetController::class, 'index'])->name('pets.index');
    Route::get('/{pet}', [PetController::class, 'show'])->name('pets.show');

    // Pet owner routes
    Route::middleware(['auth', 'role:pet owner'])->group(function () {
        Route::get('/my-pets', [PetController::class, 'myPets'])->name('pets.my-pets');
        Route::get('/create', [PetController::class, 'create'])->name('pets.create');
        Route::post('/', [PetController::class, 'store'])->name('pets.store');
        Route::get('/{pet}/edit', [PetController::class, 'edit'])->name('pets.edit');
        Route::put('/{pet}', [PetController::class, 'update'])->name('pets.update');
        Route::delete('/{pet}', [PetController::class, 'destroy'])->name('pets.destroy');
        Route::patch('/{pet}/toggle-availability', [PetController::class, 'toggleAvailability'])
            ->name('pets.toggle-availability');
    });
});
