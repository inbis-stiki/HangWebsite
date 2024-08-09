<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReportRcatDetail extends Model
{
    protected $table = 'report_recat_detail';
    protected $primaryKey = 'ID_DET';
    public $timestamps = false;
}
