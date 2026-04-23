<?php

namespace App\Models\Act;

use Illuminate\Database\Eloquent\Model;

class ActivityTarget extends Model
{
    protected $fillable = ['activity_id', 'target_number', 'description'];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
