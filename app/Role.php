<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'md_role';
    protected $primaryKey = 'ID_ROLE';
    public $timestamps = false;
}
