<?php

namespace App\Models\Act;

use Illuminate\Database\Eloquent\Model;

class ActivityComponentScore extends Model
{
    protected $fillable = [
        'activity_participant_id',
        'activity_score_component_id',
        'score',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
        ];
    }

    public function activityParticipant()
    {
        return $this->belongsTo(ActivityParticipant::class);
    }

    public function component()
    {
        return $this->belongsTo(ActivityScoreComponent::class, 'activity_score_component_id');
    }
}
