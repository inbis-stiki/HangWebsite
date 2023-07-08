<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportPerformanceController extends Controller
{
    public function index()
    {
        $data['title']          = "Laporan";
        $data['sidebar']        = "performance";
        $data['sidebar2']       = "";

        $data['category']   = DB::table('md_product_category')
        ->whereNull('deleted_at')->get();

        return view('laporan.performance.performance', $data);
    }
}
