<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReportShopDet extends Model
{
    protected $table = 'report_shop_detail';
    protected $primaryKey = 'ID_DET';
    public $timestamps = false;
}
