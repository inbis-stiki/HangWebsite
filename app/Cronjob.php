<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cronjob extends Model
{
    public static function queryGetDashboardMobile($date, $month, $year, $queryCategory, $tgtUser){
        // return DB::select('
        //     SELECT
        //         t.ID_USER ,
        //         COALESCE((
        //             SELECT COUNT(*)
        //             FROM `transaction` t2
        //             WHERE t2.ID_USER = t.ID_USER AND (t2.ID_TYPE = 2 OR t2.ID_TYPE = 3)
        //             GROUP BY DATE(t.DATE_TRANS)
        //         ), 0) as UBUBLP_DM,
        //         COALESCE((
        //             SELECT COUNT(*)
        //             FROM `transaction` t2
        //             WHERE t2.ID_USER = t.ID_USER AND t2.ID_TYPE = 1
        //             GROUP BY DATE(t.DATE_TRANS)
        //         ), 0) as SPREADING_DM,
        //         COALESCE((
        //             SELECT ('.$date.' - COUNT(*) OVER())
        //             FROM `transaction` t2
        //             WHERE t2.ID_USER = t.ID_USER
        //             GROUP BY DATE(t.DATE_TRANS)
        //             HAVING COUNT(t2.QTY_TRANS) < ut.TOTALSALES_UT 
        //         ), 0) as OFFTARGET_DM,
        //         COALESCE((
        //             SELECT SUM(td.QTY_TD)
        //             FROM 
        //                 `transaction` t2, 
        //                 transaction_detail td,
        //                 md_product mp
        //             WHERE
        //                 t2.ID_USER = t.ID_USER
        //                 AND t2.ID_TRANS = td.ID_TRANS
        //                 AND td.ID_PRODUCT = mp.ID_PRODUCT
        //                 AND mp.ID_PC = 12 -- 12 == CATEGORY UST in md_product_category
        //         ), 0) as REALUST_DM,
        //         COALESCE((
        //             SELECT SUM(td.QTY_TD)
        //             FROM 
        //                 `transaction` t2, 
        //                 transaction_detail td,
        //                 md_product mp
        //             WHERE
        //                 t2.ID_USER = t.ID_USER
        //                 AND t2.ID_TRANS = td.ID_TRANS
        //                 AND td.ID_PRODUCT = mp.ID_PRODUCT
        //                 AND mp.ID_PC = 2 -- 2 == CATEGORY NON UST in md_product_category
        //         ), 0) as REALNONUST_DM,
        //         COALESCE((
        //             SELECT SUM(td.QTY_TD)
        //             FROM 
        //                 `transaction` t2, 
        //                 transaction_detail td,
        //                 md_product mp
        //             WHERE
        //                 t2.ID_USER = t.ID_USER
        //                 AND t2.ID_TRANS = td.ID_TRANS
        //                 AND td.ID_PRODUCT = mp.ID_PRODUCT
        //                 AND mp.ID_PC = 3 -- 3 == CATEGORY SELERAKU in md_product_category
        //         ), 0) as REALSELERAKU_DM
        //     FROM `transaction` t, user_target ut
        //     WHERE 
        //         YEAR(t.DATE_TRANS) = '.$year.'
        //         AND MONTH(t.DATE_TRANS) = '.$month.'
        //         AND ut.ID_USER = t.ID_USER
        //     GROUP BY t.ID_USER 
        // ');

        return DB::select("
            SELECT
                u.ID_USER ,
                COALESCE((
                    SELECT COUNT(*)
                    FROM `transaction` t
                    WHERE 
                    	YEAR(t.DATE_TRANS) = ".$year."
						AND MONTH(t.DATE_TRANS) = ".$month."
						AND t.ID_USER = u.ID_USER 
                    	AND (t.ID_TYPE = 2 OR t.ID_TYPE = 3)
                ), 0) as UBUBLP_DM,
                COALESCE((
                    SELECT COUNT(*)
                    FROM `transaction` t
                    WHERE 
                    	YEAR(t.DATE_TRANS) = ".$year."
						AND MONTH(t.DATE_TRANS) = ".$month."
						AND t.ID_USER = u.ID_USER 
                    	AND t.ID_TYPE = 1
                ), 0) as SPREADING_DM,
                COALESCE((
                    SELECT COUNT(t.ID_USER)
					FROM (
						SELECT t2.ID_USER
						FROM `transaction` t2
						WHERE 
							YEAR(t2.DATE_TRANS) = ".$year."
							AND MONTH(t2.DATE_TRANS) = ".$month."
						GROUP BY t2.ID_USER, DATE(t2.DATE_TRANS)
						HAVING SUM(t2.QTY_TRANS) < ".$tgtUser."
					) as t
					WHERE t.ID_USER = u.ID_USER  
                ), 0) as OFFTARGET_DM,
                ".$queryCategory."
            FROM `user` u 
            WHERE 
                u.ID_ROLE IN (5, 6) 
                AND u.deleted_at IS NULL
        ");
    }
    public static function queryGetTransactionDaily($querySumProd, $date, $regional){
        return DB::select("
                SELECT 
                    u.ID_USER,
                    u.NAME_USER,
                    mt.NAME_TYPE ,
                    td.AREA_TD ,
                    (
                        SELECT IF(t.DISTRICT IS NOT NULL, t.DISTRICT, t.KECAMATAN)
                        FROM `transaction` t
                        WHERE t.ID_TD = td.ID_TD AND t.ID_TYPE = mt.ID_TYPE 
                        LIMIT 1
                    ) as DISTRICT ,
                    (
                    	SELECT t.DETAIL_LOCATION
                        FROM `transaction` t
                        WHERE t.ID_TD = td.ID_TD AND t.ID_TYPE = mt.ID_TYPE 
                        LIMIT 1
                    ) as DETAIL_LOCATION ,
                    mr.NAME_ROLE ,
                    td.ISFINISHED_TD,
                    td.TOTAL_TD ,
                    ".$querySumProd."
                FROM transaction_daily td 
                INNER JOIN md_type mt 
                    ON DATE(td.DATE_TD) = '".$date."' AND td.REGIONAL_TD = '".$regional."' AND mt.ID_TYPE = td.ID_TYPE
                INNER JOIN `user` u
                    ON u.ID_USER = td.ID_USER
                INNER JOIN md_role mr 
                    ON mr.ID_ROLE = u.ID_ROLE 
                ORDER BY td.AREA_TD ASC, u.NAME_USER ASC
        ");
    }
}
