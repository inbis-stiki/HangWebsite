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
        
    }

    public function DetailUB(Request $req)
    {
        
    }

    public function DetailUBLP(Request $req)
    {
        
    }
}

?>