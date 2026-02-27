<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Act\Activity;
use App\Models\User\WorkUnit;
use App\Models\User\Profession;
use App\Models\User\EmploymentType;
use App\Models\User\Positions;

class User extends Model
{
    use HasFactory;

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
}
