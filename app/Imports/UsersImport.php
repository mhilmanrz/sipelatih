<?php

namespace App\Imports;

use App\Models\User\EmploymentType;
use App\Models\User\Positions;
use App\Models\User\Profession;
use App\Models\User\Rank;
use App\Models\User\User;
use App\Models\User\WorkUnit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithChunkReading, WithHeadingRow
{
    protected string $passwordHash;

    protected array $workUnits = [];

    protected array $professions = [];

    protected array $positions = [];

    protected array $employmentTypes = [];

    protected array $ranks = [];

    protected array $existingEmails = [];

    public function __construct()
    {
        // 1. Hash password once to save massive CPU cycles (5000x bcrypt hashes takes ~10-15 mins)
        $this->passwordHash = Hash::make('password123');

        // 2. Preload lookup tables to memory to avoid 25,000+ sequential DB queries
        $this->workUnits = $this->getLookupMap(WorkUnit::class);
        $this->professions = $this->getLookupMap(Profession::class);
        $this->positions = $this->getLookupMap(Positions::class);
        $this->employmentTypes = $this->getLookupMap(EmploymentType::class);
        $this->ranks = $this->getLookupMap(Rank::class);

        // 3. Preload existing emails to avoid DB query per row
        $this->existingEmails = User::whereNotNull('email')
            ->pluck('email')
            ->mapWithKeys(fn ($email) => [strtolower(trim($email)) => true])
            ->all();
    }

    /**
     * @return Model|null
     */
    public function model(array $row)
    {
        if (empty($row['nama']) || empty($row['email'])) {
            return null;
        }

        $email = strtolower(trim($row['email']));

        // Check if email already exists in database
        if (isset($this->existingEmails[$email])) {
            return null;
        }

        // Register the email in memory to prevent duplicate entries within the same file
        $this->existingEmails[$email] = true;

        $workUnitId = $this->resolveIdByCode($this->workUnits, $row['unit_kerja'] ?? null);
        $professionId = $this->resolveIdByCode($this->professions, $row['profesi'] ?? null);
        $positionId = $this->resolveIdByCode($this->positions, $row['jabatan'] ?? null);
        $employmentTypeId = $this->resolveIdByCode($this->employmentTypes, $row['jenis_pegawai'] ?? null);
        $rankId = $this->resolveIdByCode($this->ranks, $row['pangkat'] ?? null);

        return new User([
            'name' => $row['nama'],
            'email' => $row['email'],
            'password' => $this->passwordHash,
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

    private function getLookupMap(string $modelClass): array
    {
        return $modelClass::pluck('id', 'code')
            ->mapWithKeys(fn ($id, $code) => [strtolower(trim($code)) => $id])
            ->all();
    }

    private function resolveIdByCode(array $lookupArray, ?string $code): ?int
    {
        if (blank($code)) {
            return null;
        }

        $key = strtolower(trim($code));

        return $lookupArray[$key] ?? null;
    }
}
