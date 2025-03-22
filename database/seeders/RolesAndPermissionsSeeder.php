<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Pet management
            'create pets',
            'edit pets',
            'delete pets',
            'view pets',
            
            // Booking management
            'create bookings',
            'approve bookings',
            'reject bookings',
            'cancel bookings',
            'view bookings',
            
            // Review management
            'create reviews',
            'moderate reviews',
            'view reviews',
            
            // Profile management
            'edit profile',
            'view profile',
            
            // Income management
            'view earnings',
            'withdraw earnings'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Admin role
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Pet Owner role
        $petOwnerRole = Role::create(['name' => 'pet owner']);
        $petOwnerRole->givePermissionTo([
            'create pets',
            'edit pets',
            'delete pets',
            'view pets',
            'approve bookings',
            'reject bookings',
            'view bookings',
            'view reviews',
            'edit profile',
            'view profile',
            'view earnings',
            'withdraw earnings'
        ]);

        // Renter role
        $renterRole = Role::create(['name' => 'renter']);
        $renterRole->givePermissionTo([
            'view pets',
            'create bookings',
            'cancel bookings',
            'view bookings',
            'create reviews',
            'view reviews',
            'edit profile',
            'view profile'
        ]);
    }
}
