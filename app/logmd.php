<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class logmd extends Model
{
    protected $table = 'log_md';
    protected $primaryKey = 'ID_LOG';
    public $timestamps = false;

}