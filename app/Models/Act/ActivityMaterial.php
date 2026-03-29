<?php

namespace App\Models\Act;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivityMaterial extends Model
{
    use HasFactory;

    protected $table = 'activity_materials';

    protected $fillable = [
        'activity_id',
        'name',
        'value',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
