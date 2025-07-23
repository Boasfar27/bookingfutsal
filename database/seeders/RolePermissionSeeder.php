<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Field Management
            'view fields',
            'create fields',
            'edit fields',
            'delete fields',
            'manage own fields',

            // Booking Management
            'view bookings',
            'create bookings',
            'edit bookings',
            'delete bookings',
            'confirm bookings',
            'cancel bookings',
            'view own bookings',

            // Payment Management
            'view payments',
            'verify payments',
            'reject payments',
            'view own payments',

            // Schedule Management
            'view schedules',
            'create schedules',
            'edit schedules',
            'delete schedules',
            'manage field schedules',

            // User Management
            'view users',
            'create users',
            'edit users',
            'delete users',
            'assign roles',

            // Report Management
            'view reports',
            'export reports',
            'view field reports',
            'view global reports',

            // Dashboard Access
            'access admin dashboard',
            'access user dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions

        // 1. User Role
        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo([
            'create bookings',
            'view own bookings',
            'view own payments',
            'access user dashboard',
        ]);

        // 2. Admin Role (Field Owner)
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo([
            'view fields',
            'manage own fields',
            'view bookings',
            'confirm bookings',
            'cancel bookings',
            'view payments',
            'verify payments',
            'reject payments',
            'view schedules',
            'create schedules',
            'edit schedules',
            'delete schedules',
            'manage field schedules',
            'view reports',
            'export reports',
            'view field reports',
            'access admin dashboard',
        ]);

        // 3. Superadmin Role
        $superadminRole = Role::create(['name' => 'superadmin']);
        $superadminRole->givePermissionTo(Permission::all());

        // Create default users

        // Create Superadmin User
        $superadmin = User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@futsalpro.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $superadmin->assignRole('superadmin');

        // Create Admin User
        $admin = User::create([
            'name' => 'Admin Lapangan A',
            'email' => 'admin@futsalpro.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        // Create Regular User
        $user = User::create([
            'name' => 'Customer Demo',
            'email' => 'user@futsalpro.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $user->assignRole('user');

        $this->command->info('Roles and permissions created successfully!');
        $this->command->info('Default users created:');
        $this->command->info('Superadmin: superadmin@futsalpro.com / password');
        $this->command->info('Admin: admin@futsalpro.com / password');
        $this->command->info('User: user@futsalpro.com / password');
    }
}
