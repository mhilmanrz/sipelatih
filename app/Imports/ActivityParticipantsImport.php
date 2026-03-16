<?php

namespace App\Imports;

use App\Models\Act\ActivityParticipant;
use App\Models\User\User;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class ActivityParticipantsImport implements ToCollection, WithHeadingRow
{
    protected int $activityId;
    protected array $imported = [];
    protected array $errors = [];

    public function __construct(int $activityId)
    {
        $this->activityId = $activityId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $nip = trim($row['nip'] ?? '');
            $name = trim($row['nama'] ?? '');

            if (empty($nip) && empty($name)) continue;

            // Cari user berdasarkan NIP (employee_id)
            $user = User::where('employee_id', $nip)->first();

            // Jika tidak ketemu by NIP, coba by nama
            if (!$user && $name) {
                $user = User::where('name', $name)->first();
            }

            if (!$user) {
                $this->errors[] = "User tidak ditemukan: NIP={$nip}, Nama={$name}";
                continue;
            }

            // Cek duplikat
            $exists = ActivityParticipant::where('activity_id', $this->activityId)
                ->where('user_id', $user->id)
                ->exists();

            if ($exists) {
                $this->errors[] = "{$user->name} sudah terdaftar di kegiatan ini.";
                continue;
            }

            ActivityParticipant::create([
                'activity_id' => $this->activityId,
                'user_id' => $user->id,
            ]);

            $this->imported[] = $user->name;
        }
    }

    public function getImported(): array
    {
        return $this->imported;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
