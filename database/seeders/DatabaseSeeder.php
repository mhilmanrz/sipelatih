<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BatchSeeder::class,
            ActivityFormatSeeder::class,
            ActivityTypeSeeder::class,
            ActivityScopeSeeder::class,
            MaterialTypeSeeder::class,
            WorkUnitSeeder::class,
            PositionSeeder::class,
            TargetParticipantSeeder::class,
            ProfessionSeeder::class,
            EmploymentTypeSeeder::class,
            BudgetCategorySeeder::class,
            BudgetSeeder::class,
            ActivitySeeder::class,
            ActivityProfessionSeeder::class,
            ActivityKakFileSeeder::class,
            ActivityMaterialSeeder::class,
            UserSeeder::class,
            ActivitySpeakerSeeder::class,
            ActivityModeratorSeeder::class,
            ActivityParticipantSeeder::class,
            ActivityStatusSeeder::class,
            ActivityScoreSeeder::class,
        ]);
    }
}
