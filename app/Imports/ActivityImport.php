<?php

namespace App\Imports;

use App\Models\Act\Activity;
use App\Models\Act\ActivityName;
use App\Models\Act\ActivityType;
use App\Models\Act\MaterialType;
use App\Models\User\WorkUnit;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ActivityImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (! isset($row['judul_kegiatan'])) {
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
                if (! empty($row['waktu_mulai'])) {
                    // Coba parsing
                    $startDate = Date::excelToDateTimeObject($row['waktu_mulai'])->format('Y-m-d');
                }
            } catch (\Exception $e) {
                $startDate = date('Y-m-d', strtotime($row['waktu_mulai']));
            }

            try {
                if (! empty($row['waktu_selesai'])) {
                    $endDate = Date::excelToDateTimeObject($row['waktu_selesai'])->format('Y-m-d');
                }
            } catch (\Exception $e) {
                $endDate = date('Y-m-d', strtotime($row['waktu_selesai']));
            }

            Activity::create([
                'activity_name_id' => $activityName ? $activityName->id : null,
                'work_unit_id' => $workUnit ? $workUnit->id : null,
                'activity_type_id' => $activityType ? $activityType->id : null,
                'material_type_id' => $materialType ? $materialType->id : null,
                'start_date' => $startDate,
                'end_date' => $endDate,
                // Tambahan default lainnya jika model require
            ]);
        }
    }
}
