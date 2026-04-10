<?php

namespace Database\Seeders;

use App\Models\Act\ActivityScope;
use Illuminate\Database\Seeder;

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
