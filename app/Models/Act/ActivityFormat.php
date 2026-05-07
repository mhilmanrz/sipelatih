<?php

namespace App\Models\Act;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityFormat extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name'];

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
