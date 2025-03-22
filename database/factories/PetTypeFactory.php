<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PetType>
 */
class PetTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Dog', 'Cat', 'Bird', 'Fish', 'Hamster', 'Rabbit', 'Guinea Pig', 'Reptile']),
            'description' => fake()->paragraph(),
            'icon' => 'fa-' . fake()->word(),
            'is_active' => fake()->boolean(90),
        ];
    }
}
