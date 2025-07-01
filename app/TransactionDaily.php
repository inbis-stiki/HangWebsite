<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionDaily extends Model
{
    protected $table = 'transaction_daily';
    protected $primaryKey = 'ID_TD';
    public $timestamps = false;
}
