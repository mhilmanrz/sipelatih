<?php

namespace Database\Seeders;

use App\Models\User\User;
use App\Models\User\WorkUnit;
use App\Models\User\Positions;
use App\Models\User\EmploymentType;
use App\Models\User\Profession;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $workUnits = WorkUnit::all();
        $positions = Positions::all();
        $employmentTypes = EmploymentType::all();
        $professions = Profession::all();

        $users = [
            [
                'name' => 'Admin Sistem',
                'email' => 'admin@mail.com',
                'password' => Hash::make('password'),
                'work_unit_id' => $workUnits->where('code', 'TI')->first()->id ?? null,
                'position_id' => $positions->first()->id ?? null,
                'employment_type_id' => $employmentTypes->where('code', 'ASN')->first()->id ?? null,
                'profession_id' => $professions->where('code', 'PRO')->first()->id ?? null,
                'employee_id' => 'EMP001',
                'phone_number' => '081234567890',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@mail.com',
                'password' => Hash::make('password'),
                'work_unit_id' => $workUnits->where('code', 'KEU')->first()->id ?? null,
                'position_id' => $positions->skip(1)->first()->id ?? null,
                'employment_type_id' => $employmentTypes->where('code', 'ASN')->first()->id ?? null,
                'profession_id' => $professions->where('code', 'ACT')->first()->id ?? null,
                'employee_id' => 'EMP002',
                'phone_number' => '081234567891',
            ],
            [
                'name' => 'Siti Aminah',
                'email' => 'siti@mail.com',
                'password' => Hash::make('password'),
                'work_unit_id' => $workUnits->where('code', 'SDM')->first()->id ?? null,
                'position_id' => $positions->skip(2)->first()->id ?? null,
                'employment_type_id' => $employmentTypes->where('code', 'NON_ASN')->first()->id ?? null,
                'profession_id' => $professions->where('code', 'NUR')->first()->id ?? null,
                'employee_id' => 'EMP003',
                'phone_number' => '081234567892',
            ],
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad@mail.com',
                'password' => Hash::make('password'),
                'work_unit_id' => $workUnits->where('code', 'UMUM')->first()->id ?? null,
                'position_id' => $positions->skip(3)->first()->id ?? null,
                'employment_type_id' => $employmentTypes->where('code', 'MITRA')->first()->id ?? null,
                'profession_id' => $professions->where('code', 'DOC')->first()->id ?? null,
                'employee_id' => 'EMP004',
                'phone_number' => '081234567893',
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi@mail.com',
                'password' => Hash::make('password'),
                'work_unit_id' => $workUnits->where('code', 'DIKLAT')->first()->id ?? null,
                'position_id' => $positions->skip(4)->first()->id ?? null,
                'employment_type_id' => $employmentTypes->where('code', 'ASN')->first()->id ?? null,
                'profession_id' => $professions->where('code', 'BID')->first()->id ?? null,
                'employee_id' => 'EMP005',
                'phone_number' => '081234567894',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
