<?php

namespace App\Models\Act;

use Illuminate\Database\Eloquent\Model;

class ActivityScoreSetting extends Model
{
    protected $fillable = [
        'activity_id',
        'passing_threshold',
    ];

    protected function casts(): array
    {
        return [
            'passing_threshold' => 'decimal:2',
        ];
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
