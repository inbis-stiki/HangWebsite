<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReportOmsetHead extends Model
{
    protected $table = 'report_omset_head';
    protected $fillable = ['ID_HEAD', 'ID_REGIONAL', 'BULAN', 'TAHUN'];
    protected $primaryKey = 'ID_HEAD';
    public $timestamps = false;
}
