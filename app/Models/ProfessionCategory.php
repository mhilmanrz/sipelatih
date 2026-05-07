<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfessionCategory extends Model
{
    protected $fillable = [
        'code',
        'name',
        'jpl_target',
    ];
}
