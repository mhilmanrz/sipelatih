<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Act\Activity;

class WorkUnit extends Model
{
    use HasFactory;

    protected $table = 'work_units';

    protected $fillable = [
        'code',
        'name',
    ];

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
