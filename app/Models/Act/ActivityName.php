<?php

namespace App\Models\Act;

use Illuminate\Database\Eloquent\Model;

class ActivityName extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = ['name'];

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
