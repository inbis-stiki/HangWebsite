<?php

namespace App\Http\Controllers;

use App\TargetUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\Input;

class DashboardController extends Controller
{
    public function index(Request $req)
    {
        $data['title']      = "Dashboard";
        $data['sidebar']    = "dashboard";
        $data['location']   = DB::table('md_location')
            ->where('md_location.deleted_at', '=', NULL)
            ->get();
        $id_regional        = $req->session()->get('regional');
        ($req->session()->get('role') == 2) ? $data['area'] = DB::table('md_area')->get() : $data['area'] = DB::table('md_area')->where('md_area.ID_REGIONAL', '=', $id_regional)->get();
        return view('dashboard', $data);
    }

    public function total_activity(Request $req)
    {
        $id_role  = $req->session()->get('role');
        $role_act = $_POST['role'];
        if ($id_role == 2 || $id_role == 1) {
            if ($role_act == 'asmen') {
                $data_activity = DB::select("
                    SELECT
                        stl.LOCATION_STL AS PLACE,
                        SUM(stl.REALACTUB_STL) + IF(
                            (
                            SELECT
                                COUNT(tdt.ID_TRANS)
                            FROM
                                transaction_detail_today tdt
                            WHERE 
                                tdt.LOCATION_TRANS = stl.LOCATION_STL
                            GROUP BY 
                                tdt.LOCATION_TRANS
                        ) IS NOT NULL, (
                            SELECT
                                COUNT(tdt.ID_TRANS)
                            FROM
                                transaction_detail_today tdt
                            WHERE 
                                tdt.LOCATION_TRANS = stl.LOCATION_STL
                            GROUP BY 
                                tdt.LOCATION_TRANS
                        ), 0
                        ) AS total_activity_ub,
                        SUM(stl.REALACTPS_STL) + IF(
                            (
                            SELECT
                                COUNT(tdt.ID_TRANS)
                            FROM
                                transaction_detail_today tdt
                            WHERE 
                                tdt.LOCATION_TRANS = stl.LOCATION_STL
                            GROUP BY 
                                tdt.LOCATION_TRANS
                        ) IS NOT NULL, (
                            SELECT
                                COUNT(tdt.ID_TRANS)
                            FROM
                                transaction_detail_today tdt
                            WHERE 
                                tdt.LOCATION_TRANS = stl.LOCATION_STL
                            GROUP BY 
                                tdt.LOCATION_TRANS
                        ), 0
                        ) AS total_activity_ps,
                        SUM(stl.REALACTRETAIL_STL) + IF(
                            (
                            SELECT
                                COUNT(tdt.ID_TRANS)
                            FROM
                                transaction_detail_today tdt
                            WHERE 
                                tdt.LOCATION_TRANS = stl.LOCATION_STL
                            GROUP BY 
                                tdt.LOCATION_TRANS
                        ) IS NOT NULL, (
                            SELECT
                                COUNT(tdt.ID_TRANS)
                            FROM
                                transaction_detail_today tdt
                            WHERE 
                                tdt.LOCATION_TRANS = stl.LOCATION_STL
                            GROUP BY 
                                tdt.LOCATION_TRANS
                        ), 0
                        ) AS total_activity_retail,
                        ( " . (string)app(TargetUser::class)->getAsmen()['acts']['UB'] . " ) AS TGT_UB,
                        ( " . (string)app(TargetUser::class)->getAsmen()['acts']['PS'] . " ) AS TGT_PS,
                        ( " . (string)app(TargetUser::class)->getAsmen()['acts']['Retail'] . " ) AS TGT_RETAIL
                    FROM
                        summary_trans_location stl
                    RIGHT JOIN md_location ml ON
                        ml.NAME_LOCATION COLLATE utf8mb4_unicode_ci = stl.LOCATION_STL
                    WHERE
                        stl.updated_at LIKE '" . date('Y-m') . "%'
                    GROUP BY
                        stl.LOCATION_STL
                ");
            } else if ($role_act == 'rpo') {
                $data_activity = DB::select("
                    SELECT
                        stl.REGIONAL_STL AS PLACE,
                        SUM(stl.REALACTUB_STL) + IF(
                            (
                            SELECT
                                COUNT(tdt.ID_TRANS)
                            FROM
                                transaction_detail_today tdt
                            WHERE 
                                tdt.REGIONAL_TRANS = stl.REGIONAL_STL
                            GROUP BY 
                                tdt.REGIONAL_TRANS
                        ) IS NOT NULL, (
                            SELECT
                                COUNT(tdt.ID_TRANS)
                            FROM
                                transaction_detail_today tdt
                            WHERE 
                                tdt.REGIONAL_TRANS = stl.REGIONAL_STL
                            GROUP BY 
                                tdt.REGIONAL_TRANS
                        ), 0
                        ) AS total_activity_ub,
                        SUM(stl.REALACTPS_STL) + IF(
                            (
                            SELECT
                                COUNT(tdt.ID_TRANS)
                            FROM
                                transaction_detail_today tdt
                            WHERE 
                                tdt.REGIONAL_TRANS = stl.REGIONAL_STL
                            GROUP BY 
                                tdt.REGIONAL_TRANS
                        ) IS NOT NULL, (
                            SELECT
                                COUNT(tdt.ID_TRANS)
                            FROM
                                transaction_detail_today tdt
                            WHERE 
                                tdt.REGIONAL_TRANS = stl.REGIONAL_STL
                            GROUP BY 
                                tdt.REGIONAL_TRANS
                        ), 0
                        ) AS total_activity_ps,
                        SUM(stl.REALACTRETAIL_STL) + IF(
                            (
                            SELECT
                                COUNT(tdt.ID_TRANS)
                            FROM
                                transaction_detail_today tdt
                            WHERE 
                                tdt.REGIONAL_TRANS = stl.REGIONAL_STL
                            GROUP BY 
                                tdt.REGIONAL_TRANS
                        ) IS NOT NULL, (
                            SELECT
                                COUNT(tdt.ID_TRANS)
                            FROM
                                transaction_detail_today tdt
                            WHERE 
                                tdt.REGIONAL_TRANS = stl.REGIONAL_STL
                            GROUP BY 
                                tdt.REGIONAL_TRANS
                        ), 0
                        ) AS total_activity_retail,
                        ( " . (string)app(TargetUser::class)->getRegional()['acts']['UB'] . " ) AS TGT_UB,
                        ( " . (string)app(TargetUser::class)->getRegional()['acts']['PS'] . " ) AS TGT_PS,
                        ( " . (string)app(TargetUser::class)->getRegional()['acts']['Retail'] . " ) AS TGT_RETAIL
                    FROM
                        summary_trans_location stl
                    RIGHT JOIN md_regional mr ON
                        mr.NAME_REGIONAL COLLATE utf8mb4_unicode_ci = stl.REGIONAL_STL
                    WHERE
                        stl.updated_at LIKE '" . date('Y-m') . "%'
                    GROUP BY
                        stl.REGIONAL_STL
                ");
            }
        } else if ($id_role == 3) {
            $id_location  = $req->session()->get('location');
            if ($role_act == 'asmen') {
                $data_activity = DB::select("
                    SELECT
                        stl.LOCATION_STL AS PLACE,
                        SUM(stl.REALACTUB_STL) + IF(
                            (
                            SELECT
                                COUNT(tdt.ID_TRANS)
                            FROM
                                transaction_detail_today tdt
                            WHERE 
                                tdt.LOCATION_TRANS = stl.LOCATION_STL
                            GROUP BY 
                                tdt.LOCATION_TRANS
                        ) IS NOT NULL, (
                            SELECT
                                COUNT(tdt.ID_TRANS)
                            FROM
                                transaction_detail_today tdt
                            WHERE 
                                tdt.LOCATION_TRANS = stl.LOCATION_STL
                            GROUP BY 
                                tdt.LOCATION_TRANS
                        ), 0
                        ) AS total_activity_ub,
                        SUM(stl.REALACTPS_STL) + IF(
                            (
                            SELECT
                                COUNT(tdt.ID_TRANS)
                            FROM
                                transaction_detail_today tdt
                            WHERE 
                                tdt.LOCATION_TRANS = stl.LOCATION_STL
                            GROUP BY 
                                tdt.LOCATION_TRANS
                        ) IS NOT NULL, (
                            SELECT
                                COUNT(tdt.ID_TRANS)
                            FROM
                                transaction_detail_today tdt
                            WHERE 
                                tdt.LOCATION_TRANS = stl.LOCATION_STL
                            GROUP BY 
                                tdt.LOCATION_TRANS
                        ), 0
                        ) AS total_activity_ps,
                        SUM(stl.REALACTRETAIL_STL) + IF(
                            (
                            SELECT
                                COUNT(tdt.ID_TRANS)
                            FROM
                                transaction_detail_today tdt
                            WHERE 
                                tdt.LOCATION_TRANS = stl.LOCATION_STL
                            GROUP BY 
                                tdt.LOCATION_TRANS
                        ) IS NOT NULL, (
                            SELECT
                                COUNT(tdt.ID_TRANS)
                            FROM
                                transaction_detail_today tdt
                            WHERE 
                                tdt.LOCATION_TRANS = stl.LOCATION_STL
                            GROUP BY 
                                tdt.LOCATION_TRANS
                        ), 0
                        ) AS total_activity_retail,
                        ( " . (string)app(TargetUser::class)->getAsmen()['acts']['UB'] . " ) AS TGT_UB,
                        ( " . (string)app(TargetUser::class)->getAsmen()['acts']['PS'] . " ) AS TGT_PS,
                        ( " . (string)app(TargetUser::class)->getAsmen()['acts']['Retail'] . " ) AS TGT_RETAIL                        
                    FROM
                        summary_trans_location stl
                    RIGHT JOIN md_location ml ON
                        ml.NAME_LOCATION COLLATE utf8mb4_unicode_ci = stl.LOCATION_STL
                    WHERE
                        stl.updated_at LIKE '" . date('Y-m') . "%'
                        AND ml.ID_LOCATION = " . $id_location . "
                    GROUP BY
                        stl.LOCATION_STL
                ");
            } else if ($role_act == 'rpo') {
                $data_activity = DB::select("
                    SELECT
                        stl.REGIONAL_STL AS PLACE,
                        SUM(stl.REALACTUB_STL) + IF(
                            (
                            SELECT
                                COUNT(tdt.ID_TRANS)
                            FROM
                                transaction_detail_today tdt
                            WHERE 
                                tdt.REGIONAL_TRANS = stl.REGIONAL_STL
                            GROUP BY 
                                tdt.REGIONAL_TRANS
                        ) IS NOT NULL, (
                            SELECT
                                COUNT(tdt.ID_TRANS)
                            FROM
                                transaction_detail_today tdt
                            WHERE 
                                tdt.REGIONAL_TRANS = stl.REGIONAL_STL
                            GROUP BY 
                                tdt.REGIONAL_TRANS
                        ), 0
                        ) AS total_activity_ub,
                        SUM(stl.REALACTPS_STL) + IF(
                            (
                            SELECT
                                COUNT(tdt.ID_TRANS)
                            FROM
                                transaction_detail_today tdt
                            WHERE 
                                tdt.REGIONAL_TRANS = stl.REGIONAL_STL
                            GROUP BY 
                                tdt.REGIONAL_TRANS
                        ) IS NOT NULL, (
                            SELECT
                                COUNT(tdt.ID_TRANS)
                            FROM
                                transaction_detail_today tdt
                            WHERE 
                                tdt.REGIONAL_TRANS = stl.REGIONAL_STL
                            GROUP BY 
                                tdt.REGIONAL_TRANS
                        ), 0
                        ) AS total_activity_ps,
                        SUM(stl.REALACTRETAIL_STL) + IF(
                            (
                            SELECT
                                COUNT(tdt.ID_TRANS)
                            FROM
                                transaction_detail_today tdt
                            WHERE 
                                tdt.REGIONAL_TRANS = stl.REGIONAL_STL
                            GROUP BY 
                                tdt.REGIONAL_TRANS
                        ) IS NOT NULL, (
                            SELECT
                                COUNT(tdt.ID_TRANS)
                            FROM
                                transaction_detail_today tdt
                            WHERE 
                                tdt.REGIONAL_TRANS = stl.REGIONAL_STL
                            GROUP BY 
                                tdt.REGIONAL_TRANS
                        ), 0
                        ) AS total_activity_retail,
                        ( " . (string)app(TargetUser::class)->getRegional()['acts']['UB'] . " ) AS TGT_UB,
                        ( " . (string)app(TargetUser::class)->getRegional()['acts']['PS'] . " ) AS TGT_PS,
                        ( " . (string)app(TargetUser::class)->getRegional()['acts']['Retail'] . " ) AS TGT_RETAIL
                    FROM
                        summary_trans_location stl
                    RIGHT JOIN md_regional mr ON
                        mr.NAME_REGIONAL COLLATE utf8mb4_unicode_ci = stl.REGIONAL_STL
                    WHERE
                        stl.updated_at LIKE '" . date('Y-m') . "%'
                        AND mr.ID_LOCATION = " . $id_location . "
                    GROUP BY
                        stl.REGIONAL_STL
                ");
            }
        } else if ($id_role == 4) {
            $id_regional  = $req->session()->get('regional');
            $data_activity = DB::select("
                SELECT
                    stl.AREA_STL AS PLACE,
                    SUM(stl.REALACTUB_STL) + IF(
                        (
                        SELECT
                            COUNT(tdt.ID_TRANS)
                        FROM
                            transaction_detail_today tdt
                        WHERE 
                            tdt.REGIONAL_TRANS = stl.REGIONAL_STL
                        GROUP BY 
                            tdt.REGIONAL_TRANS
                    ) IS NOT NULL, (
                        SELECT
                            COUNT(tdt.ID_TRANS)
                        FROM
                            transaction_detail_today tdt
                        WHERE 
                            tdt.REGIONAL_TRANS = stl.REGIONAL_STL
                        GROUP BY 
                            tdt.REGIONAL_TRANS
                    ), 0
                    ) AS total_activity_ub,
                    SUM(stl.REALACTPS_STL) + IF(
                        (
                        SELECT
                            COUNT(tdt.ID_TRANS)
                        FROM
                            transaction_detail_today tdt
                        WHERE 
                            tdt.REGIONAL_TRANS = stl.REGIONAL_STL
                        GROUP BY 
                            tdt.REGIONAL_TRANS
                    ) IS NOT NULL, (
                        SELECT
                            COUNT(tdt.ID_TRANS)
                        FROM
                            transaction_detail_today tdt
                        WHERE 
                            tdt.REGIONAL_TRANS = stl.REGIONAL_STL
                        GROUP BY 
                            tdt.REGIONAL_TRANS
                    ), 0
                    ) AS total_activity_ps,
                    SUM(stl.REALACTRETAIL_STL) + IF(
                        (
                        SELECT
                            COUNT(tdt.ID_TRANS)
                        FROM
                            transaction_detail_today tdt
                        WHERE 
                            tdt.REGIONAL_TRANS = stl.REGIONAL_STL
                        GROUP BY 
                            tdt.REGIONAL_TRANS
                    ) IS NOT NULL, (
                        SELECT
                            COUNT(tdt.ID_TRANS)
                        FROM
                            transaction_detail_today tdt
                        WHERE 
                            tdt.REGIONAL_TRANS = stl.REGIONAL_STL
                        GROUP BY 
                            tdt.REGIONAL_TRANS
                    ), 0
                    ) AS total_activity_retail,
                    ( " . (string)app(TargetUser::class)->getRegional()['acts']['UB'] . " ) AS TGT_UB,
                    ( " . (string)app(TargetUser::class)->getRegional()['acts']['PS'] . " ) AS TGT_PS,
                    ( " . (string)app(TargetUser::class)->getRegional()['acts']['Retail'] . " ) AS TGT_RETAIL
                FROM
                    summary_trans_location stl
                RIGHT JOIN md_regional mr ON
                    mr.NAME_REGIONAL COLLATE utf8mb4_unicode_ci = stl.REGIONAL_STL
                WHERE
                    stl.updated_at LIKE '" . date('Y-m') . "%'
                    AND mr.ID_REGIONAL = " . $id_regional . "
                GROUP BY
                    stl.AREA_STL
            ");
        }

        $activity = [
            'data' => $data_activity
        ];
        echo json_encode($activity);
    }

    public function ranking_activity()
    {
        $ranking_activity_asmen = DB::select("
            SELECT
                (ROW_NUMBER() OVER(ORDER BY (ROUND((
                    (
                        ((((SUM(stl.REALACTUB_STL) + IF(TB_UB.TOT_QTYTD IS NOT NULL, TB_UB.TOT_QTYTD, 0)) / " . (int)app(TargetUser::class)->getAsmen()['acts']['UB'] . ") * 100) * (TB_UB.PERCENTAGE_AC / 100)) + 
                        ((((SUM(stl.REALACTPS_STL) + IF(TB_PS.TOT_QTYTD IS NOT NULL, TB_PS.TOT_QTYTD, 0)) / " . (int)app(TargetUser::class)->getAsmen()['acts']['PS'] . ") * 100) * (TB_PS.PERCENTAGE_AC / 100)) + 
                        ((((SUM(stl.REALACTRETAIL_STL) + IF(TB_RETAIL.TOT_QTYTD IS NOT NULL, TB_RETAIL.TOT_QTYTD, 0)) / " . (int)app(TargetUser::class)->getAsmen()['acts']['Retail'] . ") * 100) * (TB_RETAIL.PERCENTAGE_AC / 100))
                    ) / 3
                    ), 2)) DESC)) AS NUM_ROW,
                stl.LOCATION_STL AS NAME_LOCATION,
                ROUND((
                    (
                        ((((SUM(stl.REALACTUB_STL) + IF(TB_UB.TOT_QTYTD IS NOT NULL, TB_UB.TOT_QTYTD, 0)) / " . (int)app(TargetUser::class)->getAsmen()['acts']['UB'] . ") * 100) * (TB_UB.PERCENTAGE_AC / 100)) + 
                        ((((SUM(stl.REALACTPS_STL) + IF(TB_PS.TOT_QTYTD IS NOT NULL, TB_PS.TOT_QTYTD, 0)) / " . (int)app(TargetUser::class)->getAsmen()['acts']['PS'] . ") * 100) * (TB_PS.PERCENTAGE_AC / 100)) + 
                        ((((SUM(stl.REALACTRETAIL_STL) + IF(TB_RETAIL.TOT_QTYTD IS NOT NULL, TB_RETAIL.TOT_QTYTD, 0)) / " . (int)app(TargetUser::class)->getAsmen()['acts']['Retail'] . ") * 100) * (TB_RETAIL.PERCENTAGE_AC / 100))
                    ) / 3
                    ), 2) AS NEW_AVERAGE
            FROM
                (
                    SELECT
                        *,
                        (
                        SELECT
                            SUM(QTY_TD)
                        FROM
                            transaction_detail_today tdt
                        WHERE
                            tdt.TYPE_ACTIVITY LIKE 'Aktivitas UB'
                        GROUP BY
                            tdt.TYPE_ACTIVITY
                                            ) AS TOT_QTYTD
                    FROM
                        md_activity_category mac
                    WHERE
                        mac.ID_AC = 1
                ) AS TB_UB,
                (
                    SELECT
                        *,
                        (
                        SELECT
                            SUM(QTY_TD)
                        FROM
                            transaction_detail_today tdt
                        WHERE
                            tdt.TYPE_ACTIVITY LIKE 'Pedagang Sayur'
                        GROUP BY
                            tdt.TYPE_ACTIVITY
                                            ) AS TOT_QTYTD
                    FROM
                        md_activity_category mac
                    WHERE
                        mac.ID_AC = 2
                ) AS TB_PS,
                (
                    SELECT
                        *,
                        (
                        SELECT
                            SUM(QTY_TD)
                        FROM
                            transaction_detail_today tdt
                        WHERE
                            tdt.TYPE_ACTIVITY LIKE 'Retail'
                        GROUP BY
                            tdt.TYPE_ACTIVITY
                                            ) AS TOT_QTYTD
                    FROM
                        md_activity_category mac
                    WHERE
                        mac.ID_AC = 3
                ) AS TB_RETAIL,
                summary_trans_location stl
            WHERE
                stl.updated_at LIKE '" . date('Y-m') . "%'
            GROUP BY
                stl.LOCATION_STL
            ORDER BY
                NUM_ROW ASC
        ");
        $ranking_activity_rpo = DB::select("
            SELECT
                (ROW_NUMBER() OVER(ORDER BY (ROUND((
                    (
                        ((((SUM(stl.REALACTUB_STL) + IF(TB_UB.TOT_QTYTD IS NOT NULL, TB_UB.TOT_QTYTD, 0)) / " . (int)app(TargetUser::class)->getAsmen()['acts']['UB'] . ") * 100) * (TB_UB.PERCENTAGE_AC / 100)) + 
                        ((((SUM(stl.REALACTPS_STL) + IF(TB_PS.TOT_QTYTD IS NOT NULL, TB_PS.TOT_QTYTD, 0)) / " . (int)app(TargetUser::class)->getAsmen()['acts']['PS'] . ") * 100) * (TB_PS.PERCENTAGE_AC / 100)) + 
                        ((((SUM(stl.REALACTRETAIL_STL) + IF(TB_RETAIL.TOT_QTYTD IS NOT NULL, TB_RETAIL.TOT_QTYTD, 0)) / " . (int)app(TargetUser::class)->getAsmen()['acts']['Retail'] . ") * 100) * (TB_RETAIL.PERCENTAGE_AC / 100))
                    ) / 3
                    ), 2)) DESC)) AS NUM_ROW,
                ROUND((
                    (
                        ((((SUM(stl.REALACTUB_STL) + IF(TB_UB.TOT_QTYTD IS NOT NULL, TB_UB.TOT_QTYTD, 0)) / " . (int)app(TargetUser::class)->getAsmen()['acts']['UB'] . ") * 100) * (TB_UB.PERCENTAGE_AC / 100)) + 
                        ((((SUM(stl.REALACTPS_STL) + IF(TB_PS.TOT_QTYTD IS NOT NULL, TB_PS.TOT_QTYTD, 0)) / " . (int)app(TargetUser::class)->getAsmen()['acts']['PS'] . ") * 100) * (TB_PS.PERCENTAGE_AC / 100)) + 
                        ((((SUM(stl.REALACTRETAIL_STL) + IF(TB_RETAIL.TOT_QTYTD IS NOT NULL, TB_RETAIL.TOT_QTYTD, 0)) / " . (int)app(TargetUser::class)->getAsmen()['acts']['Retail'] . ") * 100) * (TB_RETAIL.PERCENTAGE_AC / 100))
                    ) / 3
                    ), 2) AS NEW_AVERAGE,
                stl.REGIONAL_STL AS NAME_REGIONAL
            FROM
                (
                    SELECT
                    *,
                    (
                        SELECT
                            SUM(QTY_TD)
                        FROM
                            transaction_detail_today tdt
                        WHERE
                            tdt.TYPE_ACTIVITY LIKE 'Aktivitas UB'
                        GROUP BY
                            tdt.TYPE_ACTIVITY
                    ) AS TOT_QTYTD
                    FROM
                        md_activity_category mac
                    WHERE
                        mac.ID_AC = 1
                ) AS TB_UB,
                (
                    SELECT
                    *,
                    (
                        SELECT
                            SUM(QTY_TD)
                        FROM
                            transaction_detail_today tdt
                        WHERE
                            tdt.TYPE_ACTIVITY LIKE 'Pedagang Sayur'
                        GROUP BY
                            tdt.TYPE_ACTIVITY
                    ) AS TOT_QTYTD
                    FROM
                        md_activity_category mac
                    WHERE
                        mac.ID_AC = 2
                ) AS TB_PS,
                (
                    SELECT
                    *,
                    (
                        SELECT
                            SUM(QTY_TD)
                        FROM
                            transaction_detail_today tdt
                        WHERE
                            tdt.TYPE_ACTIVITY LIKE 'Retail'
                        GROUP BY
                            tdt.TYPE_ACTIVITY
                    ) AS TOT_QTYTD
                    FROM
                        md_activity_category mac
                    WHERE
                        mac.ID_AC = 3
                ) AS TB_RETAIL,
                summary_trans_location stl
            WHERE
                stl.updated_at LIKE '" . date('Y-m') . "%'
            GROUP BY 
                stl.REGIONAL_STL 
            ORDER BY 
                NUM_ROW ASC
        ");
        $ranking_activity_apo = DB::select("
            SELECT
                (ROW_NUMBER() OVER(ORDER BY (ROUND((
                    (
                        ((((SUM(stl.REALACTUB_STL) + IF(TB_UB.TOT_QTYTD IS NOT NULL, TB_UB.TOT_QTYTD, 0)) / " . (int)app(TargetUser::class)->getAsmen()['acts']['UB'] . ") * 100) * (TB_UB.PERCENTAGE_AC / 100)) + 
                        ((((SUM(stl.REALACTPS_STL) + IF(TB_PS.TOT_QTYTD IS NOT NULL, TB_PS.TOT_QTYTD, 0)) / " . (int)app(TargetUser::class)->getAsmen()['acts']['PS'] . ") * 100) * (TB_PS.PERCENTAGE_AC / 100)) + 
                        ((((SUM(stl.REALACTRETAIL_STL) + IF(TB_RETAIL.TOT_QTYTD IS NOT NULL, TB_RETAIL.TOT_QTYTD, 0)) / " . (int)app(TargetUser::class)->getAsmen()['acts']['Retail'] . ") * 100) * (TB_RETAIL.PERCENTAGE_AC / 100))
                    ) / 3
                    ), 2)) DESC)) AS NUM_ROW,
                ROUND((
                    (
                        ((((SUM(stl.REALACTUB_STL) + IF(TB_UB.TOT_QTYTD IS NOT NULL, TB_UB.TOT_QTYTD, 0)) / " . (int)app(TargetUser::class)->getAsmen()['acts']['UB'] . ") * 100) * (TB_UB.PERCENTAGE_AC / 100)) + 
                        ((((SUM(stl.REALACTPS_STL) + IF(TB_PS.TOT_QTYTD IS NOT NULL, TB_PS.TOT_QTYTD, 0)) / " . (int)app(TargetUser::class)->getAsmen()['acts']['PS'] . ") * 100) * (TB_PS.PERCENTAGE_AC / 100)) + 
                        ((((SUM(stl.REALACTRETAIL_STL) + IF(TB_RETAIL.TOT_QTYTD IS NOT NULL, TB_RETAIL.TOT_QTYTD, 0)) / " . (int)app(TargetUser::class)->getAsmen()['acts']['Retail'] . ") * 100) * (TB_RETAIL.PERCENTAGE_AC / 100))
                    ) / 3
                    ), 2) AS NEW_AVERAGE,
                stl.AREA_STL AS NAME_AREA
            FROM
                (
                    SELECT
                    *,
                    (
                        SELECT
                            SUM(QTY_TD)
                        FROM
                            transaction_detail_today tdt
                        WHERE
                            tdt.TYPE_ACTIVITY LIKE 'Aktivitas UB'
                        GROUP BY
                            tdt.TYPE_ACTIVITY
                    ) AS TOT_QTYTD
                    FROM
                        md_activity_category mac
                    WHERE
                        mac.ID_AC = 1
                ) AS TB_UB,
                (
                    SELECT
                    *,
                    (
                        SELECT
                            SUM(QTY_TD)
                        FROM
                            transaction_detail_today tdt
                        WHERE
                            tdt.TYPE_ACTIVITY LIKE 'Pedagang Sayur'
                        GROUP BY
                            tdt.TYPE_ACTIVITY
                    ) AS TOT_QTYTD
                    FROM
                        md_activity_category mac
                    WHERE
                        mac.ID_AC = 2
                ) AS TB_PS,
                (
                    SELECT
                    *,
                    (
                        SELECT
                            SUM(QTY_TD)
                        FROM
                            transaction_detail_today tdt
                        WHERE
                            tdt.TYPE_ACTIVITY LIKE 'Retail'
                        GROUP BY
                            tdt.TYPE_ACTIVITY
                    ) AS TOT_QTYTD
                    FROM
                        md_activity_category mac
                    WHERE
                        mac.ID_AC = 3
                ) AS TB_RETAIL,
                summary_trans_location stl
            WHERE
                stl.updated_at LIKE '" . date('Y-m') . "%'
            GROUP BY 
                stl.AREA_STL 
            ORDER BY 
                NUM_ROW ASC
        ");
        $ranking_activity = [
            'asmen' => $ranking_activity_asmen,
            'rpo' => $ranking_activity_rpo,
            'apo' => $ranking_activity_apo
        ];
        echo json_encode($ranking_activity);
    }

    public function ranking_sale()
    {
        $ranking_sale_asmen = DB::select("
            SELECT
                (ROW_NUMBER() OVER(ORDER BY (ROUND((
                    (
                        ((((SUM(stl.REALUST_STL) + IF(TB_UST.TOT_QTYTD IS NOT NULL, TB_UST.TOT_QTYTD, 0)) / " . (int)app(TargetUser::class)->getAsmen()['prods']['UST'] . ") * 100) * (TB_UST.PERCENTAGE_PC / 100)) + 
                        ((((SUM(stl.REALNONUST_STL) + IF(TB_NONUST.TOT_QTYTD IS NOT NULL, TB_NONUST.TOT_QTYTD, 0)) / " . (int)app(TargetUser::class)->getAsmen()['prods']['NONUST'] . ") * 100) * (TB_NONUST.PERCENTAGE_PC / 100)) + 
                        ((((SUM(stl.REALSELERAKU_STL) + IF(TB_SELERAKU.TOT_QTYTD IS NOT NULL, TB_SELERAKU.TOT_QTYTD, 0)) / " . (int)app(TargetUser::class)->getAsmen()['prods']['Seleraku'] . ") * 100) * (TB_SELERAKU.PERCENTAGE_PC / 100)) + 
                        ((((SUM(stl.REALGEPREK_STL) + IF(TB_RENDANG.TOT_QTYTD IS NOT NULL, TB_RENDANG.TOT_QTYTD, 0)) / " . (int)app(TargetUser::class)->getAsmen()['prods']['Rendang'] . ") * 100) * (TB_RENDANG.PERCENTAGE_PC / 100)) + 
                        ((((SUM(stl.REALRENDANG_STL) + IF(TB_GEPREK.TOT_QTYTD IS NOT NULL, TB_GEPREK.TOT_QTYTD, 0)) / " . (int)app(TargetUser::class)->getAsmen()['prods']['Geprek'] . ") * 100) * (TB_GEPREK.PERCENTAGE_PC / 100))
                    ) / 5
                    ), 2)) DESC)) AS NUM_ROW,
                stl.LOCATION_STL AS NAME_LOCATION,
                ROUND((
                    (
                        ((((SUM(stl.REALUST_STL) + IF(TB_UST.TOT_QTYTD IS NOT NULL, TB_UST.TOT_QTYTD, 0)) / " . (int)app(TargetUser::class)->getAsmen()['prods']['UST'] . ") * 100) * (TB_UST.PERCENTAGE_PC / 100)) + 
                        ((((SUM(stl.REALNONUST_STL) + IF(TB_NONUST.TOT_QTYTD IS NOT NULL, TB_NONUST.TOT_QTYTD, 0)) / " . (int)app(TargetUser::class)->getAsmen()['prods']['NONUST'] . ") * 100) * (TB_NONUST.PERCENTAGE_PC / 100)) + 
                        ((((SUM(stl.REALSELERAKU_STL) + IF(TB_SELERAKU.TOT_QTYTD IS NOT NULL, TB_SELERAKU.TOT_QTYTD, 0)) / " . (int)app(TargetUser::class)->getAsmen()['prods']['Seleraku'] . ") * 100) * (TB_SELERAKU.PERCENTAGE_PC / 100)) + 
                        ((((SUM(stl.REALGEPREK_STL) + IF(TB_RENDANG.TOT_QTYTD IS NOT NULL, TB_RENDANG.TOT_QTYTD, 0)) / " . (int)app(TargetUser::class)->getAsmen()['prods']['Rendang'] . ") * 100) * (TB_RENDANG.PERCENTAGE_PC / 100)) + 
                        ((((SUM(stl.REALRENDANG_STL) + IF(TB_GEPREK.TOT_QTYTD IS NOT NULL, TB_GEPREK.TOT_QTYTD, 0)) / " . (int)app(TargetUser::class)->getAsmen()['prods']['Geprek'] . ") * 100) * (TB_GEPREK.PERCENTAGE_PC / 100))
                    ) / 5
                    ), 2) AS NEW_AVERAGE
            FROM
                (
                    SELECT
                        *,
                        (
                            SELECT
                                SUM(QTY_TD)
                            FROM
                                transaction_detail_today tdt
                            WHERE
                                tdt.ID_PC = mpc.ID_PC
                            GROUP BY
                                tdt.ID_PC
                        ) AS TOT_QTYTD
                    FROM
                        md_product_category mpc
                    WHERE
                        mpc.ID_PC = 12
                ) AS TB_UST,
                (
                    SELECT
                        *,
                        (
                            SELECT
                                SUM(QTY_TD)
                            FROM
                                transaction_detail_today tdt
                            WHERE
                                tdt.ID_PC = mpc.ID_PC
                            GROUP BY
                                tdt.ID_PC
                        ) AS TOT_QTYTD
                    FROM
                        md_product_category mpc
                    WHERE
                        mpc.ID_PC = 2
                ) AS TB_NONUST,
                (
                    SELECT
                        *,
                        (
                            SELECT
                                SUM(QTY_TD)
                            FROM
                                transaction_detail_today tdt
                            WHERE
                                tdt.ID_PC = mpc.ID_PC
                            GROUP BY
                                tdt.ID_PC
                        ) AS TOT_QTYTD
                    FROM
                        md_product_category mpc
                    WHERE
                        mpc.ID_PC = 3
                ) AS TB_SELERAKU,
                (
                    SELECT
                        *,
                        (
                            SELECT
                                SUM(QTY_TD)
                            FROM
                                transaction_detail_today tdt
                            WHERE
                                tdt.ID_PC = mpc.ID_PC
                            GROUP BY
                                tdt.ID_PC
                        ) AS TOT_QTYTD
                    FROM
                        md_product_category mpc
                    WHERE
                        mpc.ID_PC = 16
                ) AS TB_RENDANG,
                (
                    SELECT
                        *,
                        (
                            SELECT
                                SUM(QTY_TD)
                            FROM
                                transaction_detail_today tdt
                            WHERE
                                tdt.ID_PC = mpc.ID_PC
                            GROUP BY
                                tdt.ID_PC
                        ) AS TOT_QTYTD
                    FROM
                        md_product_category mpc
                    WHERE
                        mpc.ID_PC = 17
                ) AS TB_GEPREK,
                summary_trans_location stl
            WHERE
                stl.updated_at LIKE '" . date('Y-m') . "%'
            GROUP BY
                stl.LOCATION_STL
            ORDER BY
                NUM_ROW ASC
        ");

        $ranking_sale_rpo = DB::select("
            SELECT
                (ROW_NUMBER() OVER(ORDER BY (ROUND((
                    (
                        ((((stl.REALUST_STL + TB_UST.TOT_QTYTD) / " . (int)app(TargetUser::class)->getRegional()['prods']['UST'] . ") * 100) * (TB_UST.PERCENTAGE_PC / 100)) + 
                        ((((stl.REALNONUST_STL + TB_NONUST.TOT_QTYTD) / " . (int)app(TargetUser::class)->getRegional()['prods']['NONUST'] . ") * 100) * (TB_NONUST.PERCENTAGE_PC / 100)) + 
                        ((((stl.REALSELERAKU_STL + TB_SELERAKU.TOT_QTYTD) / " . (int)app(TargetUser::class)->getRegional()['prods']['Seleraku'] . ") * 100) * (TB_SELERAKU.PERCENTAGE_PC / 100)) + 
                        ((((stl.REALGEPREK_STL + TB_RENDANG.TOT_QTYTD) / " . (int)app(TargetUser::class)->getRegional()['prods']['Rendang'] . ") * 100) * (TB_RENDANG.PERCENTAGE_PC / 100)) + 
                        ((((stl.REALRENDANG_STL + TB_GEPREK.TOT_QTYTD) / " . (int)app(TargetUser::class)->getRegional()['prods']['Geprek'] . ") * 100) * (TB_GEPREK.PERCENTAGE_PC / 100))
                    ) / 5
                    ), 2)) DESC)) AS NUM_ROW,
                stl.REGIONAL_STL AS NAME_REGIONAL,
                ROUND((
                        (
                            ((((stl.REALUST_STL + TB_UST.TOT_QTYTD) / " . (int)app(TargetUser::class)->getRegional()['prods']['UST'] . ") * 100) * (TB_UST.PERCENTAGE_PC / 100)) + 
                            ((((stl.REALNONUST_STL + TB_NONUST.TOT_QTYTD) / " . (int)app(TargetUser::class)->getRegional()['prods']['NONUST'] . ") * 100) * (TB_NONUST.PERCENTAGE_PC / 100)) + 
                            ((((stl.REALSELERAKU_STL + TB_SELERAKU.TOT_QTYTD) / " . (int)app(TargetUser::class)->getRegional()['prods']['Seleraku'] . ") * 100) * (TB_SELERAKU.PERCENTAGE_PC / 100)) + 
                            ((((stl.REALGEPREK_STL + TB_RENDANG.TOT_QTYTD) / " . (int)app(TargetUser::class)->getRegional()['prods']['Rendang'] . ") * 100) * (TB_RENDANG.PERCENTAGE_PC / 100)) + 
                            ((((stl.REALRENDANG_STL + TB_GEPREK.TOT_QTYTD) / " . (int)app(TargetUser::class)->getRegional()['prods']['Geprek'] . ") * 100) * (TB_GEPREK.PERCENTAGE_PC / 100))
                        ) / 5
                        ), 2) AS NEW_AVERAGE
            FROM
                (
                    SELECT
                        *,
                        (
                        SELECT
                            SUM(QTY_TD)
                        FROM
                            transaction_detail_today tdt
                        WHERE
                            tdt.ID_PC = mpc.ID_PC
                        GROUP BY
                            tdt.ID_PC
                        ) AS TOT_QTYTD
                    FROM
                        md_product_category mpc
                    WHERE
                        mpc.ID_PC = 12
                ) AS TB_UST,
                (
                    SELECT
                        *,
                        (
                        SELECT
                            SUM(QTY_TD)
                        FROM
                            transaction_detail_today tdt
                        WHERE
                            tdt.ID_PC = mpc.ID_PC
                        GROUP BY
                            tdt.ID_PC
                        ) AS TOT_QTYTD
                    FROM
                        md_product_category mpc
                    WHERE
                        mpc.ID_PC = 2
                ) AS TB_NONUST,
                (
                    SELECT
                        *,
                        (
                        SELECT
                            SUM(QTY_TD)
                        FROM
                            transaction_detail_today tdt
                        WHERE
                            tdt.ID_PC = mpc.ID_PC
                        GROUP BY
                            tdt.ID_PC
                        ) AS TOT_QTYTD
                    FROM
                        md_product_category mpc
                    WHERE
                        mpc.ID_PC = 3
                ) AS TB_SELERAKU,
                (
                    SELECT
                        *,
                        (
                            SELECT
                                SUM(QTY_TD)
                            FROM
                                transaction_detail_today tdt
                            WHERE
                                tdt.ID_PC = mpc.ID_PC
                            GROUP BY
                                tdt.ID_PC
                        ) AS TOT_QTYTD
                    FROM
                        md_product_category mpc
                    WHERE
                        mpc.ID_PC = 16
                ) AS TB_RENDANG,
                (
                    SELECT
                        *,
                        (
                            SELECT
                                SUM(QTY_TD)
                            FROM
                                transaction_detail_today tdt
                            WHERE
                                tdt.ID_PC = mpc.ID_PC
                            GROUP BY
                                tdt.ID_PC
                        ) AS TOT_QTYTD
                    FROM
                        md_product_category mpc
                    WHERE
                        mpc.ID_PC = 17
                ) AS TB_GEPREK,
                summary_trans_location stl
            WHERE
                stl.updated_at LIKE '" . date('Y-m') . "%'
            GROUP BY 
                stl.REGIONAL_STL 
            ORDER BY
                NUM_ROW ASC
        ");

        $ranking_sale_apo = DB::select("
            SELECT
                (ROW_NUMBER() OVER(ORDER BY (ROUND((
                    (
                        ((((dm.REALUST_DM + TB_UST.TOT_QTYTD) / " . (int)app(TargetUser::class)->getUser()['prods']['UST'] . ") * 100) * (TB_UST.PERCENTAGE_PC / 100)) + 
                        ((((dm.REALNONUST_DM + TB_NONUST.TOT_QTYTD) / " . (int)app(TargetUser::class)->getUser()['prods']['NONUST'] . ") * 100) * (TB_NONUST.PERCENTAGE_PC / 100)) + 
                        ((((dm.REALSELERAKU_DM + TB_SELERAKU.TOT_QTYTD) / " . (int)app(TargetUser::class)->getUser()['prods']['Seleraku'] . ") * 100) * (TB_SELERAKU.PERCENTAGE_PC / 100)) + 
                        ((((dm.REALGEPREK_DM + TB_RENDANG.TOT_QTYTD) / " . (int)app(TargetUser::class)->getUser()['prods']['Rendang'] . ") * 100) * (TB_RENDANG.PERCENTAGE_PC / 100)) + 
                        ((((dm.REALRENDANG_DM + TB_GEPREK.TOT_QTYTD) / " . (int)app(TargetUser::class)->getUser()['prods']['Geprek'] . ") * 100) * (TB_GEPREK.PERCENTAGE_PC / 100))
                    ) / 5
                ), 2)) DESC)) AS NUM_ROW,
                ma.NAME_AREA,
                ROUND((
                    (
                        ((((dm.REALUST_DM + TB_UST.TOT_QTYTD) / " . (int)app(TargetUser::class)->getUser()['prods']['UST'] . ") * 100) * (TB_UST.PERCENTAGE_PC / 100)) + 
                        ((((dm.REALNONUST_DM + TB_NONUST.TOT_QTYTD) / " . (int)app(TargetUser::class)->getUser()['prods']['NONUST'] . ") * 100) * (TB_NONUST.PERCENTAGE_PC / 100)) + 
                        ((((dm.REALSELERAKU_DM + TB_SELERAKU.TOT_QTYTD) / " . (int)app(TargetUser::class)->getUser()['prods']['Seleraku'] . ") * 100) * (TB_SELERAKU.PERCENTAGE_PC / 100)) + 
                        ((((dm.REALGEPREK_DM + TB_RENDANG.TOT_QTYTD) / " . (int)app(TargetUser::class)->getUser()['prods']['Rendang'] . ") * 100) * (TB_RENDANG.PERCENTAGE_PC / 100)) + 
                        ((((dm.REALRENDANG_DM + TB_GEPREK.TOT_QTYTD) / " . (int)app(TargetUser::class)->getUser()['prods']['Geprek'] . ") * 100) * (TB_GEPREK.PERCENTAGE_PC / 100))
                    ) / 5
                ), 2)  AS NEW_AVERAGE
            FROM
                (
                    SELECT
                        *,
                        (
                        SELECT
                            SUM(QTY_TD)
                        FROM
                            transaction_detail_today tdt
                        WHERE
                            tdt.ID_PC = mpc.ID_PC
                        GROUP BY
                            tdt.ID_PC
                        ) AS TOT_QTYTD
                    FROM
                        md_product_category mpc
                    WHERE
                        mpc.ID_PC = 12
                ) AS TB_UST,
                (
                    SELECT
                        *,
                        (
                        SELECT
                            SUM(QTY_TD)
                        FROM
                            transaction_detail_today tdt
                        WHERE
                            tdt.ID_PC = mpc.ID_PC
                        GROUP BY
                            tdt.ID_PC
                        ) AS TOT_QTYTD
                    FROM
                        md_product_category mpc
                    WHERE
                        mpc.ID_PC = 2
                ) AS TB_NONUST,
                (
                    SELECT
                        *,
                        (
                        SELECT
                            SUM(QTY_TD)
                        FROM
                            transaction_detail_today tdt
                        WHERE
                            tdt.ID_PC = mpc.ID_PC
                        GROUP BY
                            tdt.ID_PC
                        ) AS TOT_QTYTD
                    FROM
                        md_product_category mpc
                    WHERE
                        mpc.ID_PC = 3
                ) AS TB_SELERAKU,
                (
                    SELECT
                        *,
                        (
                            SELECT
                                SUM(QTY_TD)
                            FROM
                                transaction_detail_today tdt
                            WHERE
                                tdt.ID_PC = mpc.ID_PC
                            GROUP BY
                                tdt.ID_PC
                        ) AS TOT_QTYTD
                    FROM
                        md_product_category mpc
                    WHERE
                        mpc.ID_PC = 16
                ) AS TB_RENDANG,
                (
                    SELECT
                        *,
                        (
                            SELECT
                                SUM(QTY_TD)
                            FROM
                                transaction_detail_today tdt
                            WHERE
                                tdt.ID_PC = mpc.ID_PC
                            GROUP BY
                                tdt.ID_PC
                        ) AS TOT_QTYTD
                    FROM
                        md_product_category mpc
                    WHERE
                        mpc.ID_PC = 17
                ) AS TB_GEPREK,
                dashboard_mobile dm
            LEFT JOIN `user` u ON
                u.ID_USER = dm.ID_USER
            LEFT JOIN md_area ma ON
                ma.ID_AREA = u.ID_AREA
            WHERE 
                u.ID_ROLE = 5
            GROUP BY 
                dm.ID_USER
            ORDER BY 
                NUM_ROW ASC
        ");

        $ranking_sale = [
            'asmen' => $ranking_sale_asmen,
            'rpo' => $ranking_sale_rpo,
            'apo' => $ranking_sale_apo
        ];
        echo json_encode($ranking_sale);
    }

    public function trend_rpo(Request $req)
    {
        $year = $_POST['date'];
        $role = $_POST['role'];
        $id_role  = $req->session()->get('role');
        $id_location  = $req->session()->get('location');
        if ($id_role == 1 || $id_role == 2) {
            $regional_targets = DB::select("
                SELECT
                    mr.*
                FROM
                    md_regional mr
            ");
        } else {
            $regional_targets = DB::select("
                SELECT
                    mr.*
                FROM
                    md_regional mr
                WHERE
                    mr.ID_LOCATION = " . $id_location . "
            ");
        }

        $data_trend = array();
        foreach ($regional_targets as $regional_target) {
            $data['UST'] = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $data['NONUST'] = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $data['SELERAKU'] = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $data['RENDANG'] = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $data['GEPREK'] = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $trend_asmen = DB::select("
                SELECT
                    stl.REGIONAL_STL,
                    stl.MONTH_STL AS bulan,
                    (SUM(stl.REALUST_STL) + IF(TB_UST.TOT_QTYTD IS NOT NULL, TB_UST.TOT_QTYTD, 0)) AS total_ust,
                    (SUM(stl.REALNONUST_STL) + IF(TB_NONUST.TOT_QTYTD IS NOT NULL, TB_NONUST.TOT_QTYTD, 0)) AS total_non_ust,
                    (SUM(stl.REALSELERAKU_STL) + IF(TB_SELERAKU.TOT_QTYTD IS NOT NULL, TB_SELERAKU.TOT_QTYTD, 0)) AS total_seleraku,
                    (SUM(stl.REALRENDANG_STL) + IF(TB_RENDANG.TOT_QTYTD IS NOT NULL, TB_RENDANG.TOT_QTYTD, 0)) AS total_rendang,
                    (SUM(stl.REALGEPREK_STL) + IF(TB_GEPREK.TOT_QTYTD IS NOT NULL, TB_GEPREK.TOT_QTYTD, 0)) AS total_geprek
                FROM
                    (
                        SELECT
                            *,
                            (
                            SELECT
                                SUM(QTY_TD)
                            FROM
                                transaction_detail_today tdt
                            WHERE
                                tdt.ID_PC = mpc.ID_PC
                            GROUP BY
                                tdt.ID_PC
                            ) AS TOT_QTYTD
                        FROM
                            md_product_category mpc
                        WHERE
                            mpc.ID_PC = 12
                    ) AS TB_UST,
                    (
                        SELECT
                            *,
                            (
                            SELECT
                                SUM(QTY_TD)
                            FROM
                                transaction_detail_today tdt
                            WHERE
                                tdt.ID_PC = mpc.ID_PC
                            GROUP BY
                                tdt.ID_PC
                            ) AS TOT_QTYTD
                        FROM
                            md_product_category mpc
                        WHERE
                            mpc.ID_PC = 2
                    ) AS TB_NONUST,
                    (
                        SELECT
                            *,
                            (
                            SELECT
                                SUM(QTY_TD)
                            FROM
                                transaction_detail_today tdt
                            WHERE
                                tdt.ID_PC = mpc.ID_PC
                            GROUP BY
                                tdt.ID_PC
                            ) AS TOT_QTYTD
                        FROM
                            md_product_category mpc
                        WHERE
                            mpc.ID_PC = 3
                    ) AS TB_SELERAKU,
                    (
                        SELECT
                            *,
                            (
                                SELECT
                                    SUM(QTY_TD)
                                FROM
                                    transaction_detail_today tdt
                                WHERE
                                    tdt.ID_PC = mpc.ID_PC
                                GROUP BY
                                    tdt.ID_PC
                            ) AS TOT_QTYTD
                        FROM
                            md_product_category mpc
                        WHERE
                            mpc.ID_PC = 16
                    ) AS TB_RENDANG,
                    (
                        SELECT
                            *,
                            (
                                SELECT
                                    SUM(QTY_TD)
                                FROM
                                    transaction_detail_today tdt
                                WHERE
                                    tdt.ID_PC = mpc.ID_PC
                                GROUP BY
                                    tdt.ID_PC
                            ) AS TOT_QTYTD
                        FROM
                            md_product_category mpc
                        WHERE
                            mpc.ID_PC = 17
                    ) AS TB_GEPREK,
                    summary_trans_location stl
                WHERE
                    stl.YEAR_STL = " . $year . "
                    AND stl.REGIONAL_STL = '" . $regional_target->NAME_REGIONAL . "'
                GROUP BY
                    stl.REGIONAL_STL,
                    stl.MONTH_STL
            ");

            foreach ($trend_asmen as $item) {
                $data['UST'][($item->bulan - 1)] = ((!empty($item->total_ust)) ? $item->total_ust : 0);
                $data['NONUST'][($item->bulan - 1)] = ((!empty($item->total_non_ust)) ? $item->total_non_ust : 0);
                $data['SELERAKU'][($item->bulan - 1)] = ((!empty($item->total_seleraku)) ? $item->total_seleraku : 0);
                $data['RENDANG'][($item->bulan - 1)] = ((!empty($item->total_rendang)) ? $item->total_rendang : 0);
                $data['GEPREK'][($item->bulan - 1)] = ((!empty($item->total_geprek)) ? $item->total_geprek : 0);
            }
            array_push(
                $data_trend,
                array(
                    "PLACE" => $regional_target->NAME_REGIONAL,
                    "TARGET" => 0,
                    "UST" => $data['UST'],
                    "NONUST" => $data['NONUST'],
                    "SELERAKU" => $data['SELERAKU'],
                    "RENDANG" => $data['RENDANG'],
                    "GEPREK" => $data['GEPREK']
                )
            );
        }

        return $data_trend;
    }

    public function trend_asmen(Request $req)
    {
        $year = $_POST['date'];
        $role = $_POST['role'];
        $id_role  = $req->session()->get('role');
        $id_location  = $req->session()->get('location');
        if ($id_role == 1 || $id_role == 2) {
            $regional_targets = DB::select("
                SELECT
                    ml.*
                FROM
                    md_location ml
            ");
        } else {
            $regional_targets = DB::select("
                SELECT
                    ml.*
                FROM
                    md_location ml
                WHERE
                    ml.ID_LOCATION = " . $id_location . "
            ");
        }

        $data_trend = array();
        foreach ($regional_targets as $regional_target) {
            $data['UST'] = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $data['NONUST'] = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $data['SELERAKU'] = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $data['RENDANG'] = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $data['GEPREK'] = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $trend_asmen = DB::select("
                SELECT
                    stl.LOCATION_STL,
                    stl.MONTH_STL AS bulan,
                    (SUM(stl.REALUST_STL) + IF(TB_UST.TOT_QTYTD IS NOT NULL, TB_UST.TOT_QTYTD, 0)) AS total_ust,
                    (SUM(stl.REALNONUST_STL) + IF(TB_NONUST.TOT_QTYTD IS NOT NULL, TB_NONUST.TOT_QTYTD, 0)) AS total_non_ust,
                    (SUM(stl.REALSELERAKU_STL) + IF(TB_SELERAKU.TOT_QTYTD IS NOT NULL, TB_SELERAKU.TOT_QTYTD, 0)) AS total_seleraku,
                    (SUM(stl.REALRENDANG_STL) + IF(TB_RENDANG.TOT_QTYTD IS NOT NULL, TB_RENDANG.TOT_QTYTD, 0)) AS total_rendang,
                    (SUM(stl.REALGEPREK_STL) + IF(TB_GEPREK.TOT_QTYTD IS NOT NULL, TB_GEPREK.TOT_QTYTD, 0)) AS total_geprek
                FROM
                    (
                        SELECT
                            *,
                            (
                            SELECT
                                SUM(QTY_TD)
                            FROM
                                transaction_detail_today tdt
                            WHERE
                                tdt.ID_PC = mpc.ID_PC
                            GROUP BY
                                tdt.ID_PC
                            ) AS TOT_QTYTD
                        FROM
                            md_product_category mpc
                        WHERE
                            mpc.ID_PC = 12
                    ) AS TB_UST,
                    (
                        SELECT
                            *,
                            (
                            SELECT
                                SUM(QTY_TD)
                            FROM
                                transaction_detail_today tdt
                            WHERE
                                tdt.ID_PC = mpc.ID_PC
                            GROUP BY
                                tdt.ID_PC
                            ) AS TOT_QTYTD
                        FROM
                            md_product_category mpc
                        WHERE
                            mpc.ID_PC = 2
                    ) AS TB_NONUST,
                    (
                        SELECT
                            *,
                            (
                            SELECT
                                SUM(QTY_TD)
                            FROM
                                transaction_detail_today tdt
                            WHERE
                                tdt.ID_PC = mpc.ID_PC
                            GROUP BY
                                tdt.ID_PC
                            ) AS TOT_QTYTD
                        FROM
                            md_product_category mpc
                        WHERE
                            mpc.ID_PC = 3
                    ) AS TB_SELERAKU,
                    (
                        SELECT
                            *,
                            (
                                SELECT
                                    SUM(QTY_TD)
                                FROM
                                    transaction_detail_today tdt
                                WHERE
                                    tdt.ID_PC = mpc.ID_PC
                                GROUP BY
                                    tdt.ID_PC
                            ) AS TOT_QTYTD
                        FROM
                            md_product_category mpc
                        WHERE
                            mpc.ID_PC = 16
                    ) AS TB_RENDANG,
                    (
                        SELECT
                            *,
                            (
                                SELECT
                                    SUM(QTY_TD)
                                FROM
                                    transaction_detail_today tdt
                                WHERE
                                    tdt.ID_PC = mpc.ID_PC
                                GROUP BY
                                    tdt.ID_PC
                            ) AS TOT_QTYTD
                        FROM
                            md_product_category mpc
                        WHERE
                            mpc.ID_PC = 17
                    ) AS TB_GEPREK,
                    summary_trans_location stl
                WHERE
                    stl.YEAR_STL = '" . $year . "'
                    AND stl.LOCATION_STL = '" . $regional_target->NAME_LOCATION . "'
                GROUP BY
                    stl.LOCATION_STL,
                    stl.MONTH_STL
            ");

            foreach ($trend_asmen as $item) {
                $data['UST'][($item->bulan - 1)] = ((!empty($item->total_ust)) ? $item->total_ust : 0);
                $data['NONUST'][($item->bulan - 1)] = ((!empty($item->total_non_ust)) ? $item->total_non_ust : 0);
                $data['SELERAKU'][($item->bulan - 1)] = ((!empty($item->total_seleraku)) ? $item->total_seleraku : 0);
                $data['RENDANG'][($item->bulan - 1)] = ((!empty($item->total_rendang)) ? $item->total_rendang : 0);
                $data['GEPREK'][($item->bulan - 1)] = ((!empty($item->total_geprek)) ? $item->total_geprek : 0);
            }
            array_push(
                $data_trend,
                array(
                    "PLACE" => $regional_target->NAME_LOCATION,
                    "TARGET" => 0,
                    "UST" => $data['UST'],
                    "NONUST" => $data['NONUST'],
                    "SELERAKU" => $data['SELERAKU'],
                    "RENDANG" => $data['RENDANG'],
                    "GEPREK" => $data['GEPREK']
                )
            );
        }

        return $data_trend;
    }

    public function trend_apo(Request $req)
    {
        $id_reg  = $req->session()->get('regional');
        $year = $_POST['date'];
        $area_targets = DB::select("
            SELECT
                ma.*
            FROM
                md_area ma
            WHERE 
                ma.ID_REGIONAL = " . $id_reg . "
        ");

        $data_trend = array();
        foreach ($area_targets as $area_target) {
            $data['UST'] = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $data['NONUST'] = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $data['SELERAKU'] = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $data['RENDANG'] = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $data['GEPREK'] = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $trend_asmen = DB::select("
                SELECT
                    stl.AREA_STL,
                    stl.MONTH_STL AS bulan,
                    (SUM(stl.REALUST_STL) + IF(TB_UST.TOT_QTYTD IS NOT NULL, TB_UST.TOT_QTYTD, 0)) AS total_ust,
                    (SUM(stl.REALNONUST_STL) + IF(TB_NONUST.TOT_QTYTD IS NOT NULL, TB_NONUST.TOT_QTYTD, 0)) AS total_non_ust,
                    (SUM(stl.REALSELERAKU_STL) + IF(TB_SELERAKU.TOT_QTYTD IS NOT NULL, TB_SELERAKU.TOT_QTYTD, 0)) AS total_seleraku,
                    (SUM(stl.REALRENDANG_STL) + IF(TB_RENDANG.TOT_QTYTD IS NOT NULL, TB_RENDANG.TOT_QTYTD, 0)) AS total_rendang,
                    (SUM(stl.REALGEPREK_STL) + IF(TB_GEPREK.TOT_QTYTD IS NOT NULL, TB_GEPREK.TOT_QTYTD, 0)) AS total_geprek
                FROM
                    (
                        SELECT
                            *,
                            (
                            SELECT
                                SUM(QTY_TD)
                            FROM
                                transaction_detail_today tdt
                            WHERE
                                tdt.ID_PC = mpc.ID_PC
                            GROUP BY
                                tdt.ID_PC
                            ) AS TOT_QTYTD
                        FROM
                            md_product_category mpc
                        WHERE
                            mpc.ID_PC = 12
                    ) AS TB_UST,
                    (
                        SELECT
                            *,
                            (
                            SELECT
                                SUM(QTY_TD)
                            FROM
                                transaction_detail_today tdt
                            WHERE
                                tdt.ID_PC = mpc.ID_PC
                            GROUP BY
                                tdt.ID_PC
                            ) AS TOT_QTYTD
                        FROM
                            md_product_category mpc
                        WHERE
                            mpc.ID_PC = 2
                    ) AS TB_NONUST,
                    (
                        SELECT
                            *,
                            (
                            SELECT
                                SUM(QTY_TD)
                            FROM
                                transaction_detail_today tdt
                            WHERE
                                tdt.ID_PC = mpc.ID_PC
                            GROUP BY
                                tdt.ID_PC
                            ) AS TOT_QTYTD
                        FROM
                            md_product_category mpc
                        WHERE
                            mpc.ID_PC = 3
                    ) AS TB_SELERAKU,
                    (
                        SELECT
                            *,
                            (
                                SELECT
                                    SUM(QTY_TD)
                                FROM
                                    transaction_detail_today tdt
                                WHERE
                                    tdt.ID_PC = mpc.ID_PC
                                GROUP BY
                                    tdt.ID_PC
                            ) AS TOT_QTYTD
                        FROM
                            md_product_category mpc
                        WHERE
                            mpc.ID_PC = 16
                    ) AS TB_RENDANG,
                    (
                        SELECT
                            *,
                            (
                                SELECT
                                    SUM(QTY_TD)
                                FROM
                                    transaction_detail_today tdt
                                WHERE
                                    tdt.ID_PC = mpc.ID_PC
                                GROUP BY
                                    tdt.ID_PC
                            ) AS TOT_QTYTD
                        FROM
                            md_product_category mpc
                        WHERE
                            mpc.ID_PC = 17
                    ) AS TB_GEPREK,
                    summary_trans_location stl
                WHERE
                    stl.YEAR_STL = '" . $year . "'
                    AND stl.AREA_STL = '" . $area_target->NAME_AREA . "'
                GROUP BY
                    stl.AREA_STL,
                    stl.MONTH_STL
            ");

            foreach ($trend_asmen as $item) {
                $data['UST'][($item->bulan - 1)] = ((!empty($item->total_ust)) ? $item->total_ust : 0);
                $data['NONUST'][($item->bulan - 1)] = ((!empty($item->total_non_ust)) ? $item->total_non_ust : 0);
                $data['SELERAKU'][($item->bulan - 1)] = ((!empty($item->total_seleraku)) ? $item->total_seleraku : 0);
                $data['RENDANG'][($item->bulan - 1)] = ((!empty($item->total_rendang)) ? $item->total_rendang : 0);
                $data['GEPREK'][($item->bulan - 1)] = ((!empty($item->total_geprek)) ? $item->total_geprek : 0);
            }
            array_push(
                $data_trend,
                array(
                    "PLACE" => $area_target->NAME_AREA,
                    "TARGET" => 0,
                    "UST" => $data['UST'],
                    "NONUST" => $data['NONUST'],
                    "SELERAKU" => $data['SELERAKU'],
                    "RENDANG" => $data['RENDANG'],
                    "GEPREK" => $data['GEPREK']
                )
            );
        }

        return $data_trend;
    }
}
