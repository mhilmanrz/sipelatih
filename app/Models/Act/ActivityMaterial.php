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
        'jpl',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'jpl' => 'decimal:1',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
