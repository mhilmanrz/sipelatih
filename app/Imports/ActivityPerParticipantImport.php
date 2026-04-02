<?php

namespace App\Imports;

use App\Models\Act\Activity;
use App\Models\Act\ActivityName;
use App\Models\Act\ActivityType;
use App\Models\Act\MaterialType;
use App\Models\User\WorkUnit;
use App\Models\User\User;
use App\Models\Act\ActivityParticipant;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ActivityPerParticipantImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (!isset($row['judul_kegiatan']) || !isset($row['nip_peserta'])) {
                continue;
            }

            // Find valid user first, if user doesn't exist, ignore row.
            $nip = trim($row['nip_peserta']);
            $user = User::where('nip', $nip)->first();
            
            if (!$user) {
                continue;
            }

            // Find or create related dictionary items
            $activityName = $row['judul_kegiatan'] ? ActivityName::firstOrCreate(['name' => trim($row['judul_kegiatan'])]) : null;
            $workUnit = $row['pengusul_unit_kerja'] ? WorkUnit::firstOrCreate(['name' => trim($row['pengusul_unit_kerja'])]) : null;
            $activityType = $row['jenis_kegiatan'] ? ActivityType::firstOrCreate(['name' => trim($row['jenis_kegiatan'])]) : null;
            $materialType = $row['jenis_materi'] ? MaterialType::firstOrCreate(['name' => trim($row['jenis_materi'])]) : null;

            // Parse dates
            $startDate = null;
            $endDate = null;
            try {
                if (!empty($row['waktu_mulai'])) {
                    $startDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['waktu_mulai'])->format('Y-m-d');
                }
            } catch (\Exception $e) {
                $startDate = date('Y-m-d', strtotime($row['waktu_mulai']));
            }

            try {
                if (!empty($row['waktu_selesai'])) {
                    $endDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['waktu_selesai'])->format('Y-m-d');
                }
            } catch (\Exception $e) {
                $endDate = date('Y-m-d', strtotime($row['waktu_selesai']));
            }

            // Cari activity existing dengan parameters yang sama supaya tidak duplikat untuk peserta yang beda di row selanjut nya
            $activity = Activity::firstOrCreate([
                'activity_name_id' => $activityName ? $activityName->id : null,
                'work_unit_id' => $workUnit ? $workUnit->id : null,
                'activity_type_id' => $activityType ? $activityType->id : null,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ], [
                'material_type_id' => $materialType ? $materialType->id : null,
            ]);

            // Assign user
            ActivityParticipant::firstOrCreate([
                'activity_id' => $activity->id,
                'user_id' => $user->id,
            ], [
                'is_passed' => false,
            ]);
        }
    }
}
