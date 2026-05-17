<?php

namespace App\Models\Act;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityEvaluationCriteria extends Model
{
    use HasFactory;

    protected $table = 'activity_evaluation_criteria';

    protected $fillable = [
        'activity_evaluation_id',
        'evaluation_criteria_id',
        'value',
        'is_passed',
    ];

    protected function casts(): array
    {
        return [
            'is_passed' => 'boolean',
        ];
    }

    public function activityEvaluation(): BelongsTo
    {
        return $this->belongsTo(ActivityEvaluation::class, 'activity_evaluation_id');
    }

    public function criteria(): BelongsTo
    {
        return $this->belongsTo(EvaluationCriteria::class, 'evaluation_criteria_id');
    }
}
