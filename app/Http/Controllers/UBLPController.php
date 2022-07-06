<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UBLPController extends Controller {
    public function index(){
        $data['title']          = "Transaksi UBLP";
        $data['sidebar']        = "transaksi";
        $data['sidebar2']       = "ublp";
        $data['ublps']          = DB::table('transaction')
        ->where('transaction.ID_TYPE', '3')        
        ->join('user', 'user.ID_USER', '=', 'transaction.ID_USER')
        ->select('user.NAME_USER', 'transaction.AREA_TRANS', 'transaction.REGIONAL_TRANS', 'transaction.LOCATION_TRANS', 'transaction.DATE_TRANS')
        ->get();

        return view('transaction/ublp', $data);
    }
}
