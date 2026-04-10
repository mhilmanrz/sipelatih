<?php

namespace App\Imports;

use App\Models\Act\Activity;
use App\Models\Act\ActivityFormat;
use App\Models\Act\ActivityMaterial;
use App\Models\Act\ActivityMethod;
use App\Models\Act\ActivityName;
use App\Models\Act\ActivityParticipant;
use App\Models\Act\ActivityType;
use App\Models\Act\MaterialType;
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
            $materialType = $row['jenis_materi'] ? MaterialType::firstOrCreate(['name' => trim($row['jenis_materi'])]) : null;
            $activityMethod = isset($row['metode']) && $row['metode'] ? ActivityMethod::firstOrCreate(['name' => trim($row['metode'])]) : null;
            $activityFormat = isset($row['bentuk_kegiatan']) && $row['bentuk_kegiatan'] ? ActivityFormat::firstOrCreate(['name' => trim($row['bentuk_kegiatan'])]) : null;

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

            // Cari activity existing dengan parameters yang sama supaya tidak duplikat untuk peserta yang beda di row selanjut nya
            $activity = Activity::firstOrCreate([
                'activity_name_id' => $activityName ? $activityName->id : null,
                'activity_type_id' => $activityType ? $activityType->id : null,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'collaboration_inst' => isset($row['institusi']) ? trim($row['institusi']) : null,
            ], [
                'material_type_id' => $materialType ? $materialType->id : null,
                'activity_method_id' => $activityMethod ? $activityMethod->id : null,
                'activity_format_id' => $activityFormat ? $activityFormat->id : null,
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
            ActivityParticipant::updateOrCreate([
                'activity_id' => $activity->id,
                'user_id' => $user->id,
            ], [
                'certificate_number' => isset($row['no_sertifikat']) ? trim($row['no_sertifikat']) : null,
                'is_passed' => isset($row['no_sertifikat']) && trim($row['no_sertifikat']) != '' ? true : false,
            ]);
        }
    }
}
