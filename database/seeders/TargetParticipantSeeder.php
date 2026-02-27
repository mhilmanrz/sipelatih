<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Act\TargetParticipant;

class TargetParticipantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $targetParticipants = [
            ['name' => 'Dokter'],
            ['name' => 'Perawat'],
            ['name' => 'Tenaga Kesehatan Lainnya'],
            ['name' => 'Tenaga Non Kesehatan'],
            ['name' => 'Umum'],
        ];

        foreach ($targetParticipants as $targetParticipant) {
            TargetParticipant::create($targetParticipant);
        }
    }
}
