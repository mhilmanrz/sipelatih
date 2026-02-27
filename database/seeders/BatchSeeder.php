<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Act\Batch;

class BatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $batches = [
            ['name' => 'Batch 1'],
            ['name' => 'Batch 2'],
            ['name' => 'Batch 3'],
            ['name' => 'Batch 4'],
            ['name' => 'Batch 5'],
        ];

        foreach ($batches as $batch) {
            Batch::create($batch);
        }
    }
}
