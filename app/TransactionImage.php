<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionImage extends Model
{
    protected $table = 'transaction_image';
    protected $primaryKey = 'ID_TI';
    public $timestamps = false;
}
