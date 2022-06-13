<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tes extends Model
{
    protected $table = 'tabletes';
    protected $primaryKey = 'ID_TES';
    public $timestamps = false;
}
