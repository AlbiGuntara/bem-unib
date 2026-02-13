<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions
        $permissions = [
            // Users
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Roles
            'view role',
            'create role',
            'edit role',
            'delete role',

            // Permissions
            'view permission',
            'create permission',
            'edit permission',
            'delete permission',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Roles
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $operator = Role::firstOrCreate(['name' => 'operator']);

        // Assign permissions
        $superAdmin->givePermissionTo(Permission::all());

        $admin->givePermissionTo([
            'view users',
            'create users',
            'edit users',
        ]);

        $operator->givePermissionTo([
            'view users',
        ]);
    }
}
