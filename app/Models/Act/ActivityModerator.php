<?php

namespace App\Models\Act;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityModerator extends Model
{
    use HasFactory;

    protected $table = 'activity_moderators';

    protected $fillable = [
        'activity_material_id',
        'user_id',
    ];

    public function activityMaterial()
    {
        return $this->belongsTo(ActivityMaterial::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
