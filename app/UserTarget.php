<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTarget extends Model
{
    protected $table = 'user_target';
    protected $primaryKey = 'ID_UT';
    public $timestamps = false;

    public $fillable = [
        'ID_USER',
        'TOTALACTIVITY_UT',
        'TOTALSALES_UT'
    ];
}
