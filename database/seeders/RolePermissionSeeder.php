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

            // Contacts
            'view contacts',
            'create contacts',
            'edit contacts',
            'delete contacts',

            // Messages
            'view messages',
            'reply messages',
            'delete messages',

            // FAQs
            'view faqs',
            'create faqs',
            'edit faqs',
            'delete faqs',

            // Kolaborasi
            'view kolaborasi',
            'create kolaborasi',
            'edit kolaborasi',
            'delete kolaborasi',
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

            'view contacts',
            'create contacts',
            'edit contacts',
            'delete contacts',

            'view messages',
            'reply messages',
            'delete messages',

            'view faqs',
            'create faqs',
            'edit faqs',
            'delete faqs',
        ]);

        $operator->givePermissionTo([
            'view users',
            'view contacts',
            'view messages',
            'view faqs',
        ]);
    }
}
