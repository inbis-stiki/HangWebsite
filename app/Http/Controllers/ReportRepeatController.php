<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportRepeatController extends Controller
{
    public function index()
    {
        $data['title']          = "Repeat";
        $data['sidebar']        = "repeat";
        $data['sidebar2']       = "";

        $data['regional']   = DB::table('md_regional')
        ->whereNull('deleted_at')->get();

        return view('laporan.repeat.repeat', $data);
    }
}
