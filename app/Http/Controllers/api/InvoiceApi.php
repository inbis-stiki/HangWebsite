<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\TransactionDaily;
use App\Pickup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class InvoiceApi extends Controller
{
    public function listproduct(Request $req){
        try {
            $ID_USER = $req->input("id_user");
            $validator = Validator::make($req->all(), [
                'id_user'          => 'required|string|exists:user,ID_USER'
            ], [
                'required'  => 'Parameter :attribute tidak boleh kosong!',
                'string'    => 'Parameter :attribute harus bertipe string!',
            ]);

            if($validator->fails()){
                return response([
                    "status_code"       => 400,
                    "status_message"    => $validator->errors()->first()
                ], 400);
            }

            $ts = DB::table("transaction")
                ->join('md_type', 'md_type.ID_TYPE', '=', 'transaction.ID_TYPE')
                ->where('transaction.ID_USER', '=', $ID_USER)
                ->whereDate('transaction.DATE_TRANS', '=', date('Y-m-d'))
                ->get();

            $dataT = array();

            foreach($ts as $t){
                $ts_d = DB::table("transaction_detail")
                    ->join('product_price', 'product_price.ID_PRODUCT', '=', 'transaction_detail.ID_PRODUCT')
                    ->join('md_product', 'md_product.ID_PRODUCT', '=', 'transaction_detail.ID_PRODUCT')
                    ->where('transaction_detail.ID_TRANS', '=', $t->ID_TRANS)
                    ->get();

                $THargaDetail = 0;
                $dataTsD = array();

                foreach($ts_d as $tsdData){
                    $THargaDetail += ($tsdData->QTY_TD * $tsdData->PRICE_PP);
                    array_push(
                        $dataTsD,
                        array(
                            "ID_TD" => $tsdData->ID_TD,
                            "ID_PRODUCT" => $tsdData->ID_PRODUCT,
                            "NAME_PRODUCT" => $tsdData->NAME_PRODUCT,
                            "QTY_TD" => $tsdData->QTY_TD,
                            "PRICE_PP" => $tsdData->PRICE_PP
                        )
                    );
                }

                array_push(
                    $dataT,
                    array(
                        "ID_TRANS" => $t->ID_TRANS,
                        "ID_TYPE" => $t->ID_TYPE,
                        "TOT_HARGA_TRANS" => $THargaDetail,
                        "TRANSACTION_DETAIL" =>$dataTsD
                    )
                );
            }

            $productQty = array();
            foreach ($dataT as $item) {
                foreach ($item['TRANSACTION_DETAIL'] as $item2) {
                    if(array_key_exists($item2['ID_PRODUCT'], $productQty)){
                        $productQty[$item2['ID_PRODUCT']]['TOT_QTY_PROD'] += $item2['QTY_TD'];
                    }else{
                        $productQty[$item2['ID_PRODUCT']] = array(
                            "NAME_PRODUCT" => $item2['NAME_PRODUCT'],
                            "TOT_QTY_PROD" => $item2['QTY_TD']
                        );
                    }
                }
            }

            $THargaFaktur = 0;
            $typetr = "";
            foreach($dataT as $Faktur){
                $THargaFaktur += $Faktur['TOT_HARGA_TRANS'];
                if ($Faktur['ID_TYPE'] != 1) {
                    $typetr = $Faktur['ID_TYPE'];
                }else{
                    $typetr = 1;
                }
            }
            
            $dataFaktur = array();
            array_push(
                $dataFaktur,
                array(
                    "TOT_HARGA_FAKTUR" => $THargaFaktur,
                    "ID_TYPE" => $typetr,
                    "TOTAL_QTY_FAKTUR" => $productQty,
                    "FAKTUR" => $dataT
                )
            );
            
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
            $ID_USER = $req->input("id_user");
            
            $ID_LOCATION = DB::table("user")
                ->select('md_area.NAME_AREA', 'md_regional.NAME_REGIONAL', 'md_location.NAME_LOCATION')
                ->join('md_area', 'md_area.ID_AREA', '=', 'user.ID_AREA')
                ->join('md_regional', 'md_regional.ID_REGIONAL', '=', 'user.ID_REGIONAL')
                ->JOIN('md_location', 'md_location.ID_LOCATION', '=', 'user.ID_LOCATION')
                ->where('ID_USER', '=', ''.$ID_USER.'')
                ->first();

            $validator = Validator::make($req->all(), [
                'id_user'          => 'required|string|exists:user,ID_USER',
                'id_type'          => 'required|numeric',
                'photo_faktur'     => 'required|image',
                'total_qty'        => 'required|numeric',
                'total_hrg'        => 'required|numeric'
            ], [
                'required'  => 'Parameter :attribute tidak boleh kosong!',
                'string'    => 'Parameter :attribute harus bertipe string!',
                'numeric'   => 'Parameter :attribute harus bertipe angka!',
                'image'     => 'Paramater :attribute harus bertipe gambar!'
            ]);
    
            if($validator->fails()){
                return response([
                    "status_code"       => 400,
                    "status_message"    => $validator->errors()->first()
                ], 400);
            }

            date_default_timezone_set("Asia/Bangkok");
            $path = $req->file('photo_faktur')->store('images', 's3');

            $cekPickup = Pickup::where('ID_USER', '=', ''.$ID_USER.'')
                ->whereDate('TIME_PICKUP', '=', date('Y-m-d'))
                ->first();

            $cek = TransactionDaily::where('ID_USER', '=', ''.$ID_USER.'')
                ->where('ISFINISHED_TD', '=', '0')
                ->whereDate('DATE_TD', '=', date('Y-m-d'))
                ->first();

            if ($cek == null) {
                return response([
                    "status_code"       => 200,
                    "status_message"    => 'Anda sudah mengirimkan faktur'
                ], 200);
            }else{
                $TransactionDaily = TransactionDaily::find($cek->ID_TD);
                $TransactionDaily->ID_TYPE         = $req->input('id_type');
                $TransactionDaily->LOCATION_TD     = $ID_LOCATION->NAME_LOCATION;
                $TransactionDaily->AREA_TD         = $ID_LOCATION->NAME_AREA;
                $TransactionDaily->TOTQTY_TD       = $req->input('total_qty');
                $TransactionDaily->TOTAL_TD        = $req->input('total_hrg');
                $TransactionDaily->DATEFACTUR_TD   = date('Y-m-d H:i:s');
                $TransactionDaily->FACTUR_TD       = Storage::disk('s3')->url($path);
                $TransactionDaily->REGIONAL_TD     = $ID_LOCATION->NAME_LOCATION;
                $TransactionDaily->ISFINISHED_TD   = '1';
                $TransactionDaily->save();
                
                $Pickup = Pickup::find($cekPickup->ID_PICKUP);
                $Pickup->ISFINISHED_PICKUP         = '1';
                $Pickup->save();
    
                return response([
                    "status_code"       => 200,
                    "status_message"    => 'Data berhasil diupdate!',
                    "data"              => ['ID_TD' => $TransactionDaily->ID_TD], ['ID_PICKUP' => $Pickup->ID_PICKUP]
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