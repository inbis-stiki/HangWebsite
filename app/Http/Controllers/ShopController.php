<?php

namespace App\Http\Controllers;

use App\Shop;
use App\logmd;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Session;

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
        $role = SESSION::get('role');
        $draw   = $req->input('draw');
        $offset = $req->input('start');
        $limit  = $req->input('length');
        $search = $req->input('search')['value'];

        $data_shop = Shop::join('md_district', 'md_shop.ID_DISTRICT', '=', 'md_district.ID_DISTRICT')
            ->select("md_district.NAME_DISTRICT", "md_shop.ID_SHOP", "md_shop.NAME_SHOP", "md_shop.OWNER_SHOP", "md_shop.DETLOC_SHOP", "md_shop.TYPE_SHOP", "md_shop.deleted_at")
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

            if($role == 1 || $role == 2){
                
                $data = array(
                    "NO" => $i,
                    "NAME_DISTRICT" => $shop->NAME_DISTRICT,
                    "NAME_SHOP"     => $shop->NAME_SHOP,
                    "OWNER_SHOP"    => $shop->OWNER_SHOP,
                    "TYPE_SHOP"     => $shop->TYPE_SHOP,
                    "ISACTIVE"      => $isActive,
                    "ACTION_BUTTON" => '
                        <button data-id="'.$shop->ID_SHOP.'" data-name="'.$shop->NAME_SHOP.'" data-own="'.$shop->OWNER_SHOP.'" data-lok="'.$shop->DETLOC_SHOP.'" data-del="'.$shop->deleted_at.'" onclick="showMdlEdit(this)" class="btn btn-primary btn-sm">
                            <i class="flaticon-381-edit-1"></i>
                        </button>
                        <button onclick="" class="btn btn-primary btn-sm">
                            <i class="flaticon-381-trash-1"></i>
                        </button>
                    '
                );
                array_push($NewData_all, $data);
            }else{
                $data = array(
                    "NO" => $i,
                    "NAME_DISTRICT" => $shop->NAME_DISTRICT,
                    "NAME_SHOP"     => $shop->NAME_SHOP,
                    "OWNER_SHOP"    => $shop->OWNER_SHOP,
                    "TYPE_SHOP"     => $shop->TYPE_SHOP,
                    "ISACTIVE"      => $isActive,
                    "ACTION_BUTTON" => ''
                );
                array_push($NewData_all, $data);
            }
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

    public function update(Request $req){
        $validator = Validator::make($req->all(), [
            'id'        => 'required',
            'shop'      => 'required',
            'owner'     => 'required',
            'detlok'    => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!'
        ]);

        if($validator->fails()){
            return redirect('master/shop')->withErrors($validator);
        }

        $shop                = Shop::find($req->input('id'));
        $shop->NAME_SHOP     = Str::upper($req->input('shop'));
        $shop->OWNER_SHOP     = $req->input('owner');
        $shop->DETLOC_SHOP     = $req->input('detlok');
        $shop->deleted_at    = $req->input('status') == '1' ? NULL : date('Y-m-d H:i:s');
        $shop->save();

        $id_userU = SESSION::get('id_user');
        $log                    = new logmd();
        $log->UPDATED_BY        = $id_userU;
        $log->DETAIL            = 'Updating Shop ' . (string)$req->input('id'); 
        $log->log_time          = now();
        $log->save();   

        return redirect('master/shop')->with('succ_msg', 'Berhasil mengubah data Toko!');
    }
}