<?php

namespace App\Models\Act;

use Illuminate\Database\Eloquent\Model;

class ActivityGradeCategory extends Model
{
    protected $fillable = [
        'activity_id',
        'label',
        'min_score',
        'max_score',
        'color',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'min_score' => 'decimal:2',
            'max_score' => 'decimal:2',
            'order' => 'integer',
        ];
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function matches(float $score): bool
    {
        return $score >= $this->min_score && $score <= $this->max_score;
    }
}
