<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class ReportRepeatController extends Controller
{
    public function index(Request $req)
    {
        $user = $req->session()->get('id_user');
        $data['title']          = "Repeat Order";
        $data['sidebar']        = "repeat";
        $data['sidebar2']       = "";

        if ($user == "e35f88a4" || $user == "8735afgu"){
            $data['regional']      = DB::table('md_regional')
            ->select('md_regional.ID_REGIONAL', 'md_regional.NAME_REGIONAL')
            ->join('md_location', 'md_location.ID_LOCATION', '=', 'md_regional.ID_LOCATION')
            ->join('user', 'user.ID_LOCATION', '=', 'md_location.ID_LOCATION')
            ->groupBy('md_regional.NAME_REGIONAL')
            ->get();
        }else {
            $data['regional'] = DB::table('md_regional')
            ->select('md_regional.ID_REGIONAL', 'md_regional.NAME_REGIONAL')
            ->join('md_location', 'md_location.ID_LOCATION', '=', 'md_regional.ID_LOCATION')
            ->join('user', 'user.ID_LOCATION', '=', 'md_location.ID_LOCATION')
            ->where('user.ID_USER', '=', $user)
            ->get();
        }

        $data['tipe_toko'] = DB::select("
            SELECT 
                ms.TYPE_SHOP
            FROM 
                md_shop ms
            GROUP BY
                ms.TYPE_SHOP
        ");

        return view('laporan.repeat.repeat', $data);
    }

    public function index_repeat_cat(Request $req)
    {
        $user = $req->session()->get('id_user');
        $data['title']          = "Repeat Order Kategori";
        $data['sidebar']        = "repeat";
        $data['sidebar2']       = "";

        if ($user == "e35f88a4" || $user == "8735afgu"){
            $data['regional']      = DB::table('md_regional')
            ->select('md_regional.ID_REGIONAL', 'md_regional.NAME_REGIONAL')
            ->join('md_location', 'md_location.ID_LOCATION', '=', 'md_regional.ID_LOCATION')
            ->join('user', 'user.ID_LOCATION', '=', 'md_location.ID_LOCATION')
            ->groupBy('md_regional.NAME_REGIONAL')
            ->get();
        }else {
            $data['regional'] = DB::table('md_regional')
            ->select('md_regional.ID_REGIONAL', 'md_regional.NAME_REGIONAL')
            ->join('md_location', 'md_location.ID_LOCATION', '=', 'md_regional.ID_LOCATION')
            ->join('user', 'user.ID_LOCATION', '=', 'md_location.ID_LOCATION')
            ->where('user.ID_USER', '=', $user)
            ->get();
        }

        $data['tipe_toko'] = DB::select("
            SELECT 
                ms.TYPE_SHOP
            FROM 
                md_shop ms
            GROUP BY
                ms.TYPE_SHOP
        ");

        return view('laporan.repeat.repeat_cat', $data);
    }
}
