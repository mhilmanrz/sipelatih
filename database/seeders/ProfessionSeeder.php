<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profession;

class ProfessionSeeder extends Seeder
{
    public function run(): void
    {
        $professions = [
            'Dokter',
            'Perawat',
            'Apoteker',
            'Bidan',
            'Programmer',
            'Guru',
            'Akuntan',
            'Arsitek',
            'Teknisi',
            'Wiraswasta',
        ];

        foreach ($professions as $profession) {
            Profession::firstOrCreate([
                'name' => $profession
            ]);
        }
    }
}
