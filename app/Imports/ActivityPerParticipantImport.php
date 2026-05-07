<?php

namespace App\Imports;

use App\Models\Act\Activity;
use App\Models\Act\ActivityCategory;
use App\Models\Act\ActivityComponentScore;
use App\Models\Act\ActivityFormat;
use App\Models\Act\ActivityMaterial;
use App\Models\Act\ActivityMethod;
use App\Models\Act\ActivityName;
use App\Models\Act\ActivityParticipant;
use App\Models\Act\ActivityScope;
use App\Models\Act\ActivityScoreComponent;
use App\Models\Act\ActivityScoreSetting;
use App\Models\Act\ActivityType;
use App\Models\Act\Batch;
use App\Models\Act\FundSource;
use App\Models\Act\MaterialType;
use App\Models\Act\TargetParticipant;
use App\Models\User\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ActivityPerParticipantImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (! isset($row['nama_pelatihan']) || ! isset($row['nip'])) {
                continue;
            }

            // Find valid user first, if user doesn't exist, ignore row.
            $nip = trim($row['nip']);
            $user = User::where('employee_id', $nip)->first();

            if (! $user) {
                continue;
            }

            // Find or create related dictionary items
            $activityName = $row['nama_pelatihan'] ? ActivityName::firstOrCreate(['name' => trim($row['nama_pelatihan'])]) : null;
            $activityType = $row['jenis_kegiatan'] ? ActivityType::firstOrCreate(['name' => trim($row['jenis_kegiatan'])]) : null;
            $activityCategory = isset($row['kategori_kegiatan']) && $row['kategori_kegiatan'] ? ActivityCategory::firstOrCreate(['name' => trim($row['kategori_kegiatan'])]) : null;
            $materialType = $row['jenis_materi'] ? MaterialType::firstOrCreate(['name' => trim($row['jenis_materi'])]) : null;
            $activityMethod = isset($row['metode']) && $row['metode'] ? ActivityMethod::firstOrCreate(['name' => trim($row['metode'])]) : null;
            $activityFormat = isset($row['bentuk_kegiatan']) && $row['bentuk_kegiatan'] ? ActivityFormat::firstOrCreate(['name' => trim($row['bentuk_kegiatan'])]) : null;
            $activityScope = isset($row['scope']) && $row['scope'] ? ActivityScope::firstOrCreate(['name' => trim($row['scope'])]) : null;
            $batch = isset($row['batch']) && $row['batch'] ? Batch::firstOrCreate(['name' => trim($row['batch'])]) : null;
            $targetParticipant = isset($row['target_peserta']) && $row['target_peserta'] ? TargetParticipant::firstOrCreate(['name' => trim($row['target_peserta'])]) : null;

            // Resolve fund source by name
            $fundSource = null;
            if (isset($row['sumber_dana']) && $row['sumber_dana']) {
                $fundSource = FundSource::firstOrCreate(['name' => trim($row['sumber_dana'])]);
            }

            // Parse dates
            $startDate = null;
            $endDate = null;
            try {
                if (! empty($row['tanggal_mulai'])) {
                    $startDate = Date::excelToDateTimeObject($row['tanggal_mulai'])->format('Y-m-d');
                }
            } catch (\Exception $e) {
                $startDate = date('Y-m-d', strtotime($row['tanggal_mulai']));
            }

            try {
                if (! empty($row['tanggal_selesai'])) {
                    $endDate = Date::excelToDateTimeObject($row['tanggal_selesai'])->format('Y-m-d');
                }
            } catch (\Exception $e) {
                $endDate = date('Y-m-d', strtotime($row['tanggal_selesai']));
            }

            // Parse times
            $startTime = isset($row['waktu_mulai']) && $row['waktu_mulai'] ? trim($row['waktu_mulai']) : null;
            $endTime = isset($row['waktu_selesai']) && $row['waktu_selesai'] ? trim($row['waktu_selesai']) : null;

            // Parse numeric fields
            $budgetAmount = isset($row['jumlah_anggaran']) && is_numeric($row['jumlah_anggaran']) ? (float) $row['jumlah_anggaran'] : null;
            $quotaParticipant = isset($row['kuota_peserta']) && is_numeric($row['kuota_peserta']) ? (int) $row['kuota_peserta'] : null;

            // Find or create activity — match on unique keys to avoid duplicates
            $activity = Activity::firstOrCreate([
                'activity_name_id' => $activityName ? $activityName->id : null,
                'activity_type_id' => $activityType ? $activityType->id : null,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'collaboration_inst' => isset($row['institusi']) ? trim($row['institusi']) : null,
            ], [
                'activity_category_id' => $activityCategory ? $activityCategory->id : null,
                'material_type_id' => $materialType ? $materialType->id : null,
                'activity_method_id' => $activityMethod ? $activityMethod->id : null,
                'activity_format_id' => $activityFormat ? $activityFormat->id : null,
                'activity_scope_id' => $activityScope ? $activityScope->id : null,
                'batch_id' => $batch ? $batch->id : null,
                'target_participant_id' => $targetParticipant ? $targetParticipant->id : null,
                'fund_source_id' => $fundSource ? $fundSource->id : null,
                'tempat' => isset($row['tempat']) ? trim($row['tempat']) : null,
                'tujuan' => isset($row['tujuan']) ? trim($row['tujuan']) : null,
                'justifikasi' => isset($row['justifikasi']) ? trim($row['justifikasi']) : null,
                'target_kompetensi' => isset($row['target_kompetensi']) ? trim($row['target_kompetensi']) : null,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'budget_amount' => $budgetAmount,
                'quota_participant' => $quotaParticipant,
                'work_unit_id' => $user->work_unit_id,
                'user_id' => $user->id,
            ]);

            // Save JPL if exists
            if (isset($row['jpl']) && is_numeric($row['jpl'])) {
                $jplValue = (float) $row['jpl'] * 45;
                $activityMaterial = ActivityMaterial::firstOrCreate(
                    ['activity_id' => $activity->id, 'name' => 'JPL'],
                    ['value' => $jplValue]
                );
                if ($activityMaterial->value != $jplValue) {
                    $activityMaterial->update(['value' => $jplValue]);
                }
            }

            // Assign user
            $participant = ActivityParticipant::updateOrCreate([
                'activity_id' => $activity->id,
                'user_id' => $user->id,
            ], [
                'certificate_number' => isset($row['no_sertifikat']) ? trim($row['no_sertifikat']) : null,
                'is_passed' => true,
            ]);

            // Handle default scoring (Post Test 100% and Threshold 0)
            ActivityScoreSetting::firstOrCreate(
                ['activity_id' => $activity->id],
                ['passing_threshold' => 0]
            );

            $scoreComponent = ActivityScoreComponent::firstOrCreate(
                ['activity_id' => $activity->id, 'name' => 'Post Test'],
                [
                    'type' => 'post_test',
                    'percentage' => 100,
                    'order' => 1,
                ]
            );

            // Save Nilai (default to 100 automatically since they are considered passed)
            ActivityComponentScore::updateOrCreate(
                [
                    'activity_participant_id' => $participant->id,
                    'activity_score_component_id' => $scoreComponent->id,
                ],
                [
                    'score' => 100,
                ]
            );
        }
    }
}
