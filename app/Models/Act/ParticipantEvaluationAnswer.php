<?php

namespace App\Models\Act;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParticipantEvaluationAnswer extends Model
{
    protected $fillable = [
        'participant_evaluation_id',
        'evaluation_criteria_id',
        'rating',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
        ];
    }

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(ParticipantEvaluation::class, 'participant_evaluation_id');
    }

    public function criteria(): BelongsTo
    {
        return $this->belongsTo(EvaluationCriteria::class, 'evaluation_criteria_id');
    }
}
