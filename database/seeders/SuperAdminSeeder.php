<?php

namespace Database\Seeders;

use App\Models\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminRole = Role::firstOrCreate(['name' => 'SuperAdmin']);

        $user = User::firstOrCreate(
            ['email' => 'admin@sipelatih.com'],
            [
                'name' => 'Super Admin Diklat',
                'password' => Hash::make('password'),
            ]
        );

        // Assign Role
        if (! $user->hasRole('SuperAdmin')) {
            $user->assignRole($superAdminRole);
        }
    }
}
