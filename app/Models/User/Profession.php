<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profession extends Model
{
    use HasFactory;

    protected $table = 'professions';

    protected $fillable = [
        'code',
        'name',
        'profession_category_id',
    ];

    public function category()
    {
        return $this->belongsTo(\App\Models\ProfessionCategory::class, 'profession_category_id');
    }
}
