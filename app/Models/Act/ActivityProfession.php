<?php

namespace App\Models\Act;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User\Profession;

class ActivityProfession extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'profession_id',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function profession()
    {
        return $this->belongsTo(Profession::class);
    }
}
