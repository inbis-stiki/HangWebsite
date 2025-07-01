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

            $transactionImage       = new TransactionImage();
            $url_display            = $this->UploadFileR2($req->file('image_display'), 'images');
            $url_lapak              = $this->UploadFileR2($req->file('image_lapak'), 'images');
            $url_arr                = array();

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

            $url_booth = $this->UploadFileR2($req->file('image_booth'), 'images');
            $url_masak   = $this->UploadFileR2($req->file('image_masak'), 'images');
            $url_icip = $this->UploadFileR2($req->file('image_icip'), 'images');
            $url_selling   = $this->UploadFileR2($req->file('image_selling'), 'images');

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
            $url_booth = $this->UploadFileR2($req->file('image_booth'), 'images');
            $url_masak   = $this->UploadFileR2($req->file('image_masak'), 'images');
            $url_icip = $this->UploadFileR2($req->file('image_icip'), 'images');
            $url_selling   = $this->UploadFileR2($req->file('image_selling'), 'images');

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
            $url                   = $this->UploadFileR2($req->file('image'), 'images');

            $cekData    = TransactionImage::select('ID_TI', 'ID_TRANS', 'PHOTO_TI', 'DESCRIPTION_TI')->where([
                ['ID_TRANS', '=', $req->input('id_trans')],
            ])->first();

            if ($cekData != null) {
                $id                                 = $cekData->ID_TI;
                $transactionImage                   = TransactionImage::find($id);
                $transactionImage->PHOTO_TI         = $transactionImage->PHOTO_TI . ";" . $url;
                $transactionImage->DESCRIPTION_TI   = $transactionImage->DESCRIPTION_TI . ";" . $req->input('desc');
                $transactionImage->save();

                return response([
                    "status_code"       => 200,
                    "status_message"    => 'Data berhasil disimpan!',
                    "data"              => ['ID_TI' => $transactionImage->ID_TI]
                ], 200);
            } else {
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

    public function UploadFile($fileData, $folder)
    {
        $extension = $fileData->getClientOriginalExtension();
        $fileName = $fileData->getClientOriginalName();
        $s3 = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
        $bucket = config('filesystems.disks.s3.bucket');

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 10; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        $path = $folder . '/' . hash('sha256', $fileName) . $randomString . '.' . $extension;
        $s3->putObject([
            'Bucket' => $bucket,
            'Key' => $path,
            'SourceFile' => $fileData->path(),
            'ACL' => 'public-read',
            'ContentType' => $fileData->getMimeType(),
            'ContentDisposition' => 'inline; filename="' . $fileName . '"',
        ]);

        return 'https://' . $bucket . '.is3.cloudhost.id/' . $path;
    }

    public function UploadFileR2($fileData, $folder)
    {
        $extension = $fileData->getClientOriginalExtension();
        $fileName = $fileData->getClientOriginalName();

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 10; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        $path = $folder . '/' . hash('sha256', $fileName) . $randomString . '.' . $extension;

        $s3 = Storage::disk('r2')->getDriver()->getAdapter()->getClient();
        $bucket = config('filesystems.disks.r2.bucket');

        $s3->putObject([
            'Bucket' => $bucket,
            'Key' => $path,
            'SourceFile' => $fileData->path(),
            'ACL' => 'public-read',
            'ContentType' => $fileData->getMimeType(),
            'ContentDisposition' => 'inline; filename="' . $fileName . '"',
        ]);
        
        return 'https://finna.yntkts.my.id/' . $path;
    }
}
