<?php

namespace Database\Seeders;

use App\Models\Act\Batch;
use Illuminate\Database\Seeder;

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
            ['name' => 'Batch 6'],
            ['name' => 'Batch 7'],
            ['name' => 'Batch 8'],
            ['name' => 'Batch 9'],
            ['name' => 'Batch 10'],
        ];

        foreach ($batches as $batch) {
            Batch::create($batch);
        }
    }
}
