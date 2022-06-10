<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'md_product';
    protected $primaryKey = 'ID_PRODUCT';
    public $timestamps = false;
}
