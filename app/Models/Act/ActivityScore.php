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

    protected $casts = [
        'pre_test_score' => 'float',
        'post_test_score' => 'float',
        'practice_score' => 'float',
    ];

    public function activityParticipant()
    {
        return $this->belongsTo(ActivityParticipant::class);
    }
}
