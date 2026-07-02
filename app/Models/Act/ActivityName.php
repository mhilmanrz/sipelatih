<?php

namespace App\Models\Act;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityName extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'start_date', 'end_date', 'year', 'quota'];

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
