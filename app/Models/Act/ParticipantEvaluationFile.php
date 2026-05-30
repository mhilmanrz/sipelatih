<?php

namespace App\Models\Act;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParticipantEvaluationFile extends Model
{
    protected $fillable = [
        'participant_evaluation_id',
        'file_path',
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
}
