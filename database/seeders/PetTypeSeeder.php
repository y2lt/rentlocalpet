<?php

namespace Database\Seeders;

use App\Models\PetType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PetTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $petTypes = [
            [
                'name' => 'Dog',
                'description' => 'Loyal and friendly companions that come in many breeds and sizes.',
                'icon' => 'fa-dog',
            ],
            [
                'name' => 'Cat',
                'description' => 'Independent and graceful pets that make perfect companions for any home.',
                'icon' => 'fa-cat',
            ],
            [
                'name' => 'Bird',
                'description' => 'Colorful and intelligent pets that can bring music and joy to your home.',
                'icon' => 'fa-dove',
            ],
            [
                'name' => 'Fish',
                'description' => 'Peaceful and low-maintenance pets that can create a calming atmosphere.',
                'icon' => 'fa-fish',
            ],
            [
                'name' => 'Small Animal',
                'description' => 'Hamsters, guinea pigs, and other small pets that are perfect for smaller spaces.',
                'icon' => 'fa-rabbit',
            ],
            [
                'name' => 'Reptile',
                'description' => 'Fascinating and unique pets including snakes, lizards, and turtles.',
                'icon' => 'fa-dragon',
            ],
        ];

        foreach ($petTypes as $type) {
            PetType::create($type);
        }
    }
}
