<?php

namespace App\Models\Act;

use App\Models\User\Profession;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
