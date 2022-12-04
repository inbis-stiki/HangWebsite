<?php

namespace App\Http\Controllers;

use App\Area;
use App\Location;
use App\ReportShop;
use App\Shop;
use Illuminate\Http\Request;

class ReportShopController extends Controller
{
    public function index(Request $req){
        $data['title']          = "Toko";
        $data['sidebar']        = "shop";
        $data['sidebar2']       = "";
        $data['locations']      = Location::where('deleted_at', NULL)->get();
        $data['coords']         = Shop::getShopByLoc($data['locations'][0]->ID_LOCATION);
        $data['shopTypeCounts'] = Shop::getTotTypeByLoc($data['locations'][0]->ID_LOCATION);
        $data['area']           = $data['locations'][0]->NAME_LOCATION;

        return view('laporan.shop.shop', $data);
    }

    public function get($idLoc){
        $data['title']          = "Toko";
        $data['sidebar']        = "shop";
        $data['sidebar2']       = "";
        $data['locations']      = Location::where('deleted_at', NULL)->get();
        $data['coords']         = Shop::getShopByLoc($idLoc);
        $data['shopTypeCounts'] = Shop::getTotTypeByLoc($idLoc);
        $data['area']           = $data['locations'][0]->NAME_LOCATION;
        $data['idLoc']          = $idLoc;

        return view('laporan.shop.shop', $data);
    }

    public function download(){
        $data['location']   = Location::where('ID_LOCATION', $_POST['idLocation'])->first();
        $data['areas']      = Shop::getTotTypePerArea($_POST['idLocation']);

        $report = new ReportShop();
        $report->generate($data);
    }
}
