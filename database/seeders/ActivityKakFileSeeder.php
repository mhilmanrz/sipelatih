<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Act\ActivityKakFile;

class ActivityKakFileSeeder extends Seeder
{
    public function run(): void
    {
        $activityKakFiles = [
            ['activity_id' => 1, 'file_path' => 'kak-files/sample1.pdf', 'original_name' => 'KAK_Workshop_ICTEC.pdf'],
            ['activity_id' => 1, 'file_path' => 'kak-files/sample2.pdf', 'original_name' => 'KAK_Workshop_ICTEC_v2.pdf'],
        ];

        foreach ($activityKakFiles as $activityKakFile) {
            ActivityKakFile::create($activityKakFile);
        }
    }
}
