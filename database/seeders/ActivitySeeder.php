<?php

namespace Database\Seeders;

use App\Models\Act\Activity;
use App\Models\Act\ActivityCategory;
use App\Models\Act\ActivityFormat;
use App\Models\Act\ActivityMethod;
use App\Models\Act\ActivityName;
use App\Models\Act\ActivityScope;
use App\Models\Act\ActivityType;
use App\Models\Act\Batch;
use App\Models\Act\FundSource;
use App\Models\Act\MaterialType;
use App\Models\Act\TargetParticipant;
use App\Models\User\User;
use App\Models\User\WorkUnit;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@sipelatih.com')->first();

        $typePelatihan = ActivityType::where('name', 'Pelatihan')->first();
        $typeWorkshop = ActivityType::where('name', 'Workshop')->first();

        $catTeknis = ActivityCategory::where('name', 'Teknis')->first();
        $catSosialKultural = ActivityCategory::where('name', 'Sosial Kultural')->first();

        $scopeInternal = ActivityScope::where('name', 'Internal')->first();
        $scopeExternal = ActivityScope::where('name', 'External')->first();

        $matKurikulum = MaterialType::where('name', 'Kurikulum')->first();
        $matNonKurikulum = MaterialType::where('name', 'Non Kurikulum')->first();

        $methodLuring = ActivityMethod::where('name', 'Luring')->first();
        $methodDaring = ActivityMethod::where('name', 'Daring')->first();
        $methodBlanded = ActivityMethod::where('name', 'Blanded')->first();

        $batch1 = Batch::where('name', 'Batch 1')->first();
        $batch2 = Batch::where('name', 'Batch 2')->first();
        $batch3 = Batch::where('name', 'Batch 3')->first();

        $formatMandiri = ActivityFormat::where('name', 'Mandiri')->first();
        $formatKerjasama = ActivityFormat::where('name', 'Kerjasama')->first();

        $targetDokter = TargetParticipant::where('name', 'Dokter')->first();
        $targetPerawat = TargetParticipant::where('name', 'Perawat')->first();
        $targetUmum = TargetParticipant::where('name', 'Umum')->first();

        $wuDiklat = WorkUnit::where('code', 'DIKLAT')->first();
        $wuSdm = WorkUnit::where('code', 'SDM')->first();

        $fundBlud = FundSource::where('name', 'BLUD')->first();
        $fundApbn = FundSource::where('name', 'APBN')->first();
        $fundMandiri = FundSource::where('name', 'Dana Mandiri')->first();

        $namePatientSafety = ActivityName::where('name', 'Pelatihan Keselamatan Pasien (Patient Safety)')->first();
        $nameBls = ActivityName::where('name', 'Pelatihan Basic Life Support (BLS)')->first();
        $nameSimrs = ActivityName::where('name', 'Pelatihan Penggunaan Sistem Informasi Rumah Sakit (SIMRS)')->first();
        $nameKomunikasi = ActivityName::where('name', 'Pelatihan Komunikasi Efektif dalam Pelayanan')->first();
        $namePpi = ActivityName::where('name', 'Pelatihan Pencegahan & Pengendalian Infeksi (PPI)')->first();

        $activities = [
            // Kegiatan selesai — 2 bulan lalu
            [
                'reference_number' => 'DIKLAT/2026/001',
                'date' => Carbon::now()->subMonths(2)->toDateString(),
                'activity_name_id' => $namePatientSafety?->id,
                'activity_type_id' => $typePelatihan?->id,
                'activity_category_id' => $catTeknis?->id,
                'activity_scope_id' => $scopeInternal?->id,
                'material_type_id' => $matKurikulum?->id,
                'activity_method_id' => $methodLuring?->id,
                'batch_id' => $batch1?->id,
                'activity_format_id' => $formatMandiri?->id,
                'target_participant_id' => $targetPerawat?->id,
                'quota_participant' => 30,
                'start_date' => Carbon::now()->subMonths(2)->toDateString(),
                'end_date' => Carbon::now()->subMonths(2)->addDays(2)->toDateString(),
                'start_time' => '08:00:00',
                'end_time' => '16:00:00',
                'tempat' => 'Aula Rumah Sakit Lt. 3',
                'tujuan' => 'Meningkatkan pemahaman dan kemampuan tenaga kesehatan dalam menerapkan prinsip keselamatan pasien.',
                'justifikasi' => 'Tingginya angka insiden terkait keselamatan pasien di beberapa unit memerlukan penguatan kompetensi staf.',
                'target_kompetensi' => 'Peserta mampu mengidentifikasi risiko, melaporkan insiden, dan menerapkan budaya keselamatan pasien.',
                'budget_amount' => 18500000,
                'fund_source_id' => $fundBlud?->id,
                'work_unit_id' => $wuDiklat?->id,
                'user_id' => $admin?->id,
                'pic_user_id' => $admin?->id,
            ],

            // Kegiatan selesai — 3 minggu lalu
            [
                'reference_number' => 'DIKLAT/2026/002',
                'date' => Carbon::now()->subWeeks(3)->toDateString(),
                'activity_name_id' => $nameBls?->id,
                'activity_type_id' => $typeWorkshop?->id,
                'activity_category_id' => $catTeknis?->id,
                'activity_scope_id' => $scopeInternal?->id,
                'material_type_id' => $matKurikulum?->id,
                'activity_method_id' => $methodLuring?->id,
                'batch_id' => $batch2?->id,
                'activity_format_id' => $formatMandiri?->id,
                'target_participant_id' => $targetDokter?->id,
                'quota_participant' => 20,
                'start_date' => Carbon::now()->subWeeks(3)->toDateString(),
                'end_date' => Carbon::now()->subWeeks(3)->addDay()->toDateString(),
                'start_time' => '07:30:00',
                'end_time' => '15:30:00',
                'tempat' => 'Ruang Simulasi Klinik',
                'tujuan' => 'Membekali tenaga medis dengan keterampilan resusitasi jantung paru dan pertolongan pertama gawat darurat.',
                'justifikasi' => 'Kebutuhan sertifikasi BLS bagi seluruh dokter dan tenaga kesehatan sesuai standar akreditasi.',
                'target_kompetensi' => 'Peserta mampu melakukan RJP dengan benar, menggunakan AED, dan mengelola pasien gawat darurat.',
                'budget_amount' => 12000000,
                'fund_source_id' => $fundBlud?->id,
                'work_unit_id' => $wuDiklat?->id,
                'user_id' => $admin?->id,
                'pic_user_id' => $admin?->id,
            ],

            // Kegiatan akan datang — minggu depan
            [
                'reference_number' => 'DIKLAT/2026/003',
                'date' => Carbon::now()->addWeek()->toDateString(),
                'activity_name_id' => $nameSimrs?->id,
                'activity_type_id' => $typePelatihan?->id,
                'activity_category_id' => $catTeknis?->id,
                'activity_scope_id' => $scopeInternal?->id,
                'material_type_id' => $matNonKurikulum?->id,
                'activity_method_id' => $methodDaring?->id,
                'batch_id' => $batch1?->id,
                'activity_format_id' => $formatKerjasama?->id,
                'collaboration_inst' => 'PT Medika Sistem Indonesia',
                'target_participant_id' => $targetUmum?->id,
                'quota_participant' => 50,
                'start_date' => Carbon::now()->addWeek()->toDateString(),
                'end_date' => Carbon::now()->addWeek()->addDays(2)->toDateString(),
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'tempat' => 'Online via Zoom',
                'tujuan' => 'Meningkatkan kemampuan penggunaan SIMRS bagi seluruh unit pelayanan rumah sakit.',
                'justifikasi' => 'Implementasi modul baru SIMRS memerlukan pelatihan ulang agar proses pelayanan berjalan optimal.',
                'target_kompetensi' => 'Peserta mampu mengoperasikan modul pendaftaran, rawat inap, dan pelaporan pada SIMRS terbaru.',
                'budget_amount' => 9500000,
                'fund_source_id' => $fundApbn?->id,
                'work_unit_id' => $wuDiklat?->id,
                'user_id' => $admin?->id,
                'pic_user_id' => $admin?->id,
            ],

            // Kegiatan selesai — sebulan lalu, eksternal
            [
                'reference_number' => 'DIKLAT/2026/004',
                'date' => Carbon::now()->subMonth()->toDateString(),
                'activity_name_id' => $namePpi?->id,
                'activity_type_id' => $typePelatihan?->id,
                'activity_category_id' => $catTeknis?->id,
                'activity_scope_id' => $scopeExternal?->id,
                'material_type_id' => $matKurikulum?->id,
                'activity_method_id' => $methodBlanded?->id,
                'batch_id' => $batch3?->id,
                'activity_format_id' => $formatMandiri?->id,
                'target_participant_id' => $targetPerawat?->id,
                'quota_participant' => 40,
                'start_date' => Carbon::now()->subMonth()->toDateString(),
                'end_date' => Carbon::now()->subMonth()->addDays(3)->toDateString(),
                'start_time' => '08:00:00',
                'end_time' => '16:00:00',
                'tempat' => 'Hotel Grand Sahid, Jakarta',
                'tujuan' => 'Memperkuat kompetensi tenaga kesehatan dalam pencegahan dan pengendalian infeksi nosokomial.',
                'justifikasi' => 'Peningkatan kasus infeksi nosokomial membutuhkan pembaruan protokol PPI berbasis bukti terkini.',
                'target_kompetensi' => 'Peserta mampu menerapkan kewaspadaan standar, isolasi, dan desinfeksi sesuai pedoman PPI nasional.',
                'budget_amount' => 24000000,
                'fund_source_id' => $fundBlud?->id,
                'work_unit_id' => $wuSdm?->id,
                'user_id' => $admin?->id,
                'pic_user_id' => $admin?->id,
            ],

            // Kegiatan berjalan — mulai kemarin
            [
                'reference_number' => 'DIKLAT/2026/005',
                'date' => Carbon::now()->subDay()->toDateString(),
                'activity_name_id' => $nameKomunikasi?->id,
                'activity_type_id' => $typePelatihan?->id,
                'activity_category_id' => $catSosialKultural?->id,
                'activity_scope_id' => $scopeInternal?->id,
                'material_type_id' => $matNonKurikulum?->id,
                'activity_method_id' => $methodLuring?->id,
                'batch_id' => $batch1?->id,
                'activity_format_id' => $formatMandiri?->id,
                'target_participant_id' => $targetUmum?->id,
                'quota_participant' => 35,
                'start_date' => Carbon::now()->subDay()->toDateString(),
                'end_date' => Carbon::now()->addDays(2)->toDateString(),
                'start_time' => '08:30:00',
                'end_time' => '15:30:00',
                'tempat' => 'Ruang Pelatihan Gedung B Lt. 2',
                'tujuan' => 'Meningkatkan keterampilan komunikasi terapeutik dan interpersonal staf dalam memberikan pelayanan pasien.',
                'justifikasi' => 'Hasil survei kepuasan pasien menunjukkan kebutuhan peningkatan komunikasi antara tenaga kesehatan dan pasien.',
                'target_kompetensi' => 'Peserta mampu menerapkan komunikasi efektif, empatik, dan asertif dalam situasi klinis dan non-klinis.',
                'budget_amount' => 7500000,
                'fund_source_id' => $fundMandiri?->id,
                'work_unit_id' => $wuDiklat?->id,
                'user_id' => $admin?->id,
                'pic_user_id' => $admin?->id,
            ],
        ];

        foreach ($activities as $data) {
            Activity::updateOrCreate(
                ['reference_number' => $data['reference_number']],
                $data,
            );
        }
    }
}
