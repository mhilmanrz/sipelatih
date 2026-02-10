<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'nip',
        'name',
        'email',
        'password',
        'nik',
        'status',
        'department',
        'no_hp',
        'profession',
        'office',
        'grade',
        'position',
        'jabfung',
        'npwp',
        'norek',
    ];

    protected $hidden = [
        'password',
    ];
}
