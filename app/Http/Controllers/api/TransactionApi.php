<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Transaction;
use App\TransactionDetail;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;

class TransactionApi extends Controller
{
    public function store(Request $req){
        try {
            // dd($req->input('id_user'));
            $validator = Validator::make($req->all(), [
                'id_shop'                   => 'required',
                'id_type'                   => 'required',
                'product.*.id_product'      => 'required',
                'qty_trans'                 => 'required',
                'total_trans'               => 'required',
            ], [
                'required'  => 'Parameter :attribute tidak boleh kosong!',
            ]);
    
            if($validator->fails()){
                return response([
                    "status_code"       => 400,
                    "status_message"    => $validator->errors()->first()
                ], 400);
            }

            $transaction        = new Transaction();
            // $transactionDetail  = new TransactionDetail();

            $transaction->ID_USER           = $req->input('id_user');
            $transaction->ID_SHOP           = $req->input('id_shop');
            $transaction->ID_TYPE           = $req->input('id_type');
            

            $idproduct  = array();
            $qtyproduct = array();

            foreach ($req->input('product') as $item) {
                array_push($idproduct, $item['id_product']);                
                array_push($qtyproduct, $item['qty_product']);  
            }
            
            // $pickup->ID_PRODUCT = implode(';', $idproduct);
            // $pickup->TOTAL_PICKUP = implode(';', $totalpickup);
            // $pickup->REMAININGSTOCK_PICKUP = $pickup->TOTAL_PICKUP;
            $transaction->save();

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
