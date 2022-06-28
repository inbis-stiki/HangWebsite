<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SpreadingController extends Controller
{
    public function index(){
        $data['title']          = "Transaksi Spreading";
        $data['sidebar']        = "transaksi";
        $data['sidebar2']       = "spreading";
        $data['spreadings']     = DB::table('transaction')
        ->where('ID_TYPE', '1')
        ->join('user', 'user.ID_USER', '=', 'presence.ID_USER')
        ->orderBy('transaction.DATE_TRANS', 'DESC')
        ->select('presence.*', 'user.NAME_USER', 'md_district.NAME_DISTRICT', 'md_area.NAME_AREA', 'md_regional.NAME_REGIONAL', 'md_type.NAME_TYPE')
        ->get();

        return view('presence', $data);
    }
}
