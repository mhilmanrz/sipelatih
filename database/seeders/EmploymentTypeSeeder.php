<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User\EmploymentType;

class EmploymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['code' => 'ASN', 'name' => 'Aparatur Sipil Negara'],
            ['code' => 'NON_ASN', 'name' => 'Non Aparatur Sipil Negara'],
            ['code' => 'MITRA', 'name' => 'Mitra Kerja'],
            ['code' => 'MAGANG', 'name' => 'Peserta Magang'],
            ['code' => 'RELAWAN', 'name' => 'Relawan'],
        ];

        foreach ($types as $type) {
            EmploymentType::create($type);
        }
    }
}
