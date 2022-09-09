<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportTransactionController extends Controller
{
    public function index()
    {
        $data['title']          = "Transaction";
        $data['sidebar']        = "transaction";
        $data['sidebar2']       = "";

        return view('laporan.transaction.transaction', $data);
    }
}
