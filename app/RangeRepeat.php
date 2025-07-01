<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RangeRepeat extends Model
{
    protected $table = 'md_range_repeat';
    protected $primaryKey = 'ID_RANGE';
    public $timestamps = false;
}
