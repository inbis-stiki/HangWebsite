<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pickup extends Model
{
    protected $table = 'md_pickup';
    protected $primaryKey = 'ID_PICKUP';
    public $timestamps = false;
}
