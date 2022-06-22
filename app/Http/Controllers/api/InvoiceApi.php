<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\TransactionDaily;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class InvoiceApi extends Controller
{
    public function listproduct(Request $req){
        try {
            $id_regional = $req->input("id_regional");

            $dataT = array();
            $ts = DB::table("transaction")
                ->join('md_type', 'md_type.ID_TYPE', '=', 'transaction.ID_TYPE')
                ->get();

            foreach($ts as $t){
                $ts_d = DB::table("transaction_detail")
                    ->join('product_price', 'product_price.ID_PRODUCT', '=', 'transaction_detail.ID_PRODUCT')
                    ->join('md_product', 'md_product.ID_PRODUCT', '=', 'transaction_detail.ID_PRODUCT')
                    ->where('transaction_detail.ID_TRANS', '=', $t->ID_TRANS)
                    ->get();

                $dataTsD = array();
                $THargaDetail = 0;

                foreach($ts_d as $tsdData){
                    $THargaDetail += ($tsdData->QTY_TD * $tsdData->PRICE_PP);
                    array_push(
                        $dataTsD,
                        array(
                            "ID_TD" => $tsdData->ID_TD,
                            "NAME_PRODUCT" => $tsdData->NAME_PRODUCT,
                            "QTY_TD" => $tsdData->QTY_TD,
                            "PRICE_PP" => $tsdData->PRICE_PP,
                            "THARGA_DETAIL" => $THargaDetail
                        )
                    );
                }

                array_push(
                    $dataT,
                    array(
                        "ID_TRANS" => $t->ID_TRANS,
                        "TRANSACTION DETAIL" =>$dataTsD
                    )
                );
            }
            
            $dataFaktur = array();
            // foreach()

            
            return response([
                'status_code'       => 200,
                'status_message'    => 'Data berhasil diambil!',
                'data'              => $dataFaktur
            ], 200);
         } catch (Exception $exp) {
             return response([
                 'status_code'       => 500,
                 'status_message'    => $exp->getMessage(),
             ], 500);
         }
    }

    
    public function storedata(Request $req){
        try{
            $ID_USER = $req->get("id_user");
            $ID_AREA = $req->get("id_area");
            $ID_REGIONAL = $req->get("id_regional");
            $ID_LOCATION = $req->get("id_location");

            date_default_timezone_set("Asia/Bangkok");
            $path = $req->file('photo_shop')->store('images', 's3');

            $cek = TransactionDaily::where('ID_USER', '=', ''.$id_user.'')
                ->where('DATE_TD', '=', date('Y-m-d'))
                ->exists();
                
            if ($cek == true) {
                return response([
                    "status_code"       => 200,
                    "status_message"    => 'Anda sudah mengirimkan faktur'
                ], 200);
            }else{
                $TransactionDaily = new TransactionDaily();
                $TransactionDaily->ID_USER         = $ID_USER;
                $TransactionDaily->ID_SHOP         = $req->input('id_shop');
                $TransactionDaily->ID_TYPE         = $req->input('id_type');
                $TransactionDaily->LOCATION_TD     = $ID_LOCATION;
                $TransactionDaily->AREA_TD         = $ID_AREA;
                $TransactionDaily->TOTQTY_TD       = $req->input('total_qty');
                $TransactionDaily->TOTAL_TD        = $req->input('total_hrg');
                $TransactionDaily->DATE_TD         = date('Y-m-d H:i:s');
                $TransactionDaily->FACTUR_TD       = Storage::disk('s3')->url($path);
                $TransactionDaily->REGIONAL_TD     = $ID_REGIONAL;
                $TransactionDaily->save();
    
                return response([
                    "status_code"       => 200,
                    "status_message"    => 'Data berhasil disimpan!',
                    "data"              => ['ID_TD' => $TransactionDaily->ID_TD]
                ], 200);
            }
        } catch (HttpResponseException $exp) {
            return response([
                'status_code'       => $exp->getCode(),
                'status_message'    => $exp->getMessage(),
            ], $exp->getCode());
        }
    }
}