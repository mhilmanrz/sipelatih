<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('activities')->insert([
            [
                'date' => Carbon::now()->toDateString(),
                'reference_number' => 'ACT-' . Str::upper(Str::random(6)),
                'name' => 'Pelatihan Laravel Dasar',
                'activity_type_id' => 1,
                'activity_scope_id' => 1,
                'material_type_id' => 1,
                'activity_method_id' => 1,
                'batch_id' => 1,
                'activity_format_id' => 1,
                'collaboration_inst' => 'PT Teknologi Nusantara',
                'target_participant_id' => 1, // ⚠️ pastikan tabel & ID valid
                'start_date' => Carbon::now()->toDateString(),
                'end_date' => Carbon::now()->addDays(3)->toDateString(),
                'budget_amount' => 15000000.00,
                'work_unit_id' => 1,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'date' => Carbon::now()->subDays(10)->toDateString(),
                'reference_number' => 'ACT-' . Str::upper(Str::random(6)),
                'name' => 'Workshop Keamanan Aplikasi',
                'activity_type_id' => 2,
                'activity_scope_id' => 2,
                'material_type_id' => 2,
                'activity_method_id' => 2,
                'batch_id' => 2,
                'activity_format_id' => 2,
                'collaboration_inst' => null,
                'target_participant_id' => 2,
                'start_date' => Carbon::now()->subDays(10)->toDateString(),
                'end_date' => Carbon::now()->subDays(8)->toDateString(),
                'budget_amount' => 8000000.00,
                'work_unit_id' => 1,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
