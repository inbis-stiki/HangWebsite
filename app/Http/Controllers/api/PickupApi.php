<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Pickup;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;

class PickupApi extends Controller
{
    public function store(Request $req){
        try {
            $validator = Validator::make($req->all(), [
                'id_user'                   => 'required',
                // 'id_district'               => 'required',
                // 'id_type'                   => 'required',
                'time'                      => 'required',
                'product.*.id_product'      => 'required',
                'product.*.name_product'    => 'required',
            ], [
                'required'  => 'Parameter :attribute tidak boleh kosong!',
            ]);
    
            if($validator->fails()){
                return response([
                    "status_code"       => 400,
                    "status_message"    => $validator->errors()->first()
                ], 400);
            }

            $pickup = new Pickup();
            $pickup->ID_USER                = $req->input('id_user');
            // $pickup->ID_DISTRICT            = $req->input('id_district');
            // $pickup->DETAILLOKASI_PICKUP    = $req->input('detail_lokasi');
            // $pickup->ID_TYPE                = $req->input('id_type');
            $pickup->TIME_PICKUP            = $req->input('time');

            $idproduct = array();
            $nameproduct = array();
            $totalpickup = array();
            foreach ($req->input('product') as $item) {
                array_push($idproduct, $item['id_product']);
                array_push($nameproduct, $item['name_product']);                
                array_push($totalpickup, $item['total_pickup']);  
            }
            
            $pickup->ID_PRODUCT = implode(';', $idproduct);
            $pickup->NAMEPRODUCT_PICKUP = implode(';', $nameproduct);
            $pickup->TOTAL_PICKUP = implode(';', $totalpickup);
            $pickup->REMAININGSTOCK_PICKUP = $pickup->TOTAL_PICKUP;
            $pickup->save();

            return response([
                "status_code"       => 200,
                "status_message"    => 'Data berhasil disimpan!'
            ], 200);
        } catch (HttpResponseException $exp) {
            return response([
                'status_code'       => $exp->getCode(),
                'status_message'    => $exp->getMessage(),
            ], $exp->getCode());
        }
    }
}