<?php

namespace App\Imports;

use App\Models\Act\ActivityParticipant;
use App\Models\Act\ParticipantImportLog;
use App\Models\User\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ParticipantImport implements ToCollection, WithHeadingRow
{
    protected $activityId;

    protected $logId;

    public function __construct($activityId, $logId = null)
    {
        $this->activityId = $activityId;
        $this->logId = $logId;
    }

    public function collection(Collection $rows)
    {
        $log = $this->logId ? ParticipantImportLog::find($this->logId) : null;

        $total_rows = 0;
        $success_count = 0;
        $failed_count = 0;
        $errors = [];

        // Check if there is at least one row, and verify that 'nip' key exists in the first row's columns.
        if ($rows->isNotEmpty()) {
            $firstRow = $rows->first();
            $keys = [];
            if (is_array($firstRow)) {
                $keys = array_keys($firstRow);
            } elseif ($firstRow instanceof Collection) {
                $keys = $firstRow->keys()->toArray();
            } elseif (is_object($firstRow) && method_exists($firstRow, 'toArray')) {
                $keys = array_keys($firstRow->toArray());
            }

            if (! in_array('nip', $keys)) {
                throw new \Exception("Format file tidak valid. Kolom 'NIP' tidak ditemukan pada file Excel.");
            }
        }

        foreach ($rows as $index => $row) {
            $total_rows++;
            $rowNumber = $index + 2; // Row 1 is header

            if (! isset($row['nip'])) {
                $failed_count++;
                $errors[] = [
                    'row' => $rowNumber,
                    'nip' => '-',
                    'reason' => 'Kolom NIP tidak ditemukan pada baris ini.',
                ];

                continue;
            }

            $nip = trim($row['nip']);
            if (empty($nip)) {
                $failed_count++;
                $errors[] = [
                    'row' => $rowNumber,
                    'nip' => '-',
                    'reason' => 'NIP kosong.',
                ];

                continue;
            }

            // Skip example rows in the template
            if (isset($row['nama_opsional']) && str_contains($row['nama_opsional'], 'CONTOH - HAPUS BARIS INI')) {
                $total_rows--;

                continue;
            }

            $user = User::where('employee_id', $nip)->first();

            if (! $user) {
                $failed_count++;
                $errors[] = [
                    'row' => $rowNumber,
                    'nip' => $nip,
                    'reason' => "Pegawai dengan NIP '$nip' tidak ditemukan di database.",
                ];

                continue;
            }

            // Pastikan belum menjadi participant sebelumnya
            $exists = ActivityParticipant::where('activity_id', $this->activityId)
                ->where('user_id', $user->id)
                ->exists();

            if ($exists) {
                $failed_count++;
                $errors[] = [
                    'row' => $rowNumber,
                    'nip' => $nip,
                    'reason' => "Pegawai dengan NIP '$nip' sudah terdaftar di kegiatan ini.",
                ];

                continue;
            }

            try {
                ActivityParticipant::create([
                    'activity_id' => $this->activityId,
                    'user_id' => $user->id,
                    'is_passed' => false,
                ]);
                $success_count++;
            } catch (\Exception $e) {
                $failed_count++;
                $errors[] = [
                    'row' => $rowNumber,
                    'nip' => $nip,
                    'reason' => 'Gagal menyimpan ke database: '.$e->getMessage(),
                ];
            }
        }

        if ($log) {
            $log->update([
                'status' => 'completed',
                'total_rows' => $total_rows,
                'success_count' => $success_count,
                'failed_count' => $failed_count,
                'errors' => $errors,
            ]);
        }
    }
}
