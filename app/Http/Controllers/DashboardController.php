<?php

namespace App\Http\Controllers;

use App\ActivityCategory;
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

        $id_regional        = $req->session()->get('regional');
        ($req->session()->get('role') == 2) ? $data['area'] = DB::table('md_area')->get() : $data['area'] = DB::table('md_area')->where('md_area.ID_REGIONAL', '=', $id_regional)->get();
        return view('dashboard', $data);
    }

    public function AktivitasRPO()
    {
        $month = date('n');
        $data = array();
        $user_regionals = DB::select("
        SELECT
        (
            SELECT
                u.NAME_USER
            FROM
                `user` u
            WHERE
                u.ID_ROLE = 4 
                AND u.ID_REGIONAL = mr.ID_REGIONAL
                LIMIT 1
        ) AS NAME_USER,
        (
            SELECT
                u.ID_ROLE
            FROM
                `user` u
            WHERE
                u.ID_ROLE = 4 
                AND u.ID_REGIONAL = mr.ID_REGIONAL
                LIMIT 1
        ) AS ROLE_USER,
        mr.NAME_REGIONAL,
        mr.ID_REGIONAL
        FROM
            md_regional mr,
            md_location ml
        WHERE
            mr.ID_LOCATION = ml.ID_LOCATION           
        ");

        foreach ($user_regionals as $user_regional) {
            $activity_rankings = DB::select("
            SELECT 
            (
                SELECT
                    QUANTITY
                    FROM
                    target_activity ta
                    WHERE
                        ID_ACTIVITY = 1
                        AND ID_REGIONAL = usa.ID_REGIONAL
            ) AS TARGET_UB, 
            SUM(usa.REAL_UB) AS REAL_UB, 
            usa.VSTARGET_UB, 
            (
                SELECT
                    QUANTITY
                    FROM
                    target_activity ta
                    WHERE
                        ID_ACTIVITY = 2
                        AND ID_REGIONAL = usa.ID_REGIONAL
            ) AS TARGET_PDGSAYUR, 
            SUM(usa.REAL_PDGSAYUR) AS REAL_PDGSAYUR, 
            usa.VSTARGET_PDGSAYUR, 
            (
                SELECT
                    QUANTITY
                    FROM
                    target_activity ta
                    WHERE
                        ID_ACTIVITY = 3
                        AND ID_REGIONAL = usa.ID_REGIONAL
            ) AS TARGET_RETAIL, 
            SUM(usa.REAL_RETAIL) AS REAL_RETAIL, 
            usa.VSTARGET_RETAIL, 
            usa.AVERAGE, 
            usa.ID_USER_RANKACTIVITY
            FROM
                user_ranking_activity AS usa,
                md_regional AS mr
            WHERE
                usa.ID_REGIONAL	= " . $user_regional->ID_REGIONAL . "
                AND usa.ID_REGIONAL = mr.ID_REGIONAL
                AND MONTH(DATE(usa.created_at)) = " . $month . "
            GROUP BY
                usa.ID_REGIONAL
            ");

            $temp['NAME_USER']          = $user_regional->NAME_USER;
            $temp['NAME_AREA']          = $user_regional->NAME_REGIONAL;
            $temp['ID_ROLE']            = $user_regional->ROLE_USER;
            $temp['TARGET_UB']          = !empty($activity_rankings[0]->TARGET_UB) ? $activity_rankings[0]->TARGET_UB : "-";
            $temp['REAL_UB']            = !empty($activity_rankings[0]->REAL_UB) ? $activity_rankings[0]->REAL_UB : "-";
            $temp['VSTARGET_UB']        = $temp['REAL_UB'] != "-" && $temp['TARGET_UB'] != "-" ? ($temp['REAL_UB'] / $temp['TARGET_UB']) * 100 : "-";
            $temp['TARGET_PDGSAYUR']    = !empty($activity_rankings[0]->TARGET_PDGSAYUR) ? $activity_rankings[0]->TARGET_PDGSAYUR : "-";
            $temp['REAL_PDGSAYUR']      = !empty($activity_rankings[0]->REAL_PDGSAYUR) ? $activity_rankings[0]->REAL_PDGSAYUR : "-";
            $temp['VSTARGET_PDGSAYUR']  = $temp['REAL_PDGSAYUR'] != "-" && $temp['TARGET_PDGSAYUR'] != "-" ? ($temp['REAL_PDGSAYUR'] / $temp['TARGET_PDGSAYUR']) * 100 : "-";
            $temp['TARGET_RETAIL']      = !empty($activity_rankings[0]->TARGET_RETAIL) ? $activity_rankings[0]->TARGET_RETAIL : "-";
            $temp['REAL_RETAIL']        = !empty($activity_rankings[0]->REAL_RETAIL) ? $activity_rankings[0]->REAL_RETAIL : "-";
            $temp['VSTARGET_RETAIL']    = $temp['REAL_RETAIL'] != "-" && $temp['TARGET_RETAIL'] != "-" ? ($temp['REAL_RETAIL'] / $temp['TARGET_RETAIL']) * 100 : "-";

            $weightActCat = ActivityCategory::where('deleted_at', NULL)->get()->toArray();
            $avgCount = 0;
            if ($weightActCat[0]["PERCENTAGE_AC"] != 0) $avgCount++;
            if ($weightActCat[1]["PERCENTAGE_AC"] != 0) $avgCount++;
            if ($weightActCat[2]["PERCENTAGE_AC"] != 0) $avgCount++;

            $temp['AVERAGE'] = (((float)$temp['VSTARGET_UB'] / 100) * ((float)$weightActCat[0]["PERCENTAGE_AC"] / 100));
            $temp['AVERAGE'] += (((float)$temp['VSTARGET_PDGSAYUR'] / 100) * ((float)$weightActCat[1]["PERCENTAGE_AC"] / 100));
            $temp['AVERAGE'] += (((float)$temp['VSTARGET_RETAIL'] / 100) * ((float)$weightActCat[2]["PERCENTAGE_AC"] / 100));
            $temp['AVERAGE'] = $temp['AVERAGE'] / $avgCount;

            $data[] = $temp;
        }
        usort($data, function ($a, $b) {
            return strnatcmp($b['AVERAGE'], $a['AVERAGE']);
        });
        foreach ($data as $key => $value) {
            $data[$key]['ID_USER_RANKSALE'] = $key + 1;
        }
        return $data;
    }

    public function ranking_activity()
    {
        $ranking_activity = $this->AktivitasRPO();
        return json_encode($ranking_activity);
    }

    public function ranking_sale()
    {
        $ranking_sale = DB::table('user_ranking_sale')
            ->select('user_ranking_sale.ID_USER_RANKSALE', 'user_ranking_sale.ID_ROLE', 'user_ranking_sale.AVERAGE', 'user.NAME_USER')
            ->join('user', 'user_ranking_sale.ID_USER', '=', 'user.ID_USER')
            ->orderBy('ID_USER_RANKSALE', 'ASC')
            ->skip(0)->take(5)->get();
        return json_encode($ranking_sale);
    }

    public function ranking_aktivitas(Request $request)
    {
        $date = $request->input('filter_date');
        $area = $request->input('filter_area');
        if ($area == 0) {
            $data_activity = DB::table('user_ranking_activity')
                ->where('user_ranking_activity.created_at', 'like', $date . '%')
                ->selectRaw(
                    'SUM(user_ranking_activity.TARGET_UB) as TotalTGT_UB, 
                SUM(user_ranking_activity.REAL_UB) as TotalREAL_UB,
                SUM(user_ranking_activity.TARGET_PDGSAYUR) as TotalTGT_PDGSAYUR, 
                SUM(user_ranking_activity.REAL_PDGSAYUR) as TotalREAL_PDGSAYUR,
                SUM(user_ranking_activity.TARGET_RETAIL) as TotalTGT_RETAIL, 
                SUM(user_ranking_activity.REAL_RETAIL) as TotalREAL_RETAIL'
                )
                ->first();
        } else {
            $data_activity = DB::table('user_ranking_activity')
                ->where('user_ranking_activity.created_at', 'like', $date . '%')
                ->where('user_ranking_activity.ID_LOCATION', '=', $area)
                ->selectRaw(
                    'SUM(user_ranking_activity.TARGET_UB) as TotalTGT_UB, 
                SUM(user_ranking_activity.REAL_UB) as TotalREAL_UB,
                SUM(user_ranking_activity.TARGET_PDGSAYUR) as TotalTGT_PDGSAYUR, 
                SUM(user_ranking_activity.REAL_PDGSAYUR) as TotalREAL_PDGSAYUR,
                SUM(user_ranking_activity.TARGET_RETAIL) as TotalTGT_RETAIL, 
                SUM(user_ranking_activity.REAL_RETAIL) as TotalREAL_RETAIL'
                )
                ->first();
        }

        if ($data_activity->TotalTGT_UB == null) {
            $ranking_activity = array(
                "TGT_UB" => "0",
                "REAL_UB" => "0",
                "VSTARGET_UB" => "NO DATA",
                "TGT_PDGSAYUR" => "0",
                "REAL_PDGSAYUR" => "0",
                "VSTARGET_PDGSAYUR" => "NO DATA",
                "TGT_RETAIL" => "0",
                "REAL_RETAIL" => "0",
                "VSTARGET_RETAIL" => "NO DATA"
            );
        } else {
            $vsTarget_ub = ($data_activity->TotalREAL_UB / $data_activity->TotalTGT_UB) * 100;
            $vsTarget_pdgSayur = ($data_activity->TotalREAL_PDGSAYUR / $data_activity->TotalTGT_PDGSAYUR) * 100;
            $vsTarget_retail = ($data_activity->TotalREAL_RETAIL / $data_activity->TotalTGT_RETAIL) * 100;

            $ranking_activity = array(
                "TGT_UB" => $data_activity->TotalTGT_UB,
                "REAL_UB" => $data_activity->TotalREAL_UB,
                "VSTARGET_UB" => (number_format((float)$vsTarget_ub, 1, '.', '') + 0) . "%",
                "TGT_PDGSAYUR" => $data_activity->TotalTGT_PDGSAYUR,
                "REAL_PDGSAYUR" => $data_activity->TotalREAL_PDGSAYUR,
                "VSTARGET_PDGSAYUR" => (number_format((float)$vsTarget_pdgSayur, 1, '.', '') + 0) . "%",
                "TGT_RETAIL" => $data_activity->TotalTGT_RETAIL,
                "REAL_RETAIL" => $data_activity->TotalREAL_RETAIL,
                "VSTARGET_RETAIL" => (number_format((float)$vsTarget_retail, 1, '.', '') + 0) . "%"
            );
        }

        return json_encode($ranking_activity);
    }

    public function ranking_pencapaian(Request $request)
    {
        $date = $request->input('filter_date');
        $area = $request->input('filter_area');
        if ($area == 0) {
            $data_sale = DB::table('user_ranking_sale')
                ->where('user_ranking_sale.created_at', 'like', $date . '%')
                ->selectRaw(
                    'SUM(user_ranking_sale.TARGET_UST) as TotalTGT_UST, 
                SUM(user_ranking_sale.REAL_UST) as TotalREAL_UST,
                SUM(user_ranking_sale.TARGET_NONUST) as TotalTGT_NONUST, 
                SUM(user_ranking_sale.REAL_NONUST) as TotalREAL_NONUST,
                SUM(user_ranking_sale.TARGET_SELERAKU) as TotalTGT_SELERAKU, 
                SUM(user_ranking_sale.REAL_SELERAKU) as TotalREAL_SELERAKU'
                )
                ->first();
        } else {
            $data_sale = DB::table('user_ranking_sale')
                ->where('user_ranking_sale.created_at', 'like', $date . '%')
                ->where('user_ranking_sale.ID_LOCATION', '=', $area)
                ->selectRaw(
                    'SUM(user_ranking_sale.TARGET_UST) as TotalTGT_UST, 
                SUM(user_ranking_sale.REAL_UST) as TotalREAL_UST,
                SUM(user_ranking_sale.TARGET_NONUST) as TotalTGT_NONUST, 
                SUM(user_ranking_sale.REAL_NONUST) as TotalREAL_NONUST,
                SUM(user_ranking_sale.TARGET_SELERAKU) as TotalTGT_SELERAKU, 
                SUM(user_ranking_sale.REAL_SELERAKU) as TotalREAL_SELERAKU'
                )
                ->first();
        }

        if ($data_sale->TotalTGT_UST == null) {
            $ranking_sale = array(
                "TGT_UST" => "0",
                "REAL_UST" => "0",
                "VSTARGET_UST" => "NO DATA",
                "TGT_NONUST" => "0",
                "REAL_NONUST" => "0",
                "VSTARGET_NONUST" => "NO DATA",
                "TGT_SELERAKU" => "0",
                "REAL_SELERAKU" => "0",
                "VSTARGET_SELERAKU" => "NO DATA"
            );
        } else {
            $vsTarget_UST = ($data_sale->TotalREAL_UST / $data_sale->TotalTGT_UST) * 100;
            $vsTarget_NONUST = ($data_sale->TotalREAL_NONUST / $data_sale->TotalTGT_NONUST) * 100;
            $vsTarget_SELERAKU = ($data_sale->TotalREAL_SELERAKU / $data_sale->TotalTGT_SELERAKU) * 100;
            $ranking_sale = array(
                "TGT_UST" => $data_sale->TotalTGT_UST,
                "REAL_UST" => $data_sale->TotalREAL_UST,
                "VSTARGET_UST" => (number_format((float)$vsTarget_UST, 1, '.', '') + 0) . "%",
                "TGT_NONUST" => $data_sale->TotalTGT_NONUST,
                "REAL_NONUST" => $data_sale->TotalREAL_NONUST,
                "VSTARGET_NONUST" => (number_format((float)$vsTarget_NONUST, 1, '.', '') + 0) . "%",
                "TGT_SELERAKU" => $data_sale->TotalTGT_SELERAKU,
                "REAL_SELERAKU" => $data_sale->TotalREAL_SELERAKU,
                "VSTARGET_SELERAKU" => (number_format((float)$vsTarget_SELERAKU, 1, '.', '') + 0) . "%"
            );
        }

        return json_encode($ranking_sale);
    }

    public function presensi(Request $req)
    {
        $id_role  = $req->session()->get('role');
        $id_regional  = $req->session()->get('regional');
        $tgl_presence  = $req->input('filter_date');
        $area  = $req->input('filter_area');

        if ($id_role != 2) {
            $data_presence      = DB::table('presence')
                ->select('presence.*', 'user.NAME_USER', 'md_district.NAME_DISTRICT', 'md_area.NAME_AREA', 'md_regional.NAME_REGIONAL')
                ->selectRaw("MONTH(presence.DATE_PRESENCE) as month, YEAR(presence.DATE_PRESENCE) as year, COUNT(presence.ID_PRESENCE) as TotalPresence")
                ->join('user', 'user.ID_USER', '=', 'presence.ID_USER')
                ->join('md_district', 'md_district.ID_DISTRICT', '=', 'presence.ID_DISTRICT')
                ->join('md_area', 'md_area.ID_AREA', '=', 'md_district.ID_AREA')
                ->join('md_regional', 'md_regional.ID_REGIONAL', '=', 'md_area.ID_REGIONAL')
                ->orderBy('presence.DATE_PRESENCE', 'DESC')
                ->groupByRaw("MONTH(presence.DATE_PRESENCE), YEAR(presence.DATE_PRESENCE), presence.ID_USER")
                ->where('md_regional.ID_REGIONAL', '=', $id_regional)
                ->where('md_area.ID_AREA', '=', $area)
                ->where('presence.DATE_PRESENCE', 'like', $tgl_presence . '%')
                ->get();
        } else {
            $data_presence      = DB::table('presence')
                ->select('presence.*', 'user.NAME_USER', 'md_district.NAME_DISTRICT', 'md_area.NAME_AREA', 'md_regional.NAME_REGIONAL')
                ->selectRaw("MONTH(presence.DATE_PRESENCE) as month, YEAR(presence.DATE_PRESENCE) as year, COUNT(presence.ID_PRESENCE) as TotalPresence")
                ->join('user', 'user.ID_USER', '=', 'presence.ID_USER')
                ->join('md_district', 'md_district.ID_DISTRICT', '=', 'presence.ID_DISTRICT')
                ->join('md_area', 'md_area.ID_AREA', '=', 'md_district.ID_AREA')
                ->join('md_regional', 'md_regional.ID_REGIONAL', '=', 'md_area.ID_REGIONAL')
                ->orderBy('presence.DATE_PRESENCE', 'DESC')
                ->groupByRaw("MONTH(presence.DATE_PRESENCE), YEAR(presence.DATE_PRESENCE), presence.ID_USER")
                ->where('presence.DATE_PRESENCE', 'like', $tgl_presence . '%')
                ->where('md_area.ID_AREA', '=', $area)
                ->get();
        }

        $NewData_all = array();
        $i = 0;
        foreach ($data_presence as $presence) {
            $i++;

            $data = array(
                "NO" => $i,
                "NAME_USER" => $presence->NAME_USER,
                "NAME_AREA" => $presence->NAME_AREA,
                "JML_PRESENCE" => $presence->TotalPresence,
                "DATE_PRESENCE" => date_format(date_create($presence->year . '-' . $presence->month), 'F Y'),
            );
            array_push($NewData_all, $data);
        }

        return response([
            'status_code'       => 200,
            'status_message'    => 'Data berhasil diambil!',
            'data'              => $NewData_all
        ], 200);
    }

    public function trend_asmen()
    {
        $year = date('Y');
        $regional_targets = DB::select("
        SELECT
            mr.ID_REGIONAL,
            mr.NAME_REGIONAL,
            (
                SELECT 
                    SUM(ts.QUANTITY) 
                FROM target_sale ts
                WHERE ts.ID_REGIONAL = mr.ID_REGIONAL
            ) AS QUANTITY
        FROM
            md_regional mr
        GROUP BY mr.ID_REGIONAL
        ");

        
        $data_trend = array();
        foreach ($regional_targets as $regional_target) {
            $trend_asmen = DB::select("
            SELECT
                urs.NAME_REGIONAL ,
                MONTH(created_at) as bulan,
                SUM(urs.REAL_UST) as total_ust,
                SUM(urs.REAL_NONUST) as total_non_ust,
                SUM(urs.REAL_SELERAKU) as total_seleraku
            FROM user_ranking_sale urs
            WHERE 
                YEAR(DATE(urs.created_at)) = 2022
                AND urs.ID_ROLE = 3
                AND urs.ID_REGIONAL = " . $regional_target->ID_REGIONAL . "
            GROUP BY MONTH(urs.created_at), urs.NAME_REGIONAL
            ORDER BY MONTH(urs.created_at) ASC
            ");

            array_push(
                $data_trend,
                array(
                    "NAME_AREA" => $regional_target->NAME_REGIONAL,
                    "TARGET" => $regional_target->QUANTITY,
                    "UST" => array(
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 1 && !empty($trend_asmen[0]->total_ust)) ? $trend_asmen[0]->total_ust : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 2 && !empty($trend_asmen[0]->total_ust)) ? $trend_asmen[0]->total_ust : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 3 && !empty($trend_asmen[0]->total_ust)) ? $trend_asmen[0]->total_ust : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 4 && !empty($trend_asmen[0]->total_ust)) ? $trend_asmen[0]->total_ust : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 5 && !empty($trend_asmen[0]->total_ust)) ? $trend_asmen[0]->total_ust : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 6 && !empty($trend_asmen[0]->total_ust)) ? $trend_asmen[0]->total_ust : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 7 && !empty($trend_asmen[0]->total_ust)) ? $trend_asmen[0]->total_ust : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 8 && !empty($trend_asmen[0]->total_ust)) ? $trend_asmen[0]->total_ust : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 9 && !empty($trend_asmen[0]->total_ust)) ? $trend_asmen[0]->total_ust : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 10 && !empty($trend_asmen[0]->total_ust)) ? $trend_asmen[0]->total_ust : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 11 && !empty($trend_asmen[0]->total_ust)) ? $trend_asmen[0]->total_ust : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 12 && !empty($trend_asmen[0]->total_ust)) ? $trend_asmen[0]->total_ust : 0) : 0
                    ),
                    "NONUST" => array(
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 1 && !empty($trend_asmen[0]->total_non_ust)) ? $trend_asmen[0]->total_non_ust : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 2 && !empty($trend_asmen[0]->total_non_ust)) ? $trend_asmen[0]->total_non_ust : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 3 && !empty($trend_asmen[0]->total_non_ust)) ? $trend_asmen[0]->total_non_ust : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 4 && !empty($trend_asmen[0]->total_non_ust)) ? $trend_asmen[0]->total_non_ust : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 5 && !empty($trend_asmen[0]->total_non_ust)) ? $trend_asmen[0]->total_non_ust : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 6 && !empty($trend_asmen[0]->total_non_ust)) ? $trend_asmen[0]->total_non_ust : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 7 && !empty($trend_asmen[0]->total_non_ust)) ? $trend_asmen[0]->total_non_ust : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 8 && !empty($trend_asmen[0]->total_non_ust)) ? $trend_asmen[0]->total_non_ust : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 9 && !empty($trend_asmen[0]->total_non_ust)) ? $trend_asmen[0]->total_non_ust : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 10 && !empty($trend_asmen[0]->total_non_ust)) ? $trend_asmen[0]->total_non_ust : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 11 && !empty($trend_asmen[0]->total_non_ust)) ? $trend_asmen[0]->total_non_ust : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 12 && !empty($trend_asmen[0]->total_non_ust)) ? $trend_asmen[0]->total_non_ust : 0) : 0
                    ),
                    "SELERAKU" => array(
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 1 && !empty($trend_asmen[0]->seleraku)) ? $trend_asmen[0]->seleraku : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 2 && !empty($trend_asmen[0]->seleraku)) ? $trend_asmen[0]->seleraku : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 3 && !empty($trend_asmen[0]->seleraku)) ? $trend_asmen[0]->seleraku : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 4 && !empty($trend_asmen[0]->seleraku)) ? $trend_asmen[0]->seleraku : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 5 && !empty($trend_asmen[0]->seleraku)) ? $trend_asmen[0]->seleraku : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 6 && !empty($trend_asmen[0]->seleraku)) ? $trend_asmen[0]->seleraku : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 7 && !empty($trend_asmen[0]->seleraku)) ? $trend_asmen[0]->seleraku : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 8 && !empty($trend_asmen[0]->seleraku)) ? $trend_asmen[0]->seleraku : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 9 && !empty($trend_asmen[0]->seleraku)) ? $trend_asmen[0]->seleraku : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 10 && !empty($trend_asmen[0]->seleraku)) ? $trend_asmen[0]->seleraku : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 11 && !empty($trend_asmen[0]->seleraku)) ? $trend_asmen[0]->seleraku : 0) : 0,
                        (!empty($trend_asmen[0]->bulan)) ? (($trend_asmen[0]->bulan == 12 && !empty($trend_asmen[0]->seleraku)) ? $trend_asmen[0]->seleraku : 0) : 0
                    )
                )
            );
        }

        return $data_trend;
    }
}
