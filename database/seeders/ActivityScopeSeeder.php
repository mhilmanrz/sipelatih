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
            ['name' => 'Internal'],
            ['name' => 'External'],
        ];

        foreach ($activityScopes as $activityScope) {
            ActivityScope::create($activityScope);
        }
    }
}
