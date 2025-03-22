<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);

        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);
        $admin->assignRole('admin');

        // Create pet owner user
        $petOwner = User::factory()->create([
            'name' => 'Pet Owner',
            'email' => 'owner@example.com',
        ]);
        $petOwner->assignRole('pet owner');

        // Create renter user
        $renter = User::factory()->create([
            'name' => 'Renter',
            'email' => 'renter@example.com',
        ]);
        $renter->assignRole('renter');
    }
}
