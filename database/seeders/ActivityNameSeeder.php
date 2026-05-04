<?php

namespace Database\Seeders;

use App\Models\Act\ActivityName;
use Illuminate\Database\Seeder;

class ActivityNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            ['name' => 'Pelatihan Manajemen Rumah Sakit', 'year' => 2025],
            ['name' => 'Pelatihan Keselamatan Pasien (Patient Safety)', 'year' => 2025],
            ['name' => 'Pelatihan Pencegahan & Pengendalian Infeksi (PPI)', 'year' => 2025],
            ['name' => 'Pelatihan Basic Life Support (BLS)', 'year' => 2025],
            ['name' => 'Pelatihan Komunikasi Efektif dalam Pelayanan', 'year' => 2025],
            ['name' => 'Pelatihan Etika dan Hukum Kesehatan', 'year' => 2025],
            ['name' => 'Pelatihan Penggunaan Sistem Informasi Rumah Sakit (SIMRS)', 'year' => 2026],
            ['name' => 'Pelatihan Koding Diagnosis (ICD-10)', 'year' => 2026],
        ];

        foreach ($names as $name) {
            ActivityName::firstOrCreate(['name' => $name['name']], $name);
        }
    }
}
