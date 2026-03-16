<?php

namespace App\Models\Act;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivityKakFile extends Model
{
    use HasFactory;

    protected $table = 'activity_kak_files';

    protected $fillable = [
        'activity_id',
        'file_path',
        'original_name',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
