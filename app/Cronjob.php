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
    public static function queryGetSmy($year, $month, $area, $isInside){
        // SET WEIGHT
        $wUST       = CategoryProduct::where("ID_PC", "12")->first()->PERCENTAGE_PC;
        $wNONUST    = CategoryProduct::where("ID_PC", "2")->first()->PERCENTAGE_PC;
        $wSELERAKU  = CategoryProduct::where("ID_PC", "3")->first()->PERCENTAGE_PC;
        
        $wUB        = ActivityCategory::where("ID_AC", "1")->first()->PERCENTAGE_AC;
        $wPS        = ActivityCategory::where("ID_AC", "2")->first()->PERCENTAGE_AC;
        $wRETAIL    = ActivityCategory::where("ID_AC", "3")->first()->PERCENTAGE_AC;
        // SET AREA
        if($area == "Regional"){
            $groupBy = "GROUP BY stl.REGIONAL_STL";
            $nameUser = "
                SELECT DISTINCT(u.NAME_USER)
                FROM md_regional mr 
                JOIN `user` u  
                    ON 
                        mr.NAME_REGIONAL = smy.REGIONAL_STL COLLATE utf8mb4_unicode_ci
                        AND u.ID_REGIONAL = mr.ID_REGIONAL 
                        AND u.ID_ROLE = 4
            ";
            // SET TARGET
            $tgtUB      = (12 * 14); // (tgt * jmlarea)
            $tgtPS      = (14 * 3) * 25; // (tgt * jmluser) * totharikerja
            $tgtRetail  = (14 * 3) * 25; // (tgt * jmluser) * totharikerja

            $tgtUST         = (80 * 3) * 25;
            $tgtNONUST      = (1000 * 3) * 25;
            $tgtSeleraku    = (180 * 3) * 25;
        }else if($area == "Location"){
            $groupBy = "GROUP BY stl.LOCATION_STL";
            $nameUser = "
                SELECT DISTINCT(u.NAME_USER)
                FROM md_regional mr 
                JOIN `user` u  
                    ON 
                        ml.NAME_LOCATION = smy.LOCATION_STL COLLATE utf8mb4_unicode_ci
                        AND u.ID_LOCATION = ml.ID_LOCATION 
                        AND u.ID_ROLE = 3
            ";

            // SET TARGET
            $tgtUB      = (12 * 14) * 2; // (tgt * jmlarea) * totasmen
            $tgtPS      = ((14 * 3) * 25) * 2; // ((tgt * jmluser) * totharikerja) * totasmen
            $tgtRetail  = (14 * 3) * 25; // ((tgt * jmluser) * totharikerja) * totasmen

            $tgtUST         = ((80 * 3) * 25) * 2;
            $tgtNONUST      = ((1000 * 3) * 25) * 2;
            $tgtSeleraku    = ((180 * 3) * 25) * 2;
        }

        // SET DAPUL OR LAPUL
        if($isInside == "1"){
            $isInsideQry = "AND ml.ISINSIDE_LOCATION = 1";
        }else{
            $isInsideQry = "AND ml.ISINSIDE_LOCATION = 0";
        }

        $resProds = DB::select("
            SELECT 
                ( ".$nameUser." ) as NAME_USER,
                smy.LOCATION_STL,
                smy.REGIONAL_STL,
                smy.REALUST_STL ,
                (".$tgtUST.") as TGTUST ,
                smy.VSUST ,
                smy.REALNONUST_STL ,
                (".$tgtNONUST.") as TGTNONUST ,
                smy.VSNONUST ,
                smy.REALSELERAKU_STL ,
                (".$tgtSeleraku.") as TGTSELERAKU ,
                smy.VSSELERAKU ,
                ((smy.VSUST * ".$wUST.") / 100) + ((smy.VSNONUST * ".$wNONUST.") / 100) + ((smy.VSSELERAKU * ".$wSELERAKU.") / 100) as AVG_VS
            FROM (
                SELECT
                    stl.*,
                    (
                        (stl.REALUST_STL / (".$tgtUST.")) * 100
                    ) as VSUST,
                    (
                        (stl.REALNONUST_STL / (".$tgtNONUST.")) * 100
                    ) as VSNONUST,
                    (
                        (stl.REALSELERAKU_STL / (".$tgtSeleraku.")) * 100
                    ) as VSSELERAKU
                FROM summary_trans_location stl
                INNER JOIN md_location ml
                    ON 
                        stl.YEAR_STL = ".$year."
                        AND stl.MONTH_STL = ".$month."
                        AND ml.NAME_LOCATION = stl.LOCATION_STL COLLATE utf8mb4_unicode_ci
                        ".$isInsideQry."
                    ".$groupBy."
            ) as smy
            ORDER BY AVG_VS DESC
        ");

        $resActs = DB::select("
            SELECT 
                ( ".$nameUser." ) as NAME_USER,
                smy.LOCATION_STL,
                smy.REGIONAL_STL,
                smy.REALACTUB_STL ,
                (".$tgtUB.") as TGTUB ,
                smy.VSUB ,
                smy.REALACTPS_STL ,
                (".$tgtPS.") as TGTPS ,
                smy.VSPS ,
                smy.REALACTRETAIL_STL ,
                (".$tgtRetail.") as TGTRETAIL ,
                smy.VSRETAIL ,
                ((smy.VSUB * ".$wUB.") / 100) + ((smy.VSPS * ".$wPS.") / 100) + ((smy.VSRETAIL * ".$wRETAIL.") / 100) as AVG_VS
            FROM (
                SELECT
                    stl.*,
                    (
                        (stl.REALACTUB_STL / (".$tgtUB.")) * 100
                    ) as VSUB,
                    (
                        (stl.REALACTUB_STL / (".$tgtPS.")) * 100
                    ) as VSPS,
                    (
                        (stl.REALACTUB_STL / (".$tgtRetail.")) * 100
                    ) as VSRETAIL
                FROM summary_trans_location stl
                INNER JOIN md_location ml
                    ON 
                        stl.YEAR_STL = ".$year."
                        AND stl.MONTH_STL = ".$month."
                        AND ml.NAME_LOCATION = stl.LOCATION_STL COLLATE utf8mb4_unicode_ci
                        ".$isInsideQry."
                    ".$groupBy."
            ) as smy
            ORDER BY AVG_VS DESC    
        ");

        $tot1 = 0;
        $tot2 = 0;
        $tot3 = 0;
        foreach ($resProds as $resProd) {
            $tot1 += $resProd->REALUST_STL;
            $tot2 += $resProd->REALNONUST_STL;
            $tot3 += $resProd->REALSELERAKU_STL;
        }
        $reportProds['DATAS']                = $resProds;
        $reportProds['wUST']                 = $wUST;
        $reportProds['wNONUST']              = $wNONUST;
        $reportProds['wSELERAKU']            = $wSELERAKU;
        $reportProds['AVG_REALUST']          = round($tot1 / count($reportProds['DATAS']));
        $reportProds['AVG_TGTUST']           = $tgtUST;
        $reportProds['AVG_VSUST']            = $reportProds['AVG_REALUST'] / $reportProds['AVG_TGTUST'];
        $reportProds['AVG_REALNONUST']       = round($tot2 / count($reportProds['DATAS']));
        $reportProds['AVG_TGTNONUST']        = $tgtNONUST;
        $reportProds['AVG_VSNONUST']         = $reportProds['AVG_REALNONUST'] / $reportProds['AVG_TGTNONUST'];
        $reportProds['AVG_REALSELERAKU']     = round($tot3 / count($reportProds['DATAS']));
        $reportProds['AVG_TGTSELERAKU']      = $tgtSeleraku;
        $reportProds['AVG_VSSELERAKU']       = $reportProds['AVG_REALSELERAKU'] / $reportProds['AVG_TGTSELERAKU'];
        $reportProds['AVG_VS']               = (($reportProds['AVG_VSUST'] * $wUST) / 100);
        $reportProds['AVG_VS']               += (($reportProds['AVG_VSNONUST'] * $wNONUST) / 100);
        $reportProds['AVG_VS']               += (($reportProds['AVG_VSSELERAKU'] * $wSELERAKU) / 100);
        
        $tot1 = 0;
        $tot2 = 0;
        $tot3 = 0;
        foreach ($resActs as $resAct) {
            $tot1 += $resAct->REALACTUB_STL;
            $tot2 += $resAct->REALACTPS_STL;
            $tot3 += $resAct->REALACTRETAIL_STL;
        }
        $reportActs['DATAS']                = $resActs;
        $reportActs['wUB']                  = $wUB;
        $reportActs['wPS']                  = $wPS;
        $reportActs['wRETAIL']              = $wRETAIL;
        $reportActs['DATAS']                = $resActs;
        $reportActs['AVG_REALACTUB']        = round($tot1 / count($reportActs['DATAS']));
        $reportActs['AVG_TGTUB']            = $tgtUB;
        $reportActs['AVG_VSUB']             = $reportActs['AVG_REALACTUB'] / $reportActs['AVG_TGTUB'];
        $reportActs['AVG_REALACTPS']        = round($tot2 / count($reportActs['DATAS']));
        $reportActs['AVG_TGTPS']            = $tgtPS;
        $reportActs['AVG_VSPS']             = $reportActs['AVG_REALACTPS'] / $reportActs['AVG_TGTPS'];
        $reportActs['AVG_REALACTRETAIL']    = round($tot3 / count($reportActs['DATAS']));
        $reportActs['AVG_TGTRETAIL']        = $tgtRetail;
        $reportActs['AVG_VSRETAIL']         = $reportActs['AVG_REALACTRETAIL'] / $reportActs['AVG_TGTRETAIL'];
        $reportActs['AVG_VS']               = (($reportActs['AVG_VSUB'] * $wUB) / 100);
        $reportActs['AVG_VS']               += (($reportActs['AVG_VSPS'] * $wPS) / 100);
        $reportActs['AVG_VS']               += (($reportActs['AVG_VSRETAIL'] * $wRETAIL) / 100);

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
