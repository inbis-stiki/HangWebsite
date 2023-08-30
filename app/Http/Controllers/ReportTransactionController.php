<?php

namespace App\Http\Controllers;

use App\Regional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class ReportTransactionController extends Controller
{
    public function index(Request $req)
    {
        $user = $req->session()->get('id_user');
        $data['title']          = "Transaksi Harian";
        $data['sidebar']        = "transaction";
        $data['sidebar2']       = "";

        if ($user = "e35f88a4" || $user = "8735afgu"){
            $data['regionals']      = DB::table('md_regional')
            ->select('md_regional.ID_REGIONAL', 'md_regional.NAME_REGIONAL')
            ->join('md_location', 'md_location.ID_LOCATION', '=', 'md_regional.ID_LOCATION')
            ->join('user', 'user.ID_LOCATION', '=', 'md_location.ID_LOCATION')
            ->groupBy('md_regional.NAME_REGIONAL')
            ->get();
        } else{
            $data['regionals']      = DB::table('md_regional')
            ->select('md_regional.ID_REGIONAL', 'md_regional.NAME_REGIONAL')
            ->join('md_location', 'md_location.ID_LOCATION', '=', 'md_regional.ID_LOCATION')
            ->join('user', 'user.ID_LOCATION', '=', 'md_location.ID_LOCATION')
            ->where('user.ID_USER', '=', $user)
            ->get();
        }
        
        return view('laporan.transaction.transaction', $data);
    }
}
