<?php

namespace Database\Seeders;

use App\Models\Act\EvaluationCategory;
use App\Models\Act\EvaluationCriteria;
use Illuminate\Database\Seeder;

class EvaluationCriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch categories to get their IDs
        $catPelayanan = EvaluationCategory::where('evaluation_type', 1)->where('name', 'Pelayanan Administrasi')->first();
        $catSarana = EvaluationCategory::where('evaluation_type', 1)->where('name', 'Sarana dan Fasilitas')->first();
        $catMetode = EvaluationCategory::where('evaluation_type', 1)->where('name', 'Metode dan Proses Pembelajaran')->first();
        $catKepuasan = EvaluationCategory::where('evaluation_type', 1)->where('name', 'Kepuasan dan Keberlanjutan Program')->first();

        $catTarget = EvaluationCategory::where('evaluation_type', 3)->where('name', 'Penetapan Pencapaian Target')->first();
        $catAkreditasi = EvaluationCategory::where('evaluation_type', 3)->where('name', 'Kesesuaian dengan Standar Akreditasi & Kompetensi')->first();
        $catPatientSafety = EvaluationCategory::where('evaluation_type', 3)->where('name', 'Fokus pada Keselamatan Pasien (Patient Safety)')->first();
        $catImplementasi = EvaluationCategory::where('evaluation_type', 3)->where('name', 'Implementasi di Tempat Kerja (Transfer of Training)')->first();
        $catEfektivitas = EvaluationCategory::where('evaluation_type', 3)->where('name', 'Evaluasi Efektivitas Pelatihan (Outcome-Based)')->first();
        $catDataDukung = EvaluationCategory::where('evaluation_type', 3)->where('name', 'Data Dukung / Rekomendasi / Saran')->first();

        $criteria = [
            // ==========================================
            // Level 1: Scope Narasumber
            // ==========================================
            [
                'code' => 'E1-N01',
                'name' => 'Sistematika penyajian materi/bahan ajar',
                'type' => 'rating',
                'evaluation_type' => 1,
                'evaluation_category_id' => null,
                'form_type' => 'speaker',
                'order' => 1,
            ],
            [
                'code' => 'E1-N02',
                'name' => 'Kesesuaian materi/bahan ajar dengan pokok bahasan',
                'type' => 'rating',
                'evaluation_type' => 1,
                'evaluation_category_id' => null,
                'form_type' => 'speaker',
                'order' => 2,
            ],
            [
                'code' => 'E1-N03',
                'name' => 'Kemampuan menyampaikan substansi pokok bahasan',
                'type' => 'rating',
                'evaluation_type' => 1,
                'evaluation_category_id' => null,
                'form_type' => 'speaker',
                'order' => 3,
            ],
            [
                'code' => 'E1-N04',
                'name' => 'Penggunaan metode dan media pembelajaran (slide/transparan/power point alat bantu belajar mengajar)',
                'type' => 'rating',
                'evaluation_type' => 1,
                'evaluation_category_id' => null,
                'form_type' => 'speaker',
                'order' => 4,
            ],
            [
                'code' => 'E1-N05',
                'name' => 'Memberikan kesempatan peserta untuk bertanya, mengungkapkan pendapat secara bebas tanpa rasa takut/ragu',
                'type' => 'rating',
                'evaluation_type' => 1,
                'evaluation_category_id' => null,
                'form_type' => 'speaker',
                'order' => 5,
            ],
            [
                'code' => 'E1-N06',
                'name' => 'Kemampuan menumbuhkan daya tarik dan motivasi peserta berpartisipasi aktif dalam pelatihan',
                'type' => 'rating',
                'evaluation_type' => 1,
                'evaluation_category_id' => null,
                'form_type' => 'speaker',
                'order' => 6,
            ],
            [
                'code' => 'E1-N07',
                'name' => 'Ketepatan waktu Narasumber/Pengajar hadir dan menyampaikan materi/bahan ajar (durasi) sesuai jadwal',
                'type' => 'rating',
                'evaluation_type' => 1,
                'evaluation_category_id' => null,
                'form_type' => 'speaker',
                'order' => 7,
            ],

            // ==========================================
            // Level 1: Scope Kegiatan
            // ==========================================
            // Kategori: Pelayanan Administrasi
            [
                'code' => 'E1-K01',
                'name' => 'Pelayanan administrasi, distribusi materi/bahan ajar, Kit Pelatihan',
                'type' => 'rating',
                'evaluation_type' => 1,
                'evaluation_category_id' => $catPelayanan?->id,
                'form_type' => 'activity',
                'order' => 1,
            ],
            [
                'code' => 'E1-K02',
                'name' => 'Ketanggapan (respon) petugas dalam memberikan pelayanan administrasi pelatihan',
                'type' => 'rating',
                'evaluation_type' => 1,
                'evaluation_category_id' => $catPelayanan?->id,
                'form_type' => 'activity',
                'order' => 2,
            ],
            // Kategori: Sarana dan Fasilitas
            [
                'code' => 'E1-K03',
                'name' => 'Sarana Pelatihan (Meja, Kursi, Sound Sytem, LCD, Toilet)',
                'type' => 'rating',
                'evaluation_type' => 1,
                'evaluation_category_id' => $catSarana?->id,
                'form_type' => 'activity',
                'order' => 3,
            ],
            [
                'code' => 'E1-K04',
                'name' => 'Ketanggapan (respon) petugas dalam memenuhi kebutuhan sarana dan fasilitas pelatihan',
                'type' => 'rating',
                'evaluation_type' => 1,
                'evaluation_category_id' => $catSarana?->id,
                'form_type' => 'activity',
                'order' => 4,
            ],
            [
                'code' => 'E1-K05',
                'name' => 'Keramahan petugas dalam memenuhi kebutuhan sarana dan fasilitas pelatihan',
                'type' => 'rating',
                'evaluation_type' => 1,
                'evaluation_category_id' => $catSarana?->id,
                'form_type' => 'activity',
                'order' => 5,
            ],
            // Kategori: Metode dan Proses Pembelajaran
            [
                'code' => 'E1-K06',
                'name' => 'Metode pelatihan sesuai dengan kebutuhan pembelajaran orang dewasa',
                'type' => 'rating',
                'evaluation_type' => 1,
                'evaluation_category_id' => $catMetode?->id,
                'form_type' => 'activity',
                'order' => 6,
            ],
            [
                'code' => 'E1-K07',
                'name' => 'Pelatihan memberikan simulasi/praktik yang relevan',
                'type' => 'rating',
                'evaluation_type' => 1,
                'evaluation_category_id' => $catMetode?->id,
                'form_type' => 'activity',
                'order' => 7,
            ],
            [
                'code' => 'E1-K08',
                'name' => 'Evaluasi pembelajaran (pre-test/post-test) dilakukan dengan baik',
                'type' => 'rating',
                'evaluation_type' => 1,
                'evaluation_category_id' => $catMetode?->id,
                'form_type' => 'activity',
                'order' => 8,
            ],
            [
                'code' => 'E1-K09',
                'name' => 'Media pembelajaran mendukung pemahaman materi',
                'type' => 'rating',
                'evaluation_type' => 1,
                'evaluation_category_id' => $catMetode?->id,
                'form_type' => 'activity',
                'order' => 9,
            ],
            // Kategori: Kepuasan dan Keberlanjutan Program
            [
                'code' => 'E1-K10',
                'name' => 'Saya puas terhadap pelatihan ini',
                'type' => 'rating',
                'evaluation_type' => 1,
                'evaluation_category_id' => $catKepuasan?->id,
                'form_type' => 'activity',
                'order' => 10,
            ],
            [
                'code' => 'E1-K11',
                'name' => 'Pelatihan ini perlu dilanjutkan secara berkala',
                'type' => 'rating',
                'evaluation_type' => 1,
                'evaluation_category_id' => $catKepuasan?->id,
                'form_type' => 'activity',
                'order' => 11,
            ],
            [
                'code' => 'E1-K12',
                'name' => 'Pelatihan ini penting untuk peningkatan mutu layanan RS',
                'type' => 'rating',
                'evaluation_type' => 1,
                'evaluation_category_id' => $catKepuasan?->id,
                'form_type' => 'activity',
                'order' => 12,
            ],
            [
                'code' => 'E1-K13',
                'name' => 'Saya bersedia mengikuti pelatihan lanjutan',
                'type' => 'rating',
                'evaluation_type' => 1,
                'evaluation_category_id' => $catKepuasan?->id,
                'form_type' => 'activity',
                'order' => 13,
            ],
            [
                'code' => 'E1-K14',
                'name' => 'Saya merekomendasikan pelatihan ini kepada rekan kerja',
                'type' => 'rating',
                'evaluation_type' => 1,
                'evaluation_category_id' => $catKepuasan?->id,
                'form_type' => 'activity',
                'order' => 14,
            ],
            [
                'code' => 'E1-K15',
                'name' => 'Mohon saran/masukan Saudara/i untuk meningkatkan kualitas penyelenggaraan pelatihan di RSUPN Dr. Cipto Mangunkusumo',
                'type' => 'isian',
                'evaluation_type' => 1,
                'evaluation_category_id' => $catKepuasan?->id,
                'form_type' => 'activity',
                'order' => 15,
            ],

            // ==========================================
            // Level 3: Evaluasi Implementasi Pelatihan
            // ==========================================
            // Penetapan Pencapaian Target
            [
                'code' => 'E3-T01',
                'name' => 'Target/Tugas (3-6 bulan pasca pelatihan)',
                'type' => 'isian',
                'evaluation_type' => 3,
                'evaluation_category_id' => $catTarget?->id,
                'form_type' => null,
                'order' => 1,
            ],
            [
                'code' => 'E3-T02',
                'name' => 'Apakah Target/Penugasan Tercapai?',
                'type' => 'isian',
                'evaluation_type' => 3,
                'evaluation_category_id' => $catTarget?->id,
                'form_type' => null,
                'order' => 2,
            ],

            // A. Kesesuaian dengan Standar Akreditasi & Kompetensi
            [
                'code' => 'E3-A01',
                'name' => 'Pelatihan ini mendukung peningkatan kompetensi sesuai standar jabatan saya.',
                'type' => 'rating',
                'evaluation_type' => 3,
                'evaluation_category_id' => $catAkreditasi?->id,
                'form_type' => null,
                'order' => 1,
            ],
            [
                'code' => 'E3-A02',
                'name' => 'Materi pelatihan sesuai dengan standar pelayanan rumah sakit.',
                'type' => 'rating',
                'evaluation_type' => 3,
                'evaluation_category_id' => $catAkreditasi?->id,
                'form_type' => null,
                'order' => 2,
            ],
            [
                'code' => 'E3-A03',
                'name' => 'Pelatihan ini mengacu pada kebutuhan peningkatan mutu pelayanan.',
                'type' => 'rating',
                'evaluation_type' => 3,
                'evaluation_category_id' => $catAkreditasi?->id,
                'form_type' => null,
                'order' => 3,
            ],
            [
                'code' => 'E3-A04',
                'name' => 'Tujuan pelatihan selaras dengan indikator mutu unit kerja.',
                'type' => 'rating',
                'evaluation_type' => 3,
                'evaluation_category_id' => $catAkreditasi?->id,
                'form_type' => null,
                'order' => 4,
            ],
            [
                'code' => 'E3-A05',
                'name' => 'Pelatihan ini relevan dengan standar akreditasi.',
                'type' => 'rating',
                'evaluation_type' => 3,
                'evaluation_category_id' => $catAkreditasi?->id,
                'form_type' => null,
                'order' => 5,
            ],

            // B. Fokus pada Keselamatan Pasien (Patient Safety)
            [
                'code' => 'E3-B01',
                'name' => 'Materi pelatihan meningkatkan pemahaman saya tentang keselamatan pasien.',
                'type' => 'rating',
                'evaluation_type' => 3,
                'evaluation_category_id' => $catPatientSafety?->id,
                'form_type' => null,
                'order' => 1,
            ],
            [
                'code' => 'E3-B02',
                'name' => 'Pelatihan ini membantu mengurangi risiko kesalahan pelayanan.',
                'type' => 'rating',
                'evaluation_type' => 3,
                'evaluation_category_id' => $catPatientSafety?->id,
                'form_type' => null,
                'order' => 2,
            ],
            [
                'code' => 'E3-B03',
                'name' => 'Prinsip patient safety dijelaskan dengan jelas selama pelatihan.',
                'type' => 'rating',
                'evaluation_type' => 3,
                'evaluation_category_id' => $catPatientSafety?->id,
                'form_type' => null,
                'order' => 3,
            ],
            [
                'code' => 'E3-B04',
                'name' => 'Saya memahami penerapan patient safety setelah mengikuti pelatihan.',
                'type' => 'rating',
                'evaluation_type' => 3,
                'evaluation_category_id' => $catPatientSafety?->id,
                'form_type' => null,
                'order' => 4,
            ],
            [
                'code' => 'E3-B05',
                'name' => 'Pelatihan ini mendukung budaya keselamatan di unit kerja saya.',
                'type' => 'rating',
                'evaluation_type' => 3,
                'evaluation_category_id' => $catPatientSafety?->id,
                'form_type' => null,
                'order' => 5,
            ],

            // C. Implementasi di Tempat Kerja (Transfer of Training)
            [
                'code' => 'E3-C01',
                'name' => 'Saya menerapkan hasil pelatihan dalam pekerjaan sehari-hari.',
                'type' => 'rating',
                'evaluation_type' => 3,
                'evaluation_category_id' => $catImplementasi?->id,
                'form_type' => null,
                'order' => 1,
            ],
            [
                'code' => 'E3-C02',
                'name' => 'Pelatihan ini membantu meningkatkan kualitas pelayanan pasien.',
                'type' => 'rating',
                'evaluation_type' => 3,
                'evaluation_category_id' => $catImplementasi?->id,
                'form_type' => null,
                'order' => 2,
            ],
            [
                'code' => 'E3-C03',
                'name' => 'Materi pelatihan dapat langsung diaplikasikan di unit kerja.',
                'type' => 'rating',
                'evaluation_type' => 3,
                'evaluation_category_id' => $catImplementasi?->id,
                'form_type' => null,
                'order' => 3,
            ],
            [
                'code' => 'E3-C04',
                'name' => 'Atasan mendukung penerapan hasil pelatihan.',
                'type' => 'rating',
                'evaluation_type' => 3,
                'evaluation_category_id' => $catImplementasi?->id,
                'form_type' => null,
                'order' => 4,
            ],
            [
                'code' => 'E3-C05',
                'name' => 'Terdapat rencana tindak lanjut setelah pelatihan.',
                'type' => 'rating',
                'evaluation_type' => 3,
                'evaluation_category_id' => $catImplementasi?->id,
                'form_type' => null,
                'order' => 5,
            ],

            // D. Evaluasi Efektivitas Pelatihan (Outcome-Based)
            [
                'code' => 'E3-D01',
                'name' => 'Pelatihan ini meningkatkan kinerja saya secara nyata.',
                'type' => 'rating',
                'evaluation_type' => 3,
                'evaluation_category_id' => $catEfektivitas?->id,
                'form_type' => null,
                'order' => 1,
            ],
            [
                'code' => 'E3-D02',
                'name' => 'Pelatihan berkontribusi terhadap peningkatan indikator mutu.',
                'type' => 'rating',
                'evaluation_type' => 3,
                'evaluation_category_id' => $catEfektivitas?->id,
                'form_type' => null,
                'order' => 2,
            ],
            [
                'code' => 'E3-D03',
                'name' => 'Pelatihan membantu menurunkan insiden/kesalahan kerja.',
                'type' => 'rating',
                'evaluation_type' => 3,
                'evaluation_category_id' => $catEfektivitas?->id,
                'form_type' => null,
                'order' => 3,
            ],
            [
                'code' => 'E3-D04',
                'name' => 'Kompetensi saya meningkat setelah pelatihan.',
                'type' => 'rating',
                'evaluation_type' => 3,
                'evaluation_category_id' => $catEfektivitas?->id,
                'form_type' => null,
                'order' => 4,
            ],
            [
                'code' => 'E3-D05',
                'name' => 'Pelatihan ini berdampak pada peningkatan kepuasan pasien.',
                'type' => 'rating',
                'evaluation_type' => 3,
                'evaluation_category_id' => $catEfektivitas?->id,
                'form_type' => null,
                'order' => 5,
            ],

            // Data Dukung / Rekomendasi / Saran
            [
                'code' => 'E3-S01',
                'name' => 'Data Dukung/Dokumentasi',
                'type' => 'file',
                'evaluation_type' => 3,
                'evaluation_category_id' => $catDataDukung?->id,
                'form_type' => null,
                'order' => 1,
            ],
            [
                'code' => 'E3-S02',
                'name' => 'Rekomendasi/Saran Atasan Langsung untuk Pegawai',
                'type' => 'isian',
                'evaluation_type' => 3,
                'evaluation_category_id' => $catDataDukung?->id,
                'form_type' => null,
                'order' => 2,
            ],
        ];

        foreach ($criteria as $c) {
            EvaluationCriteria::updateOrCreate(
                ['code' => $c['code']],
                $c,
            );
        }
    }
}
