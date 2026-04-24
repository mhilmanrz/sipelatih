<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Reference / lookup data
            BatchSeeder::class,
            ActivityFormatSeeder::class,
            ActivityTypeSeeder::class,
            ActivityMethodSeeder::class,
            ActivityScopeSeeder::class,
            MaterialTypeSeeder::class,
            WorkUnitSeeder::class,
            PositionSeeder::class,
            TargetParticipantSeeder::class,
            ProfessionSeeder::class,
            EmploymentTypeSeeder::class,

            // Budget
            BudgetCategorySeeder::class,
            BudgetSeeder::class,

            // Roles, permissions & users
            RolePermissionSeeder::class,
            SuperAdminSeeder::class,
            UserSeeder::class,

            // Activities & relations
            ActivitySeeder::class,
            ActivityKakFileSeeder::class,
            ActivityMaterialSeeder::class,
            ActivityProfessionSeeder::class,
            ActivitySpeakerSeeder::class,
            ActivityModeratorSeeder::class,
            ActivityParticipantSeeder::class,
            ActivityStatusSeeder::class,
            ActivityScoreSeeder::class,
        ]);
    }
}
