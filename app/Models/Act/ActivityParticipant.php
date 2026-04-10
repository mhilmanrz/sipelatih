<?php

namespace App\Models\Act;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityParticipant extends Model
{
    use HasFactory;

    protected $table = 'activity_participants';

    protected $fillable = [
        'activity_id',
        'user_id',
        'certificate_number',
        'is_passed',
    ];

    protected $casts = [
        'is_passed' => 'boolean',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function score()
    {
        return $this->hasOne(ActivityScore::class);
    }
}
