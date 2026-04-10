<?php

namespace App\Models\User;

use App\Models\Act\Activity;
use App\Models\Act\ActivityParticipant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'work_unit_id',
        'position_id',
        'employment_type_id',
        'profession_id',
        'employee_id',
        'phone_number',
    ];

    protected $hidden = [
        'password',
    ];

    public function workUnit()
    {
        return $this->belongsTo(WorkUnit::class);
    }

    public function position()
    {
        return $this->belongsTo(Positions::class);
    }

    public function employmentType()
    {
        return $this->belongsTo(EmploymentType::class);
    }

    public function profession()
    {
        return $this->belongsTo(Profession::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function activityParticipants()
    {
        return $this->hasMany(ActivityParticipant::class);
    }
}
