<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'ID_USER';
    public $incrementing = false;
    public $timestamps = false;
}
