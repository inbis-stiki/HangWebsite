<?php

namespace App\Http\Controllers;

use App\ActivityCategory;
use App\CategoryProduct;
use App\ReportQuery;
use App\UserRankingActivity;
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

    public function total_activity()
    {
        $data_activity = DB::select("
            SELECT
                stl.REGIONAL_STL,
                stl.MONTH_STL AS bulan,
                (stl.REALACTUB_STL + (
                    SELECT
                        COUNT(tdt.ID_USER) 
                    FROM
                        transaction_detail_today tdt
                    WHERE
                        tdt.LOCATION_TRANS = stl.LOCATION_STL
                    GROUP BY
                        tdt.LOCATION_TRANS
                )) AS total_activity_ub,
                (stl.REALACTPS_STL + (
                    SELECT
                        COUNT(tdt.ID_USER) 
                    FROM
                        transaction_detail_today tdt
                    WHERE
                        tdt.LOCATION_TRANS = stl.LOCATION_STL
                    GROUP BY
                        tdt.LOCATION_TRANS
                )) AS total_activity_ps,
                (stl.REALACTRETAIL_STL + (
                    SELECT
                        COUNT(tdt.ID_USER) 
                    FROM
                        transaction_detail_today tdt
                    WHERE
                        tdt.LOCATION_TRANS = stl.LOCATION_STL
                    GROUP BY
                        tdt.LOCATION_TRANS
                )) AS total_activity_retail
            FROM
                summary_trans_location stl
            WHERE	
                stl.YEAR_STL = 2022 AND 
                stl.MONTH_STL = 12
            GROUP BY
                stl.REGIONAL_STL,
                stl.MONTH_STL
        ");
        $activity = [
            'data' => $data_activity
        ];
        echo json_encode($activity);
    }

    public function ranking_activity()
    {
        $ranking_sale_asmen = DB::select("
            SELECT
                ROW_NUMBER() OVER(ORDER BY NEW_AVERAGE DESC) AS NUM_ROW,
                stl.LOCATION_STL AS NAME_LOCATION,
                ROUND((
                    (
                        ((((SUM(stl.REALACTUB_STL) + TB_UB.TOT_QTYTD) / TB_UB.TGTREGIONAL_AC) * 100) * (TB_UB.PERCENTAGE_AC / 100)) + 
                        ((((SUM(stl.REALACTPS_STL) + TB_PS.TOT_QTYTD) / TB_PS.TGTREGIONAL_AC) * 100) * (TB_PS.PERCENTAGE_AC / 100)) + 
                        ((((SUM(stl.REALACTRETAIL_STL) + TB_RETAIL.TOT_QTYTD) / TB_RETAIL.TGTREGIONAL_AC) * 100) * (TB_RETAIL.PERCENTAGE_AC / 100))
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
                stl.updated_at LIKE '2022-12%'
            GROUP BY
                stl.LOCATION_STL
            ORDER BY
                NEW_AVERAGE DESC
        ");
        $ranking_sale_rpo = DB::select("
            SELECT
                ROW_NUMBER() OVER(ORDER BY NEW_AVERAGE DESC) AS NUM_ROW,
                stl.REGIONAL_STL AS NAME_REGIONAL,
                ROUND((
                    (
                        ((((stl.REALACTUB_STL + TB_UB.TOT_QTYTD) / TB_UB.TGTREGIONAL_AC) * 100) * (TB_UB.PERCENTAGE_AC / 100)) + 
                        ((((stl.REALACTPS_STL + TB_PS.TOT_QTYTD) / TB_PS.TGTREGIONAL_AC) * 100) * (TB_PS.PERCENTAGE_AC / 100)) + 
                        ((((stl.REALACTRETAIL_STL + TB_RETAIL.TOT_QTYTD) / TB_RETAIL.TGTREGIONAL_AC) * 100) * (TB_RETAIL.PERCENTAGE_AC / 100))
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
                stl.updated_at LIKE '2022-12%'
            ORDER BY 
                NEW_AVERAGE DESC
        ");
        $ranking_sale_apo = DB::select("
            SELECT
                ROW_NUMBER() OVER(ORDER BY NEW_AVERAGE DESC) AS NUM_ROW,
                ma.NAME_AREA,
                ROUND((
                    (
                        ((((dm.REALUST_DM + TB_UB.TOT_QTYTD) / TB_UB.TGTREGIONAL_AC) * 100) * (TB_UB.PERCENTAGE_AC / 100)) + 
                        ((((dm.REALNONUST_DM + TB_PS.TOT_QTYTD) / TB_PS.TGTREGIONAL_AC) * 100) * (TB_PS.PERCENTAGE_AC / 100)) + 
                        ((((dm.REALSELERAKU_DM + TB_RETAIL.TOT_QTYTD) / TB_RETAIL.TGTREGIONAL_AC) * 100) * (TB_RETAIL.PERCENTAGE_AC / 100))
                    ) / 3
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
                NEW_AVERAGE DESC
        ");
        $ranking_sale = [
            'asmen' => $ranking_sale_asmen,
            'rpo' => $ranking_sale_rpo,
            'apo' => $ranking_sale_apo
        ];
        echo json_encode($ranking_sale);
    }

    public function ranking_sale()
    {
        $ranking_sale_asmen = DB::select("
            SELECT
                ROW_NUMBER() OVER(ORDER BY NEW_AVERAGE DESC) AS NUM_ROW,
                stl.LOCATION_STL AS NAME_LOCATION,
                ROUND((
                    (
                        ((((SUM(stl.REALUST_STL) + TB_UST.TOT_QTYTD) / TB_UST.TGTLOCATION_PC) * 100) * (TB_UST.PERCENTAGE_PC / 100)) + 
                        ((((SUM(stl.REALNONUST_STL) + TB_NONUST.TOT_QTYTD) / TB_NONUST.TGTLOCATION_PC) * 100) * (TB_NONUST.PERCENTAGE_PC / 100)) + 
                        ((((SUM(stl.REALSELERAKU_STL) + TB_SELERAKU.TOT_QTYTD) / TB_SELERAKU.TGTLOCATION_PC) * 100) * (TB_SELERAKU.PERCENTAGE_PC / 100))
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
                summary_trans_location stl
            WHERE
                stl.updated_at LIKE '2022-12%'
            GROUP BY
                stl.LOCATION_STL
            ORDER BY
                NEW_AVERAGE DESC
        ");

        $ranking_sale_rpo = DB::select("
            SELECT
                ROW_NUMBER() OVER(ORDER BY NEW_AVERAGE DESC) AS NUM_ROW,
                stl.REGIONAL_STL AS NAME_REGIONAL,
                ROUND((
                        (
                            ((((stl.REALUST_STL + TB_UST.TOT_QTYTD) / TB_UST.TGTREGIONAL_PC) * 100) * (TB_UST.PERCENTAGE_PC / 100)) + 
                            ((((stl.REALNONUST_STL + TB_NONUST.TOT_QTYTD) / TB_NONUST.TGTREGIONAL_PC) * 100) * (TB_NONUST.PERCENTAGE_PC / 100)) + 
                            ((((stl.REALSELERAKU_STL + TB_SELERAKU.TOT_QTYTD) / TB_SELERAKU.TGTREGIONAL_PC) * 100) * (TB_SELERAKU.PERCENTAGE_PC / 100))
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
                summary_trans_location stl
            WHERE
                stl.updated_at LIKE '2022-12%'
            ORDER BY
                NEW_AVERAGE DESC
        ");

        $ranking_sale_apo = DB::select("
            SELECT
                ROW_NUMBER() OVER(ORDER BY NEW_AVERAGE DESC) AS NUM_ROW,
                ma.NAME_AREA,
                ROUND((
                    (
                        ((((dm.REALUST_DM + TB_UST.TOT_QTYTD) / TB_UST.TGTREGIONAL_PC) * 100) * (TB_UST.PERCENTAGE_PC / 100)) + 
                        ((((dm.REALNONUST_DM + TB_NONUST.TOT_QTYTD) / TB_NONUST.TGTREGIONAL_PC) * 100) * (TB_NONUST.PERCENTAGE_PC / 100)) + 
                        ((((dm.REALSELERAKU_DM + TB_SELERAKU.TOT_QTYTD) / TB_SELERAKU.TGTREGIONAL_PC) * 100) * (TB_SELERAKU.PERCENTAGE_PC / 100))
                    ) / 3
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
                NEW_AVERAGE DESC
        ");

        $ranking_sale = [
            'asmen' => $ranking_sale_asmen,
            'rpo' => $ranking_sale_rpo,
            'apo' => $ranking_sale_apo
        ];
        echo json_encode($ranking_sale);
    }

    public function trend_rpo()
    {
        $year = $_POST['date'];
        $role = $_POST['role'];
        $regional_targets = DB::select("
            SELECT
                mr.*
            FROM
                md_regional mr
        ");

        $data_trend = array();
        foreach ($regional_targets as $regional_target) {
            $data['UST'] = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $data['NONUST'] = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $data['SELERAKU'] = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $trend_asmen = DB::select("
                SELECT
                    stl.LOCATION_STL,
                    stl.MONTH_STL AS bulan,
                    (stl.REALUST_STL + TB_UST.TOT_QTYTD) AS total_ust,
                    (stl.REALNONUST_STL + TB_NONUST.TOT_QTYTD) AS total_non_ust,
                    (stl.REALSELERAKU_STL + TB_SELERAKU.TOT_QTYTD) AS total_seleraku	
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
                    summary_trans_location stl
                WHERE
                    stl.updated_at LIKE '%" . $year . "%'
                    AND stl.REGIONAL_STL = '" . $regional_target->NAME_REGIONAL . "'
                GROUP BY
                    stl.REGIONAL_STL,
                    stl.MONTH_STL
            ");

            foreach ($trend_asmen as $item) {
                $data['UST'][($item->bulan - 1)] = ((!empty($item->total_ust)) ? $item->total_ust : 0);
                $data['NONUST'][($item->bulan - 1)] = ((!empty($item->total_non_ust)) ? $item->total_non_ust : 0);
                $data['SELERAKU'][($item->bulan - 1)] = ((!empty($item->total_seleraku)) ? $item->total_seleraku : 0);
            }
            array_push(
                $data_trend,
                array(
                    "NAME_AREA" => $regional_target->NAME_REGIONAL,
                    "TARGET" => 0,
                    "UST" => $data['UST'],
                    "NONUST" => $data['NONUST'],
                    "SELERAKU" => $data['SELERAKU']
                )
            );
        }

        return $data_trend;
    }

    public function trend_asmen()
    {
        $year = $_POST['date'];
        $role = $_POST['role'];
        $regional_targets = DB::select("
            SELECT
                ml.*
            FROM
                md_location ml
        ");

        $data_trend = array();
        foreach ($regional_targets as $regional_target) {
            $data['UST'] = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $data['NONUST'] = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $data['SELERAKU'] = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            $trend_asmen = DB::select("
                SELECT
                    stl.LOCATION_STL,
                    stl.MONTH_STL AS bulan,
                    (SUM(stl.REALUST_STL) + TB_UST.TOT_QTYTD) AS total_ust,
                    (SUM(stl.REALNONUST_STL) + TB_NONUST.TOT_QTYTD) AS total_non_ust,
                    (SUM(stl.REALSELERAKU_STL) + TB_SELERAKU.TOT_QTYTD) AS total_seleraku	
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
            }
            array_push(
                $data_trend,
                array(
                    "NAME_AREA" => $regional_target->NAME_LOCATION,
                    "TARGET" => 0,
                    "UST" => $data['UST'],
                    "NONUST" => $data['NONUST'],
                    "SELERAKU" => $data['SELERAKU']
                )
            );
        }

        return $data_trend;
    }
}
