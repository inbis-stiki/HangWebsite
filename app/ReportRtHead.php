<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportRtHead extends Model
{
    protected $table = 'report_rt_head';
    protected $fillable = ['ID_HEAD', 'NAME_REGIONAL', 'YEAR'];
    protected $primaryKey = 'ID_HEAD';
    public $timestamps = false;
}
