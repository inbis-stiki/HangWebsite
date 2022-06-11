<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'md_district';
    protected $primaryKey = 'ID_DISTRICT';
    public $timestamps = false;
}
