<?php

namespace App\Policies;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PetPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Anyone can view pets
    }

    public function view(User $user, Pet $pet): bool
    {
        return true; // Anyone can view individual pets
    }

    public function create(User $user): bool
    {
        return $user->hasRole('pet owner');
    }

    public function update(User $user, Pet $pet): bool
    {
        return $user->id === $pet->user_id || $user->hasRole('admin');
    }

    public function delete(User $user, Pet $pet): bool
    {
        return $user->id === $pet->user_id || $user->hasRole('admin');
    }

    public function toggleAvailability(User $user, Pet $pet): bool
    {
        return $user->id === $pet->user_id || $user->hasRole('admin');
    }

    public function manageBookings(User $user, Pet $pet): bool
    {
        return $user->id === $pet->user_id || $user->hasRole('admin');
    }
}
