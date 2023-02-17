<?php

namespace App\Http\Controllers;

use App\Regional;
use Illuminate\Http\Request;

class ReportTransactionController extends Controller
{
    public function index()
    {
        $data['title']          = "Transaction";
        $data['sidebar']        = "transaction";
        $data['sidebar2']       = "";
        $data['regionals']      = Regional::where('deleted_at', NULL)->get();

        return view('laporan.transaction.transaction', $data);
    }
}
