<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        return view("dashboard.index");
    }
    public function lokasi_region()
    {
        return view("dashboard.lokasi.region");
    }
    public function lokasi_lokasi()
    {
        return view("dashboard.lokasi.lokasi");
    }
    public function lokasi_area()
    {
        return view("dashboard.lokasi.area");
    }
    public function lokasi_kecamatan()
    {
        return view("dashboard.lokasi.kecamatan");
    }
    public function lokasi_pasar()
    {
        return view("dashboard.lokasi.pasar");
    }
}
