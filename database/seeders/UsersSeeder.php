<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('superadmin@gmail.com'),
            'email_verified_at' => now(),
        ]);
        $superAdmin->assignRole('super_admin');

        $admin = User::create([
            'name' => 'Admin Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin@gmail.com'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        $superNarek = User::create([
            'name' => 'Super Narek',
            'email' => 'supernarek@gmail.com',
            'password' => bcrypt('supernarek@gmail.com'),
            'email_verified_at' => now(),
        ]);
        $superNarek->assignRole('super_admin');

        $superHayko = User::create([
            'name' => 'Super Hayko',
            'email' => 'superhayko@gmail.com',
            'password' => bcrypt('superhayko@gmail.com'),
            'email_verified_at' => now(),
        ]);
        $superHayko->assignRole('super_admin');

        $superSvetlana = User::create([
            'name' => 'Super Svetlana',
            'email' => 'supersvetlana@gmail.com',
            'password' => bcrypt('supersvetlana@gmail.com'),
            'email_verified_at' => now(),
        ]);
        $superSvetlana->assignRole('super_admin');

        $superSusanna = User::create([
            'name' => 'Super Susanna',
            'email' => 'supersusanna@gmail.com',
            'password' => bcrypt('supersusanna@gmail.com'),
            'email_verified_at' => now(),
        ]);
        $superSusanna->assignRole('super_admin');
    }
}
