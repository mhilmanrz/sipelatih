<?php

namespace App\Imports;

use App\Models\User\EmploymentType;
use App\Models\User\Positions;
use App\Models\User\Profession;
use App\Models\User\Rank;
use App\Models\User\User;
use App\Models\User\WorkUnit;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ShouldQueue, ToModel, WithChunkReading, WithHeadingRow
{
    /**
     * @return Model|null
     */
    public function model(array $row)
    {
        if (empty($row['nama']) || empty($row['email'])) {
            return null;
        }

        if (User::where('email', $row['email'])->exists()) {
            return null;
        }

        $workUnitId = $this->resolveIdByCode(WorkUnit::class, $row['unit_kerja'] ?? null);
        $professionId = $this->resolveIdByCode(Profession::class, $row['profesi'] ?? null);
        $positionId = $this->resolveIdByCode(Positions::class, $row['jabatan'] ?? null);
        $employmentTypeId = $this->resolveIdByCode(EmploymentType::class, $row['jenis_pegawai'] ?? null);
        $rankId = $this->resolveIdByCode(Rank::class, $row['pangkat'] ?? null);

        return new User([
            'name' => $row['nama'],
            'email' => $row['email'],
            'password' => Hash::make('password123'),
            'phone_number' => $row['telepon'] ?? null,
            'employee_id' => $row['nip'] ?? null,
            'work_unit_id' => $workUnitId,
            'profession_id' => $professionId,
            'position_id' => $positionId,
            'employment_type_id' => $employmentTypeId,
            'rank_id' => $rankId,
            'npwp' => $row['npwp'] ?? null,
            'bank_name' => $row['nama_bank'] ?? null,
            'account_number' => $row['nomor_rekening'] ?? null,
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    private function resolveIdByCode(string $modelClass, ?string $code): ?int
    {
        if (blank($code)) {
            return null;
        }

        $record = $modelClass::where('code', $code)->first();

        return $record?->id;
    }
}
