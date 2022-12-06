<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Shop extends Model
{
    protected $table = 'md_shop';
    protected $primaryKey = 'ID_SHOP';
    public $timestamps = false;

    public static function getShopByLoc($idLocation){
        return DB::select("
            SELECT ms.NAME_SHOP , ms.LONG_SHOP , ms.LAT_SHOP
            FROM md_regional mr 
            INNER JOIN md_area ma 
                ON mr.ID_LOCATION = '".$idLocation."' AND ma.ID_REGIONAL = mr.ID_REGIONAL 
            INNER JOIN md_district md 
                ON md.ISMARKET_DISTRICT = 0 AND md.ID_AREA = ma.ID_AREA 
            INNER JOIN md_shop ms 
                ON ms.ID_DISTRICT = md.ID_DISTRICT
        ");
    }

    public static function getTotTypeByLoc($idLocation){
        return DB::select("
            SELECT ms.TYPE_SHOP , COUNT(ms.TYPE_SHOP) as TOTAL
            FROM md_regional mr 
            INNER JOIN md_area ma 
                ON mr.ID_LOCATION = '".$idLocation."' AND ma.ID_REGIONAL = mr.ID_REGIONAL 
            INNER JOIN md_district md 
                ON md.ISMARKET_DISTRICT = 0 AND md.ID_AREA = ma.ID_AREA 
            INNER JOIN md_shop ms 
                ON ms.ID_DISTRICT = md.ID_DISTRICT 
            GROUP BY ms.TYPE_SHOP
            ORDER BY FIELD(ms.TYPE_SHOP, 'Pedagang Sayur', 'Retail', 'Loss', 'Permanen')
        ");
    }
    public function getTotTypeByArea($idArea){
        return DB::select("
            SELECT ms.TYPE_SHOP , COUNT(ms.TYPE_SHOP) as TOTAL
            FROM md_district md 
            INNER JOIN md_shop ms 
                ON md.ID_AREA = ".$idArea." AND md.ISMARKET_DISTRICT = 0 AND ms.ID_DISTRICT = md.ID_DISTRICT 
            GROUP BY ms.TYPE_SHOP 
        ");
    }
    public static function getTotTypePerArea($idLocation){
        return DB::select('
            SELECT ma.ID_AREA, ma.NAME_AREA ,
                (
                    SELECT COUNT(ms.ID_SHOP)
                    FROM md_shop ms 
                    WHERE ms.ID_DISTRICT IN (
                        SELECT md.ID_DISTRICT 
                        FROM md_district md 
                        WHERE md.ID_AREA = ma.ID_AREA AND md.ISMARKET_DISTRICT = 0
                    ) AND ms.TYPE_SHOP = "Pedagang Sayur" 
                ) as TOT_PS,
                (
                    SELECT COUNT(ms.ID_SHOP)
                    FROM md_shop ms 
                    WHERE ms.ID_DISTRICT IN (
                        SELECT md.ID_DISTRICT 
                        FROM md_district md 
                        WHERE md.ID_AREA = ma.ID_AREA AND md.ISMARKET_DISTRICT = 0
                    ) AND ms.TYPE_SHOP = "Retail" 
                ) as TOT_RETAIL,
                (
                    SELECT COUNT(ms.ID_SHOP)
                    FROM md_shop ms 
                    WHERE ms.ID_DISTRICT IN (
                        SELECT md.ID_DISTRICT 
                        FROM md_district md 
                        WHERE md.ID_AREA = ma.ID_AREA AND md.ISMARKET_DISTRICT = 0
                    ) AND ms.TYPE_SHOP = "Loss" 
                ) as TOT_LOSS,
                (
                    SELECT COUNT(ms.ID_SHOP)
                    FROM md_shop ms 
                    WHERE ms.ID_DISTRICT IN (
                        SELECT md.ID_DISTRICT 
                        FROM md_district md 
                        WHERE md.ID_AREA = ma.ID_AREA AND md.ISMARKET_DISTRICT = 0
                    ) AND ms.TYPE_SHOP = "Permanen" 
                ) as TOT_PERMANEN
            FROM md_area ma
            INNER JOIN md_regional mr 
                ON mr.ID_LOCATION = '.$idLocation.' AND mr.ID_REGIONAL = ma.ID_REGIONAL 
            ORDER BY ma.NAME_AREA ASC
        ');
    }
    public static function getTotTypePerDistrict($idArea){
        return DB::select("
            SELECT md.NAME_DISTRICT,
                (
                    SELECT COUNT(ms.ID_SHOP)
                    FROM md_shop ms 
                    WHERE ms.ID_DISTRICT = md.ID_DISTRICT AND ms.TYPE_SHOP = 'Pedagang Sayur' 
                ) as TOT_PS,
                (
                    SELECT COUNT(ms.ID_SHOP)
                    FROM md_shop ms 
                    WHERE ms.ID_DISTRICT = md.ID_DISTRICT AND ms.TYPE_SHOP = 'Retail'
                ) as TOT_RETAIL,
                (
                    SELECT COUNT(ms.ID_SHOP)
                    FROM md_shop ms 
                    WHERE ms.ID_DISTRICT = md.ID_DISTRICT AND ms.TYPE_SHOP = 'Loss' 
                ) as TOT_LOSS,
                (
                    SELECT COUNT(ms.ID_SHOP)
                    FROM md_shop ms 
                    WHERE ms.ID_DISTRICT = md.ID_DISTRICT AND ms.TYPE_SHOP = 'Permanen' 
                ) as TOT_PERMANEN
            FROM md_district md 
            WHERE md.ID_AREA = ".$idArea." AND md.ISMARKET_DISTRICT = 0
            ORDER BY md.NAME_DISTRICT ASC
        ");
    }
}