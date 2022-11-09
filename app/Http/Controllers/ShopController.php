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
        $data['shop']      = Shop::join('md_district', 'md_shop.ID_DISTRICT', '=', 'md_district.ID_DISTRICT')->limit(5)->select('*')->get();
        // $data['shop']      = NULL;
        return view('master.shop.shop', $data);
    }

    public function AllShop(Request $req){
        $draw   = $req->input('draw');
        $offset = $req->input('start');
        $limit  = $req->input('length');
        $search = $req->input('search')['value'];

        $data_shop = Shop::join('md_district', 'md_shop.ID_DISTRICT', '=', 'md_district.ID_DISTRICT')
            ->select("md_district.NAME_DISTRICT", "md_shop.NAME_SHOP", "md_shop.OWNER_SHOP", "md_shop.TYPE_SHOP", "md_shop.deleted_at")
            ->orWhere('md_district.NAME_DISTRICT', 'like', '%'.$search.'%')
            ->orWhere('md_shop.NAME_SHOP', 'like', '%'.$search.'%')
            ->orWhere('md_shop.OWNER_SHOP', 'like', '%'.$search.'%')
            ->orWhere('md_shop.TYPE_SHOP', 'like', '%'.$search.'%')
            ->offset($offset)
            ->limit($limit)
            ->get();

        if($search != ''){
            $filteredSearch = Shop::join('md_district', 'md_shop.ID_DISTRICT', '=', 'md_district.ID_DISTRICT')
            ->select("md_district.NAME_DISTRICT", "md_shop.NAME_SHOP", "md_shop.OWNER_SHOP", "md_shop.TYPE_SHOP", "md_shop.deleted_at")
            ->orWhere('md_district.NAME_DISTRICT', 'like', '%'.$search.'%')
            ->orWhere('md_shop.NAME_SHOP', 'like', '%'.$search.'%')
            ->orWhere('md_shop.OWNER_SHOP', 'like', '%'.$search.'%')
            ->orWhere('md_shop.TYPE_SHOP', 'like', '%'.$search.'%')
            ->get()->count();
        }
        
        
        $countShop = Shop::count();

        $NewData_all = array();
        $i = 0;
        foreach ($data_shop as $shop) {
            $i++;

            if($shop->deleted_at == NULL){
                $isActive = '<i class="fa-solid fa-circle mr-2" style="color:#3CC13B;"></i>Enable';
            }else{
                $isActive = '<i class="fa-solid fa-circle mr-2" style="color:#C13B3B;"></i>Disable';
            }

            $data = array(
                "NO" => $i,
                "NAME_DISTRICT" => $shop->NAME_DISTRICT,
                "NAME_SHOP"     => $shop->NAME_SHOP,
                "OWNER_SHOP"    => $shop->OWNER_SHOP,
                "TYPE_SHOP"     => $shop->TYPE_SHOP,
                "ISACTIVE"      => $isActive,
                "ACTION_BUTTON" => '
                    <button onclick="" class="btn btn-primary btn-sm">
                        <i class="flaticon-381-edit-1"></i>
                    </button>
                    <button onclick="" class="btn btn-primary btn-sm">
                        <i class="flaticon-381-trash-1"></i>
                    </button>
                '
            );
            array_push($NewData_all, $data);
        }

        return response([
            'status_code'       => 200,
            'status_message'    => 'Data berhasil diambil!',
            "draw" => intval($draw),
            "recordsTotal" => $countShop,
            "recordsFiltered" => ($search != "" ? $filteredSearch : $countShop),
            "aaData" => $NewData_all,
        ], 200);
    }
}