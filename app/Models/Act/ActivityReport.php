<?php

namespace App\Models\Act;

use Illuminate\Database\Eloquent\Model;

class ActivityReport extends Model
{
    protected $fillable = ['activity_id', 'file_path'];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
