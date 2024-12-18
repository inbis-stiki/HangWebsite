<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReportRcatExcel extends Model
{
    protected $table = 'report_recat_excel';
    protected $fillable = ['ID_EX', 'ID_REGIONAL', 'BULAN', 'TAHUN', 'EXCEL'];
    protected $primaryKey = 'ID_EX';
    public $timestamps = false;
}
