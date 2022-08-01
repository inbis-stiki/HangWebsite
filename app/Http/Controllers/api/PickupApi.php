<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Pickup;
use App\TransactionDaily;
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
                'product.*.id_product'      => 'required|exists:md_product,ID_PRODUCT',
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
            $pickup->TIME_PICKUP            = date("Y-m-d");

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

            $transDaily = new TransactionDaily();
            $transDaily->ID_USER = $req->input('id_user');
            $transDaily->DATE_TD = date("Y-m-d H:i:s");
            $transDaily->save();

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

    public function pickup(Request $req){
        try {
            $pick = Pickup::select('ID_PICKUP', 'ID_PRODUCT','REMAININGSTOCK_PICKUP')
            ->where([
                ['ID_USER', '=', $req->input('id_user')],
                ['ISFINISHED_PICKUP', '=', 0]
            ])->latest('ID_PICKUP')->first();
            
            if($pick == null){
                return response([
                    'status_code'       => 200,
                    'status_message'    => 'User belum pickup!',
                    'data'              => []
                ], 200);
            }

            $arrId = explode(";", $pick->ID_PRODUCT);
            $arrRemain =explode(";", $pick->REMAININGSTOCK_PICKUP);

            $products = array();
            foreach ($arrId as $key => $value) {
                $product = array('id_product'=>$value, 'qty_product'=>$arrRemain[$key]);
                array_push($products, $product);
            }

            return response([
                'status_code'       => 200,
                'status_message'    => 'Data berhasil diambil!',
                'data'              => [
                    "id_pickup" => $pick->ID_PICKUP,
                    "product"   => $products
                    ]
            ], 200);
        } catch (Exception $exp) {
            return response([
                'status_code'       => 500,
                'status_message'    => $exp->getMessage(),
            ], 500);
        }
    }

    public function cekPickup(Request $req){
        try {
            $cekData = Pickup::select('ID_PICKUP', 'ID_PRODUCT','REMAININGSTOCK_PICKUP')
            ->where([
                ['ID_USER', '=', $req->input('id_user')],
                ['ISFINISHED_PICKUP', '=', 0]
            ])->latest('ID_PICKUP')->first();
            
            if ($cekData == null) {
                return response([
                    'status_code'       => 200,
                    'status_message'    => 'User belum pickup!',
                    'status_success'    => 1,
                    'data'              => []
                ], 200);
            }else{
                $pick = Pickup::where([
                    ['ID_USER', '=', $req->input('id_user')]
                ])
                ->whereDate('TIME_PICKUP', '<', date('Y-m-d'))
                ->latest('ID_PICKUP')->first();
                
                $cekPick = Pickup::where([
                    ['ID_USER', '=', $req->input('id_user')]
                ])
                ->whereDate('TIME_PICKUP', '=', date('Y-m-d'))
                ->latest('ID_PICKUP')->first();


                $success = "";
                if (empty($pick) || $pick->ISFINISHED_PICKUP == 0) {
                    $msg        = 'Data terakhir pickup!';
                    $success    = 0;
                    $pickup     = $pick;
                }else{
                    if ($cekPick == null) {
                        $msg    = 'Anda bisa pickup barang!';
                        $success    = 1;
                        $pickup = [];
                    }else{
                        $msg    = 'Anda sudah pickup hari ini!';
                        $success    = 0;
                        $pickup = $cekPick;
                    }
                }
    
                return response([
                    'status_code'       => 200,
                    'status_message'    => $msg,
                    'status_success'    => $success,
                    'data'              => $pickup
                ], 200);
            }
        } catch (Exception $exp) {
            return response([
                'status_code'       => 500,
                'status_message'    => $exp->getMessage(),
            ], 500);
        }
    }
}