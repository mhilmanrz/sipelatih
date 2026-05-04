<?php

namespace Database\Seeders;

use App\Models\Act\FundSource;
use Illuminate\Database\Seeder;

class FundSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fundSources = [
            ['name' => 'APBN'],
            ['name' => 'APBD'],
            ['name' => 'BLUD'],
            ['name' => 'Dana Mandiri'],
            ['name' => 'Hibah'],
        ];

        foreach ($fundSources as $fundSource) {
            FundSource::firstOrCreate(['name' => $fundSource['name']]);
        }
    }
}
