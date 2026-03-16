<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityNameSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('activity_names')->insert([
            ['name' => 'Pelatihan Laravel Dasar', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Workshop Keamanan Aplikasi', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
