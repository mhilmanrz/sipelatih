<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Act\ActivityScope;

class ActivityScopeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activityScopes = [
            ['name' => 'Teknis'],
            ['name' => 'Manajerial'],
            ['name' => 'Sosiokultural'],
        ];

        foreach ($activityScopes as $activityScope) {
            ActivityScope::firstOrCreate($activityScope);
        }
    }
}
