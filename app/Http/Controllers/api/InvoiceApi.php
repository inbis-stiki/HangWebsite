<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\TransactionDaily;
use App\Pickup;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class InvoiceApi extends Controller
{
    public function listproduct(Request $req){
        try {
            $ID_USER = $req->input("id_user");
            $ID_REGIONAL = $req->input("id_regional");
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
                    ->where('product_price.ID_REGIONAL', '=', $ID_REGIONAL)
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
            
            $TotalQty = array();
            foreach ($dataT as $item) {
                foreach ($item['TRANSACTION_DETAIL'] as $item2) {
                    if(array_key_exists($item2['ID_PRODUCT'], $TotalQty)){
                        $TotalQty[$item2['ID_PRODUCT']]['TOTAL'] += $item2['QTY_TD'];
                    }else{
                        $TotalQty[$item2['ID_PRODUCT']] = array(
                            "NAME_PRODUCT" => $item2['NAME_PRODUCT'],
                            "TOTAL" => $item2['QTY_TD']
                        );
                    }
                }
            }

            $productQty = array();
            foreach($TotalQty as $Data3){
                array_push(
                    $productQty,
                    array(
                        "NAME_PRODUCT" => $Data3['NAME_PRODUCT'],
                        "TOT_QTY_PROD" => $Data3['TOTAL']
                    )
                );
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
            $ID_USER = $req->input('id_user');
            
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
                'photo_faktur_1'   => 'required|image',
                'photo_faktur_2'   => 'required|image',
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
            $path_faktur1 = $req->file('photo_faktur_1')->store('images', 's3');
            $path_faktur2 = $req->file('photo_faktur_2')->store('images', 's3');

            $url_faktur1   = Storage::disk('s3')->url($path_faktur1);
            $url_faktur2   = Storage::disk('s3')->url($path_faktur2);
            $url_array     = array();

            array_push($url_array, $url_faktur1);
            array_push($url_array, $url_faktur2);
            $url = implode(";", $url_array);

            $cekPickup = Pickup::where([
                            ['ID_USER', '=', $ID_USER]
                        ])
                        ->latest('ID_PICKUP')->first();

            $cek = TransactionDaily::where('ID_USER', '=', ''.$ID_USER.'')
                ->where('ISFINISHED_TD', '=', '0')
                ->latest('ID_TD')->first();

            $cektd = TransactionDaily::where('ID_USER', '=', ''.$ID_USER.'')
                ->latest('ID_TD')->first();
            
            if ($cektd['ISFINISHED_TD'] == '1') {
                return response([
                    "status_code"       => 200,
                    "status_message"    => 'Anda sudah mengirimkan faktur'
                ], 200);
            }else{
                if ($cekPickup == null || $cekPickup['ISFINISHED_PICKUP'] == '1'){
                    return response([
                        "status_code"       => 200,
                        "status_message"    => 'Anda belum pickup'
                    ], 200);
                }else {
                    $TransactionDaily = TransactionDaily::find($cek->ID_TD);
                    $TransactionDaily->ID_TYPE         = $req->input('id_type');
                    $TransactionDaily->LOCATION_TD     = $ID_LOCATION->NAME_LOCATION;
                    $TransactionDaily->AREA_TD         = $ID_LOCATION->NAME_AREA;
                    $TransactionDaily->TOTQTY_TD       = $req->input('total_qty');
                    $TransactionDaily->TOTAL_TD        = $req->input('total_hrg');
                    $TransactionDaily->DATEFACTUR_TD   = date('Y-m-d H:i:s');
                    $TransactionDaily->FACTUR_TD       = $url;
                    $TransactionDaily->REGIONAL_TD     = $ID_LOCATION->NAME_LOCATION;
                    $TransactionDaily->ISFINISHED_TD   = '1';
                    $TransactionDaily->save();
                    
                    $Pickup = Pickup::find($cekPickup->ID_PICKUP);
                    $Pickup->ISFINISHED_PICKUP         = '1';
                    $Pickup->save();
        
                    return response([
                        "status_code"       => 200,
                        "status_message"    => 'Data berhasil diupdate!',
                        "data"              => [
                            'ID_TD' => $TransactionDaily->ID_TD, 
                            'ID_PICKUP' => $Pickup->ID_PICKUP]
                    ], 200);
                }
            }
        } catch (HttpResponseException $exp) {
            return response([
                'status_code'       => $exp->getCode(),
                'status_message'    => $exp->getMessage(),
            ], $exp->getCode());
        }
    }
    
    public function cekFaktur(Request $req){
        try {
            $ID_USER = $req->input("id_user");

            $cektd = TransactionDaily::where([
                ['ID_USER', '=', $req->input('id_user')]
            ])->latest('ID_TD')->first();

            $td = [];
            if($cektd == null){
                $msg        = 'Anda belum Pickup Barang!';
                $success    = 0;
            }else{
                if ($cektd['ISFINISHED_TD'] == 0) {
                    $msg        = 'Anda bisa input faktur!';
                    $success    = 1;
                    $td         = [];
                }else{
                    $msg        = 'Anda sudah Mengirimkan Faktur!';
                    $success    = 0;
                    $td         = $cektd;
                }
            }

            return response([
                'status_code'       => 200,
                'status_message'    => $msg,
                'status_success'    => $success,
                'data'              => $td
            ], 200);
        } catch (Exception $exp) {
            return response([
                'status_code'       => 500,
                'status_message'    => $exp->getMessage(),
            ], 500);
        }
    }
}