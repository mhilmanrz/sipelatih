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
            // === Referensi Dasar ===
            RolePermissionSeeder::class,
            SuperAdminSeeder::class,

            // === Master Profesi ===
            ProfessionCategorySeeder::class,
            ProfessionSeeder::class,
            EmploymentTypeSeeder::class,
            PositionSeeder::class,
            WorkUnitSeeder::class,

            // === Master Kegiatan ===
            ActivityTypeSeeder::class,
            ActivityFormatSeeder::class,
            ActivityScopeSeeder::class,
            ActivityMethodSeeder::class,
            ActivityCategorySeeder::class,
            ActivityNameSeeder::class,
            MaterialTypeSeeder::class,
            TargetParticipantSeeder::class,
            BatchSeeder::class,

            // === Anggaran ===
            FundSourceSeeder::class,
            BudgetCategorySeeder::class,
            BudgetSeeder::class,

            // === Data Kegiatan & Peserta ===
            UserSeeder::class,
            ActivitySeeder::class,
            ActivityProfessionSeeder::class,
            ActivityKakFileSeeder::class,
            ActivityMaterialSeeder::class,
            ActivitySpeakerSeeder::class,
            ActivityModeratorSeeder::class,
            ActivityParticipantSeeder::class,
            ActivityStatusSeeder::class,
            ActivityScoreSeeder::class,
        ]);
    }
}
