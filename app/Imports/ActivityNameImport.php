<?php

namespace App\Imports;

use App\Models\Act\ActivityName;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithValidation;

class ActivityNameImport implements ToModel, WithHeadingRow, SkipsEmptyRows, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Check if the activity name already exists to avoid duplicates
        $existing = ActivityName::where('name', $row['nama_kegiatan'])->first();
        if ($existing) {
            return null;
        }

        return new ActivityName([
            'name' => $row['nama_kegiatan'],
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_kegiatan' => 'required|string|max:255',
        ];
    }
}
