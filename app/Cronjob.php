<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cronjob extends Model
{
    public static function queryGetDashboardMobile($date, $month, $year){
        return DB::select('
            SELECT
                t.ID_USER ,
                (
                    SELECT COUNT(*)
                    FROM `transaction` t2
                    WHERE t2.ID_USER = t.ID_USER AND (t2.ID_TYPE = 2 OR t2.ID_TYPE = 3)
                    GROUP BY DATE(t.DATE_TRANS)
                ) as UBUBLP_DM,
                (
                    SELECT COUNT(*)
                    FROM `transaction` t2
                    WHERE t2.ID_USER = t.ID_USER AND t2.ID_TYPE = 1
                    GROUP BY DATE(t.DATE_TRANS)
                ) as SPREADING_DM,
                (
                    SELECT SUM(t2.QTY_TRANS)
                    FROM `transaction` t2
                    WHERE t2.ID_USER = t.ID_USER
                ) as LASTSALE_DM,
                "'.$date.'" as DAYLASTSALE_DM,
                (
                    SELECT SUM(t2.QTY_TRANS) / '.$date.' 
                    FROM `transaction` t2
                    WHERE t2.ID_USER = t.ID_USER 
                ) as AVERAGESALE_DM,
                (
                    SELECT ('.$date.' - COUNT(*) OVER())
                    FROM `transaction` t2
                    WHERE t2.ID_USER = t.ID_USER
                    GROUP BY DATE(t.DATE_TRANS)
                    HAVING COUNT(t2.QTY_TRANS) < ut.TOTALSALES_UT 
                ) as OFFTARGET_DM,
                (
                    SELECT ((SUM(t2.QTY_TRANS) / (ut.TOTALSALES_UT * 25)) * 100)
                    FROM `transaction` t2
                    WHERE t2.ID_USER = t.ID_USER
                ) as PROGRESS_DM
            FROM `transaction` t, user_target ut
            WHERE 
                YEAR(t.DATE_TRANS) = '.$year.' 
                AND MONTH(t.DATE_TRANS) = '.$month.'
                AND ut.ID_USER = t.ID_USER
            GROUP BY t.ID_USER     
        ');
    }
}
