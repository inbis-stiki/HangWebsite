<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportTrendController extends Controller
{
    public function index()
    {
        $data['title']          = "Trend";
        $data['sidebar']        = "trend";
        $data['sidebar2']       = "";

        return view('laporan.trend.trend', $data);
    }
}
