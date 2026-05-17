<?php

namespace App\Models\Act;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EvaluationCriteria extends Model
{
    use HasFactory;

    protected $table = 'evaluation_criteria';

    protected $fillable = [
        'code',
        'name',
        'is_fillable',
        'type',
        'evaluation_type',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'is_fillable' => 'boolean',
            'evaluation_type' => 'integer',
            'order' => 'integer',
        ];
    }

    public function activityEvaluationCriteria(): HasMany
    {
        return $this->hasMany(ActivityEvaluationCriteria::class, 'evaluation_criteria_id');
    }
}
