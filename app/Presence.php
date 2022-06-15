<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    protected $table = 'presence';
    protected $primaryKey = 'ID_PRESENCE';
    public $timestamps = false;
}
