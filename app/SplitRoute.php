<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SplitRoute extends Model
{
    protected $table = 'md_split_route';
    protected $primaryKey = 'ID_SPLIT';
    public $timestamps = false;
}
