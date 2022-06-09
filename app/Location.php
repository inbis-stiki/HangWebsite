<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'md_location';
    protected $primaryKey = 'ID_LOCATION';
    public $timestamps = false;
}
