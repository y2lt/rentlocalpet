<?php

namespace Database\Factories;

use App\Models\PetType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PetBreed>
 */
class PetBreedFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pet_type_id' => PetType::factory(),
            'name' => fake()->unique()->words(2, true),
            'description' => fake()->paragraph(),
            'characteristics' => [
                'temperament' => fake()->words(3, true),
                'size' => fake()->randomElement(['Small', 'Medium', 'Large']),
                'life_span' => fake()->numberBetween(5, 20) . ' years',
                'grooming_needs' => fake()->randomElement(['Low', 'Medium', 'High']),
            ],
            'care_instructions' => [
                'diet' => fake()->paragraph(),
                'exercise' => fake()->paragraph(),
                'grooming' => fake()->paragraph(),
            ],
            'is_active' => fake()->boolean(90),
        ];
    }
}
