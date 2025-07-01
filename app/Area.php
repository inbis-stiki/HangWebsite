<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Area extends Model
{
    protected $table = 'md_area';
    protected $primaryKey = 'ID_AREA';
    public $timestamps = false;

    public static function getAreaByLoc($idLocation){
        return DB::select("
            SELECT ma.ID_AREA , ma.NAME_AREA , mr.NAME_REGIONAL 
            FROM md_area ma 
            INNER JOIN md_regional mr
                ON mr.ID_LOCATION = ".$idLocation." AND mr.ID_REGIONAL = ma.ID_REGIONAL
        ");
    }
}
