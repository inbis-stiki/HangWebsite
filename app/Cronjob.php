<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cronjob extends Model
{
    public static function queryGetDashboardMobile($month, $year, $queryCategory, $tgtUser){
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
    public static function queryGetSmyTransLocation($year, $month){
        return DB::select("
            SELECT
                t.LOCATION_TRANS ,
                t.REGIONAL_TRANS ,
                t.AREA_TRANS ,
                COALESCE((
                    SELECT SUM(td.QTY_TD)
                    FROM `transaction` t2 
                    INNER JOIN transaction_detail td 
                        ON 
                            YEAR(t2.DATE_TRANS) = ".$year."
                            AND MONTH(t2.DATE_TRANS) = ".$month."
                            AND t2.AREA_TRANS = t.AREA_TRANS 
                            AND td.ID_TRANS = t2.ID_TRANS 
                            AND td.ID_PC = 12
                ), 0) as 'REALUST_STL',
                COALESCE((
                    SELECT SUM(td.QTY_TD)
                    FROM `transaction` t2 
                    INNER JOIN transaction_detail td 
                        ON 
                            YEAR(t2.DATE_TRANS) = ".$year."
                            AND MONTH(t2.DATE_TRANS) = ".$month."
                            AND t2.AREA_TRANS = t.AREA_TRANS 
                            AND td.ID_TRANS = t2.ID_TRANS 
                            AND td.ID_PC = 2
                ), 0) as 'REALNONUST_STL',
                COALESCE((
                    SELECT SUM(td.QTY_TD)
                    FROM `transaction` t2 
                    INNER JOIN transaction_detail td 
                        ON 
                            YEAR(t2.DATE_TRANS) = ".$year."
                            AND MONTH(t2.DATE_TRANS) = ".$month."
                            AND t2.AREA_TRANS = t.AREA_TRANS 
                            AND td.ID_TRANS = t2.ID_TRANS 
                            AND td.ID_PC = 3
                ), 0) as 'REALSELERAKU_STL',
                COALESCE((
                    SELECT COUNT(*)
                    FROM `transaction` t2
                    WHERE 
                        YEAR(t2.DATE_TRANS) = ".$year."
                        AND MONTH(t2.DATE_TRANS) = ".$month."
                        AND t2.AREA_TRANS = t.AREA_TRANS 
                        AND t2.TYPE_ACTIVITY = 'AKTIVITAS UB'
                ), 0) as 'REALACTUB_STL',
                COALESCE((
                    SELECT COUNT(*)
                    FROM `transaction` t2
                    WHERE 
                        YEAR(t2.DATE_TRANS) = ".$year."
                        AND MONTH(t2.DATE_TRANS) = ".$month."
                        AND t2.AREA_TRANS = t.AREA_TRANS 
                        AND t2.TYPE_ACTIVITY = 'Pedagang Sayur'
                ), 0) as 'REALACTPS_STL',
                COALESCE((
                    SELECT COUNT(*)
                    FROM `transaction` t2
                    WHERE 
                        YEAR(t2.DATE_TRANS) = ".$year."
                        AND MONTH(t2.DATE_TRANS) = ".$month."
                        AND t2.AREA_TRANS = t.AREA_TRANS 
                        AND t2.TYPE_ACTIVITY = 'Retail'
                ), 0) as 'REALACTRETAIL_STL'
            FROM `transaction` t
            GROUP BY t.AREA_TRANS 
        ");
    }
    public static function queryGetSmyRegional($year, $month){
        $reportProds = DB::select("
            SELECT 
                smy.*,
                ((smy.VSUST * smy.WUST) / 100) + ((smy.VSNONUST * smy.WNONUST) / 100) + ((smy.VSSELERAKU * smy.WSELERAKU) / 100) as AVG_VS
            FROM (
                SELECT
                    stl.*,
                    (
                        SELECT mpc.PERCENTAGE_PC
                        FROM md_product_category mpc 
                        WHERE mpc.ID_PC = 12
                    ) as WUST,
                    (
                        SELECT mpc.PERCENTAGE_PC
                        FROM md_product_category mpc 
                        WHERE mpc.ID_PC = 2
                    ) as WNONUST,
                    (
                        SELECT mpc.PERCENTAGE_PC
                        FROM md_product_category mpc 
                        WHERE mpc.ID_PC = 3
                    ) as WSELERAKU,
                    (
                        SELECT mpc.TGTREGIONAL_PC
                        FROM md_product_category mpc 
                        WHERE mpc.ID_PC = 12
                    ) as TGTUST,
                    (
                        SELECT mpc.TGTREGIONAL_PC
                        FROM md_product_category mpc 
                        WHERE mpc.ID_PC = 2
                    ) as TGTNONUST,
                    (
                        SELECT mpc.TGTREGIONAL_PC
                        FROM md_product_category mpc 
                        WHERE mpc.ID_PC = 3
                    ) as TGTSELERAKU,
                    (
                        SELECT (stl.REALUST_STL / mpc.TGTREGIONAL_PC) * 100
                        FROM md_product_category mpc 
                        WHERE mpc.ID_PC = 12
                    ) as VSUST,
                    (
                        SELECT (stl.REALNONUST_STL / mpc.TGTREGIONAL_PC) * 100
                        FROM md_product_category mpc 
                        WHERE mpc.ID_PC = 2
                    ) as VSNONUST,
                    (
                        SELECT (stl.REALSELERAKU_STL / mpc.TGTREGIONAL_PC) * 100
                        FROM md_product_category mpc 
                        WHERE mpc.ID_PC = 3
                    ) as VSSELERAKU
                FROM summary_trans_location stl
                WHERE
                    stl.YEAR_STL = ".$year."
                    AND stl.MONTH_STL = ".$month."
            ) as smy
            ORDER BY AVG_VS DESC
        ");

        $reportActs = DB::select("
            SELECT 
                smy.*,
                ((smy.VSUB * smy.WUB) / 100) + ((smy.VSPS * smy.WPS) / 100) + ((smy.VSRETAIL * smy.WRETAIL) / 100) as AVG_VS
            FROM (
                SELECT
                    stl.*,
                    (
                        SELECT mac.PERCENTAGE_AC 
                        FROM md_activity_category mac  
                        WHERE mac.ID_AC = 1
                    ) as WUB,
                    (
                        SELECT mac.PERCENTAGE_AC 
                        FROM md_activity_category mac  
                        WHERE mac.ID_AC = 2
                    ) as WPS,
                    (
                        SELECT mac.PERCENTAGE_AC 
                        FROM md_activity_category mac  
                        WHERE mac.ID_AC = 3
                    ) as WRETAIL,
                    (
                        SELECT mac.TGTREGIONAL_AC 
                        FROM md_activity_category mac  
                        WHERE mac.ID_AC = 1
                    ) as TGTUB,
                    (
                        SELECT mac.TGTREGIONAL_AC 
                        FROM md_activity_category mac  
                        WHERE mac.ID_AC = 2
                    ) as TGTPS,
                    (
                        SELECT mac.TGTREGIONAL_AC 
                        FROM md_activity_category mac  
                        WHERE mac.ID_AC = 3
                    ) as TGTRETAIL,
                    (
                        SELECT (stl.REALACTUB_STL / mac.TGTREGIONAL_AC) * 100
                        FROM md_activity_category mac
                        WHERE mac.ID_AC = 1
                    ) as VSUB,
                    (
                        SELECT (stl.REALACTPS_STL / mac.TGTREGIONAL_AC) * 100
                        FROM md_activity_category mac
                        WHERE mac.ID_AC = 2
                    ) as VSPS,
                    (
                        SELECT (stl.REALACTRETAIL_STL / mac.TGTREGIONAL_AC) * 100
                        FROM md_activity_category mac
                        WHERE mac.ID_AC = 3
                    ) as VSRETAIL
                FROM summary_trans_location stl
                WHERE
                    stl.YEAR_STL = ".$year."
                    AND stl.MONTH_STL = ".$month."
            ) as smy
            ORDER BY AVG_VS DESC    
        ");

        return ['reportProds' => $reportProds, 'reportActs' => $reportActs];
    }
    public static function queryGetDetailSmyRegional($regional){
        return DB::select("
            SELECT mr.NAME_REGIONAL , GROUP_CONCAT(u.NAME_USER) as NAME_USER , ml.ISINSIDE_LOCATION 
            FROM md_regional mr 
            INNER JOIN `user` u 
                ON 
                    mr.NAME_REGIONAL = '".$regional."'
                    AND u.ID_ROLE = 4 
                    AND u.deleted_at IS NULL AND u.ID_REGIONAL = mr.ID_REGIONAL 
            INNER JOIN md_location ml 
                ON ml.ID_LOCATION = mr.ID_LOCATION
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
