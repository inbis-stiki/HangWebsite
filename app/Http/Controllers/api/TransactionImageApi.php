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
    public function store(Request $req)
    {
        try {
            date_default_timezone_set("Asia/Bangkok");
            $validator = Validator::make($req->all(), [
                'id_trans'                  => 'required|exists:transaction,ID_TRANS',
                'image_display'             => 'required|image',
                'image_lapak'               => 'required|image',
            ], [
                'required'  => 'Parameter :attribute tidak boleh kosong!',
            ]);

            if ($validator->fails()) {
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

    public function ublp(Request $req)
    {
        try {
            date_default_timezone_set("Asia/Bangkok");
            $validator = Validator::make($req->all(), [
                'id_trans'                  => 'required|exists:transaction,ID_TRANS',
                'image_booth'               => 'required|image',
                'image_masak'               => 'required|image',
                'image_icip'                => 'required|image',
                'image_selling'             => 'required|image',
                'desc_booth'                => 'required',
                'desc_masak'                => 'required',
                'desc_icip'                 => 'required',
                'desc_selling'              => 'required',
            ], [
                'required'  => 'Parameter :attribute tidak boleh kosong!',
            ]);

            if ($validator->fails()) {
                return response([
                    "status_code"       => 400,
                    "status_message"    => $validator->errors()->first()
                ], 400);
            }

            $transactionImage        = new TransactionImage();
            $path_booth = $req->file('image_booth')->store('images', 's3');
            $path_masak   = $req->file('image_masak')->store('images', 's3');
            $path_icip = $req->file('image_icip')->store('images', 's3');
            $path_selling   = $req->file('image_selling')->store('images', 's3');

            $url_booth = Storage::disk('s3')->url($path_booth);
            $url_masak   = Storage::disk('s3')->url($path_masak);
            $url_icip = Storage::disk('s3')->url($path_icip);
            $url_selling   = Storage::disk('s3')->url($path_selling);
            
            $url_arr         = array();
            array_push($url_arr, $url_booth);
            array_push($url_arr, $url_masak);
            array_push($url_arr, $url_icip);
            array_push($url_arr, $url_selling);
            $url = implode(";", $url_arr);

            $desc_arr = array();
            array_push($desc_arr, $req->input('desc_booth'));
            array_push($desc_arr, $req->input('desc_masak'));
            array_push($desc_arr, $req->input('desc_icip'));
            array_push($desc_arr, $req->input('desc_selling'));
            $desc = implode(";", $desc_arr);

            $transactionImage->ID_TRANS           = $req->input('id_trans');
            $transactionImage->PHOTO_TI           = $url;
            $transactionImage->DESCRIPTION_TI     = $desc;
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

    public function ubImage(Request $req)
    {
        try {
            date_default_timezone_set("Asia/Bangkok");
            $validator = Validator::make($req->all(), [
                'id_trans'                  => 'required|exists:transaction,ID_TRANS',
                'image_booth'               => 'required|image',
                'image_masak'               => 'required|image',
                'image_icip'                => 'required|image',
                'image_selling'             => 'required|image',
                'desc_booth'                => 'required',
                'desc_masak'                => 'required',
                'desc_icip'                 => 'required',
                'desc_selling'              => 'required',
            ], [
                'required'  => 'Parameter :attribute tidak boleh kosong!',
            ]);

            if ($validator->fails()) {
                return response([
                    "status_code"       => 400,
                    "status_message"    => $validator->errors()->first()
                ], 400);
            }

            $transactionImage        = new TransactionImage();
            $path_booth = $req->file('image_booth')->store('images', 's3');
            $path_masak   = $req->file('image_masak')->store('images', 's3');
            $path_icip   = $req->file('image_icip')->store('images', 's3');
            $path_selling   = $req->file('image_selling')->store('images', 's3');

            $url_booth      = Storage::disk('s3')->url($path_booth);
            $url_masak      = Storage::disk('s3')->url($path_masak);
            $url_icip       = Storage::disk('s3')->url($path_icip);
            $url_selling    = Storage::disk('s3')->url($path_selling);

            $url_arr         = array();
            array_push($url_arr, $url_booth);
            array_push($url_arr, $url_masak);
            array_push($url_arr, $url_icip);
            array_push($url_arr, $url_selling);

            $url = implode(";", $url_arr);

            $desc_arr = array();
            array_push($desc_arr, $req->input('desc_booth'));
            array_push($desc_arr, $req->input('desc_masak'));
            array_push($desc_arr, $req->input('desc_icip'));
            array_push($desc_arr, $req->input('desc_selling'));

            $desc = implode(";", $desc_arr);

            $transactionImage->ID_TRANS           = $req->input('id_trans');
            $transactionImage->PHOTO_TI           = $url;
            $transactionImage->DESCRIPTION_TI     = $desc;
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

    public function newImage(Request $req)
    {
        try {
            date_default_timezone_set("Asia/Bangkok");
            $validator = Validator::make($req->all(), [
                'id_trans'                  => 'required|exists:transaction,ID_TRANS',
                'image'                     => 'required|image',
                'desc'                      => 'required',
            ], [
                'required'  => 'Parameter :attribute tidak boleh kosong!',
            ]);

            if ($validator->fails()) {
                return response([
                    "status_code"       => 400,
                    "status_message"    => $validator->errors()->first()
                ], 400);
            }

            $transactionImage       = new TransactionImage();
            $path                   = $req->file('image')->store('images', 's3');
            $url                    = Storage::disk('s3')->url($path);

            $cekData    = TransactionImage::select('ID_TI','ID_TRANS', 'PHOTO_TI', 'DESCRIPTION_TI')->where([
                        ['ID_TRANS', '=', $req->input('id_trans')],
                        ])->get();            
            
            if ($cekData != null) {
                $id                                 = $cekData->first()->ID_TI;
                $transactionImage                   = TransactionImage::find($id);
                $transactionImage->PHOTO_TI         = $transactionImage->PHOTO_TI.";".$url;
                $transactionImage->DESCRIPTION_TI   = $transactionImage->DESCRIPTION_TI.";".$req->input('desc');
                $transactionImage->save();

                return response([
                    "status_code"       => 200,
                    "status_message"    => 'Data berhasil disimpan!',
                    "data"              => ['ID_TI' => $transactionImage->ID_TI]
                ], 200);
            }else{
                $transactionImage->ID_TRANS           = $req->input('id_trans');
                $transactionImage->PHOTO_TI           = $url;
                $transactionImage->DESCRIPTION_TI     = $req->input('desc');
                $transactionImage->DATE_TI            = date('Y-m-d H:i:s');
                $transactionImage->save();

                return response([
                    "status_code"       => 200,
                    "status_message"    => 'Data berhasil disimpan!',
                    "data"              => ['ID_TI' => $transactionImage->ID_TI]
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
