<?php

namespace Database\Seeders;

use App\Models\Act\ActivityStatus;
use Illuminate\Database\Seeder;

class ActivityStatusSeeder extends Seeder
{
    public function run(): void
    {
        $activityStatuses = [
            ['activity_id' => 1, 'stage' => 'pengusul', 'status' => 'draft', 'note' => 'Kegiatan baru dibuat'],
            ['activity_id' => 1, 'stage' => 'perencanaan', 'status' => 'pending', 'note' => 'Kegiatan telah diajukan'],
            ['activity_id' => 1, 'stage' => 'pengusul', 'status' => 'revision', 'note' => 'Perlu revisi pada anggaran'],
            ['activity_id' => 1, 'stage' => 'perencanaan', 'status' => 'pending', 'note' => 'Kegiatan diajukan kembali'],
            ['activity_id' => 1, 'stage' => 'penyelenggara', 'status' => 'pending', 'note' => 'Kegiatan disetujui'],
        ];

        foreach ($activityStatuses as $activityStatus) {
            ActivityStatus::create($activityStatus);
        }
    }
}
