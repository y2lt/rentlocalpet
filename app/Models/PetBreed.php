<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetBreed extends Model
{
    /** @use HasFactory<\Database\Factories\PetBreedFactory> */
    use HasFactory;

    protected $fillable = [
        'pet_type_id',
        'name',
        'description',
        'characteristics',
        'care_instructions',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'characteristics' => 'array',
        'care_instructions' => 'array'
    ];

    public function type()
    {
        return $this->belongsTo(PetType::class, 'pet_type_id');
    }

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $typeId)
    {
        return $query->where('pet_type_id', $typeId);
    }
}
