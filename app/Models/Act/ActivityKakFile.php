<?php

namespace App\Models\Act;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityKakFile extends Model
{
    use HasFactory;

    protected $table = 'activity_kak_files';

    protected $fillable = [
        'activity_id',
        'url',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
