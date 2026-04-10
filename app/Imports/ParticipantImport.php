<?php

namespace App\Imports;

use App\Models\Act\ActivityParticipant;
use App\Models\User\User;
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
            // Kita coba membaca header 'nip' / 'NIP' via array mapping WithHeadingRow
            if (! isset($row['nip'])) {
                continue; // Lemah jika tidak ada kolom nip
            }

            $nip = trim($row['nip']);
            if (empty($nip)) {
                continue;
            }

            $user = User::where('nip', $nip)->first();

            // Jika User ditemukan
            if ($user) {
                // Pastikan belum menjadi participant sebelumnya
                $exists = ActivityParticipant::where('activity_id', $this->activityId)
                    ->where('user_id', $user->id)
                    ->exists();

                if (! $exists) {
                    ActivityParticipant::create([
                        'activity_id' => $this->activityId,
                        'user_id' => $user->id,
                        'is_passed' => false,
                    ]);
                }
            }
        }
    }
}
