<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $table = 'budgets';

    protected $fillable = [
        'budget_category_id',
        'rkkal_code',
        'submark',
        'total_amount',
        'remaining_amount',
    ];

    public function budgetCategory()
    {
        return $this->belongsTo(BudgetCategory::class);
    }
}
