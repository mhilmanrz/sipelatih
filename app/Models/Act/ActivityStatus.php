<?php

namespace App\Models\Act;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityStatus extends Model
{
    use HasFactory;

    protected $table = 'activity_statuses';

    protected $fillable = [
        'activity_id',
        'status',
        'note',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
