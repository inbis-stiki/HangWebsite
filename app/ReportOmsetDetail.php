<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReportOmsetDetail extends Model
{
    protected $table = 'report_omset_detail';
    protected $fillable = ['ID_DET', 'ID_HEAD', 'NAME_AREA', 'ID_USER', 'ID_PC', 'TYPE_SHOP', 'TOTAL_OMSET', 'TOTAL_OUTLET', 'last_updated'];
    protected $primaryKey = 'ID_DET';
    public $timestamps = false;
}
