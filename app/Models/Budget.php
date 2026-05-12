<?php

namespace App\Models;

use App\Models\Act\Activity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $table = 'budgets';

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
