<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rovscalldet extends Model
{
    protected $table = 'report_rovscall_detail';
    protected $fillable = ['ID_HEAD', 'ID_REGION', 'NAME_AREA', 'MONTH', 'VALUE', 'TYPE'];
    protected $primaryKey = 'ID_DET';
    public $timestamps = false;
}
