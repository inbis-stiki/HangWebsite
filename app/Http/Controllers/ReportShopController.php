<?php

namespace App\Http\Controllers;

use App\Area;
use App\Location;
use App\Regional;
use App\ReportShop;
use App\Shop;
use Illuminate\Http\Request;

class ReportShopController extends Controller
{
    public function index(Request $req){
        $data['title']          = "Toko";
        $data['sidebar']        = "shop";
        $data['sidebar2']       = "";
        $data['regionals']      = Regional::where('deleted_at', NULL)->get();
        $data['coords']         = Shop::getShopByReg($data['regionals'][0]->ID_REGIONAL);
        $data['shopTypeCounts'] = Shop::getTotTypeByReg($data['regionals'][0]->ID_REGIONAL);
        $data['area']           = $data['regionals'][0]->NAME_REGIONAL;

        return view('laporan.shop.shop', $data);
    }

    public function get($idReg){
        $data['title']          = "Toko";
        $data['sidebar']        = "shop";
        $data['sidebar2']       = "";
        $data['regionals']      = Regional::where('deleted_at', NULL)->get();
        $data['coords']         = Shop::getShopByReg($idReg);
        $data['shopTypeCounts'] = Shop::getTotTypeByReg($idReg);
        $data['area']           = $data['regionals'][0]->NAME_REGIONAL;
        $data['idReg']          = $idReg;

        return view('laporan.shop.shop', $data);
    }

    public function download(){
        $data['regional']   = Regional::where('ID_LOCATION', $_POST['idRegional'])->first();
        $data['areas']      = Shop::getTotTypePerArea($_POST['idRegional']);

        $report = new ReportShop();
        $report->generate($data);
    }
}
