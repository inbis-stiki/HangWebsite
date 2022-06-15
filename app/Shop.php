<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $table = 'md_shop';
    protected $primaryKey = 'ID_SHOP';
    public $timestamps = false;
}