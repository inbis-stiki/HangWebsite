<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportRtDetail extends Model
{
    protected $table = 'report_rt_detail';
    protected $fillable = ['ID_HEAD', 'NAME_SHOP', 'NAME_AREA', 'JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER', 'PERCENTAGE_CURRENT', 'CAT_PERCENTAGE','TYPE_SHOP','NAME_DISTRICT'];
    protected $primaryKey = 'ID_DET';
    public $timestamps = false;
}
