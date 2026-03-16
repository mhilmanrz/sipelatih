<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User\Position;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Position = [
            ['code' => 'DIR', 'name' => 'Direktur'],
            ['code' => 'MGR', 'name' => 'Manager'],
            ['code' => 'SPV', 'name' => 'Supervisor'],
            ['code' => 'STF', 'name' => 'Staff'],
            ['code' => 'ADM', 'name' => 'Admin'],
        ];

        foreach ($Position as $position) {
            Position::create($position);
        }
    }
}
