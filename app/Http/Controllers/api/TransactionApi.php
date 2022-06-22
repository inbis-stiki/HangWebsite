<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Transaction;
use App\TransactionDetail;
use App\Location;
use App\Regional;
use App\Area;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;
use League\Flysystem\Adapter\Local;

class TransactionApi extends Controller
{
    public function store(Request $req){
        try {
            date_default_timezone_set("Asia/Bangkok");
            $validator = Validator::make($req->all(), [
                'id_shop'                   => 'required',
                'id_type'                   => 'required',
                'product.*.id_product'      => 'required|exists:md_product,ID_PRODUCT',
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
            $transactionDetail  = new TransactionDetail();
            $location           = new Location();
            $regional           = new Regional();
            $area               = new Area();

            $unik                           = md5($req->input('id_user')."_".date('Y-m-d H:i:s'));
            $transaction->ID_TRANS          = "TRANS_".$unik;
            $transaction->ID_USER           = $req->input('id_user');
            $transaction->ID_SHOP           = $req->input('id_shop');
            $transaction->ID_TYPE           = $req->input('id_type');
            $transaction->LOCATION_TRANS    = $location::select('NAME_LOCATION')->where('ID_LOCATION', $req->input('id_location'))->first()->NAME_LOCATION;
            $transaction->REGIONAL_TRANS    = $regional::select('NAME_REGIONAL')->where('ID_REGIONAL', $req->input('id_regional'))->first()->NAME_REGIONAL;
            $transaction->QTY_TRANS     = $req->input('qty_trans');
            $transaction->TOTAL_TRANS   = $req->input('total_trans');
            $transaction->DATE_TRANS    = date('Y-m-d H:i:s');
            $transaction->AREA_TRANS    = $area::select('NAME_AREA')->where('ID_AREA', $req->input('id_area'))->first()->NAME_AREA;
            $transaction->save();

            foreach ($req->input('product') as $item) {
                TransactionDetail::insert([
                    [
                        'ID_TRANS'      => "TRANS_".$unik,
                        'ID_SHOP'       => $req->input('id_shop'),
                        'ID_PRODUCT'    => $item['id_product'],
                        'QTY_TD'        => $item['qty_product'],
                        'DATE_TD'       => date('Y-m-d H:i:s'),
                    ]
                ]);
            }
        
            return response([
                "status_code"       => 200,
                "status_message"    => 'Data berhasil disimpan!',
                "data"              => ['ID_TRANS' => $transaction->ID_TRANS]
            ], 200);
        } catch (HttpResponseException $exp) {
            return response([
                'status_code'       => $exp->getCode(),
                'status_message'    => $exp->getMessage(),
            ], $exp->getCode());
        }
    }
}
