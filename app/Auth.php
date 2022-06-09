<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Auth extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'USERNAME_USER';
    protected $hidden = [
        'PASS_USER'
    ];
}