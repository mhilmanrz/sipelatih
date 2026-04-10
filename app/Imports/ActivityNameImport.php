<?php

namespace App\Imports;

use App\Models\Act\ActivityName;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ActivityNameImport implements SkipsEmptyRows, ToModel, WithHeadingRow, WithValidation
{
    /**
     * @return Model|null
     */
    public function model(array $row)
    {
        // Check if the activity name already exists to avoid duplicates
        $existing = ActivityName::where('name', $row['nama_kegiatan'])->first();
        if ($existing) {
            return null;
        }

        $startDate = null;
        if (! empty($row['start_date'])) {
            $startDate = is_numeric($row['start_date'])
                ? Date::excelToDateTimeObject($row['start_date'])
                : Carbon::parse($row['start_date']);
        }

        $endDate = null;
        if (! empty($row['end_date'])) {
            $endDate = is_numeric($row['end_date'])
                ? Date::excelToDateTimeObject($row['end_date'])
                : Carbon::parse($row['end_date']);
        }

        return new ActivityName([
            'name' => $row['nama_kegiatan'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'year' => $row['year'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_kegiatan' => 'required|string|max:255',
        ];
    }
}
