<?php

namespace App\Models\Act;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParticipantEvaluationFile extends Model
{
    protected $fillable = [
        'participant_evaluation_id',
        'evaluation_criteria_id',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'original_name',
        'size',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
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
