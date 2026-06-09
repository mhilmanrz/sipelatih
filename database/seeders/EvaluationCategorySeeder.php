<?php

namespace Database\Seeders;

use App\Models\Act\EvaluationCategory;
use Illuminate\Database\Seeder;

class EvaluationCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Level 1: Evaluasi Kegiatan (Activity Categories)
            [
                'name' => 'Pelayanan Administrasi',
                'evaluation_type' => 1,
                'form_type' => 'activity',
                'order' => 1,
            ],
            [
                'name' => 'Sarana dan Fasilitas',
                'evaluation_type' => 1,
                'form_type' => 'activity',
                'order' => 2,
            ],
            [
                'name' => 'Metode dan Proses Pembelajaran',
                'evaluation_type' => 1,
                'form_type' => 'activity',
                'order' => 3,
            ],
            [
                'name' => 'Kepuasan dan Keberlanjutan Program',
                'evaluation_type' => 1,
                'form_type' => 'activity',
                'order' => 4,
            ],

            // Level 3: Evaluasi Implementasi Pelatihan
            [
                'name' => 'Penetapan Pencapaian Target',
                'evaluation_type' => 3,
                'form_type' => null,
                'order' => 1,
            ],
            [
                'name' => 'Kesesuaian dengan Standar Akreditasi & Kompetensi',
                'evaluation_type' => 3,
                'form_type' => null,
                'order' => 2,
            ],
            [
                'name' => 'Fokus pada Keselamatan Pasien (Patient Safety)',
                'evaluation_type' => 3,
                'form_type' => null,
                'order' => 3,
            ],
            [
                'name' => 'Implementasi di Tempat Kerja (Transfer of Training)',
                'evaluation_type' => 3,
                'form_type' => null,
                'order' => 4,
            ],
            [
                'name' => 'Evaluasi Efektivitas Pelatihan (Outcome-Based)',
                'evaluation_type' => 3,
                'form_type' => null,
                'order' => 5,
            ],
            [
                'name' => 'Data Dukung / Rekomendasi / Saran',
                'evaluation_type' => 3,
                'form_type' => null,
                'order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            EvaluationCategory::updateOrCreate(
                ['name' => $category['name'], 'evaluation_type' => $category['evaluation_type']],
                $category,
            );
        }
    }
}
