<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
    protected $table = 'md_product_category';
    protected $primaryKey = 'ID_PC';
    public $timestamps = false;
}
