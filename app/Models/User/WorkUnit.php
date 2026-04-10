<?php

namespace App\Models\User;

use App\Models\Act\Activity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
