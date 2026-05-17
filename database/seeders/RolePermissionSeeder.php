<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // All system permissions
        $permissions = [
            // General & Dashboard
            'view dashboard',

            // Usulan Diklat
            'view usulan diklat',

            // Monitoring
            'view monitoring jpl',

            // Evaluasi & Laporan
            'view budget categories',
            'view pagu',
            'view kegiatan laporan',
            'view evaluasi',

            // Master Data - Pegawai & Akun
            'view users',
            'view accounts',
            'view professions',
            'view profession categories',
            'view roles',
            'view permissions',
            'view positions',
            'view ranks',
            'view work units',

            // Master Data - Dictionaries
            'view activity types',
            'view activity categories',
            'view activity scopes',
            'view material types',
            'view activity formats',
            'view activity methods',
            'view employment types',
            'view batches',
            'view fund sources',
            'view activity names',
            'view evaluation criteria',

            // Settings
            'view settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create Roles matching our sidebar and system needs
        $superadmin = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
        $perencanaan = Role::firstOrCreate(['name' => 'perencanaan', 'guard_name' => 'web']);
        $penyelenggara = Role::firstOrCreate(['name' => 'penyelenggara', 'guard_name' => 'web']);
        $evaluasi = Role::firstOrCreate(['name' => 'evaluasi', 'guard_name' => 'web']);
        $pengusul = Role::firstOrCreate(['name' => 'pengusul', 'guard_name' => 'web']);

        // Superadmin gets all permissions
        $superadmin->syncPermissions(Permission::all());

        // Assign some basic permissions to other roles as sensible defaults
        $pengusul->syncPermissions([
            'view dashboard',
            'view usulan diklat',
        ]);

        $perencanaan->syncPermissions([
            'view dashboard',
            'view usulan diklat',
            'view budget categories',
            'view pagu',
            'view kegiatan laporan',
        ]);

        $penyelenggara->syncPermissions([
            'view dashboard',
            'view usulan diklat',
            'view kegiatan laporan',
        ]);

        $evaluasi->syncPermissions([
            'view dashboard',
            'view usulan diklat',
            'view kegiatan laporan',
            'view evaluasi',
            'view evaluation criteria',
        ]);
    }
}
