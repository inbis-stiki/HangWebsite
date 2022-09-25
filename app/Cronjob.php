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
                COALESCE((
                    SELECT COUNT(*)
                    FROM `transaction` t2
                    WHERE t2.ID_USER = t.ID_USER AND (t2.ID_TYPE = 2 OR t2.ID_TYPE = 3)
                    GROUP BY DATE(t.DATE_TRANS)
                ), 0) as UBUBLP_DM,
                COALESCE((
                    SELECT COUNT(*)
                    FROM `transaction` t2
                    WHERE t2.ID_USER = t.ID_USER AND t2.ID_TYPE = 1
                    GROUP BY DATE(t.DATE_TRANS)
                ), 0) as SPREADING_DM,
                COALESCE((
                    SELECT SUM(t2.QTY_TRANS)
                    FROM `transaction` t2
                    WHERE t2.ID_USER = t.ID_USER
                ), 0) as LASTSALE_DM,
                "'.$date.'" as DAYLASTSALE_DM,
                COALESCE((
                    SELECT SUM(t2.QTY_TRANS) / '.$date.' 
                    FROM `transaction` t2
                    WHERE t2.ID_USER = t.ID_USER 
                ), 0) as AVERAGESALE_DM,
                COALESCE((
                    SELECT ('.$date.' - COUNT(*) OVER())
                    FROM `transaction` t2
                    WHERE t2.ID_USER = t.ID_USER
                    GROUP BY DATE(t.DATE_TRANS)
                    HAVING COUNT(t2.QTY_TRANS) < ut.TOTALSALES_UT 
                ), 0) as OFFTARGET_DM,
                COALESCE((
                    SELECT ((SUM(t2.QTY_TRANS) / (ut.TOTALSALES_UT * 25)) * 100)
                    FROM `transaction` t2
                    WHERE t2.ID_USER = t.ID_USER
                ), 0) as PROGRESS_DM,
                COALESCE((
                    SELECT ((SUM(td.QTY_TD) / (ut.SALESUST_UT  * 25)) * 100)
                    FROM 
                        `transaction` t2, 
                        transaction_detail td,
                        md_product mp
                    WHERE
                        t2.ID_USER = t.ID_USER
                        AND t2.ID_TRANS = td.ID_TRANS
                        AND td.ID_PRODUCT = mp.ID_PRODUCT
                        AND mp.ID_PC = 12 -- 12 == CATEGORY UST in md_product_category
                ), 0) as PROGRESSUST_DM,
                COALESCE((
                    SELECT ((SUM(td.QTY_TD) / (ut.SALESNONUST_UT * 25)) * 100)
                    FROM 
                        `transaction` t2, 
                        transaction_detail td,
                        md_product mp
                    WHERE
                        t2.ID_USER = t.ID_USER
                        AND t2.ID_TRANS = td.ID_TRANS
                        AND td.ID_PRODUCT = mp.ID_PRODUCT
                        AND mp.ID_PC = 2 -- 2 == CATEGORY NON UST in md_product_category
                ), 0) as PROGRESSNONUST_DM,
                COALESCE((
                    SELECT ((SUM(td.QTY_TD) / (ut.SALESSELERAKU_UT * 25)) * 100)
                    FROM 
                        `transaction` t2, 
                        transaction_detail td,
                        md_product mp
                    WHERE
                        t2.ID_USER = t.ID_USER
                        AND t2.ID_TRANS = td.ID_TRANS
                        AND td.ID_PRODUCT = mp.ID_PRODUCT
                        AND mp.ID_PC = 3 -- 3 == CATEGORY SELERAKU in md_product_category
                ), 0) as PROGRESSSELERAKU_DM
            FROM `transaction` t, user_target ut
            WHERE 
                YEAR(t.DATE_TRANS) = '.$year.' 
                AND MONTH(t.DATE_TRANS) = '.$month.'
                AND ut.ID_USER = t.ID_USER
            GROUP BY t.ID_USER 
        ');
    }
}
