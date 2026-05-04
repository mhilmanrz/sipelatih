<?php

namespace App\Imports;

use App\Models\Act\Activity;
use App\Models\Act\ActivityComponentScore;
use App\Models\Act\ActivityParticipant;
use App\Models\Act\ActivityScoreComponent;
use App\Models\User\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
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
        $activity = Activity::with('scoreSetting')->find($this->activityId);
        if (! $activity) {
            return;
        }

        $threshold = $activity->scoreSetting?->passing_threshold ?? 70;
        $components = ActivityScoreComponent::where('activity_id', $this->activityId)->get();

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

            // Save component scores
            foreach ($components as $component) {
                $headerKey = Str::slug($component->name, '_');

                if (isset($row[$headerKey]) && $row[$headerKey] !== '') {
                    $scoreValue = (float) $row[$headerKey];

                    ActivityComponentScore::updateOrCreate(
                        [
                            'activity_participant_id' => $participant->id,
                            'activity_score_component_id' => $component->id,
                        ],
                        [
                            'score' => $scoreValue,
                        ]
                    );
                }
            }

            // Recalculate final score and update is_passed
            $participant->load('componentScores');
            $finalScore = $participant->calculateFinalScore();

            $participant->update([
                'is_passed' => $finalScore !== null && $finalScore >= $threshold,
            ]);
        }
    }
}
