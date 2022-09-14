<?php

namespace App\Http\Controllers;

use App\UserRankingActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\Input;

class DashboardController extends Controller
{
    public function index()
    {
        $data['title']      = "Dashboard";
        $data['sidebar']    = "dashboard";
        return view('dashboard', $data);
    }

    public function ranking_activity()
    {
        $ranking_activity = DB::table('user_ranking_activity')
            ->select('user_ranking_activity.ID_USER_RANKACTIVITY', 'user_ranking_activity.ID_ROLE', 'user_ranking_activity.AVERAGE', 'user.NAME_USER')
            ->join('user', 'user_ranking_activity.ID_USER', '=', 'user.ID_USER')
            ->orderBy('ID_USER_RANKACTIVITY', 'ASC')
            ->skip(0)->take(5)->get();
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
        $tgl_presence  = $req->input('tglSearchPresence');

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
}
