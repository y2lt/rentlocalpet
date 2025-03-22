<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'pet_type_id',
        'pet_breed_id',
        'name',
        'description',
        'age',
        'size',
        'daily_rate',
        'is_available',
        'care_instructions',
        'photos'
    ];

    protected $casts = [
        'care_instructions' => 'array',
        'photos' => 'array',
        'is_available' => 'boolean',
        'daily_rate' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function type()
    {
        return $this->belongsTo(PetType::class, 'pet_type_id');
    }

    public function breed()
    {
        return $this->belongsTo(PetBreed::class, 'pet_breed_id');
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeByType($query, $typeId)
    {
        return $query->where('pet_type_id', $typeId);
    }

    public function scopeByBreed($query, $breedId)
    {
        return $query->where('pet_breed_id', $breedId);
    }

    public function scopeBySize($query, $size)
    {
        return $query->where('size', $size);
    }

    public function scopeByPriceRange($query, $min, $max)
    {
        return $query->whereBetween('daily_rate', [$min, $max]);
    }

    public function scopeWithAverageRating($query)
    {
        return $query->withAvg('reviews', 'rating');
    }

}
