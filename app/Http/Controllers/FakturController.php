<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FakturController extends Controller
{
    public function index(){
        $data['title']          = "Faktur";
        $data['sidebar']        = "faktur";
        $data['sidebar2']       = "";
        $data['fakturs']        = DB::table('transaction_daily')
        ->join('user', 'user.ID_USER', '=', 'transaction_daily.ID_USER')
        ->join('md_type', 'md_type.ID_TYPE', '=', 'transaction_daily.ID_TYPE')
        ->orderBy('transaction_daily.DATE_TD', 'DESC')
        ->select('transaction_daily.*', 'user.NAME_USER', 'md_type.NAME_TYPE')
        ->get();

        return view('master.faktur.faktur', $data);
    }

    public function DetailFaktur(Request $req)
    {
        $data['title']          = "Detail Faktur";
        $data['sidebar']        = "faktur";

        $ID_Faktur    = $req->input('id_td');

        $data['fakturs']        = DB::table('transaction_daily')
        ->where('transaction_daily.ID_TD', $ID_Faktur)
        ->join('user', 'user.ID_USER', '=', 'transaction_daily.ID_USER')
        ->join('md_type', 'md_type.ID_TYPE', '=', 'transaction_daily.ID_TYPE')
        ->orderBy('transaction_daily.DATE_TD', 'DESC')
        ->select('transaction_daily.*', 'user.NAME_USER', 'md_type.NAME_TYPE')
        ->get();

        return view('master.faktur.detail_faktur', $data);
    }
}