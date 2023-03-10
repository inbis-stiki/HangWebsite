<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportRepeatController extends Controller
{
    public function index()
    {
        $data['title']          = "Repeat";
        $data['sidebar']        = "repeat";
        $data['sidebar2']       = "";

        return view('laporan.repeat.repeat', $data);
    }
}
