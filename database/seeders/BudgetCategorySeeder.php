<?php

namespace Database\Seeders;

use App\Models\BudgetCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BudgetCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        BudgetCategory::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $budgetCategories = [
            ['code' => '29107', 'name' => 'Studi Banding Pegawai Internal'],
            ['code' => '29108', 'name' => 'Pelatihan Teknis Bidang Kesehatan'],
            ['code' => '29109', 'name' => 'Kegiatan Pemenuhan Kompetensi Pegawai'],
            ['code' => '29110', 'name' => 'Studi Banding Diklat Eksternal'],
            ['code' => '29111', 'name' => 'Praktek Kerja Lapangan Mahasiswa'],
            ['code' => '29112', 'name' => 'Kegiatan Lembaga Sertifikasi Profesi'],
            ['code' => '29114', 'name' => 'Bantuan Dana Pendidikan Pegawai'],
            ['code' => '29115', 'name' => 'Orientasi Pegawai'],
            ['code' => '29116', 'name' => 'Pelatihan Manajerial Sosial Kultural (Umum)'],
            ['code' => '29117', 'name' => 'Pelatihan Teknis Kepegawaian'],
        ];

        foreach ($budgetCategories as $budgetCategory) {
            BudgetCategory::create($budgetCategory);
        }
    }
}
