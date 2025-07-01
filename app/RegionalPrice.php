<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegionalPrice extends Model
{
    //
    protected $table = 'product_price';
    protected $primaryKey = 'ID_PP';
    public $timestamps = false;
    public $fillable = [
        'ID_PRODUCT',
        'ID_REGIONAL',
        'PRICE_PP',
        'TARGET_PP',
        'START_PP',
        'END_PP',
        'DELETED_AT'
    ];
    public function regional()
    {
        return $this->hasOne('App\Regional', 'ID_REGIONAL', 'ID_REGIONAL');
    }
    public function product()
    {
        return $this->hasOne('App\Product', 'ID_PRODUCT', 'ID_PRODUCT');
    }
}
