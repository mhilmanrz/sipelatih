<?php

namespace App\Models\Act;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Act\ActivityType;
use App\Models\Act\ActivityScope;
use App\Models\Act\MaterialType;
use App\Models\Act\ActivityMethod;
use App\Models\Act\Batch;
use App\Models\Act\ActivityFormat;
use App\Models\Act\TargerParticipant;
use App\Models\User\WorkUnit;
use App\Models\User\User;



class Activity extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'reference_number',
        'name',
        'activity_type_id',
        'activity_scope_id',
        'material_type_id',
        'activity_method_id',
        'batch_id',
        'activity_format_id',
        'collaboration_inst',
        'target_participant_id',
        'start_date',
        'end_date',
        'budget_amount',
        'work_unit_id',
        'user_id',
    ];

    public function activityType()
    {
        return $this->belongsTo(ActivityType::class);
    }

    public function activityScope()
    {
        return $this->belongsTo(ActivityScope::class);
    }

    public function materialType()
    {
        return $this->belongsTo(MaterialType::class);
    }

    public function activityMethod()
    {
        return $this->belongsTo(ActivityMethod::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function activityFormat()
    {
        return $this->belongsTo(ActivityFormat::class);
    }

    public function targetParticipant()
    {
        return $this->belongsTo(TargerParticipant::class, 'target_participant_id');
    }

    public function workUnit()
    {
        return $this->belongsTo(WorkUnit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
