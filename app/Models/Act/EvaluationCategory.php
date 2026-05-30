<?php

namespace App\Models\Act;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EvaluationCategory extends Model
{
    protected $fillable = [
        'name',
        'evaluation_type',
        'form_type',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'evaluation_type' => 'integer',
            'order' => 'integer',
        ];
    }

    public function criteria(): HasMany
    {
        return $this->hasMany(EvaluationCriteria::class);
    }
}
