<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportRankingController extends Controller
{
    public function index()
    {
        $data['title']          = "Ranking";
        $data['sidebar']        = "ranking";
        $data['sidebar2']       = "";

        return view('laporan.ranking.ranking', $data);
    }
}
