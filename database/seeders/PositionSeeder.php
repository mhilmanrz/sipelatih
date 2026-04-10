<?php

namespace Database\Seeders;

use App\Models\User\Positions;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            ['code' => 'DIR', 'name' => 'Direktur'],
            ['code' => 'MGR', 'name' => 'Manager'],
            ['code' => 'SPV', 'name' => 'Supervisor'],
            ['code' => 'STF', 'name' => 'Staff'],
            ['code' => 'ADM', 'name' => 'Admin'],
        ];

        foreach ($positions as $position) {
            Positions::create($position);
        }
    }
}
