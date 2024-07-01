<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $table = 'md_route';
    protected $primaryKey = 'ID_RUTE';
    public $timestamps = false;
}
