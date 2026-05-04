<?php

namespace App\Models\Act;

use Illuminate\Database\Eloquent\Model;

class ActivityScoreComponent extends Model
{
    protected $fillable = [
        'activity_id',
        'name',
        'type',
        'percentage',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'percentage' => 'decimal:2',
            'order' => 'integer',
        ];
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function componentScores()
    {
        return $this->hasMany(ActivityComponentScore::class);
    }

    public function isPreTest(): bool
    {
        return $this->type === 'pre_test';
    }

    public function countsTowardFinalScore(): bool
    {
        return $this->type !== 'pre_test';
    }
}
