<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TransactionImage;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class TransactionImageApi extends Controller
{
    public function store(Request $req){
        try {
            date_default_timezone_set("Asia/Bangkok");
            $validator = Validator::make($req->all(), [
                'id_trans'                  => 'required|exists:transaction,ID_TRANS',
                'image_display'             => 'required|image',
                'image_lapak'               => 'required|image',
            ], [
                'required'  => 'Parameter :attribute tidak boleh kosong!',
            ]);
    
            if($validator->fails()){
                return response([
                    "status_code"       => 400,
                    "status_message"    => $validator->errors()->first()
                ], 400);
            }

            $transactionImage        = new TransactionImage();
            $path_display = $req->file('image_display')->store('images', 's3');
            $path_lapak   = $req->file('image_lapak')->store('images', 's3');

            $url_display = Storage::disk('s3')->url($path_display);
            $url_lapak   = Storage::disk('s3')->url($path_lapak);
            $url_arr         = array();
            
            array_push($url_arr, $url_display);
            array_push($url_arr, $url_lapak);
            $url = implode(";", $url_arr);

            $transactionImage->ID_TRANS           = $req->input('id_trans');
            $transactionImage->PHOTO_TI           = $url;
            $transactionImage->DATE_TI            = date('Y-m-d H:i:s');
            $transactionImage->save();
        
            return response([
                "status_code"       => 200,
                "status_message"    => 'Data berhasil disimpan!',
                "data"              => ['ID_TRANS' => $transactionImage->ID_TI]
            ], 200);
        } catch (HttpResponseException $exp) {
            return response([
                'status_code'       => $exp->getCode(),
                'status_message'    => $exp->getMessage(),
            ], $exp->getCode());
        }
    }
}
