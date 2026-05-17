<?php

namespace App\Models;

use App\Models\Act\Activity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $table = 'budgets';

    protected static function booted()
    {
        static::saving(function ($budget) {
            $budget->blocked_amount = $budget->blocked_amount ?? 0;
            $rawRemaining = $budget->getAttributes()['remaining_amount'] ?? null;
            if (is_null($rawRemaining) || $budget->isDirty(['total_amount', 'blocked_amount'])) {
                $budget->attributes['remaining_amount'] = $budget->total_amount - $budget->blocked_amount;
            }
        });
    }

    protected $fillable = [
        'budget_category_id',
        'year',
        'rkkal_code',
        'submark',
        'total_amount',
        'blocked_amount',
        'remaining_amount',
    ];

    public function budgetCategory()
    {
        return $this->belongsTo(BudgetCategory::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function getRemainingAmountAttribute()
    {
        return $this->total_amount - $this->activities()->sum('budget_amount');
    }
}
