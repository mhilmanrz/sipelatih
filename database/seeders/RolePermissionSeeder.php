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

            // Document Permissions
            'view document formulir',
            'view document nota dinas',
            'view document surat pemanggilan',
            'view document surat tugas',

            // Master Data - Pegawai & Akun
            'view users',
            'view accounts',
            'view external persons',
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
            'view evaluation categories',

            // Settings
            'view settings',

            // Activity Tab Permissions
            'view activity tab kegiatan',
            'view activity tab dokumen',
            'view activity tab justifikasi',
            'view activity tab sasaran',
            'view activity tab kak',
            'view activity tab materi',
            'view activity tab narasumber',
            'view activity tab peserta',
            'view activity tab input-nilai',
            'view activity tab pengiriman',
            'view activity tab penilaian',
            'view activity tab sertifikat',
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

        // Classification-only roles for external narasumber/moderator: no
        // permissions, never used for login/menu access — they exist purely
        // to distinguish a person's capacity in the external directory.
        Role::firstOrCreate(['name' => 'narasumber eksternal', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'moderator eksternal', 'guard_name' => 'web']);

        // Superadmin gets all permissions
        $superadmin->syncPermissions(Permission::all());

        // Assign some basic permissions to other roles as sensible defaults
        $pengusul->syncPermissions([
            'view dashboard',
            'view usulan diklat',
            'view document formulir',
            'view document nota dinas',
            'view document surat pemanggilan',
            'view document surat tugas',
            'view activity tab kegiatan',
            'view activity tab dokumen',
            'view activity tab justifikasi',
            'view activity tab sasaran',
            'view activity tab kak',
            'view activity tab materi',
            'view activity tab narasumber',
            'view activity tab peserta',
            'view activity tab pengiriman',
        ]);

        $perencanaan->syncPermissions([
            'view dashboard',
            'view usulan diklat',
            'view budget categories',
            'view pagu',
            'view kegiatan laporan',
            'view document formulir',
            'view document nota dinas',
            'view document surat pemanggilan',
            'view document surat tugas',
            'view activity tab kegiatan',
            'view activity tab dokumen',
            'view activity tab justifikasi',
            'view activity tab sasaran',
            'view activity tab kak',
            'view activity tab materi',
            'view activity tab narasumber',
            'view activity tab peserta',
            'view activity tab pengiriman',
            'view activity tab penilaian',
        ]);

        $penyelenggara->syncPermissions([
            'view dashboard',
            'view usulan diklat',
            'view kegiatan laporan',
            'view external persons',
            'view document formulir',
            'view document nota dinas',
            'view document surat pemanggilan',
            'view document surat tugas',
            'view activity tab kegiatan',
            'view activity tab dokumen',
            'view activity tab justifikasi',
            'view activity tab sasaran',
            'view activity tab kak',
            'view activity tab materi',
            'view activity tab narasumber',
            'view activity tab peserta',
            'view activity tab input-nilai',
            'view activity tab pengiriman',
            'view activity tab sertifikat',
        ]);

        $evaluasi->syncPermissions([
            'view dashboard',
            'view usulan diklat',
            'view kegiatan laporan',
            'view evaluasi',
            'view evaluation criteria',
            'view evaluation categories',
            'view document formulir',
            'view document nota dinas',
            'view document surat pemanggilan',
            'view document surat tugas',
            'view activity tab kegiatan',
            'view activity tab dokumen',
            'view activity tab justifikasi',
            'view activity tab sasaran',
            'view activity tab kak',
            'view activity tab materi',
            'view activity tab narasumber',
            'view activity tab peserta',
            'view activity tab input-nilai',
            'view activity tab pengiriman',
            'view activity tab penilaian',
            'view activity tab sertifikat',
        ]);
    }
}
