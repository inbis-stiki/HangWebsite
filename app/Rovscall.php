<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rovscall extends Model
{
    protected $table = 'report_rovscall_head';
    protected $fillable = ['ID_HEAD', 'ID_REGIONAL', 'TAHUN'];
    protected $primaryKey = 'ID_HEAD';
    public $timestamps = false;
}
