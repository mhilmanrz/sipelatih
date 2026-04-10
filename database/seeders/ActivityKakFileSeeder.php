<?php

namespace Database\Seeders;

use App\Models\Act\ActivityKakFile;
use Illuminate\Database\Seeder;

class ActivityKakFileSeeder extends Seeder
{
    public function run(): void
    {
        $activityKakFiles = [
            ['activity_id' => 1, 'url' => 'https://example.com/kak/file1.pdf'],
            ['activity_id' => 1, 'url' => 'https://example.com/kak/file2.pdf'],
            ['activity_id' => 1, 'url' => 'https://example.com/kak/file3.pdf'],
            ['activity_id' => 1, 'url' => 'https://example.com/kak/file4.pdf'],
            ['activity_id' => 1, 'url' => 'https://example.com/kak/file5.pdf'],
        ];

        foreach ($activityKakFiles as $activityKakFile) {
            ActivityKakFile::create($activityKakFile);
        }
    }
}
