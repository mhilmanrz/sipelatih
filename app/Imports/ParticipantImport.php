<?php

namespace App\Imports;

use App\Models\Act\ActivityParticipant;
use App\Models\User\User;
use App\Models\User\WorkUnit;
use App\Models\User\Positions;
use App\Models\User\EmploymentType;
use App\Models\User\Profession;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ParticipantImport implements ToCollection, WithHeadingRow
{
    protected $activityId;

    public function __construct($activityId)
    {
        $this->activityId = $activityId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // NIP dan NAMA wajib ada
            if (!isset($row['nip']) || !isset($row['nama'])) {
                continue;
            }

            $nip  = trim($row['nip']);
            $nama = trim($row['nama']);

            if (empty($nip) || empty($nama)) {
                continue;
            }

            // Cari user yg sudah ada berdasarkan employee_id (NIP)
            $user = User::where('employee_id', $nip)->first();

            // Jika belum ada, buat user baru
            if (!$user) {

                // -- Resolusi relasi berdasarkan nama di Excel --

                // Work Unit
                $workUnitId = null;
                $isTI       = false;
                if (!empty($row['unit_kerja'])) {
                    $wuName   = trim($row['unit_kerja']);
                    $workUnit = WorkUnit::where('name', 'like', "%{$wuName}%")->first();
                    if ($workUnit) {
                        $workUnitId = $workUnit->id;
                        $isTI       = (stripos($wuName, 'TI') !== false || stripos($wuName, 'Teknologi Informasi') !== false);
                    }
                }

                // Jabatan / Position
                $positionId = null;
                if (!empty($row['jabatan'])) {
                    $pos = Positions::where('name', 'like', '%' . trim($row['jabatan']) . '%')->first();
                    $positionId = $pos?->id;
                }

                // Jenis Tenaga / Employment Type
                $employmentTypeId = null;
                if (!empty($row['jenis_kepegawaian'])) {
                    $emp = EmploymentType::where('name', 'like', '%' . trim($row['jenis_kepegawaian']) . '%')->first();
                    $employmentTypeId = $emp?->id;
                }

                // Profesi / Profession
                $professionId = null;
                if (!empty($row['profesi'])) {
                    $prof = Profession::where('name', 'like', '%' . trim($row['profesi']) . '%')->first();
                    $professionId = $prof?->id;
                }

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
                $user = User::create([
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
            }

            // -- Daftarkan sebagai peserta (jika belum) --
            $exists = ActivityParticipant::where('activity_id', $this->activityId)
                ->where('user_id', $user->id)
                ->exists();

            if (!$exists) {
                ActivityParticipant::create([
                    'activity_id' => $this->activityId,
                    'user_id'     => $user->id,
                    'is_passed'   => false,
                ]);
            }
        }
    }
}
