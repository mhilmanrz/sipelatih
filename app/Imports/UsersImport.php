<?php

namespace App\Imports;

use App\Models\User\User;
use App\Models\User\WorkUnit;
use App\Models\User\Positions;
use App\Models\User\EmploymentType;
use App\Models\User\Profession;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToCollection, WithHeadingRow
{
    protected int $rowCount = 0;

    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    private function getOrCreateMaster($modelClass, $name)
    {
        $name = trim($name);
        if (empty($name)) return null;

        $record = $modelClass::where('name', $name)->first();
        if ($record) {
            return $record;
        }

        // Generate acronym dari huruf pertama tiap kata
        $words = explode(' ', preg_replace('/[^a-zA-Z0-9\s]/', '', $name));
        $acronym = '';
        foreach ($words as $w) {
            if (!empty($w)) {
                $acronym .= strtoupper(substr($w, 0, 1));
            }
        }
        if (empty($acronym)) {
            $acronym = 'CODE'; // Fallback
        }

        // Cek duplikasi di DB agar aman dari constraint unik
        $originalAcronym = $acronym;
        $counter = 1;
        while ($modelClass::where('code', $acronym)->exists()) {
            $acronym = $originalAcronym . $counter;
            $counter++;
        }

        return $modelClass::create([
            'name' => $name,
            'code' => $acronym
        ]);
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // NIP dan NAMA wajib ada
            if (empty($row['nip']) || empty($row['nama'])) {
                continue;
            }

            $nip  = trim($row['nip']);
            $nama = trim($row['nama']);

            // Skip jika user dengan NIP ini sudah ada
            if (User::where('employee_id', $nip)->exists()) {
                continue;
            }

            // -- Resolusi relasi dengan auto-create singkatan huruf tiap kata --

            // Work Unit
            $workUnitId = null;
            $isTI       = false;
            if (!empty($row['unit_kerja'])) {
                $wuName   = trim($row['unit_kerja']);
                $workUnit = $this->getOrCreateMaster(WorkUnit::class, $wuName);
                $workUnitId = $workUnit->id;
                $isTI = (stripos($wuName, 'TI') !== false || stripos($wuName, 'Teknologi Informasi') !== false);
            }

            // Jabatan / Position
            $pos = $this->getOrCreateMaster(Positions::class, $row['jabatan'] ?? '');
            $positionId = $pos ? $pos->id : null;

            // Jenis Tenaga / Employment Type
            $emp = $this->getOrCreateMaster(EmploymentType::class, $row['jenis_kepegawaian'] ?? '');
            $employmentTypeId = $emp ? $emp->id : null;

            // Profesi / Profession
            $prof = $this->getOrCreateMaster(Profession::class, $row['profesi'] ?? '');
            $professionId = $prof ? $prof->id : null;

            // -- Email --
            $email = null;
            if (!empty($row['email'])) {
                $email = trim($row['email']);
                // Pastikan unik
                if (User::where('email', $email)->exists()) {
                    $email = 'user_' . uniqid() . '@peserta.lokal';
                }
            } elseif ($isTI) {
                // Email dummy hanya untuk unit TI
                $email = $nip . '@peserta.lokal';
                if (User::where('email', $email)->exists()) {
                    $email = 'user_' . uniqid() . '@peserta.lokal';
                }
            }

            // -- Password --
            $password = !empty($row['password'])
                ? bcrypt($row['password'])
                : bcrypt('password123');

            // -- Buat User --
            User::create([
                'employee_id'        => $nip,
                'name'               => $nama,
                'email'              => $email,
                'password'           => $password,
                'phone_number'       => !empty($row['no_hp']) ? trim($row['no_hp']) : null,
                'work_unit_id'       => $workUnitId,
                'position_id'        => $positionId,
                'employment_type_id' => $employmentTypeId,
                'profession_id'      => $professionId,
            ]);

            $this->rowCount++;
        }
    }
}
