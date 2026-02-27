<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User\Profession;

class ProfessionSeeder extends Seeder
{
    public function run(): void
    {
        $professions = [
            ['name' => 'Dokter', 'code' => 'DOC'],
            ['name' => 'Perawat', 'code' => 'NUR'],
            ['name' => 'Apoteker', 'code' => 'APH'],
            ['name' => 'Bidan', 'code' => 'BID'],
            ['name' => 'Programmer', 'code' => 'PRO'],
            ['name' => 'Guru', 'code' => 'GUR'],
            ['name' => 'Akuntan', 'code' => 'ACT'],
            ['name' => 'Arsitek', 'code' => 'ARC'],
            ['name' => 'Teknisi', 'code' => 'TEC'],
            ['name' => 'Wiraswasta', 'code' => 'ENT'],
        ];

        foreach ($professions as $profession) {
            Profession::create($profession);
        }
    }
}
