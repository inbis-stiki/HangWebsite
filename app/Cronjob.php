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
                WHERE mr.deleted_at IS NULL
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
                WHERE ml.deleted_at IS NULL
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
                ROUND(((smy.VSUST * " . $wUST . ") / 100) + ((smy.VSNONUST * " . $wNONUST . ") / 100) + ((smy.VSSELERAKU * " . $wSELERAKU . ") / 100) + ((smy.VSRENDANG * " . $wRENDANG . ") / 100) + ((smy.VSGEPREK * " . $wGEPREK . ") / 100), 2) as AVG_VS
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
                        ROUND((SUM(stl.REALUST_STL) / (" . $tgtUST . ")) * 100, 2)
                    ) as VSUST,
                    (
                        ROUND((SUM(stl.REALNONUST_STL) / (" . $tgtNONUST . ")) * 100, 2)
                    ) as VSNONUST,
                    (
                        ROUND((SUM(stl.REALSELERAKU_STL) / (" . $tgtSeleraku . ")) * 100, 2)
                    ) as VSSELERAKU,
                    (
                        ROUND((SUM(stl.REALRENDANG_STL) / (" . $tgtRendang . ")) * 100, 2)
                    ) as VSRENDANG,
                    (
                        ROUND((SUM(stl.REALGEPREK_STL) / (" . $tgtGeprek . ")) * 100, 2)
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
                ROUND(((smy.VSUB * " . $wUB . ") / 100) + ((smy.VSPS * " . $wPS . ") / 100) + ((smy.VSRETAIL * " . $wRETAIL . ") / 100), 2) as AVG_VS
            FROM (
                SELECT
                    stl.LOCATION_STL,
                    stl.REGIONAL_STL,
                    SUM(stl.REALACTUB_STL) as REALACTUB_STL,
                    SUM(stl.REALACTPS_STL) as REALACTPS_STL,
                    SUM(stl.REALACTRETAIL_STL) as REALACTRETAIL_STL,
                    (
                        ROUND((SUM(stl.REALACTUB_STL) / (" . $tgtUB . ")) * 100, 2)
                    ) as VSUB,
                    (
                        ROUND((SUM(stl.REALACTPS_STL) / (" . $tgtPS . ")) * 100, 2)
                    ) as VSPS,
                    (
                        ROUND((SUM(stl.REALACTRETAIL_STL) / (" . $tgtRetail . ")) * 100, 2)
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
                ROUND(((rp.VSUB * " . $wUB . ") / 100) + ((rp.VSPS * " . $wPS . ") / 100) + ((rp.VSRETAIL * " . $wRETAIL . ") / 100), 2) as AVG_VS
            FROM (
                SELECT 
                    ma.NAME_AREA ,
                    dm.*,
                    (
                        ROUND((dm.REALACTUB_DM/ (" . $tgtUB . ")) * 100, 2)
                    ) as VSUB,
                    (
                        ROUND((dm.REALACTPS_DM/ (" . $tgtPS . ")) * 100, 2)
                    ) as VSPS,
                    (
                        ROUND((dm.REALACTRETAIL_DM/ (" . $tgtRetail . ")) * 100, 2)
                    ) as VSRETAIL
                FROM summary_trans_user dm 
                INNER JOIN md_area ma
                    ON
                        dm.YEAR = '" . $year . "'
                        AND dm.MONTH = '" . $month . "'
                        AND dm.ID_REGIONAL = '" . $idRegional . "'
                        AND dm.ID_ROLE = '" . $idRole . "'
                        AND dm.ID_AREA = ma.ID_AREA
                        AND ma.deleted_at IS NULL
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
                ROUND(((rp.VSUST * " . $wUST . ") / 100) + ((rp.VSNONUST * " . $wNONUST . ") / 100) + ((rp.VSSELERAKU * " . $wSELERAKU . ") / 100) + ((rp.VSRENDANG * " . $wRENDANG . ") / 100) + ((rp.VSGEPREK * " . $wGEPREK . ") / 100), 2) as AVG_VS
            FROM 
            (
                SELECT 
                    ma.NAME_AREA ,
                    dm.* ,
                    (
                        ROUND((dm.REALUST_DM/ (" . $tgtUST . ")) * 100, 2)
                    ) as VSUST,
                    (
                        ROUND((dm.REALNONUST_DM/ (" . $tgtNONUST . ")) * 100, 2)
                    ) as VSNONUST,
                    (
                        ROUND((dm.REALSELERAKU_DM/ (" . $tgtSeleraku . ")) * 100, 2)
                    ) as VSSELERAKU,
                    (
                        ROUND((dm.REALRENDANG_DM/ (" . $tgtRendang . ")) * 100, 2)
                    ) as VSRENDANG,
                    (
                        ROUND((dm.REALGEPREK_DM/ (" . $tgtGeprek . ")) * 100, 2)
                    ) as VSGEPREK
                FROM summary_trans_user dm 
                INNER JOIN md_area ma
                    ON
                        dm.YEAR = '" . $year . "'
                        AND dm.MONTH = '" . $month . "'
                        AND dm.ID_REGIONAL = '" . $idRegional . "'
                        AND dm.ID_ROLE = '" . $idRole . "'
                        AND dm.ID_AREA = ma.ID_AREA
                        AND ma.deleted_at IS NULL
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

    // public static function queryGetRepeatOrder($year, $month)
    // {
    //     $areas = DB::select("
    //         SELECT t.REGIONAL_TRANS , t.AREA_TRANS 
    //         FROM `transaction` t 
    //         WHERE YEAR(t.DATE_TRANS) = " . $year . " AND MONTH(t.DATE_TRANS) = " . $month . "
    //         GROUP BY t.AREA_TRANS , t.REGIONAL_TRANS 
    //         ORDER BY t.REGIONAL_TRANS ASC , t.AREA_TRANS ASC
    //     ");

    //     $rOs = [];

    //     foreach ($areas as $area) {
    //         if (empty($rOs[$area->REGIONAL_TRANS])) $rOs[$area->REGIONAL_TRANS] = [];
    //         $rOs[$area->REGIONAL_TRANS][$area->AREA_TRANS] = DB::select("
    //             SELECT
    //                 (
    //                     SELECT
    //                         COUNT(u.ID_USER) 
    //                     FROM
    //                         user u
    //                     JOIN md_regional mr ON 
    //                         mr.ID_REGIONAL = u.ID_REGIONAL
    //                     WHERE
    //                         mr.NAME_REGIONAL = '" . $area->REGIONAL_TRANS . "'
    //                         AND
    //                         u.deleted_at IS NULL   
    //                 ) AS TOTALAPO,
    //                 (
    //                     SELECT COUNT(ms.ID_SHOP) as TOTAL
    //                     FROM md_area ma
    //                     INNER JOIN md_district md
    //                     ON 
    //                         ma.NAME_AREA = '" . $area->AREA_TRANS . "' 
    //                         AND ma.deleted_at IS NULL 
    //                         AND md.ID_AREA = ma.ID_AREA
    //                     INNER JOIN md_shop ms 
    //                     ON ms.ID_DISTRICT = md.ID_DISTRICT AND ms.TYPE_SHOP = 'Pedagang Sayur' 
    //                 ) as 'TOTALPS',
    //                 (
    //                     SELECT COUNT(ms.ID_SHOP) as TOTAL
    //                     FROM md_area ma
    //                     INNER JOIN md_district md
    //                     ON 
    //                         ma.NAME_AREA = '" . $area->AREA_TRANS . "' 
    //                         AND ma.deleted_at IS NULL 
    //                         AND md.ID_AREA = ma.ID_AREA
    //                     INNER JOIN md_shop ms 
    //                     ON ms.ID_DISTRICT = md.ID_DISTRICT AND ms.TYPE_SHOP = 'Loss' 
    //                 ) as 'TOTALLOSS',
    //                 (
    //                     SELECT COUNT(ms.ID_SHOP) as TOTAL
    //                     FROM md_area ma
    //                     INNER JOIN md_district md
    //                     ON 
    //                         ma.NAME_AREA = '" . $area->AREA_TRANS . "' 
    //                         AND ma.deleted_at IS NULL 
    //                         AND md.ID_AREA = ma.ID_AREA
    //                     INNER JOIN md_shop ms 
    //                     ON ms.ID_DISTRICT = md.ID_DISTRICT AND ms.TYPE_SHOP = 'Retail' 
    //                 ) as 'TOTALRETAIL',
    //                 (
    //                     SELECT COUNT(x.TOTAL)
    //                     FROM (
    //                         SELECT COUNT(t.ID_SHOP) as TOTAL 
    //                         FROM `transaction` t
    //                         INNER JOIN md_shop ms
    //                             ON
    //                                 YEAR(t.DATE_TRANS) = " . ($year - 1) . "
    //                                 AND t.AREA_TRANS = '" . $area->AREA_TRANS . "'
    //                                 AND t.REGIONAL_TRANS = '" . $area->REGIONAL_TRANS . "'
    //                                 AND ms.ID_SHOP = t.ID_SHOP
    //                                 AND ms.TYPE_SHOP = 'Pedagang Sayur'
    //                                 AND t.ISTRANS_TRANS = 1
    //                         GROUP BY t.ID_SHOP
    //                     ) as x	
    //                 ) as 'ROVSBPS',
    //                 (
    //                     SELECT COUNT(x.TOTAL)
    //                     FROM (
    //                         SELECT COUNT(t.ID_SHOP) as TOTAL 
    //                         FROM `transaction` t
    //                         INNER JOIN md_shop ms
    //                             ON
    //                                 YEAR(t.DATE_TRANS) = " . $year . "
    //                                 AND t.AREA_TRANS = '" . $area->AREA_TRANS . "'
    //                                 AND t.REGIONAL_TRANS = '" . $area->REGIONAL_TRANS . "'
    //                                 AND ms.ID_SHOP = t.ID_SHOP
    //                                 AND ms.TYPE_SHOP = 'Pedagang Sayur'
    //                                 AND t.ISTRANS_TRANS = 1
    //                         GROUP BY t.ID_SHOP
    //                     ) as x	
    //                 ) as 'ROVSAPS',
    //                 (
    //                     SELECT COUNT(x.TOTAL)
    //                     FROM (
    //                         SELECT COUNT(t.ID_SHOP) as TOTAL 
    //                         FROM `transaction` t
    //                         INNER JOIN md_shop ms
    //                             ON
    //                                 YEAR(t.DATE_TRANS) = " . ($year - 1) . "
    //                                 AND t.AREA_TRANS = '" . $area->AREA_TRANS . "'
    //                                 AND t.REGIONAL_TRANS = '" . $area->REGIONAL_TRANS . "'
    //                                 AND ms.ID_SHOP = t.ID_SHOP
    //                                 AND ms.TYPE_SHOP = 'Retail'
    //                                 AND t.ISTRANS_TRANS = 1
    //                         GROUP BY t.ID_SHOP
    //                     ) as x	
    //                 ) as 'ROVSBR',
    //                 (
    //                     SELECT COUNT(x.TOTAL)
    //                     FROM (
    //                         SELECT COUNT(t.ID_SHOP) as TOTAL 
    //                         FROM `transaction` t
    //                         INNER JOIN md_shop ms
    //                             ON
    //                                 YEAR(t.DATE_TRANS) = " . $year . "
    //                                 AND t.AREA_TRANS = '" . $area->AREA_TRANS . "'
    //                                 AND t.REGIONAL_TRANS = '" . $area->REGIONAL_TRANS . "'
    //                                 AND ms.ID_SHOP = t.ID_SHOP
    //                                 AND ms.TYPE_SHOP = 'Retail'
    //                                 AND t.ISTRANS_TRANS = 1
    //                         GROUP BY t.ID_SHOP
    //                     ) as x	
    //                 ) as 'ROVSAR',
    //                 (
    //                     SELECT COUNT(x.TOTAL)
    //                     FROM (
    //                         SELECT COUNT(t.ID_SHOP) as TOTAL 
    //                         FROM `transaction` t
    //                         INNER JOIN md_shop ms
    //                             ON
    //                                 YEAR(t.DATE_TRANS) = " . ($year - 1) . "
    //                                 AND t.AREA_TRANS = '" . $area->AREA_TRANS . "'
    //                                 AND t.REGIONAL_TRANS = '" . $area->REGIONAL_TRANS . "'
    //                                 AND ms.ID_SHOP = t.ID_SHOP
    //                                 AND ms.TYPE_SHOP = 'Loss'
    //                                 AND t.ISTRANS_TRANS = 1
    //                         GROUP BY t.ID_SHOP
    //                     ) as x	
    //                 ) as 'ROVSBL',
    //                 (
    //                     SELECT COUNT(x.TOTAL)
    //                     FROM (
    //                         SELECT COUNT(t.ID_SHOP) as TOTAL 
    //                         FROM `transaction` t
    //                         INNER JOIN md_shop ms
    //                             ON
    //                                 YEAR(t.DATE_TRANS) = " . $year . "
    //                                 AND t.AREA_TRANS = '" . $area->AREA_TRANS . "'
    //                                 AND t.REGIONAL_TRANS = '" . $area->REGIONAL_TRANS . "'
    //                                 AND ms.ID_SHOP = t.ID_SHOP
    //                                 AND ms.TYPE_SHOP = 'Loss'
    //                                 AND t.ISTRANS_TRANS = 1
    //                         GROUP BY t.ID_SHOP
    //                     ) as x	
    //                 ) as 'ROVSAL',
    //                 (
    //                     SELECT COUNT(x.TOTAL)
    //                     FROM (
    //                         SELECT COUNT(t.ID_SHOP) as TOTAL 
    //                         FROM `transaction` t
    //                         INNER JOIN md_shop ms
    //                             ON
    //                                 YEAR(t.DATE_TRANS) = " . $year . "
    //                                 AND MONTH(t.DATE_TRANS) = " . $month . "
    //                                 AND t.AREA_TRANS = '" . $area->AREA_TRANS . "'
    //                                 AND t.REGIONAL_TRANS = '" . $area->REGIONAL_TRANS . "'
    //                                 AND ms.ID_SHOP = t.ID_SHOP
    //                                 AND ms.TYPE_SHOP = 'Pedagang Sayur'
    //                                 AND t.ISTRANS_TRANS = 1
    //                         GROUP BY t.ID_SHOP 
    //                         HAVING TOTAL >= 2 AND TOTAL <= 3
    //                     ) as x	
    //                 ) as 'PS_2-3',
    //                 (
    //                     SELECT COUNT(x.TOTAL)
    //                     FROM (
    //                         SELECT COUNT(t.ID_SHOP) as TOTAL 
    //                         FROM `transaction` t
    //                         INNER JOIN md_shop ms
    //                             ON
    //                                 YEAR(t.DATE_TRANS) = " . $year . "
    //                                 AND MONTH(t.DATE_TRANS) = " . $month . "
    //                                 AND t.AREA_TRANS = '" . $area->AREA_TRANS . "'
    //                                 AND t.REGIONAL_TRANS = '" . $area->REGIONAL_TRANS . "'
    //                                 AND ms.ID_SHOP = t.ID_SHOP
    //                                 AND ms.TYPE_SHOP = 'Pedagang Sayur'
    //                                 AND t.ISTRANS_TRANS = 1
    //                         GROUP BY t.ID_SHOP 
    //                         HAVING TOTAL >= 4 AND TOTAL <= 5
    //                     ) as x	
    //                 ) as 'PS_4-5',
    //                 (
    //                     SELECT COUNT(x.TOTAL)
    //                     FROM (
    //                         SELECT COUNT(t.ID_SHOP) as TOTAL 
    //                         FROM `transaction` t
    //                         INNER JOIN md_shop ms
    //                             ON
    //                                 YEAR(t.DATE_TRANS) = " . $year . "
    //                                 AND MONTH(t.DATE_TRANS) = " . $month . "
    //                                 AND t.AREA_TRANS = '" . $area->AREA_TRANS . "'
    //                                 AND t.REGIONAL_TRANS = '" . $area->REGIONAL_TRANS . "'
    //                                 AND ms.ID_SHOP = t.ID_SHOP
    //                                 AND ms.TYPE_SHOP = 'Pedagang Sayur'
    //                                 AND t.ISTRANS_TRANS = 1
    //                         GROUP BY t.ID_SHOP 
    //                         HAVING TOTAL >= 6 AND TOTAL <= 10
    //                     ) as x	
    //                 ) as 'PS_6-10',
    //                 (
    //                     SELECT COUNT(x.TOTAL)
    //                     FROM (
    //                         SELECT COUNT(t.ID_SHOP) as TOTAL 
    //                         FROM `transaction` t
    //                         INNER JOIN md_shop ms
    //                             ON
    //                                 YEAR(t.DATE_TRANS) = " . $year . "
    //                                 AND MONTH(t.DATE_TRANS) = " . $month . "
    //                                 AND t.AREA_TRANS = '" . $area->AREA_TRANS . "'
    //                                 AND t.REGIONAL_TRANS = '" . $area->REGIONAL_TRANS . "'
    //                                 AND ms.ID_SHOP = t.ID_SHOP
    //                                 AND ms.TYPE_SHOP = 'Pedagang Sayur'
    //                                 AND t.ISTRANS_TRANS = 1
    //                         GROUP BY t.ID_SHOP 
    //                         HAVING TOTAL >= 11
    //                     ) as x	
    //                 ) as 'PS_>11',
    //                 (
    //                     SELECT COUNT(x.TOTAL)
    //                     FROM (
    //                         SELECT COUNT(t.ID_SHOP) as TOTAL 
    //                         FROM `transaction` t
    //                         INNER JOIN md_shop ms
    //                             ON
    //                                 YEAR(t.DATE_TRANS) = " . $year . "
    //                                 AND MONTH(t.DATE_TRANS) = " . $month . "
    //                                 AND t.AREA_TRANS = '" . $area->AREA_TRANS . "'
    //                                 AND t.REGIONAL_TRANS = '" . $area->REGIONAL_TRANS . "'
    //                                 AND ms.ID_SHOP = t.ID_SHOP
    //                                 AND ms.TYPE_SHOP = 'Retail'
    //                                 AND t.ISTRANS_TRANS = 1
    //                         GROUP BY t.ID_SHOP 
    //                         HAVING TOTAL >= 2 AND TOTAL <= 3
    //                     ) as x	
    //                 ) as 'Retail_2-3',
    //                 (
    //                     SELECT COUNT(x.TOTAL)
    //                     FROM (
    //                         SELECT COUNT(t.ID_SHOP) as TOTAL 
    //                         FROM `transaction` t
    //                         INNER JOIN md_shop ms
    //                             ON
    //                                 YEAR(t.DATE_TRANS) = " . $year . "
    //                                 AND MONTH(t.DATE_TRANS) = " . $month . "
    //                                 AND t.AREA_TRANS = '" . $area->AREA_TRANS . "'
    //                                 AND t.REGIONAL_TRANS = '" . $area->REGIONAL_TRANS . "'
    //                                 AND ms.ID_SHOP = t.ID_SHOP
    //                                 AND ms.TYPE_SHOP = 'Retail'
    //                                 AND t.ISTRANS_TRANS = 1
    //                         GROUP BY t.ID_SHOP 
    //                         HAVING TOTAL >= 4 AND TOTAL <= 5
    //                     ) as x	
    //                 ) as 'Retail_4-5',
    //                 (
    //                     SELECT COUNT(x.TOTAL)
    //                     FROM (
    //                         SELECT COUNT(t.ID_SHOP) as TOTAL 
    //                         FROM `transaction` t
    //                         INNER JOIN md_shop ms
    //                             ON
    //                                 YEAR(t.DATE_TRANS) = " . $year . "
    //                                 AND MONTH(t.DATE_TRANS) = " . $month . "
    //                                 AND t.AREA_TRANS = '" . $area->AREA_TRANS . "'
    //                                 AND t.REGIONAL_TRANS = '" . $area->REGIONAL_TRANS . "'
    //                                 AND ms.ID_SHOP = t.ID_SHOP
    //                                 AND ms.TYPE_SHOP = 'Retail'
    //                                 AND t.ISTRANS_TRANS = 1
    //                         GROUP BY t.ID_SHOP 
    //                         HAVING TOTAL >= 6 AND TOTAL <= 10
    //                     ) as x	
    //                 ) as 'Retail_6-10',
    //                 (
    //                     SELECT COUNT(x.TOTAL)
    //                     FROM (
    //                         SELECT COUNT(t.ID_SHOP) as TOTAL 
    //                         FROM `transaction` t
    //                         INNER JOIN md_shop ms
    //                             ON
    //                                 YEAR(t.DATE_TRANS) = " . $year . "
    //                                 AND MONTH(t.DATE_TRANS) = " . $month . "
    //                                 AND t.AREA_TRANS = '" . $area->AREA_TRANS . "'
    //                                 AND t.REGIONAL_TRANS = '" . $area->REGIONAL_TRANS . "'
    //                                 AND ms.ID_SHOP = t.ID_SHOP
    //                                 AND ms.TYPE_SHOP = 'Retail'
    //                                 AND t.ISTRANS_TRANS = 1
    //                         GROUP BY t.ID_SHOP 
    //                         HAVING TOTAL >= 11
    //                     ) as x	
    //                 ) as 'Retail_>11',
    //                 (
    //                     SELECT COUNT(x.TOTAL)
    //                     FROM (
    //                         SELECT COUNT(t.ID_SHOP) as TOTAL 
    //                         FROM `transaction` t
    //                         INNER JOIN md_shop ms
    //                             ON
    //                                 YEAR(t.DATE_TRANS) = " . $year . "
    //                                 AND MONTH(t.DATE_TRANS) = " . $month . "
    //                                 AND t.AREA_TRANS = '" . $area->AREA_TRANS . "'
    //                                 AND t.REGIONAL_TRANS = '" . $area->REGIONAL_TRANS . "'
    //                                 AND ms.ID_SHOP = t.ID_SHOP
    //                                 AND ms.TYPE_SHOP = 'Loss'
    //                                 AND t.ISTRANS_TRANS = 1
    //                         GROUP BY t.ID_SHOP 
    //                         HAVING TOTAL >= 2 AND TOTAL <= 3
    //                     ) as x	
    //                 ) as 'Loss_2-3',
    //                 (
    //                     SELECT COUNT(x.TOTAL)
    //                     FROM (
    //                         SELECT COUNT(t.ID_SHOP) as TOTAL 
    //                         FROM `transaction` t
    //                         INNER JOIN md_shop ms
    //                             ON
    //                                 YEAR(t.DATE_TRANS) = " . $year . "
    //                                 AND MONTH(t.DATE_TRANS) = " . $month . "
    //                                 AND t.AREA_TRANS = '" . $area->AREA_TRANS . "'
    //                                 AND t.REGIONAL_TRANS = '" . $area->REGIONAL_TRANS . "'
    //                                 AND ms.ID_SHOP = t.ID_SHOP
    //                                 AND ms.TYPE_SHOP = 'Loss'
    //                                 AND t.ISTRANS_TRANS = 1
    //                         GROUP BY t.ID_SHOP 
    //                         HAVING TOTAL >= 4 AND TOTAL <= 5
    //                     ) as x	
    //                 ) as 'Loss_4-5',
    //                 (
    //                     SELECT COUNT(x.TOTAL)
    //                     FROM (
    //                         SELECT COUNT(t.ID_SHOP) as TOTAL 
    //                         FROM `transaction` t
    //                         INNER JOIN md_shop ms
    //                             ON
    //                                 YEAR(t.DATE_TRANS) = " . $year . "
    //                                 AND MONTH(t.DATE_TRANS) = " . $month . "
    //                                 AND t.AREA_TRANS = '" . $area->AREA_TRANS . "'
    //                                 AND t.REGIONAL_TRANS = '" . $area->REGIONAL_TRANS . "'
    //                                 AND ms.ID_SHOP = t.ID_SHOP
    //                                 AND ms.TYPE_SHOP = 'Loss'
    //                                 AND t.ISTRANS_TRANS = 1
    //                         GROUP BY t.ID_SHOP 
    //                         HAVING TOTAL >= 6 AND TOTAL <= 10
    //                     ) as x	
    //                 ) as 'Loss_6-10',
    //                 (
    //                     SELECT COUNT(x.TOTAL)
    //                     FROM (
    //                         SELECT COUNT(t.ID_SHOP) as TOTAL 
    //                         FROM `transaction` t
    //                         INNER JOIN md_shop ms
    //                             ON
    //                                 YEAR(t.DATE_TRANS) = " . $year . "
    //                                 AND MONTH(t.DATE_TRANS) = " . $month . "
    //                                 AND t.AREA_TRANS = '" . $area->AREA_TRANS . "'
    //                                 AND t.REGIONAL_TRANS = '" . $area->REGIONAL_TRANS . "'
    //                                 AND ms.ID_SHOP = t.ID_SHOP
    //                                 AND ms.TYPE_SHOP = 'Loss'
    //                                 AND t.ISTRANS_TRANS = 1
    //                         GROUP BY t.ID_SHOP 
    //                         HAVING TOTAL >= 11
    //                     ) as x	
    //                 ) as 'Loss_>11'
    //         ")[0];
    //     }
    //     return $rOs;
    // }

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
                        SELECT COUNT(rsd.ID_DET) as x 
                        FROM 
                            report_shop_detail rsd
                        JOIN 
                            report_shop_head rsh ON
                                rsd.ID_HEAD = rsh.ID_HEAD
                        WHERE
                                rsh.TAHUN = " . ($year - 1) . "
                                AND rsd.NAME_AREA = '" . $area->AREA_TRANS . "'
                                AND rsh.ID_REGIONAL = '" . $area->REGIONAL_TRANS . "'
                                AND rsd.TYPE_SHOP = 'Pedagang Sayur'	
                    ) as 'ROVSBPS',
                    (
                        SELECT COUNT(rsd.ID_DET) as x 
                        FROM 
                            report_shop_detail rsd
                        JOIN 
                            report_shop_head rsh ON
                                rsd.ID_HEAD = rsh.ID_HEAD
                        WHERE
                                rsh.TAHUN = " . $year . "
                                AND rsd.NAME_AREA = '" . $area->AREA_TRANS . "'
                                AND rsh.ID_REGIONAL = '" . $area->REGIONAL_TRANS . "'
                                AND rsd.TYPE_SHOP = 'Pedagang Sayur'
                    ) as 'ROVSAPS',
                    (
                        SELECT COUNT(rsd.ID_DET) as x 
                        FROM 
                            report_shop_detail rsd
                        JOIN 
                            report_shop_head rsh ON
                                rsd.ID_HEAD = rsh.ID_HEAD
                        WHERE
                                rsh.TAHUN = " . ($year - 1) . "
                                AND rsd.NAME_AREA = '" . $area->AREA_TRANS . "'
                                AND rsh.ID_REGIONAL = '" . $area->REGIONAL_TRANS . "'
                                AND rsd.TYPE_SHOP = 'Retail'
                    ) as 'ROVSBR',
                    (
                        SELECT COUNT(rsd.ID_DET) as x 
                        FROM 
                            report_shop_detail rsd
                        JOIN 
                            report_shop_head rsh ON
                                rsd.ID_HEAD = rsh.ID_HEAD
                        WHERE
                                rsh.TAHUN = " . $year . "
                                AND rsd.NAME_AREA = '" . $area->AREA_TRANS . "'
                                AND rsh.ID_REGIONAL = '" . $area->REGIONAL_TRANS . "'
                                AND rsd.TYPE_SHOP = 'Retail'
                    ) as 'ROVSAR',
                    (
                        SELECT COUNT(rsd.ID_DET) as x 
                        FROM 
                            report_shop_detail rsd
                        JOIN 
                            report_shop_head rsh ON
                                rsd.ID_HEAD = rsh.ID_HEAD
                        WHERE
                                rsh.TAHUN = " . ($year - 1) . "
                                AND rsd.NAME_AREA = '" . $area->AREA_TRANS . "'
                                AND rsh.ID_REGIONAL = '" . $area->REGIONAL_TRANS . "'
                                AND rsd.TYPE_SHOP = 'Loss'
                    ) as 'ROVSBL',
                    (
                        SELECT COUNT(rsd.ID_DET) as x 
                        FROM 
                            report_shop_detail rsd
                        JOIN 
                            report_shop_head rsh ON
                                rsd.ID_HEAD = rsh.ID_HEAD
                        WHERE
                                rsh.TAHUN = " . $year . "
                                AND rsd.NAME_AREA = '" . $area->AREA_TRANS . "'
                                AND rsh.ID_REGIONAL = '" . $area->REGIONAL_TRANS . "'
                                AND rsd.TYPE_SHOP = 'Loss'
                    ) as 'ROVSAL',
                    (
                        SELECT COUNT(rsd.ID_DET) as x 
                        FROM 
                            report_shop_detail rsd
                        JOIN 
                            report_shop_head rsh ON
                                rsd.ID_HEAD = rsh.ID_HEAD
                        WHERE
                                rsh.TAHUN = " . $year . "
                                AND rsh.BULAN = " . $month . "
                                AND rsd.NAME_AREA = '" . $area->AREA_TRANS . "'
                                AND rsh.ID_REGIONAL = '" . $area->REGIONAL_TRANS . "'
                                AND rsd.TYPE_SHOP = 'Pedagang Sayur'
                                AND rsd.CATEGORY_RO = 1
                    ) as 'PS_2-3',
                    (
                        SELECT COUNT(rsd.ID_DET) as x 
                        FROM 
                            report_shop_detail rsd
                        JOIN 
                            report_shop_head rsh ON
                                rsd.ID_HEAD = rsh.ID_HEAD
                        WHERE
                                rsh.TAHUN = " . $year . "
                                AND rsh.BULAN = " . $month . "
                                AND rsd.NAME_AREA = '" . $area->AREA_TRANS . "'
                                AND rsh.ID_REGIONAL = '" . $area->REGIONAL_TRANS . "'
                                AND rsd.TYPE_SHOP = 'Pedagang Sayur'
                                AND rsd.CATEGORY_RO = 2
                    ) as 'PS_4-5',
                    (
                        SELECT COUNT(rsd.ID_DET) as x 
                        FROM 
                            report_shop_detail rsd
                        JOIN 
                            report_shop_head rsh ON
                                rsd.ID_HEAD = rsh.ID_HEAD
                        WHERE
                                rsh.TAHUN = " . $year . "
                                AND rsh.BULAN = " . $month . "
                                AND rsd.NAME_AREA = '" . $area->AREA_TRANS . "'
                                AND rsh.ID_REGIONAL = '" . $area->REGIONAL_TRANS . "'
                                AND rsd.TYPE_SHOP = 'Pedagang Sayur'
                                AND rsd.CATEGORY_RO = 3
                    ) as 'PS_6-10',
                    (
                        SELECT COUNT(rsd.ID_DET) as x 
                        FROM 
                            report_shop_detail rsd
                        JOIN 
                            report_shop_head rsh ON
                                rsd.ID_HEAD = rsh.ID_HEAD
                        WHERE
                                rsh.TAHUN = " . $year . "
                                AND rsh.BULAN = " . $month . "
                                AND rsd.NAME_AREA = '" . $area->AREA_TRANS . "'
                                AND rsh.ID_REGIONAL = '" . $area->REGIONAL_TRANS . "'
                                AND rsd.TYPE_SHOP = 'Pedagang Sayur'
                                AND rsd.CATEGORY_RO = 4
                    ) as 'PS_>11',
                    (
                        SELECT COUNT(rsd.ID_DET) as x 
                        FROM 
                            report_shop_detail rsd
                        JOIN 
                            report_shop_head rsh ON
                                rsd.ID_HEAD = rsh.ID_HEAD
                        WHERE
                                rsh.TAHUN = " . $year . "
                                AND rsh.BULAN = " . $month . "
                                AND rsd.NAME_AREA = '" . $area->AREA_TRANS . "'
                                AND rsh.ID_REGIONAL = '" . $area->REGIONAL_TRANS . "'
                                AND rsd.TYPE_SHOP = 'Retail'
                                AND rsd.CATEGORY_RO = 1	
                    ) as 'Retail_2-3',
                    (
                        SELECT COUNT(rsd.ID_DET) as x 
                        FROM 
                            report_shop_detail rsd
                        JOIN 
                            report_shop_head rsh ON
                                rsd.ID_HEAD = rsh.ID_HEAD
                        WHERE
                                rsh.TAHUN = " . $year . "
                                AND rsh.BULAN = " . $month . "
                                AND rsd.NAME_AREA = '" . $area->AREA_TRANS . "'
                                AND rsh.ID_REGIONAL = '" . $area->REGIONAL_TRANS . "'
                                AND rsd.TYPE_SHOP = 'Retail'
                                AND rsd.CATEGORY_RO = 2
                    ) as 'Retail_4-5',
                    (
                        SELECT COUNT(rsd.ID_DET) as x 
                        FROM 
                            report_shop_detail rsd
                        JOIN 
                            report_shop_head rsh ON
                                rsd.ID_HEAD = rsh.ID_HEAD
                        WHERE
                                rsh.TAHUN = " . $year . "
                                AND rsh.BULAN = " . $month . "
                                AND rsd.NAME_AREA = '" . $area->AREA_TRANS . "'
                                AND rsh.ID_REGIONAL = '" . $area->REGIONAL_TRANS . "'
                                AND rsd.TYPE_SHOP = 'Retail'
                                AND rsd.CATEGORY_RO = 3
                    ) as 'Retail_6-10',
                    (
                        SELECT COUNT(rsd.ID_DET) as x 
                        FROM 
                            report_shop_detail rsd
                        JOIN 
                            report_shop_head rsh ON
                                rsd.ID_HEAD = rsh.ID_HEAD
                        WHERE
                                rsh.TAHUN = " . $year . "
                                AND rsh.BULAN = " . $month . "
                                AND rsd.NAME_AREA = '" . $area->AREA_TRANS . "'
                                AND rsh.ID_REGIONAL = '" . $area->REGIONAL_TRANS . "'
                                AND rsd.TYPE_SHOP = 'Retail'
                                AND rsd.CATEGORY_RO = 4
                    ) as 'Retail_>11',
                    (
                        SELECT COUNT(rsd.ID_DET) as x 
                        FROM 
                            report_shop_detail rsd
                        JOIN 
                            report_shop_head rsh ON
                                rsd.ID_HEAD = rsh.ID_HEAD
                        WHERE
                                rsh.TAHUN = " . $year . "
                                AND rsh.BULAN = " . $month . "
                                AND rsd.NAME_AREA = '" . $area->AREA_TRANS . "'
                                AND rsh.ID_REGIONAL = '" . $area->REGIONAL_TRANS . "'
                                AND rsd.TYPE_SHOP = 'Loss'
                                AND rsd.CATEGORY_RO = 1	
                    ) as 'Loss_2-3',
                    (
                        SELECT COUNT(rsd.ID_DET) as x 
                        FROM 
                            report_shop_detail rsd
                        JOIN 
                            report_shop_head rsh ON
                                rsd.ID_HEAD = rsh.ID_HEAD
                        WHERE
                                rsh.TAHUN = " . $year . "
                                AND rsh.BULAN = " . $month . "
                                AND rsd.NAME_AREA = '" . $area->AREA_TRANS . "'
                                AND rsh.ID_REGIONAL = '" . $area->REGIONAL_TRANS . "'
                                AND rsd.TYPE_SHOP = 'Loss'
                                AND rsd.CATEGORY_RO = 2
                    ) as 'Loss_4-5',
                    (
                        SELECT COUNT(rsd.ID_DET) as x 
                        FROM 
                            report_shop_detail rsd
                        JOIN 
                            report_shop_head rsh ON
                                rsd.ID_HEAD = rsh.ID_HEAD
                        WHERE
                                rsh.TAHUN = " . $year . "
                                AND rsh.BULAN = " . $month . "
                                AND rsd.NAME_AREA = '" . $area->AREA_TRANS . "'
                                AND rsh.ID_REGIONAL = '" . $area->REGIONAL_TRANS . "'
                                AND rsd.TYPE_SHOP = 'Loss'
                                AND rsd.CATEGORY_RO = 3
                    ) as 'Loss_6-10',
                    (
                        SELECT COUNT(rsd.ID_DET) as x 
                        FROM 
                            report_shop_detail rsd
                        JOIN 
                            report_shop_head rsh ON
                                rsd.ID_HEAD = rsh.ID_HEAD
                        WHERE
                                rsh.TAHUN = " . $year . "
                                AND rsh.BULAN = " . $month . "
                                AND rsd.NAME_AREA = '" . $area->AREA_TRANS . "'
                                AND rsh.ID_REGIONAL = '" . $area->REGIONAL_TRANS . "'
                                AND rsd.TYPE_SHOP = 'Loss'
                                AND rsd.CATEGORY_RO = 4
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

    public static function getregy($year)
    {
        $areas = DB::select("
            SELECT t.REGIONAL_TRANS 
            FROM `transaction` t 
            WHERE YEAR(t.DATE_TRANS) = " . $year . "
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
            t.REGIONAL_TRANS,
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
                AND ISTRANS_TRANS = 1
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
            ms.ID_SHOP IS NOT NULL
            AND YEAR(t.DATE_TRANS) = " . $year . "
            AND MONTH(t.DATE_TRANS) = " . $month . "
            AND ISTRANS_TRANS = 1
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

    public static function queryGetAktTrxAPO($year)
    {
        $areas = DB::select("
            SELECT
                mr.NAME_REGIONAL,
                ma.NAME_AREA
            FROM
                `md_regional` mr
            JOIN
                `md_area` ma ON ma.ID_REGIONAL = mr.ID_REGIONAL
            GROUP BY
                mr.NAME_REGIONAL,
                ma.NAME_AREA
            ORDER BY
                mr.NAME_REGIONAL,
                ma.NAME_AREA ASC
        ");

        $rOs = [];
        $months = range(1, 12);
        $selectedYear = $year;

        foreach ($areas as $area) {
            if (empty($rOs[$area->NAME_REGIONAL])) {
                $rOs[$area->NAME_REGIONAL] = [];
            }

            $trxCounts = DB::select("
                SELECT
                    YEAR(t.DATE_TRANS) AS Year,
                    MONTH(t.DATE_TRANS) AS Month,
                    COUNT(t.ID_TRANS) AS TransactionCount
                FROM
                    `transaction` t
                WHERE
                    t.AREA_TRANS = :areaName
                    AND YEAR(t.DATE_TRANS) = :selectedYear
                GROUP BY
                    YEAR(t.DATE_TRANS),
                    MONTH(t.DATE_TRANS)
                ORDER BY
                    YEAR(t.DATE_TRANS),
                    MONTH(t.DATE_TRANS)
            ", ['areaName' => $area->NAME_AREA, 'selectedYear' => $selectedYear]);

            $trx = [];
            $tgtTrxBln = 1500;
            $tgtvsArr = [];

            foreach ($months as $month) {
                $found = false;

                foreach ($trxCounts as $count) {
                    if ($count->Month == $month) {
                        $trx[] = $count->TransactionCount;
                        $tgtvs = round(($count->TransactionCount / $tgtTrxBln) * 100, 2);
                        $tgtvsArr[] = $tgtvs;
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $trx[] = 0;
                    $tgtvsArr[] = 0;
                }
            }

            $rOs[$area->NAME_REGIONAL][] = [
                'AREA' => $area->NAME_AREA,
                'TGT_TRX_BLN' => '1500',
                'TRX' => $trx,
                'TGTVS' => $tgtvsArr,
            ];
        }

        return $rOs;
    }

    public static function queryPerformance($year, $id_pc)
    {
        $areas = DB::select("
            SELECT
                mr.NAME_REGIONAL,
                ma.NAME_AREA
            FROM
                `md_regional` mr
            JOIN
                `md_area` ma ON ma.ID_REGIONAL = mr.ID_REGIONAL
            GROUP BY
                mr.NAME_REGIONAL,
                ma.NAME_AREA
            ORDER BY
                mr.NAME_REGIONAL,
                ma.NAME_AREA ASC
        ");

        $rOs = [];
        $months = range(1, 12);
        $selectedYear = $year;

        foreach ($areas as $key => $area) {
            if (empty($rOs[$area->NAME_REGIONAL])) {
                $rOs[$area->NAME_REGIONAL] = [];
            }

            $trxCounts = DB::select("
                SELECT
                    YEAR(t.DATE_TRANS) AS Year,
                    MONTH(t.DATE_TRANS) AS Month,
                    SUM(td.QTY_TD) AS TransactionCount
                FROM
                    `transaction` t
                JOIN `transaction_detail` td ON td.ID_TRANS = t.ID_TRANS
                JOIN `md_product` mp ON mp.ID_PRODUCT = td.ID_PRODUCT
                JOIN `md_product_category` mpc ON mpc.ID_PC = mp.ID_PC
                WHERE
                    t.AREA_TRANS = :areaName
                    AND YEAR(t.DATE_TRANS) = :selectedYear
                    AND mpc.ID_PC = :selectedCat
                GROUP BY
                    YEAR(t.DATE_TRANS),
                    MONTH(t.DATE_TRANS)
                ORDER BY
                    YEAR(t.DATE_TRANS),
                    MONTH(t.DATE_TRANS)
            ", ['areaName' => $area->NAME_AREA, 'selectedYear' => $selectedYear, 'selectedCat' => $id_pc]);

            $newCountTrx = [];
            for ($i = 0; $i <= 2; $i++) {
                $cnvrtYear = $selectedYear - $i;
                $trxCountsTot2 = DB::selectOne("
                    SELECT
                        YEAR(t.DATE_TRANS) AS Year,
                        SUM(td.QTY_TD) AS TOTAL
                    FROM
                        transaction t
                    JOIN transaction_detail td ON
                        td.ID_TRANS = t.ID_TRANS
                    JOIN md_product mp ON
                        mp.ID_PRODUCT = td.ID_PRODUCT
                    JOIN md_product_category mpc ON
                        mpc.ID_PC = mp.ID_PC
                    WHERE
                        t.AREA_TRANS = :areaName
                        AND mpc.ID_PC = :selectedCat
                        AND YEAR(t.DATE_TRANS) = :selectedYear
                    GROUP BY
                        YEAR(t.DATE_TRANS)
                    ORDER BY
                        YEAR(t.DATE_TRANS)
                ", ['areaName' => $area->NAME_AREA, 'selectedYear' => $cnvrtYear, 'selectedCat' => $id_pc]);

                $newCountTrx[$i] = (!empty($trxCountsTot2->TOTAL) ? $trxCountsTot2->TOTAL : 0);
            }

            $trx = [];
            $tgtTrxBln = 1500;
            $tgtvsArr = [];

            foreach ($months as $month) {
                $found = false;

                foreach ($trxCounts as $count) {
                    if ($count->Month == $month) {
                        $trx[] = $count->TransactionCount;
                        $tgtvs = round(($count->TransactionCount / $tgtTrxBln) * 100, 2);
                        $tgtvsArr[] = $tgtvs;
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $trx[] = 0;
                    $tgtvsArr[] = 0;
                }
            }

            $rOs[$area->NAME_REGIONAL][] = [
                'AREA' => $area->NAME_AREA,
                'TOT_1' => $newCountTrx[2],
                'TOT_2' => $newCountTrx[1],
                'TOT_3' => $newCountTrx[0],
                'TGT_TRX_BLN' => '1500',
                'MONTH' => $trx,
                'TOTAL_RT' => array_sum($trx)
            ];
        }

        return $rOs;
    }

    public static function queryPerformanceAll($year)
    {
        $areas = DB::select("
            SELECT
                mr.NAME_REGIONAL,
                ma.NAME_AREA
            FROM
                `md_regional` mr
            JOIN
                `md_area` ma ON
                ma.ID_REGIONAL = mr.ID_REGIONAL
            WHERE 
                mr.NAME_REGIONAL IS NOT NULL 
                AND
                ma.NAME_AREA IS NOT NULL
            GROUP BY
                mr.NAME_REGIONAL,
                ma.NAME_AREA
            ORDER BY
                mr.NAME_REGIONAL,
                ma.NAME_AREA ASC
        ");

        $rOs = [];

        // Fetch the real counts for each year and area
        $realCounts = DB::select("
            SELECT
                YEAR(t.DATE_TRANS) AS YEAR,
                t.AREA_TRANS,
                SUM(td.QTY_TD) AS RealCount
            FROM
                `transaction` t
            JOIN `transaction_detail` td ON
                td.ID_TRANS = t.ID_TRANS
            JOIN `md_product` mp ON
                mp.ID_PRODUCT = td.ID_PRODUCT
            JOIN `md_product_category` mpc ON
                mpc.ID_PC = mp.ID_PC
            GROUP BY
                YEAR(t.DATE_TRANS),
                t.AREA_TRANS
        ");

        // Create an array to store real counts by year and area
        $realCountsByYearArea = [];
        foreach ($realCounts as $realCount) {
            $realCountsByYearArea[$realCount->YEAR][$realCount->AREA_TRANS] = $realCount->RealCount;
        }

        foreach ($areas as $key => $area) {
            if (empty($rOs[$area->NAME_REGIONAL])) {
                $rOs[$area->NAME_REGIONAL] = [];
            }

            $trans = DB::select("
                SELECT
                    t.AREA_TRANS,
                    mpc.ID_PC,
                    YEAR(t.DATE_TRANS) AS YEAR,
                    MONTH(t.DATE_TRANS) AS MONTH,
                    SUM(td.QTY_TD) AS TransactionCount
                FROM
                    `transaction` t
                JOIN `transaction_detail` td ON
                    td.ID_TRANS = t.ID_TRANS
                JOIN `md_product` mp ON
                    mp.ID_PRODUCT = td.ID_PRODUCT
                JOIN `md_product_category` mpc ON
                    mpc.ID_PC = mp.ID_PC
                WHERE
                    YEAR(t.DATE_TRANS) = :selectedYear
                    AND
                    t.AREA_TRANS = :areaName
                GROUP BY
                    t.AREA_TRANS,
                    mpc.ID_PC,
                    YEAR(t.DATE_TRANS),
                    MONTH(t.DATE_TRANS)
                ORDER BY
                    mpc.ID_PC ASC,
                    MONTH ASC
            ", ['selectedYear' => $year, 'areaName' => $area->NAME_AREA]);

            $trxPerMonthNonUst = array_fill(0, 12, 0);
            $trxPerMonthGeprek = array_fill(0, 12, 0);
            $trxPerMonthRendang = array_fill(0, 12, 0);
            $trxPerMonthUst = array_fill(0, 12, 0);

            foreach ($trans as $keyTrans => $itemTrans) {
                switch ($itemTrans->ID_PC) {
                    case 2:
                        $trxPerMonthNonUst[$itemTrans->MONTH - 1] = $itemTrans->TransactionCount;
                        break;
                    case 12:
                        $trxPerMonthNonUst[$itemTrans->MONTH - 1] = $itemTrans->TransactionCount;
                        break;
                    case 16:
                        $trxPerMonthRendang[$itemTrans->MONTH - 1] = $itemTrans->TransactionCount;
                        break;
                    case 17:
                        $trxPerMonthGeprek[$itemTrans->MONTH - 1] = $itemTrans->TransactionCount;
                        break;
                }
            }

            $RealProduct = [
                $realCountsByYearArea[$year - 2][$area->NAME_AREA] ?? 0,
                $realCountsByYearArea[$year - 1][$area->NAME_AREA] ?? 0,
                $realCountsByYearArea[$year][$area->NAME_AREA] ?? 0,
            ];

            $NonUstData = [
                'TOT_REAL' => $RealProduct,
                'MONTH' => $trxPerMonthNonUst
            ];
            $GeprekData = [
                'TOT_REAL' => $RealProduct,
                'MONTH' => $trxPerMonthGeprek
            ];
            $RendangData = [
                'TOT_REAL' => $RealProduct,
                'MONTH' => $trxPerMonthRendang
            ];
            $UstData = [
                'TOT_REAL' => $RealProduct,
                'MONTH' => $trxPerMonthUst
            ];

            $rOs[$area->NAME_REGIONAL][] = [
                'AREA' => $area->NAME_AREA,
                'NON_UST' => $NonUstData,
                'UGP' => $GeprekData,
                'URD' => $RendangData,
                'UST' => $UstData
            ];
        }

        return $rOs;
    }

    public static function queryROVSTEST($year)
    {
        $areas = DB::select("
            SELECT
                mr.NAME_REGIONAL,
                ma.NAME_AREA
            FROM
                `md_regional` mr
            JOIN
                `md_area` ma ON ma.ID_REGIONAL = mr.ID_REGIONAL
            WHERE
                ma.deleted_at IS NULL
                AND mr.deleted_at IS NULL
            GROUP BY
                mr.NAME_REGIONAL,
                ma.NAME_AREA
            ORDER BY
                mr.NAME_REGIONAL,
                ma.NAME_AREA ASC
        ");
        

        $rOs = [];
        $months = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        $selectedYear = $year;

        foreach ($areas as $area) {
            if (empty($rOs[$area->NAME_REGIONAL])) {
                $rOs[$area->NAME_REGIONAL] = [];
            }

            $CallCount = DB::select("
                    SELECT
                        YEAR(t.DATE_TRANS) AS Year,
                        MONTH(t.DATE_TRANS) AS Month,
                        COUNT(t.ID_TRANS) AS TransactionCount
                    FROM
                        `transaction` t
                    WHERE
                        t.AREA_TRANS = :areaName
                        AND YEAR(t.DATE_TRANS) = :selectedYear
                    GROUP BY
                        YEAR(t.DATE_TRANS),
                        MONTH(t.DATE_TRANS)
                    ORDER BY
                        YEAR(t.DATE_TRANS),
                        MONTH(t.DATE_TRANS)
                ", ['areaName' => $area->NAME_AREA, 'selectedYear' => $selectedYear]);

            $EffCount = DB::select("
                SELECT
                    YEAR(t.DATE_TRANS) AS Year,
                    MONTH(t.DATE_TRANS) AS Month,
                    COUNT(t.ID_TRANS) AS TransactionCount
                FROM
                    `transaction` t
                WHERE
                    t.AREA_TRANS = :areaName
                    AND YEAR(t.DATE_TRANS) = :selectedYear
                    AND t.ISTRANS_TRANS = 1
                GROUP BY
                    YEAR(t.DATE_TRANS),
                    MONTH(t.DATE_TRANS)
                ORDER BY
                    YEAR(t.DATE_TRANS),
                    MONTH(t.DATE_TRANS)
            ", ['areaName' => $area->NAME_AREA, 'selectedYear' => $selectedYear]);


            $rtcall = [];
            $effcall = [];
            $rtro = [];

            for ($month = 1; $month <= 12; $month++) {
                $RepeatCount = DB::select("
                SELECT
                (
                    SELECT COUNT(*) FROM(
                        SELECT
                            COUNT(t.ID_SHOP) AS TOTAL_TEST
                        FROM
                            `transaction` t
                        INNER JOIN (
                            SELECT
                                ID_SHOP
                            FROM
                                `transaction` t
                            WHERE
                                YEAR(DATE_TRANS) = " . $selectedYear . "
                                AND MONTH(DATE_TRANS) = ". $month ."
                                AND t.ISTRANS_TRANS = 1
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
                            ms.ID_SHOP IS NOT NULL
                            AND mr.deleted_at IS NULL
                            AND ma.deleted_at IS NULL
                            AND ms.deleted_at IS NULL
                            AND t.AREA_TRANS = '$area->NAME_AREA'
                            AND t.REGIONAL_TRANS = '$area->NAME_REGIONAL'
                            AND YEAR(t.DATE_TRANS) = " . $selectedYear . "
                            AND MONTH(DATE_TRANS) = ". $month ."
                        GROUP BY
                            t.ID_SHOP) as total1 
                        ) AS total
                FROM
                    `transaction` t
                GROUP BY total");

                $rtro[] = $RepeatCount[0]->total;
            }

            foreach ($months as $month) {

                $foundCallCount = false;
                $foundEffCount = false;

                foreach ($CallCount as $count) {
                    if ($count->Month == $month) { 
                        $rtcall[] = $count->TransactionCount;
                        $foundCallCount = true;
                        break;
                    }
                }

                foreach ($EffCount as $count) {
                    if ($count->Month == $month) { 
                        $effcall[] = $count->TransactionCount;
                        $foundEffCount = true;
                        break;
                    }
                }

                if (!$foundCallCount) {
                    $rtcall[] = 0;
                }

                if (!$foundEffCount) {
                    $effcall[] = 0;
                }
                
            }

            $rOs[$area->NAME_REGIONAL][] = [
                'AREA' => $area->NAME_AREA,
                'RTCALL' => $rtcall,
                'RTEFFCALL' => $effcall,
                'RTRO' => $rtro,
            ];
        }

        return $rOs;
    }

    public static function queryGetRepeatTransPerShop($year)
    {
        $query = "
            SELECT r.NAME_REGIONAL AS REGIONAL, a.NAME_AREA AS AREA, s.NAME_SHOP AS SHOP,
                EXTRACT(MONTH FROM t.DATE_TRANS) AS month, COUNT(*) AS transaction_count
            FROM transaction t
            JOIN md_shop s ON t.ID_SHOP = s.ID_SHOP
            JOIN md_district d ON s.ID_DISTRICT = d.ID_DISTRICT
            JOIN md_area a ON d.ID_AREA = a.ID_AREA
            JOIN md_regional r ON a.ID_REGIONAL = r.ID_REGIONAL
            WHERE EXTRACT(YEAR FROM t.DATE_TRANS) = ?
            AND t.istrans_trans = 1
            GROUP BY REGIONAL, AREA, SHOP, EXTRACT(MONTH FROM t.DATE_TRANS)
            ORDER BY REGIONAL, AREA, SHOP, month
        ";

        $results = DB::select(DB::raw($query), [$year]);

        $currentMonth = date('n');

        $rOs = [];

        foreach ($results as $row) {
            $regional = $row->REGIONAL;
            $area = $row->AREA;
            $shop = $row->SHOP;
            $month = $row->month;
            $count = $row->transaction_count;

            if ($count > 0 && $regional === 'JATIM 2') {
                if (!isset($rOs[$regional])) {
                    $rOs[$regional] = [];
                }
    
                if (!isset($rOs[$regional][$area])) {
                    $rOs[$regional][$area] = [];
                }
    
                if (!isset($rOs[$regional][$area][$shop])) {
                    // Create an indexed array for each area
                    $rOs[$regional][$area][] = [
                        'SHOP' => $shop,
                        'TRANS_COUNT' => array_fill(0, 12, 0),
                    ];
                }
    
                $index = count($rOs[$regional][$area]) - 1;
    
                $rOs[$regional][$area][$index]['TRANS_COUNT'][$month - 1] = $count;
            }
        }

        foreach ($rOs as &$regional) {
            foreach ($regional as &$area) {
                foreach ($area as &$shop) {
                    $countOfZeroMonths = count(
                        array_filter(
                            array_slice($shop['TRANS_COUNT'], 0, $currentMonth),
                            function ($count) {
                                return $count == 0;
                            }
                        )
                    );
        
                    $percentage = intval(round($countOfZeroMonths > 0 ?
                    (100 - ($countOfZeroMonths / $currentMonth) * 100) : 0));
        
                    $category = 1; // Default category is 1 (0%)
                    
                    if ($percentage < 50) {
                        $category = 2; // Category 2 (< 50%)
                    } elseif ($percentage >= 50 && $percentage < 70) {
                        $category = 3; // Category 3 (50%-70%)
                    } elseif ($percentage >= 70) {
                        $category = 4; // Category 4 (>= 70%)
                    }
        
                    $shop['PERCENTAGE_CURRENT_MONTH'] = $percentage;
                    $shop['CATEGORY'] = $category;
                }
            }
        }

        // Now, $regionalData contains the desired structured array
        return $rOs;
    }

    public static function queryROVSTESTq($year, $tipe_toko)
    {
        $results = DB::select("
        select 
            *,ID_REGIONAL as region_name, NAME_AREA as area_name 
        from report_rovscall_head rrh 
        join report_rovscall_detail rrd on rrh.ID_HEAD COLLATE utf8mb4_unicode_ci = rrd.ID_HEAD 
        where rrh.TAHUN = ".$year."
        ");

        $rOs = [];

        foreach ($results as $result) {
            $regionName = $result->region_name;
            $areaName = $result->area_name;
            $type = $result->TYPE;
            $value = $result->VALUE;

            if (!isset($rOs[$regionName])) {
                $rOs[$regionName] = [];
            }

            // Initialize the area array if it doesn't exist
            if (!isset($rOs[$regionName][$areaName])) {
                $rOs[$regionName][$areaName] = [
                    "AREA" => $areaName,
                    "RTCALL" => [],
                    "RTRO" => [],
                    "RTEFFCALL" => [],
                ];
            }

            // Append the value to the appropriate type array
            $rOs[$regionName][$areaName][$type][] = $value;
        }

        // Reset the area indices to numeric values
        foreach ($rOs as &$region) {
            $region = array_values($region);
        }

        return $rOs;
    }

    public static function getallcat($year, $month)
    {
        // Eloquent Query with Case statement to get category label
        $results = DB::table('report_shop_head as rsh')
            ->join('report_shop_detail as rsd', 'rsd.ID_HEAD', '=', 'rsh.ID_HEAD')
            ->select('rsd.*', DB::raw("
                CASE 
                    WHEN rsd.CATEGORY_RO = 1 THEN '2-3'
                    WHEN rsd.CATEGORY_RO = 2 THEN '4-5'
                    WHEN rsd.CATEGORY_RO = 3 THEN '6-10'
                    ELSE '>11'
                END AS category_label
            "))
            ->where('rsh.BULAN', $month)
            ->where('rsh.TAHUN', $year)
            ->get();
    
        // Flattened structure
        $tempData = [];
    
        foreach ($results as $row) {
            $key = $row->NAME_AREA . '|' . $row->category_label;
            $tempData[$key][] = $row;
        }
    
        // Convert flattened structure to final nested structure
        $sortedData = [];
        foreach ($tempData as $key => $rows) {
            list($nameArea, $category) = explode('|', $key);
            
            if (!isset($sortedData[$nameArea])) {
                $sortedData[$nameArea] = [
                    "2-3" => [],
                    "4-5" => [],
                    "6-10" => [],
                    ">11" => []
                ];
            }
            $sortedData[$nameArea][$category] = $rows;
        }
    
        return json_encode($sortedData);
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
