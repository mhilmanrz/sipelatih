<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define some basic permissions (optional)
        $permissions = [
            'manage users',
            'manage activities',
            'view dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles
        $superAdmin = Role::firstOrCreate(['name' => 'SuperAdmin']);
        $pengusul = Role::firstOrCreate(['name' => 'Pengusul']);

        // Assign Permissions (SuperAdmin gets all)
        $superAdmin->givePermissionTo(Permission::all());
        $pengusul->givePermissionTo(['view dashboard']); // Just an example
    }
}
