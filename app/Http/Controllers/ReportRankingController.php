<?php

namespace App\Http\Controllers;

use App\Regional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportRankingController extends Controller
{
    public function index()
    {
        $data['title']          = "Ranking";
        $data['sidebar']        = "ranking";
        $data['sidebar2']       = "";
        $data['regionals']      = Regional::where('deleted_at', NULL)->get();

        return view('laporan.ranking.ranking', $data);
    }
}
