<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $superAdminAdminPermission = Permission::firstOrCreate([
            'name' => 'super-admin-admin',
            'guard_name' => 'api',
        ]);

        //Admin
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'api'
        ]);
        $adminRole->givePermissionTo($superAdminAdminPermission);

        //Super Admin
        $superAdminRole = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'api',
        ]);
        $superAdminRole->givePermissionTo($superAdminAdminPermission);
    }
}
