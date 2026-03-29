<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Act\ActivityStatus;

class ActivityStatusSeeder extends Seeder
{
    public function run(): void
    {
        $activityStatuses = [
            ['activity_id' => 1, 'status' => 'draft', 'note' => 'Kegiatan baru dibuat'],
            ['activity_id' => 1, 'status' => 'submitted', 'note' => 'Kegiatan telah diajukan'],
            ['activity_id' => 1, 'status' => 'revision', 'note' => 'Perlu revisi pada anggaran'],
            ['activity_id' => 1, 'status' => 'submitted', 'note' => 'Kegiatan diajukan kembali'],
            ['activity_id' => 1, 'status' => 'accepted', 'note' => 'Kegiatan disetujui'],
        ];

        foreach ($activityStatuses as $activityStatus) {
            ActivityStatus::create($activityStatus);
        }
    }
}
