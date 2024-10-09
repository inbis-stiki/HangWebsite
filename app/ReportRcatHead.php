<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReportRcatHead extends Model
{
    protected $table = 'report_recat_head';
    protected $primaryKey = 'ID_HEAD';
    public $timestamps = false;
}
