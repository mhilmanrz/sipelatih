<?php

namespace App\Models\Act;

use App\Models\Budget;
use App\Models\User\User;
use App\Models\User\WorkUnit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'reference_number',
        'activity_name_id',
        'activity_type_id',
        'activity_category_id',
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
        'pic_user_id',
        'quota_participant',
        'fund_source_id',
        'budget_id',
    ];

    public function picUser()
    {
        return $this->belongsTo(User::class, 'pic_user_id');
    }

    public function activityName()
    {
        return $this->belongsTo(ActivityName::class);
    }

    public function activityType()
    {
        return $this->belongsTo(ActivityType::class);
    }

    public function activityCategory()
    {
        return $this->belongsTo(ActivityCategory::class);
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
        return $this->belongsTo(TargetParticipant::class, 'target_participant_id');
    }

    public function workUnit()
    {
        return $this->belongsTo(WorkUnit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statuses()
    {
        return $this->hasMany(ActivityStatus::class);
    }

    public function latestStatus()
    {
        return $this->hasOne(ActivityStatus::class)->latestOfMany();
    }

    public function activityProfessions()
    {
        return $this->hasMany(ActivityProfession::class);
    }

    public function fundSource()
    {
        return $this->belongsTo(FundSource::class);
    }

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    public function activityMaterials()
    {
        return $this->hasMany(ActivityMaterial::class);
    }

    public function activityParticipants()
    {
        return $this->hasMany(ActivityParticipant::class);
    }

    public function report()
    {
        return $this->hasOne(ActivityReport::class);
    }
}
