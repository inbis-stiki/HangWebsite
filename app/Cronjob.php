<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\TargetUser;

class Cronjob extends Model
{
    public static function queryGetDashboardMobile($month, $year, $queryCategory, $tgtUser)
    {
        return DB::select("
            SELECT
                u.ID_USER ,
                u.NAME_USER,
                u.ID_LOCATION ,
                u.ID_REGIONAL ,
                u.ID_AREA ,
                u.ID_ROLE ,
                COALESCE((
                    SELECT COUNT(*)
                    FROM `transaction` t
                    WHERE 
                    	YEAR(t.DATE_TRANS) = " . $year . "
						AND MONTH(t.DATE_TRANS) = " . $month . "
						AND t.ID_USER = u.ID_USER 
                    	AND (t.ID_TYPE = 2 OR t.ID_TYPE = 3)
                ), 0) as UBUBLP_DM,
                COALESCE((
                    SELECT COUNT(*)
                    FROM `transaction` t
                    WHERE 
                    	YEAR(t.DATE_TRANS) = " . $year . "
						AND MONTH(t.DATE_TRANS) = " . $month . "
						AND t.ID_USER = u.ID_USER 
                    	AND t.ID_TYPE = 1
                ), 0) as SPREADING_DM,
                COALESCE((
                    SELECT COUNT(t.ID_USER)
					FROM (
						SELECT t2.ID_USER
						FROM `transaction` t2
						WHERE 
							YEAR(t2.DATE_TRANS) = " . $year . "
							AND MONTH(t2.DATE_TRANS) = " . $month . "
						GROUP BY t2.ID_USER, DATE(t2.DATE_TRANS)
						HAVING SUM(t2.QTY_TRANS) < " . $tgtUser . "
					) as t
					WHERE t.ID_USER = u.ID_USER  
                ), 0) as OFFTARGET_DM,
                " . $queryCategory . "
            FROM `user` u 
            WHERE 
                u.ID_ROLE IN (5, 6) 
                AND u.deleted_at IS NULL
        ");
    }
    public static function queryGetSmyTransLocation($year, $month)
    {
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
                            YEAR(t2.DATE_TRANS) = " . $year . "
                            AND MONTH(t2.DATE_TRANS) = " . $month . "
                            AND t2.AREA_TRANS = t.AREA_TRANS 
                            AND td.ID_TRANS = t2.ID_TRANS 
                            AND td.ID_PC = 12
                ), 0) as 'REALUST_STL',
                COALESCE((
                    SELECT SUM(td.QTY_TD)
                    FROM `transaction` t2 
                    INNER JOIN transaction_detail td 
                        ON 
                            YEAR(t2.DATE_TRANS) = " . $year . "
                            AND MONTH(t2.DATE_TRANS) = " . $month . "
                            AND t2.AREA_TRANS = t.AREA_TRANS 
                            AND td.ID_TRANS = t2.ID_TRANS 
                            AND td.ID_PC = 2
                ), 0) as 'REALNONUST_STL',
                COALESCE((
                    SELECT SUM(td.QTY_TD)
                    FROM `transaction` t2 
                    INNER JOIN transaction_detail td 
                        ON 
                            YEAR(t2.DATE_TRANS) = " . $year . "
                            AND MONTH(t2.DATE_TRANS) = " . $month . "
                            AND t2.AREA_TRANS = t.AREA_TRANS 
                            AND td.ID_TRANS = t2.ID_TRANS 
                            AND td.ID_PC = 3
                ), 0) as 'REALSELERAKU_STL',
                COALESCE((
                    SELECT SUM(td.QTY_TD)
                    FROM `transaction` t2 
                    INNER JOIN transaction_detail td 
                        ON 
                            YEAR(t2.DATE_TRANS) = " . $year . "
                            AND MONTH(t2.DATE_TRANS) = " . $month . "
                            AND t2.AREA_TRANS = t.AREA_TRANS 
                            AND td.ID_TRANS = t2.ID_TRANS 
                            AND td.ID_PC = 16
                ), 0) as 'REALRENDANG_STL',
                COALESCE((
                    SELECT SUM(td.QTY_TD)
                    FROM `transaction` t2 
                    INNER JOIN transaction_detail td 
                        ON 
                            YEAR(t2.DATE_TRANS) = " . $year . "
                            AND MONTH(t2.DATE_TRANS) = " . $month . "
                            AND t2.AREA_TRANS = t.AREA_TRANS 
                            AND td.ID_TRANS = t2.ID_TRANS 
                            AND td.ID_PC = 17
                ), 0) as 'REALGEPREK_STL',
                COALESCE((
                    SELECT COUNT(*)
                    FROM `transaction` t2
                    WHERE 
                        YEAR(t2.DATE_TRANS) = " . $year . "
                        AND MONTH(t2.DATE_TRANS) = " . $month . "
                        AND t2.AREA_TRANS = t.AREA_TRANS 
                        AND t2.TYPE_ACTIVITY = 'AKTIVITAS UB'
                ), 0) as 'REALACTUB_STL',
                COALESCE((
                    SELECT COUNT(*)
                    FROM `transaction` t2
                    WHERE 
                        YEAR(t2.DATE_TRANS) = " . $year . "
                        AND MONTH(t2.DATE_TRANS) = " . $month . "
                        AND t2.AREA_TRANS = t.AREA_TRANS 
                        AND t2.TYPE_ACTIVITY = 'Pedagang Sayur'
                ), 0) as 'REALACTPS_STL',
                COALESCE((
                    SELECT COUNT(*)
                    FROM `transaction` t2
                    WHERE 
                        YEAR(t2.DATE_TRANS) = " . $year . "
                        AND MONTH(t2.DATE_TRANS) = " . $month . "
                        AND t2.AREA_TRANS = t.AREA_TRANS 
                        AND t2.TYPE_ACTIVITY = 'Retail'
                ), 0) as 'REALACTRETAIL_STL'
            FROM `transaction` t
            GROUP BY t.AREA_TRANS 
        ");
    }
    public static function queryGetRankLocation($year, $month, $area)
    {
        // SET WEIGHT
        $wUST       = CategoryProduct::where("ID_PC", "12")->first()->PERCENTAGE_PC;
        $wNONUST    = CategoryProduct::where("ID_PC", "2")->first()->PERCENTAGE_PC;
        $wSELERAKU  = CategoryProduct::where("ID_PC", "3")->first()->PERCENTAGE_PC;
        $wRENDANG   = CategoryProduct::where("ID_PC", "16")->first()->PERCENTAGE_PC;
        $wGEPREK    = CategoryProduct::where("ID_PC", "17")->first()->PERCENTAGE_PC;

        $wUB        = ActivityCategory::where("ID_AC", "1")->first()->PERCENTAGE_AC;
        $wPS        = ActivityCategory::where("ID_AC", "2")->first()->PERCENTAGE_AC;
        $wRETAIL    = ActivityCategory::where("ID_AC", "3")->first()->PERCENTAGE_AC;
        // SET AREA
        if ($area == "Regional") {
            $groupBy = "GROUP BY stl.REGIONAL_STL";
            $nameUser = "
                SELECT GROUP_CONCAT(u.NAME_USER)
                FROM md_regional mr 
                JOIN `user` u  
                    ON 
                        mr.NAME_REGIONAL = smy.REGIONAL_STL COLLATE utf8mb4_unicode_ci
                        AND u.ID_REGIONAL = mr.ID_REGIONAL 
                        AND u.ID_ROLE = 4
                        AND u.deleted_at IS NULL
            ";
            // SET TARGET
            $tgtUser    = app(TargetUser::class)->getRegional();
            $tgtUB      = $tgtUser['acts']['UB'];
            $tgtPS      = $tgtUser['acts']['PS'];
            $tgtRetail  = $tgtUser['acts']['Retail'];

            $tgtUST         = $tgtUser['prods']['UST'];
            $tgtNONUST      = $tgtUser['prods']['NONUST'];
            $tgtSeleraku    = $tgtUser['prods']['Seleraku'];
            $tgtRendang     = $tgtUser['prods']['Rendang'];
            $tgtGeprek      = $tgtUser['prods']['Geprek'];
        } else if ($area == "Location") {
            $groupBy = "GROUP BY stl.LOCATION_STL";
            $nameUser = "
                SELECT GROUP_CONCAT(u.NAME_USER)
                FROM md_location ml
                JOIN `user` u  
                    ON 
                        ml.NAME_LOCATION = smy.LOCATION_STL COLLATE utf8mb4_unicode_ci
                        AND u.ID_LOCATION = ml.ID_LOCATION 
                        AND u.ID_ROLE = 3
                        AND u.deleted_at IS NULL
            ";

            // SET TARGET
            $tgtUser    = app(TargetUser::class)->getAsmen();
            $tgtUB      = $tgtUser['acts']['UB'];
            $tgtPS      = $tgtUser['acts']['PS'];
            $tgtRetail  = $tgtUser['acts']['Retail'];

            $tgtUST         = $tgtUser['prods']['UST'];
            $tgtNONUST      = $tgtUser['prods']['NONUST'];
            $tgtSeleraku    = $tgtUser['prods']['Seleraku'];
            $tgtRendang     = $tgtUser['prods']['Rendang'];
            $tgtGeprek      = $tgtUser['prods']['Geprek'];
        }

        $resProds = DB::select("
            SELECT 
                ( " . $nameUser . " ) as NAME_USER,
                smy.LOCATION_STL,
                smy.REGIONAL_STL,
                smy.REALUST_STL ,
                (" . $tgtUST . ") as TGTUST ,
                smy.VSUST ,
                smy.REALNONUST_STL ,
                (" . $tgtNONUST . ") as TGTNONUST ,
                smy.VSNONUST ,
                smy.REALSELERAKU_STL ,
                (" . $tgtSeleraku . ") as TGTSELERAKU ,
                smy.VSSELERAKU ,
                smy.REALRENDANG_STL ,
                (" . $tgtRendang . ") as TGTRENDANG ,
                smy.VSRENDANG ,
                smy.REALGEPREK_STL ,
                (" . $tgtGeprek . ") as TGTGEPREK ,
                smy.VSGEPREK ,
                ((smy.VSUST * " . $wUST . ") / 100) + ((smy.VSNONUST * " . $wNONUST . ") / 100) + ((smy.VSSELERAKU * " . $wSELERAKU . ") / 100) + ((smy.VSRENDANG * " . $wRENDANG . ") / 100) + ((smy.VSGEPREK * " . $wGEPREK . ") / 100) as AVG_VS
            FROM (
                SELECT
                    stl.LOCATION_STL,
                    stl.REGIONAL_STL,
                    SUM(stl.REALUST_STL) as REALUST_STL,
                    SUM(stl.REALNONUST_STL) as REALNONUST_STL,
                    SUM(stl.REALSELERAKU_STL) as REALSELERAKU_STL,
                    SUM(stl.REALRENDANG_STL) as REALRENDANG_STL,
                    SUM(stl.REALGEPREK_STL) as REALGEPREK_STL,
                    (
                        (SUM(stl.REALUST_STL) / (" . $tgtUST . ")) * 100
                    ) as VSUST,
                    (
                        (SUM(stl.REALNONUST_STL) / (" . $tgtNONUST . ")) * 100
                    ) as VSNONUST,
                    (
                        (SUM(stl.REALSELERAKU_STL) / (" . $tgtSeleraku . ")) * 100
                    ) as VSSELERAKU,
                    (
                        (SUM(stl.REALRENDANG_STL) / (" . $tgtRendang . ")) * 100
                    ) as VSRENDANG,
                    (
                        (SUM(stl.REALGEPREK_STL) / (" . $tgtGeprek . ")) * 100
                    ) as VSGEPREK
                FROM summary_trans_location stl
                INNER JOIN md_location ml
                    ON 
                        stl.YEAR_STL = " . $year . "
                        AND stl.MONTH_STL = " . $month . "
                        AND ml.NAME_LOCATION = stl.LOCATION_STL COLLATE utf8mb4_unicode_ci
                    " . $groupBy . "
            ) as smy
            ORDER BY AVG_VS DESC
        ");

        $resActs = DB::select("
            SELECT 
                ( " . $nameUser . " ) as NAME_USER,
                smy.LOCATION_STL,
                smy.REGIONAL_STL,
                smy.REALACTUB_STL ,
                (" . $tgtUB . ") as TGTUB ,
                smy.VSUB ,
                smy.REALACTPS_STL ,
                (" . $tgtPS . ") as TGTPS ,
                smy.VSPS ,
                smy.REALACTRETAIL_STL ,
                (" . $tgtRetail . ") as TGTRETAIL ,
                smy.VSRETAIL ,
                ((smy.VSUB * " . $wUB . ") / 100) + ((smy.VSPS * " . $wPS . ") / 100) + ((smy.VSRETAIL * " . $wRETAIL . ") / 100) as AVG_VS
            FROM (
                SELECT
                    stl.LOCATION_STL,
                    stl.REGIONAL_STL,
                    SUM(stl.REALACTUB_STL) as REALACTUB_STL,
                    SUM(stl.REALACTPS_STL) as REALACTPS_STL,
                    SUM(stl.REALACTRETAIL_STL) as REALACTRETAIL_STL,
                    (
                        (SUM(stl.REALACTUB_STL) / (" . $tgtUB . ")) * 100
                    ) as VSUB,
                    (
                        (SUM(stl.REALACTPS_STL) / (" . $tgtPS . ")) * 100
                    ) as VSPS,
                    (
                        (SUM(stl.REALACTRETAIL_STL) / (" . $tgtRetail . ")) * 100
                    ) as VSRETAIL
                FROM summary_trans_location stl
                INNER JOIN md_location ml
                    ON 
                        stl.YEAR_STL = " . $year . "
                        AND stl.MONTH_STL = " . $month . "
                        AND ml.NAME_LOCATION = stl.LOCATION_STL COLLATE utf8mb4_unicode_ci
                    " . $groupBy . "
            ) as smy
            ORDER BY AVG_VS DESC    
        ");

        $tot1 = 0;
        $tot2 = 0;
        $tot3 = 0;
        $tot4 = 0;
        $tot5 = 0;
        foreach ($resProds as $resProd) {
            $tot1 += $resProd->REALUST_STL;
            $tot2 += $resProd->REALNONUST_STL;
            $tot3 += $resProd->REALSELERAKU_STL;
            $tot4 += $resProd->REALRENDANG_STL;
            $tot5 += $resProd->REALGEPREK_STL;
        }
        $reportProds['DATAS']                = $resProds;
        $reportProds['wUST']                 = $wUST;
        $reportProds['wNONUST']              = $wNONUST;
        $reportProds['wSELERAKU']            = $wSELERAKU;
        $reportProds['wRENDANG']             = $wRENDANG;
        $reportProds['wGEPREK']              = $wGEPREK;
        $reportProds['AVG_REALUST']          = count($reportProds['DATAS']) == 0 ? 0 : round($tot1 / count($reportProds['DATAS']));
        $reportProds['AVG_TGTUST']           = $tgtUST;
        $reportProds['AVG_VSUST']            = $reportProds['AVG_REALUST'] / $reportProds['AVG_TGTUST'];
        $reportProds['AVG_REALNONUST']       = count($reportProds['DATAS']) == 0 ? 0 : round($tot2 / count($reportProds['DATAS']));
        $reportProds['AVG_TGTNONUST']        = $tgtNONUST;
        $reportProds['AVG_VSNONUST']         = $reportProds['AVG_REALNONUST'] / $reportProds['AVG_TGTNONUST'];
        $reportProds['AVG_REALSELERAKU']     = count($reportProds['DATAS']) == 0 ? 0 : round($tot3 / count($reportProds['DATAS']));
        $reportProds['AVG_TGTSELERAKU']      = $tgtSeleraku;
        $reportProds['AVG_VSSELERAKU']       = $reportProds['AVG_REALSELERAKU'] / $reportProds['AVG_TGTSELERAKU'];
        $reportProds['AVG_REALRENDANG']      = count($reportProds['DATAS']) == 0 ? 0 : round($tot4 / count($reportProds['DATAS']));
        $reportProds['AVG_TGTRENDANG']       = $tgtRendang;
        $reportProds['AVG_VSRENDANG']        = $reportProds['AVG_REALRENDANG'] / $reportProds['AVG_TGTRENDANG'];
        $reportProds['AVG_REALGEPREK']       = count($reportProds['DATAS']) == 0 ? 0 : round($tot5 / count($reportProds['DATAS']));
        $reportProds['AVG_TGTGEPREK']        = $tgtGeprek;
        $reportProds['AVG_VSGEPREK']         = $reportProds['AVG_REALGEPREK'] / $reportProds['AVG_TGTGEPREK'];
        $reportProds['AVG_VS']               = (($reportProds['AVG_VSUST'] * $wUST) / 100);
        $reportProds['AVG_VS']               += (($reportProds['AVG_VSNONUST'] * $wNONUST) / 100);
        $reportProds['AVG_VS']               += (($reportProds['AVG_VSSELERAKU'] * $wSELERAKU) / 100);
        $reportProds['AVG_VS']               += (($reportProds['AVG_VSRENDANG'] * $wRENDANG) / 100);
        $reportProds['AVG_VS']               += (($reportProds['AVG_VSGEPREK'] * $wRENDANG) / 100);

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
        $reportActs['AVG_REALACTUB']        = count($reportActs['DATAS']) == 0 ? 0 : round($tot1 / count($reportActs['DATAS']));
        $reportActs['AVG_TGTUB']            = $tgtUB;
        $reportActs['AVG_VSUB']             = $reportActs['AVG_REALACTUB'] / $reportActs['AVG_TGTUB'];
        $reportActs['AVG_REALACTPS']        = count($reportActs['DATAS']) == 0 ? 0 : round($tot2 / count($reportActs['DATAS']));
        $reportActs['AVG_TGTPS']            = $tgtPS;
        $reportActs['AVG_VSPS']             = $reportActs['AVG_REALACTPS'] / $reportActs['AVG_TGTPS'];
        $reportActs['AVG_REALACTRETAIL']    = count($reportActs['DATAS']) == 0 ? 0 : round($tot3 / count($reportActs['DATAS']));
        $reportActs['AVG_TGTRETAIL']        = $tgtRetail;
        $reportActs['AVG_VSRETAIL']         = $reportActs['AVG_REALACTRETAIL'] / $reportActs['AVG_TGTRETAIL'];
        $reportActs['AVG_VS']               = (($reportActs['AVG_VSUB'] * $wUB) / 100);
        $reportActs['AVG_VS']               += (($reportActs['AVG_VSPS'] * $wPS) / 100);
        $reportActs['AVG_VS']               += (($reportActs['AVG_VSRETAIL'] * $wRETAIL) / 100);

        return ['reportProds' => $reportProds, 'reportActs' => $reportActs];
    }
    public static function queryGetRankUser($idRegional, $idRole, $year, $month)
    {
        // SET WEIGHT
        $wUST       = CategoryProduct::where("ID_PC", "12")->first()->PERCENTAGE_PC;
        $wNONUST    = CategoryProduct::where("ID_PC", "2")->first()->PERCENTAGE_PC;
        $wSELERAKU  = CategoryProduct::where("ID_PC", "3")->first()->PERCENTAGE_PC;
        $wRENDANG   = CategoryProduct::where("ID_PC", "16")->first()->PERCENTAGE_PC;
        $wGEPREK    = CategoryProduct::where("ID_PC", "17")->first()->PERCENTAGE_PC;

        $wUB        = ActivityCategory::where("ID_AC", "1")->first()->PERCENTAGE_AC;
        $wPS        = ActivityCategory::where("ID_AC", "2")->first()->PERCENTAGE_AC;
        $wRETAIL    = ActivityCategory::where("ID_AC", "3")->first()->PERCENTAGE_AC;

        // SET TARGET
        $tgtUser    = app(TargetUser::class)->getUser();
        $tgtUB      = $tgtUser['acts']['UB'];
        $tgtPS      = $tgtUser['acts']['PS'];
        $tgtRetail  = $tgtUser['acts']['Retail'];

        $tgtUST         = $tgtUser['prods']['UST'];
        $tgtNONUST      = $tgtUser['prods']['NONUST'];;
        $tgtSeleraku    = $tgtUser['prods']['Seleraku'];;
        $tgtRendang     = $tgtUser['prods']['Rendang'];;
        $tgtGeprek      = $tgtUser['prods']['Geprek'];;

        $resActs = DB::select("
            SELECT 
                rp.NAME_USER ,
                rp.NAME_AREA ,
                rp.REALACTUB_DM ,
                (" . $tgtUB . ") as TGTUB ,
                rp.VSUB ,
                rp.REALACTPS_DM ,
                (" . $tgtPS . ") as TGTPS ,
                rp.VSPS ,
                rp.REALACTRETAIL_DM ,
                (" . $tgtRetail . ") as TGTRETAIL ,
                rp.VSRETAIL ,
                ((rp.VSUB * " . $wUB . ") / 100) + ((rp.VSPS * " . $wPS . ") / 100) + ((rp.VSRETAIL * " . $wRETAIL . ") / 100) as AVG_VS
            FROM (
                SELECT 
                    ma.NAME_AREA ,
                    dm.*,
                    (
                        (dm.REALACTUB_DM/ (" . $tgtUB . ")) * 100
                    ) as VSUB,
                    (
                        (dm.REALACTPS_DM/ (" . $tgtPS . ")) * 100
                    ) as VSPS,
                    (
                        (dm.REALACTRETAIL_DM/ (" . $tgtRetail . ")) * 100
                    ) as VSRETAIL
                FROM summary_trans_user dm 
                INNER JOIN md_area ma
                    ON
                        dm.YEAR = '" . $year . "'
                        AND dm.MONTH = '" . $month . "'
                        AND dm.ID_REGIONAL = '" . $idRegional . "'
                        AND dm.ID_ROLE = '" . $idRole . "'
                        AND dm.ID_AREA = ma.ID_AREA
            ) as rp
            ORDER BY AVG_VS DESC
        ");

        $reportActs['DATAS']    = $resActs;
        $reportActs['wUB']      = $wUB;
        $reportActs['wPS']      = $wPS;
        $reportActs['wRETAIL']  = $wRETAIL;

        $resProds = DB::select("
            SELECT 
                rp.NAME_USER,
                rp.NAME_AREA,
                rp.REALUST_DM ,
                (" . $tgtUST . ") as TGTUST ,
                rp.VSUST ,
                rp.REALNONUST_DM ,
                (" . $tgtNONUST . ") as TGTNONUST ,
                rp.VSNONUST ,
                rp.REALSELERAKU_DM ,
                (" . $tgtSeleraku . ") as TGTSELERAKU ,
                rp.VSSELERAKU ,
                rp.REALRENDANG_DM ,
                (" . $tgtRendang . ") as TGTRENDANG ,
                rp.VSRENDANG ,
                rp.REALGEPREK_DM ,
                (" . $tgtGeprek . ") as TGTGEPREK ,
                rp.VSGEPREK ,
                ((rp.VSUST * " . $wUST . ") / 100) + ((rp.VSNONUST * " . $wNONUST . ") / 100) + ((rp.VSSELERAKU * " . $wSELERAKU . ") / 100) + ((rp.VSRENDANG * " . $wRENDANG . ") / 100) + ((rp.VSGEPREK * " . $wGEPREK . ") / 100) as AVG_VS
            FROM 
            (
                SELECT 
                    ma.NAME_AREA ,
                    dm.* ,
                    (
                        (dm.REALUST_DM/ (" . $tgtUST . ")) * 100
                    ) as VSUST,
                    (
                        (dm.REALNONUST_DM/ (" . $tgtNONUST . ")) * 100
                    ) as VSNONUST,
                    (
                        (dm.REALSELERAKU_DM/ (" . $tgtSeleraku . ")) * 100
                    ) as VSSELERAKU,
                    (
                        (dm.REALRENDANG_DM/ (" . $tgtRendang . ")) * 100
                    ) as VSRENDANG,
                    (
                        (dm.REALGEPREK_DM/ (" . $tgtGeprek . ")) * 100
                    ) as VSGEPREK
                FROM summary_trans_user dm 
                INNER JOIN md_area ma
                    ON
                        dm.YEAR = '" . $year . "'
                        AND dm.MONTH = '" . $month . "'
                        AND dm.ID_REGIONAL = '" . $idRegional . "'
                        AND dm.ID_ROLE = '" . $idRole . "'
                        AND dm.ID_AREA = ma.ID_AREA
            ) as rp
            ORDER BY AVG_VS DESC
        ");

        $reportProds['DATAS']       = $resProds;
        $reportProds['wUST']        = $wUST;
        $reportProds['wNONUST']     = $wNONUST;
        $reportProds['wSELERAKU']   = $wSELERAKU;
        $reportProds['wRENDANG']    = $wRENDANG;
        $reportProds['wGEPREK']     = $wGEPREK;

        return ['reportProds' => $reportProds, 'reportActs' => $reportActs];
    }
    public static function queryGetTrend($year, $area)
    {
        if ($area == "Regional") {
            $qWhere = "
                stl2.REGIONAL_STL = stl.REGIONAL_STL
                AND stl2.LOCATION_STL = stl.LOCATION_STL 
            ";

            $qGnO = "
                GROUP By stl.REGIONAL_STL 
                ORDER BY stl.REGIONAL_STL ASC
            ";

            $tgtUser = app(TargetUser::class)->getRegional();
        } else if ($area == "Location") {
            $qWhere = "
            stl2.LOCATION_STL = stl.LOCATION_STL 
            ";

            $qGnO = "
            GROUP By stl.LOCATION_STL 
            ORDER BY stl.LOCATION_STL ASC
            ";

            $tgtUser = app(TargetUser::class)->getAsmen();
        }

        $tgtUST         = $tgtUser['prods']['UST'];
        $tgtNONUST      = $tgtUser['prods']['NONUST'];
        $tgtSeleraku    = $tgtUser['prods']['Seleraku'];
        $tgtRendang     = $tgtUser['prods']['Rendang'];
        $tgtGeprek      = $tgtUser['prods']['Geprek'];

        $qRealMonths = [];
        for ($i = 1; $i <= 12; $i++) {
            $qRealMonths[] = "
                (
                    SELECT
                        CONCAT(
                            COALESCE((SUM(stl2.REALUST_STL)), 0), ';',
                            COALESCE((SUM(stl2.REALNONUST_STL)), 0), ';',
                            COALESCE((SUM(stl2.REALSELERAKU_STL)), 0), ';',
                            COALESCE((SUM(stl2.REALRENDANG_STL)), 0), ';',
                            COALESCE((SUM(stl2.REALGEPREK_STL)), 0)
                        )
                    FROM summary_trans_location stl2
                    WHERE 
                        " . $qWhere . "
                        AND stl2.YEAR_STL = " . $year . "
                        AND stl2.MONTH_STL = " . $i . "
                ) as M" . $i . "
            ";
        }
        $qRealMonth = implode(', ', $qRealMonths);

        $reports = DB::select("
            SELECT
            stl.LOCATION_STL,
            stl.REGIONAL_STL,
            " . $tgtUST . " as TGTUST,
            " . $tgtNONUST . " as TGTNONUST,
            " . $tgtSeleraku . " as TGTSELERAKU,
            " . $tgtRendang . " as TGTRENDANG,
            " . $tgtGeprek . " as TGTGEPREK,
            " . $qRealMonth . "
            FROM summary_trans_location stl 
            WHERE stl.YEAR_STL = " . $year . "
            " . $qGnO . "
        ");

        return $reports;
    }
    public static function queryGetTransactionDaily($querySumProd, $date, $regional)
    {
        return DB::select("
            SELECT
                u.ID_USER,
                u.NAME_USER,
                mt.NAME_TYPE,
                td.AREA_TD,
                IF(t.DISTRICT IS NOT NULL,
                t.DISTRICT,
                t.KECAMATAN) AS DISTRICT,
                t.DETAIL_LOCATION,
                mr.NAME_ROLE,
                " . $querySumProd . ",
                td.ISFINISHED_TD,
                td.TOTAL_TD
            FROM
                transaction_daily td
            JOIN md_type mt ON DATE(td.DATE_TD) = '" . $date . "' AND td.REGIONAL_TD = '" . $regional . "' AND (mt.ID_TYPE = td.ID_TYPE OR td.ID_TYPE IS NULL)
            INNER JOIN `user` u ON
                u.ID_USER = td.ID_USER
            INNER JOIN md_role mr ON
                mr.ID_ROLE = u.ID_ROLE
            LEFT JOIN transaction t ON
                t.ID_TD = td.ID_TD
                AND t.ID_TYPE = mt.ID_TYPE
            LEFT JOIN transaction_detail td2 ON
                t.ID_TRANS = td2.ID_TRANS
            JOIN md_type mt2 ON
                t.ID_TYPE = mt2.ID_TYPE
                AND mt.NAME_TYPE = mt2.NAME_TYPE
            GROUP BY
                u.ID_USER,
                td.ID_TD,
                mt.NAME_TYPE
            ORDER BY
                td.AREA_TD ASC,
                u.NAME_USER ASC;
	    ");
    }
    public static function queryGetRepeatOrder($year, $month)
    {
        $areas = DB::select("
            SELECT t.REGIONAL_TRANS , t.AREA_TRANS 
            FROM `transaction` t 
            WHERE YEAR(t.DATE_TRANS) = " . $year . " AND MONTH(t.DATE_TRANS) = " . $month . "
            GROUP BY t.AREA_TRANS , t.REGIONAL_TRANS 
            ORDER BY t.REGIONAL_TRANS ASC , t.AREA_TRANS ASC
        ");

        $rOs = [];

        foreach ($areas as $area) {
            if (empty($rOs[$area->REGIONAL_TRANS])) $rOs[$area->REGIONAL_TRANS] = [];
            $rOs[$area->REGIONAL_TRANS][$area->AREA_TRANS] = DB::select("
                SELECT
                    (
                        SELECT
                            COUNT(u.ID_USER) 
                        FROM
                            user u
                        JOIN md_regional mr ON 
                            mr.ID_REGIONAL = u.ID_REGIONAL
                        WHERE
                            mr.NAME_REGIONAL = '" . $area->REGIONAL_TRANS . "'
                            AND
                            u.deleted_at IS NULL   
                    ) AS TOTALAPO,
                    (
                        SELECT COUNT(ms.ID_SHOP) as TOTAL
                        FROM md_area ma
                        INNER JOIN md_district md
                        ON 
                            ma.NAME_AREA = '" . $area->AREA_TRANS . "' 
                            AND ma.deleted_at IS NULL 
                            AND md.ID_AREA = ma.ID_AREA
                        INNER JOIN md_shop ms 
                        ON ms.ID_DISTRICT = md.ID_DISTRICT AND ms.TYPE_SHOP = 'Pedagang Sayur' 
                    ) as 'TOTALPS',
                    (
                        SELECT COUNT(ms.ID_SHOP) as TOTAL
                        FROM md_area ma
                        INNER JOIN md_district md
                        ON 
                            ma.NAME_AREA = '" . $area->AREA_TRANS . "' 
                            AND ma.deleted_at IS NULL 
                            AND md.ID_AREA = ma.ID_AREA
                        INNER JOIN md_shop ms 
                        ON ms.ID_DISTRICT = md.ID_DISTRICT AND ms.TYPE_SHOP = 'Loss' 
                    ) as 'TOTALLOSS',
                    (
                        SELECT COUNT(ms.ID_SHOP) as TOTAL
                        FROM md_area ma
                        INNER JOIN md_district md
                        ON 
                            ma.NAME_AREA = '" . $area->AREA_TRANS . "' 
                            AND ma.deleted_at IS NULL 
                            AND md.ID_AREA = ma.ID_AREA
                        INNER JOIN md_shop ms 
                        ON ms.ID_DISTRICT = md.ID_DISTRICT AND ms.TYPE_SHOP = 'Retail' 
                    ) as 'TOTALRETAIL',
                    (
                        SELECT COUNT(x.TOTAL)
                        FROM (
                            SELECT COUNT(t.ID_SHOP) as TOTAL 
                            FROM `transaction` t
                            INNER JOIN md_shop ms
                                ON
                                    YEAR(t.DATE_TRANS) = " . $year . "
                                    AND MONTH(t.DATE_TRANS) = " . $month . "
                                    AND t.AREA_TRANS = '" . $area->AREA_TRANS . "'
                                    AND t.REGIONAL_TRANS = '" . $area->REGIONAL_TRANS . "'
                                    AND ms.ID_SHOP = t.ID_SHOP
                                    AND ms.TYPE_SHOP = 'Pedagang Sayur'
                            GROUP BY t.ID_SHOP 
                            HAVING TOTAL >= 2 AND TOTAL <= 3
                        ) as x	
                    ) as 'PS_2-3',
                    (
                        SELECT COUNT(x.TOTAL)
                        FROM (
                            SELECT COUNT(t.ID_SHOP) as TOTAL 
                            FROM `transaction` t
                            INNER JOIN md_shop ms
                                ON
                                    YEAR(t.DATE_TRANS) = " . $year . "
                                    AND MONTH(t.DATE_TRANS) = " . $month . "
                                    AND t.AREA_TRANS = '" . $area->AREA_TRANS . "'
                                    AND t.REGIONAL_TRANS = '" . $area->REGIONAL_TRANS . "'
                                    AND ms.ID_SHOP = t.ID_SHOP
                                    AND ms.TYPE_SHOP = 'Pedagang Sayur'
                            GROUP BY t.ID_SHOP 
                            HAVING TOTAL >= 4 AND TOTAL <= 5
                        ) as x	
                    ) as 'PS_4-5',
                    (
                        SELECT COUNT(x.TOTAL)
                        FROM (
                            SELECT COUNT(t.ID_SHOP) as TOTAL 
                            FROM `transaction` t
                            INNER JOIN md_shop ms
                                ON
                                    YEAR(t.DATE_TRANS) = " . $year . "
                                    AND MONTH(t.DATE_TRANS) = " . $month . "
                                    AND t.AREA_TRANS = '" . $area->AREA_TRANS . "'
                                    AND t.REGIONAL_TRANS = '" . $area->REGIONAL_TRANS . "'
                                    AND ms.ID_SHOP = t.ID_SHOP
                                    AND ms.TYPE_SHOP = 'Pedagang Sayur'
                            GROUP BY t.ID_SHOP 
                            HAVING TOTAL >= 6 AND TOTAL <= 10
                        ) as x	
                    ) as 'PS_6-10',
                    (
                        SELECT COUNT(x.TOTAL)
                        FROM (
                            SELECT COUNT(t.ID_SHOP) as TOTAL 
                            FROM `transaction` t
                            INNER JOIN md_shop ms
                                ON
                                    YEAR(t.DATE_TRANS) = " . $year . "
                                    AND MONTH(t.DATE_TRANS) = " . $month . "
                                    AND t.AREA_TRANS = '" . $area->AREA_TRANS . "'
                                    AND t.REGIONAL_TRANS = '" . $area->REGIONAL_TRANS . "'
                                    AND ms.ID_SHOP = t.ID_SHOP
                                    AND ms.TYPE_SHOP = 'Pedagang Sayur'
                            GROUP BY t.ID_SHOP 
                            HAVING TOTAL >= 11
                        ) as x	
                    ) as 'PS_>11',
                    (
                        SELECT COUNT(x.TOTAL)
                        FROM (
                            SELECT COUNT(t.ID_SHOP) as TOTAL 
                            FROM `transaction` t
                            INNER JOIN md_shop ms
                                ON
                                    YEAR(t.DATE_TRANS) = " . $year . "
                                    AND MONTH(t.DATE_TRANS) = " . $month . "
                                    AND t.AREA_TRANS = '" . $area->AREA_TRANS . "'
                                    AND t.REGIONAL_TRANS = '" . $area->REGIONAL_TRANS . "'
                                    AND ms.ID_SHOP = t.ID_SHOP
                                    AND ms.TYPE_SHOP = 'Retail'
                            GROUP BY t.ID_SHOP 
                            HAVING TOTAL >= 2 AND TOTAL <= 3
                        ) as x	
                    ) as 'Retail_2-3',
                    (
                        SELECT COUNT(x.TOTAL)
                        FROM (
                            SELECT COUNT(t.ID_SHOP) as TOTAL 
                            FROM `transaction` t
                            INNER JOIN md_shop ms
                                ON
                                    YEAR(t.DATE_TRANS) = " . $year . "
                                    AND MONTH(t.DATE_TRANS) = " . $month . "
                                    AND t.AREA_TRANS = '" . $area->AREA_TRANS . "'
                                    AND t.REGIONAL_TRANS = '" . $area->REGIONAL_TRANS . "'
                                    AND ms.ID_SHOP = t.ID_SHOP
                                    AND ms.TYPE_SHOP = 'Retail'
                            GROUP BY t.ID_SHOP 
                            HAVING TOTAL >= 4 AND TOTAL <= 5
                        ) as x	
                    ) as 'Retail_4-5',
                    (
                        SELECT COUNT(x.TOTAL)
                        FROM (
                            SELECT COUNT(t.ID_SHOP) as TOTAL 
                            FROM `transaction` t
                            INNER JOIN md_shop ms
                                ON
                                    YEAR(t.DATE_TRANS) = " . $year . "
                                    AND MONTH(t.DATE_TRANS) = " . $month . "
                                    AND t.AREA_TRANS = '" . $area->AREA_TRANS . "'
                                    AND t.REGIONAL_TRANS = '" . $area->REGIONAL_TRANS . "'
                                    AND ms.ID_SHOP = t.ID_SHOP
                                    AND ms.TYPE_SHOP = 'Retail'
                            GROUP BY t.ID_SHOP 
                            HAVING TOTAL >= 6 AND TOTAL <= 10
                        ) as x	
                    ) as 'Retail_6-10',
                    (
                        SELECT COUNT(x.TOTAL)
                        FROM (
                            SELECT COUNT(t.ID_SHOP) as TOTAL 
                            FROM `transaction` t
                            INNER JOIN md_shop ms
                                ON
                                    YEAR(t.DATE_TRANS) = " . $year . "
                                    AND MONTH(t.DATE_TRANS) = " . $month . "
                                    AND t.AREA_TRANS = '" . $area->AREA_TRANS . "'
                                    AND t.REGIONAL_TRANS = '" . $area->REGIONAL_TRANS . "'
                                    AND ms.ID_SHOP = t.ID_SHOP
                                    AND ms.TYPE_SHOP = 'Retail'
                            GROUP BY t.ID_SHOP 
                            HAVING TOTAL >= 11
                        ) as x	
                    ) as 'Retail_>11',
                    (
                        SELECT COUNT(x.TOTAL)
                        FROM (
                            SELECT COUNT(t.ID_SHOP) as TOTAL 
                            FROM `transaction` t
                            INNER JOIN md_shop ms
                                ON
                                    YEAR(t.DATE_TRANS) = " . $year . "
                                    AND MONTH(t.DATE_TRANS) = " . $month . "
                                    AND t.AREA_TRANS = '" . $area->AREA_TRANS . "'
                                    AND t.REGIONAL_TRANS = '" . $area->REGIONAL_TRANS . "'
                                    AND ms.ID_SHOP = t.ID_SHOP
                                    AND ms.TYPE_SHOP = 'Loss'
                            GROUP BY t.ID_SHOP 
                            HAVING TOTAL >= 2 AND TOTAL <= 3
                        ) as x	
                    ) as 'Loss_2-3',
                    (
                        SELECT COUNT(x.TOTAL)
                        FROM (
                            SELECT COUNT(t.ID_SHOP) as TOTAL 
                            FROM `transaction` t
                            INNER JOIN md_shop ms
                                ON
                                    YEAR(t.DATE_TRANS) = " . $year . "
                                    AND MONTH(t.DATE_TRANS) = " . $month . "
                                    AND t.AREA_TRANS = '" . $area->AREA_TRANS . "'
                                    AND t.REGIONAL_TRANS = '" . $area->REGIONAL_TRANS . "'
                                    AND ms.ID_SHOP = t.ID_SHOP
                                    AND ms.TYPE_SHOP = 'Loss'
                            GROUP BY t.ID_SHOP 
                            HAVING TOTAL >= 4 AND TOTAL <= 5
                        ) as x	
                    ) as 'Loss_4-5',
                    (
                        SELECT COUNT(x.TOTAL)
                        FROM (
                            SELECT COUNT(t.ID_SHOP) as TOTAL 
                            FROM `transaction` t
                            INNER JOIN md_shop ms
                                ON
                                    YEAR(t.DATE_TRANS) = " . $year . "
                                    AND MONTH(t.DATE_TRANS) = " . $month . "
                                    AND t.AREA_TRANS = '" . $area->AREA_TRANS . "'
                                    AND t.REGIONAL_TRANS = '" . $area->REGIONAL_TRANS . "'
                                    AND ms.ID_SHOP = t.ID_SHOP
                                    AND ms.TYPE_SHOP = 'Loss'
                            GROUP BY t.ID_SHOP 
                            HAVING TOTAL >= 6 AND TOTAL <= 10
                        ) as x	
                    ) as 'Loss_6-10',
                    (
                        SELECT COUNT(x.TOTAL)
                        FROM (
                            SELECT COUNT(t.ID_SHOP) as TOTAL 
                            FROM `transaction` t
                            INNER JOIN md_shop ms
                                ON
                                    YEAR(t.DATE_TRANS) = " . $year . "
                                    AND MONTH(t.DATE_TRANS) = " . $month . "
                                    AND t.AREA_TRANS = '" . $area->AREA_TRANS . "'
                                    AND t.REGIONAL_TRANS = '" . $area->REGIONAL_TRANS . "'
                                    AND ms.ID_SHOP = t.ID_SHOP
                                    AND ms.TYPE_SHOP = 'Loss'
                            GROUP BY t.ID_SHOP 
                            HAVING TOTAL >= 11
                        ) as x	
                    ) as 'Loss_>11'
            ")[0];
        }
        return $rOs;
    }

    public static function getreg($year, $month)
    {
        $areas = DB::select("
            SELECT t.REGIONAL_TRANS 
            FROM `transaction` t 
            WHERE YEAR(t.DATE_TRANS) = " . $year . " AND MONTH(t.DATE_TRANS) = " . $month . "
            GROUP BY t.REGIONAL_TRANS 
            ORDER BY t.REGIONAL_TRANS ASC
        ");

        return $areas;
    }

    public static function queryGetRepeatOrderShop($year, $month)
    {

        $rOs = DB::select("
        SELECT
            ms.ID_SHOP,
            ms.NAME_SHOP,
            md.NAME_DISTRICT,
            ms.OWNER_SHOP,
            ms.DETLOC_SHOP,
            ms.TYPE_SHOP,
            ms.TELP_SHOP,
            ma.NAME_AREA,
            mr.NAME_REGIONAL,
            COUNT(t.ID_SHOP) AS TOTAL_TEST,
            SUM(t.QTY_TRANS) AS TOTAL_RO_PRODUCT
        FROM
            `transaction` t
        INNER JOIN (
            SELECT
                ID_SHOP
            FROM
                `transaction`
            WHERE
                YEAR(DATE_TRANS) = " . $year . "
                AND MONTH(DATE_TRANS) = " . $month . "
            GROUP BY
                ID_SHOP
            HAVING
                COUNT(*) BETWEEN 2 AND 100
        ) t2 ON
            t2.ID_SHOP = t.ID_SHOP
        LEFT JOIN md_shop ms ON
            ms.ID_SHOP = t.ID_SHOP
        INNER JOIN md_district md ON
            md.ID_DISTRICT = ms.ID_DISTRICT
        INNER JOIN md_area ma ON
            ma.ID_AREA = md.ID_AREA
        INNER JOIN md_regional mr ON
            mr.ID_REGIONAL = ma.ID_REGIONAL
        WHERE
            ms.ID_SHOP IS NOT NULL AND
            YEAR(DATE_TRANS) = " . $year . "
            AND MONTH(DATE_TRANS) = " . $month . "
        GROUP BY
            t.ID_SHOP
        ORDER BY
            mr.ID_REGIONAL ASC
        ");

        return $rOs;
    }

    public static function queryGetShopByRange($startM, $startY, $endM, $endY, $idRegional)
    {
        $areas = DB::select("
            SELECT mr.NAME_REGIONAL, ma.NAME_AREA
            FROM `md_shop` ms
            JOIN `md_district` md ON md.ID_DISTRICT = ms.ID_DISTRICT
            JOIN `md_area` ma ON ma.ID_AREA = md.ID_AREA
            JOIN `md_regional` mr ON mr.ID_REGIONAL = ma.ID_REGIONAL
            WHERE mr.ID_REGIONAL = " . $idRegional . "
            GROUP BY mr.NAME_REGIONAL, ma.NAME_AREA
            ORDER BY mr.NAME_REGIONAL, ma.NAME_AREA ASC
        ");

        $dataValue = [];
        $no = 0;
        for ($y = $startY; $y <= $endY; $y++) {
            for ($m = 1; $m <= 12; $m++) {
                if ($y == $startY && $m < $startM) {
                    continue;
                }
                if ($y == $endY && $m > $endM) {
                    continue;
                }
                array_push(
                    $dataValue,
                    "SUM(CASE WHEN rh.BULAN = " . $m . " AND rh.TAHUN = " . $y . " THEN rd.TOTAL_RO ELSE 0 END) AS 'VALUE" . $no . "'"
                );
                $no++;
            }
        }

        $dataValue2 = [];
        $no = 0;
        for ($y = $startY; $y <= $endY; $y++) {
            for ($m = 1; $m <= 12; $m++) {
                if ($y == $startY && $m < $startM) {
                    continue;
                }
                if ($y == $endY && $m > $endM) {
                    continue;
                }
                array_push(
                    $dataValue2,
                    "IFNULL(SUM(CASE WHEN rh.BULAN = " . $m . " AND rh.TAHUN = " . $y . " THEN rd.TOTAL_RO_PRODUCT ELSE 0 END), 0) AS 'VALUE2" . $no . "'"
                );
                $no++;
            }
        }

        $dataKey = [];
        $no = 0;
        for ($y = $startY; $y <= $endY; $y++) {
            for ($m = 1; $m <= 12; $m++) {
                if ($y == $startY && $m < $startM) {
                    continue;
                }
                if ($y == $endY && $m > $endM) {
                    continue;
                }
                array_push(
                    $dataKey,
                    "('" . $m . ";" . $y . "') AS 'KEY" . $no . "'"
                );
                $no++;
            }
        }

        $dataKey2 = [];
        $no = 0;
        for ($y = $startY; $y <= $endY; $y++) {
            for ($m = 1; $m <= 12; $m++) {
                if ($y == $startY && $m < $startM) {
                    continue;
                }
                if ($y == $endY && $m > $endM) {
                    continue;
                }
                array_push(
                    $dataKey2,
                    "('" . $m . ";" . $y . "') AS 'KEY" . $no . "'"
                );
                $no++;
            }
        }

        $rOs = [];

        foreach ($areas as $area) {
            if (empty($rOs[$area->NAME_REGIONAL])) $rOs[$area->NAME_REGIONAL] = [];
            $rOs[$area->NAME_REGIONAL][$area->NAME_AREA] = DB::select("
                SELECT 
                    s.NAME_SHOP,
                    s.OWNER_SHOP,
                    s.DETLOC_SHOP,
                    md.NAME_DISTRICT,
                    s.TYPE_SHOP" . (!empty($dataKey2) ? ',' : '') . "
                    " . implode(',',  $dataKey2) . "" . (!empty($dataValue2) ? ',' : '') . "
                    " . implode(',',  $dataValue2) . ",
                    s.TELP_SHOP" . (!empty($dataKey) ? ',' : '') . "
                    " . implode(',',  $dataKey) . "" . (!empty($dataValue) ? ',' : '') . "
                    " . implode(',',  $dataValue) . "
                FROM md_shop s
                JOIN md_district md ON md.ID_DISTRICT = s.ID_DISTRICT
                JOIN md_area ma ON ma.ID_AREA = md.ID_AREA
                JOIN md_regional mr ON mr.ID_REGIONAL = ma.ID_REGIONAL
                JOIN report_shop_detail rd ON s.ID_SHOP = rd.ID_SHOP
                JOIN report_shop_head rh ON rd.ID_HEAD = rh.ID_HEAD
                WHERE mr.NAME_REGIONAL = '" . $area->NAME_REGIONAL . "' AND ma.NAME_AREA = '" . $area->NAME_AREA . "'
                GROUP BY s.NAME_SHOP
            ");
        }
        return $rOs;
    }

    public static function getallcat()
    {
        $results = json_decode(json_encode(DB::select('SELECT rsd.* FROM report_shop_head rsh JOIN report_shop_detail rsd ON rsd.ID_HEAD = rsh.ID_HEAD')), true);

        // Initialize an empty array to store the sorted data
        $sortedData = [];

        // Loop through the query results and group them by NAME_AREA
        foreach ($results as $row) {
            $nameArea = $row['NAME_AREA'];

            // If this is the first row for this NAME_AREA, create an empty array for it
            if (!isset($sortedData[$nameArea])) {
                $sortedData[$nameArea] = [
                    "2-3" => [],
                    "4-5" => [],
                    "6-10" => [],
                    ">11" => []
                ];
            }

            // Add the row to the array for this NAME_AREA and CATEGORY_RO combination
            if ((int)$row['CATEGORY_RO'] == 1) {
                array_push($sortedData[$nameArea]["2-3"], $row);
            } else if ((int)$row['CATEGORY_RO'] == 2) {
                array_push($sortedData[$nameArea]["4-5"], $row);
            } else if ((int)$row['CATEGORY_RO'] == 3) {
                array_push($sortedData[$nameArea]["6-10"], $row);
            } else {
                array_push($sortedData[$nameArea][">11"], $row);
            }
        }

        return $sortedData;
    }

    public static function getallcatRange($yearS, $monthS, $yearE, $monthE)
    {
        $resultsq = DB::table('report_shop_head as rsh')
            ->join('report_shop_detail as rsd', 'rsd.ID_HEAD', '=', 'rsh.ID_HEAD')
            ->select(DB::raw('SUM(rsd.TOTAL_RO) as TOTAL_RO, 
             rsd.ID_SHOP,
             rsd.NAME_SHOP,
             rsd.NAME_DISTRICT,
             rsd.OWNER_SHOP,
             rsd.DETLOC_SHOP,
             rsd.TYPE_SHOP,
             rsd.TELP_SHOP,
             rsd.NAME_AREA,
             rsd.NAME_REGIONAL,
             rsd.CATEGORY_RO'))
            ->whereBetween('rsh.TAHUN', [$yearS, $yearE])
            ->whereBetween('rsh.BULAN', [$monthS, $monthE])
            ->groupBy('rsd.ID_SHOP')
            ->get();

        $results = json_decode(json_encode($resultsq), true);

        // Initialize an empty array to store the sorted data
        $sortedData = [];

        // Loop through the query results and group them by NAME_AREA
        foreach ($results as $row) {
            $nameArea = $row['NAME_AREA'];

            // If this is the first row for this NAME_AREA, create an empty array for it
            if (!isset($sortedData[$nameArea])) {
                $sortedData[$nameArea] = [
                    "2-3" => [],
                    "4-5" => [],
                    "6-10" => [],
                    ">11" => []
                ];
            }

            // Add the row to the array for this NAME_AREA and CATEGORY_RO combination
            if ((int)$row['CATEGORY_RO'] == 1) {
                array_push($sortedData[$nameArea]["2-3"], $row);
            } else if ((int)$row['CATEGORY_RO'] == 2) {
                array_push($sortedData[$nameArea]["4-5"], $row);
            } else if ((int)$row['CATEGORY_RO'] == 3) {
                array_push($sortedData[$nameArea]["6-10"], $row);
            } else {
                array_push($sortedData[$nameArea][">11"], $row);
            }
        }

        return $sortedData;
    }
}
