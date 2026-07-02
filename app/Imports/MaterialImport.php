<?php

namespace App\Imports;

use App\Models\Act\ActivityMaterial;
use App\Models\Act\MaterialImportLog;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MaterialImport implements ToCollection, WithHeadingRow
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
        $log = $this->logId ? MaterialImportLog::find($this->logId) : null;

        $total_rows = 0;
        $success_count = 0;
        $failed_count = 0;
        $errors = [];

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

            if (! in_array('nama_materi', $keys)) {
                throw new \Exception("Format file tidak valid. Kolom 'NAMA_MATERI' tidak ditemukan pada file Excel.");
            }
        }

        foreach ($rows as $index => $row) {
            $total_rows++;
            $rowNumber = $index + 2; // Row 1 is header

            $name = trim((string) ($row['nama_materi'] ?? ''));
            if (empty($name)) {
                $failed_count++;
                $errors[] = [
                    'row' => $rowNumber,
                    'item' => '-',
                    'reason' => 'Nama Materi kosong.',
                ];

                continue;
            }

            if (str_contains($name, 'CONTOH - HAPUS BARIS INI')) {
                $total_rows--;

                continue;
            }

            $value = $row['menit'] ?? null;
            if (! is_numeric($value) || $value <= 0) {
                $failed_count++;
                $errors[] = [
                    'row' => $rowNumber,
                    'item' => $name,
                    'reason' => 'Kolom MENIT wajib diisi angka lebih dari 0.',
                ];

                continue;
            }

            try {
                ActivityMaterial::create([
                    'activity_id' => $this->activityId,
                    'name' => $name,
                    'value' => $value,
                ]);
                $success_count++;
            } catch (\Exception $e) {
                $failed_count++;
                $errors[] = [
                    'row' => $rowNumber,
                    'item' => $name,
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
