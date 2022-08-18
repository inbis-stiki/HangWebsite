<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailTransController extends Controller
{
    public function index()
    {
        $data['title']          = "Transaksi";
        $data['sidebar']        = "transaksi";
        $data['sidebar2']       = "transaksi";

        return view('transaction/transaction', $data);
    }

    public function DetailSpread(Request $req)
    {
        $data['title']          = "Detail Spreading";
        $data['sidebar']        = "transaksi";

        return view('transaction/detail_spread', $data);   
    }

    public function DetailUB(Request $req)
    {
        $data['title']          = "Detail UB";
        $data['sidebar']        = "transaksi";

        return view('transaction/detail_ub', $data);
    }

    public function DetailUBLP(Request $req)
    {
        $data['title']          = "Detail UBLP";
        $data['sidebar']        = "transaksi";

        return view('transaction/detail_ublp', $data);
    }
}

?>