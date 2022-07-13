<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityCategory extends Model
{
    protected $table = 'md_activity_category';
    protected $primaryKey = 'ID_AC';
    public $timestamps = false;
}
