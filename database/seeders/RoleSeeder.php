<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $ceoRole = Role::firstOrCreate(['name' => 'ceo']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $driverRole = Role::firstOrCreate(['name' => 'driver']);

        // Create CEO user
        $ceo = User::firstOrCreate(
            ['email' => 'ceo@example.com'],
            [
                'name' => 'CEO',
                'password' => bcrypt('password'),
                'role'=>'ceo',
            ]
        );
        $ceo->assignRole($ceoRole);

        // Create Manager user
        $manager = User::firstOrCreate(
            ['email' => 'jovan@example.com'],
            [
                'name' => 'Manager',
                'password' => bcrypt('123123123'),
                'role'=>'manager',

            ]
        );
        $manager->assignRole($managerRole);

        $manager = User::firstOrCreate(
            ['email' => 'kenan@example.com'],
            [
                'name' => 'Manager',
                'password' => bcrypt('123123123'),
                'role'=>'manager',

            ]
        );
        $manager->assignRole($managerRole);

        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
                'role'=>'admin',

            ]
        );
        $admin->assignRole($adminRole);

        // Create sample Driver user
        $driver = User::firstOrCreate(
            ['email' => ' '],
            [
                'name' => 'Angelique',
                'password' => bcrypt('password'),
                'manager_id' => 2,
            ]
        );
        $driver->assignRole($driverRole);

        $driver2 = User::firstOrCreate(
            ['email' => 'nellie@example.com'],
            [
                'name' => 'Nellie',
                'password' => bcrypt('password'),
                'manager_id' => 2,
            ]
        );
        $driver2->assignRole($driverRole);

        $driver3 = User::firstOrCreate(
            ['email' => 'embla@example.com'],
            [
                'name' => 'Embla',
                'password' => bcrypt('password'),
                'manager_id' => 3,
            ]
        );
        $driver3->assignRole($driverRole);



        $this->command->info('Roles and users created successfully!');
        $this->command->info('CEO: ceo@example.com / password');
        $this->command->info('Manager: manager@example.com / password');
        $this->command->info('Driver: driver@example.com / password');
    }
}
