<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReportShopHead extends Model
{
    protected $table = 'report_shop_head';
    protected $primaryKey = 'ID_HEAD';
    public $timestamps = false;
}
