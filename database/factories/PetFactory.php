<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\PetType;
use App\Models\PetBreed;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pet>
 */
class PetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sizes = ['small', 'medium', 'large'];
        
        return [
            'user_id' => User::factory(),
            'pet_type_id' => PetType::factory(),
            'pet_breed_id' => PetBreed::factory(),
            'name' => fake()->firstName(),
            'description' => fake()->paragraph(),
            'age' => fake()->numberBetween(1, 15),
            'size' => fake()->randomElement($sizes),
            'daily_rate' => fake()->randomFloat(2, 10, 100),
            'is_available' => fake()->boolean(80),
            'care_instructions' => [
                'feeding' => fake()->sentence(),
                'exercise' => fake()->sentence(),
                'special_needs' => fake()->sentence(),
            ],
            'photos' => [
                fake()->imageUrl(640, 480, 'animals'),
                fake()->imageUrl(640, 480, 'animals'),
            ],
        ];
    }
}
