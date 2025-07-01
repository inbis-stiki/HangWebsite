<?php

namespace App\Http\Controllers;

use App\Shop;
use App\logmd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            ->select("md_district.NAME_DISTRICT", "md_shop.ID_SHOP", "md_shop.NAME_SHOP", "md_shop.OWNER_SHOP", "md_shop.DETLOC_SHOP", "md_shop.TYPE_SHOP", "md_shop.deleted_at", "md_shop.PHOTO_SHOP")
            ->orWhere('md_district.NAME_DISTRICT', 'like', '%'.$search.'%')
            ->orWhere('md_shop.NAME_SHOP', 'like', '%'.$search.'%')
            ->orWhere('md_shop.OWNER_SHOP', 'like', '%'.$search.'%')
            ->orWhere('md_shop.TYPE_SHOP', 'like', '%'.$search.'%')
            ->offset($offset)
            ->limit($limit)
            ->get();

        if($search != ''){
            $filteredSearch = Shop::join('md_district', 'md_shop.ID_DISTRICT', '=', 'md_district.ID_DISTRICT')
            ->select("md_district.NAME_DISTRICT", "md_shop.NAME_SHOP", "md_shop.OWNER_SHOP", "md_shop.TYPE_SHOP", "md_shop.deleted_at", "md_shop.PHOTO_SHOP")
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

            if($role == 1 || $role == 2 || $role == 99){
                
                $data = array(
                    "NO" => $i,
                    "NAME_DISTRICT" => $shop->NAME_DISTRICT,
                    "NAME_SHOP"     => $shop->NAME_SHOP,
                    "OWNER_SHOP"    => $shop->OWNER_SHOP,
                    "TYPE_SHOP"     => $shop->TYPE_SHOP,
                    "PHOTO_SHOP"    => $shop->PHOTO_SHOP,
                    "ISACTIVE"      => $isActive,
                    "ACTION_BUTTON" => '
                        <button data-id="'.$shop->ID_SHOP.'" data-name="'.$shop->NAME_SHOP.'" data-own="'.$shop->OWNER_SHOP.'" data-lok="'.$shop->DETLOC_SHOP.'" data-del="'.$shop->deleted_at.'" data-shoptype="'.$shop->TYPE_SHOP.'" data-shopphoto="'.$shop->PHOTO_SHOP.'" onclick="showMdlEdit(this)" class="btn btn-primary btn-sm">
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
            'shoptype'    => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!'
        ]);

        if($validator->fails()){
            return redirect('master/shop')->withErrors($validator);
        }

        $shop                = Shop::find($req->input('id'));

        $oldValues = $shop->getOriginal();

        $shop->NAME_SHOP     = Str::upper($req->input('shop'));
        $shop->OWNER_SHOP     = $req->input('owner');
        $shop->DETLOC_SHOP     = $req->input('detlok');
        $shop->TYPE_SHOP     = $req->input('shoptype');
        $shop->deleted_at    = $req->input('status') == '1' ? NULL : date('Y-m-d H:i:s');
        $shop->save();

        $changedFields = array_keys($shop->getDirty());
        $shop->save();

        $newValues = [];
        foreach($changedFields as $field) {
            $newValues[$field] = $shop->getAttribute($field);
        }

        $id_userU = SESSION::get('id_user');

        if (!empty($newValues)) {
            DB::table('log_md')->insert([
                'UPDATED_BY' => $id_userU,
                'DETAIL' => 'Updating Shop ' . (string)$req->input('id'),
                'OLD_VALUES' => json_encode(array_intersect_key($oldValues, $newValues)),
                'NEW_VALUES' => json_encode($newValues),
                'log_time' => now(),
            ]);
        }     

        return redirect('master/shop')->with('succ_msg', 'Berhasil mengubah data Toko!');
    }

    public function ShopListByDistrict()
    {
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
        $query = DB::select(
            DB::raw("
            SELECT 
            ms.NAME_SHOP, 
            md.NAME_DISTRICT, 
            ma.NAME_AREA,
            COUNT(ms.ID_SHOP) AS TOT_SHOP
          FROM 
            md_shop ms 
            LEFT JOIN md_district md ON md.ID_DISTRICT = ms.ID_DISTRICT 
            LEFT JOIN md_area ma ON ma.ID_AREA = md.ID_AREA
            LEFT JOIN md_regional mr ON mr.ID_REGIONAL = ma.ID_REGIONAL 
          GROUP BY 
            ms.NAME_SHOP, 
            ms.ID_DISTRICT 
          HAVING
            TOT_SHOP > 6    
          ORDER BY 
            TOT_SHOP DESC, 
            ms.NAME_SHOP ASC
            ")
        );
        $i=0;
        $counter=0;
        foreach ($query as $key) {
            $limit = $key->TOT_SHOP -1;
            $counter=$counter+$limit;
            echo $key->NAME_SHOP." - ";
            echo $key->NAME_AREA." - ";
            echo $key->NAME_DISTRICT." - ";
            echo $key->TOT_SHOP." - ";
            echo $limit."</br>";
            DB::statement("
            update md_shop ms 
            join md_district md on ms.ID_DISTRICT=md.ID_DISTRICT 
            join md_area ma on ma.ID_AREA=md.ID_AREA 
            set ms.deleted_at=NOW() 
            where md.NAME_DISTRICT='".$key->NAME_DISTRICT."' and ma.NAME_AREA = '".$key->NAME_AREA."' and ms.NAME_SHOP = '".$key->NAME_SHOP."' LIMIT ". $limit.";");
        }

        echo $counter;
    }
}