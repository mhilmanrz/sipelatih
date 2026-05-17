<?php

namespace Database\Seeders;

use App\Models\Act\EvaluationCriteria;
use Illuminate\Database\Seeder;

class EvaluationCriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $criteria = [
            // Level 1: Evaluasi Penyelenggaraan / Kepuasan
            [
                'code' => 'E1-01',
                'name' => 'Kelayakan Sarana dan Prasarana Penyelenggaraan',
                'is_fillable' => true,
                'type' => 'number',
                'evaluation_type' => 1,
                'order' => 1,
            ],
            [
                'code' => 'E1-02',
                'name' => 'Kesesuaian Jadwal dengan Rencana Pembelajaran',
                'is_fillable' => false,
                'type' => 'string',
                'evaluation_type' => 1,
                'order' => 2,
            ],
            [
                'code' => 'E1-03',
                'name' => 'Rata-rata Evaluasi Kinerja Pengajar/Narasumber (Skala 1-4)',
                'is_fillable' => true,
                'type' => 'number',
                'evaluation_type' => 1,
                'order' => 3,
            ],

            // Level 2: Evaluasi Hasil Belajar
            [
                'code' => 'E2-01',
                'name' => 'Rata-rata Nilai Kelulusan Post-Test Peserta',
                'is_fillable' => true,
                'type' => 'number',
                'evaluation_type' => 2,
                'order' => 1,
            ],
            [
                'code' => 'E2-02',
                'name' => 'Ketuntasan Praktik Mandiri / Penugasan Kelompok',
                'is_fillable' => false,
                'type' => 'string',
                'evaluation_type' => 2,
                'order' => 2,
            ],
            [
                'code' => 'E2-03',
                'name' => 'Persentase Kehadiran Peserta (Minimal 80%)',
                'is_fillable' => true,
                'type' => 'number',
                'evaluation_type' => 2,
                'order' => 3,
            ],

            // Level 3: Evaluasi Dampak (Transfer of Learning)
            [
                'code' => 'E3-01',
                'name' => 'Peningkatan Indeks Kinerja Individu Pasca-Pelatihan',
                'is_fillable' => true,
                'type' => 'number',
                'evaluation_type' => 3,
                'order' => 1,
            ],
            [
                'code' => 'E3-02',
                'name' => 'Keterangan Hasil Observasi Atasan Langsung',
                'is_fillable' => true,
                'type' => 'string',
                'evaluation_type' => 3,
                'order' => 2,
            ],
            [
                'code' => 'E3-03',
                'name' => 'Realisasi Implementasi Rencana Aksi Kerja (Rencana Aksi)',
                'is_fillable' => false,
                'type' => 'string',
                'evaluation_type' => 3,
                'order' => 3,
            ],
        ];

        foreach ($criteria as $c) {
            EvaluationCriteria::create($c);
        }
    }
}
