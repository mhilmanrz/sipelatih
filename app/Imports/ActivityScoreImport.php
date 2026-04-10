<?php

namespace App\Imports;

use App\Models\Act\ActivityParticipant;
use App\Models\Act\ActivityScore;
use App\Models\User\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ActivityScoreImport implements ToCollection, WithHeadingRow
{
    protected $activityId;

    public function __construct($activityId)
    {
        $this->activityId = $activityId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (! isset($row['nip'])) {
                continue;
            }

            $nip = trim($row['nip']);

            // Find user
            $user = User::where('employee_id', $nip)->first();
            if (! $user) {
                continue; // Skip if user not found
            }

            // Find participant for this activity
            $participant = ActivityParticipant::where('activity_id', $this->activityId)
                ->where('user_id', $user->id)
                ->first();

            if (! $participant) {
                continue; // Skip if they are not participants of this activity
            }

            // Extract is_passed
            $isPassedStr = isset($row['lulus_yatidak']) ? strtolower(trim($row['lulus_yatidak'])) : '';
            $isPassed = ($isPassedStr === 'ya' || $isPassedStr === 'y' || $isPassedStr === 'true' || $isPassedStr === '1');

            // Update participant passed status
            $participant->update([
                'is_passed' => $isPassed,
            ]);

            // Safely parse scores or null
            $preTest = isset($row['pre_test']) && $row['pre_test'] !== '' ? (int) $row['pre_test'] : null;
            $postTest = isset($row['post_test']) && $row['post_test'] !== '' ? (int) $row['post_test'] : null;
            $praktik = isset($row['praktik']) && $row['praktik'] !== '' ? (int) $row['praktik'] : null;

            // Update or Create score
            ActivityScore::updateOrCreate(
                ['activity_participant_id' => $participant->id],
                [
                    'pre_test_score' => $preTest,
                    'post_test_score' => $postTest,
                    'practice_score' => $praktik,
                ]
            );
        }
    }
}
