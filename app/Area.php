<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'md_area';
    protected $primaryKey = 'ID_AREA';
    public $timestamps = false;
}
