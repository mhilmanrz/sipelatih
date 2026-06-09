<?php

namespace App\Models\Act;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EvaluationCriteria extends Model
{
    use HasFactory;

    protected $table = 'evaluation_criteria';

    protected $fillable = [
        'code',
        'name',
        'type',
        'bottom_label',
        'top_label',
        'evaluation_type',
        'evaluation_category_id',
        'form_type',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'evaluation_type' => 'integer',
            'order' => 'integer',
        ];
    }

    public function activityEvaluationCriteria(): HasMany
    {
        return $this->hasMany(ActivityEvaluationCriteria::class, 'evaluation_criteria_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(EvaluationCategory::class, 'evaluation_category_id');
    }
}
