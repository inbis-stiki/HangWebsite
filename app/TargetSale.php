<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TargetSale extends Model
{
    //
    protected $table = 'target_sale';
    protected $primaryKey = 'ID_TS';
    public $timestamps = false;
    public $fillable = [
        'ID_PROCAT',
        'ID_REGIONAL',
        'QUANTITY',
        'START_PP',
        'END_PP',
        'DELETED_AT'
    ];
    public function regional()
    {
        return $this->hasOne('App\Regional', 'ID_REGIONAL', 'ID_REGIONAL');
    }
    public function procat()
    {
        return $this->hasOne('App\CategoryProduct', 'ID_PC', 'ID_PROCAT');
    }
}