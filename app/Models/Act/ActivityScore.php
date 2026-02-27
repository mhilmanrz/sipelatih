<?php

namespace App\Models\Act;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivityScore extends Model
{
    use HasFactory;

    protected $table = 'activity_scores';

    protected $fillable = [
        'activity_participant_id',
        'pre_test_score',
        'post_test_score',
        'practice_score',
    ];

    public function activityParticipant()
    {
        return $this->belongsTo(ActivityParticipant::class);
    }
}
