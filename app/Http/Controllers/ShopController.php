<?php

namespace App\Http\Controllers;

use App\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    public function index(){
        $data['title']      = "Toko";
        $data['sidebar']    = "master";
        $data['shop']      = Shop::join('md_district', 'md_shop.ID_DISTRICT', '=', 'md_district.ID_DISTRICT')->select('*')->get();
        return view('master.shop.shop', $data);
    }
}