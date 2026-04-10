<?php

namespace Database\Seeders;

use App\Models\User\WorkUnit;
use Illuminate\Database\Seeder;

class WorkUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workUnits = [
            ['code' => 'TI', 'name' => 'Teknologi Informasi'],
            ['code' => 'SDM', 'name' => 'Sumber Daya Manusia'],
            ['code' => 'KEU', 'name' => 'Keuangan'],
            ['code' => 'UMUM', 'name' => 'Umum'],
            ['code' => 'DIKLAT', 'name' => 'Pendidikan dan Pelatihan'],
        ];

        foreach ($workUnits as $unit) {
            WorkUnit::updateOrCreate(
                ['code' => $unit['code']], // Check by code
                ['name' => $unit['name']]  // Update name if exists, or create new
            );
        }
    }
}
