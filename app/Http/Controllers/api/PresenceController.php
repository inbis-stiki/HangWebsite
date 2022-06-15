<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Presence;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PresenceController extends Controller
{
    public function index(){
        try {
            $presence = Presence::all();
            return response([
                'status_code'       => 200,
                'status_message'    => 'Data berhasil diambil!',
                'data'              => $presence
            ], 200);
        } catch (Exception $exp) {
            return response([
                'status_code'       => 500,
                'status_message'    => $exp->getMessage(),
            ], 500);
        }
    }

    public function detail($id){
        try {
            $presence = Presence::find($id);
            if($presence == null){
                return response([
                    'status_code'       => 200,
                    'status_message'    => 'Data tidak ditemukan!',
                ], 200);
            }else{
                return response([
                    'status_code'       => 200,
                    'status_message'    => 'Data berhasil ditemukan!',
                    'data'              => $presence
                ], 200);
            }
        } catch (Exception $exp) {
            return response([
                'status_code'       => 500,
                'status_message'    => $exp->getMessage(),
            ], 500);
        }
    }

    public function store(Request $req){
        try {
            $validator = Validator::make($req->all(), [
                'id_user'          => 'required|string|exists:user,ID_USER',
                'id_type'          => 'required|numeric|exists:md_type,ID_TYPE',
                'image'             => 'required|image'
            ], [
                'required'  => 'Parameter :attribute tidak boleh kosong!',
                'string'    => 'Parameter :attribute harus bertipe string!',
                'numeric'   => 'Parameter :attribute harus bertipe angka!',
                'exists'    => 'Parameter :attribute tidak ditemukan!',
            ]);
    
            if($validator->fails()){
                return response([
                    "status_code"       => 400,
                    "status_message"    => $validator->errors()->first()
                ], 400);
            }
            date_default_timezone_set("Asia/Bangkok");
            $path = $req->file('image')->store('images', 's3');

            $presence = new Presence();
            $presence->ID_USER              = $req->input('id_user');
            $presence->ID_TYPE              = $req->input('id_type');
            $presence->PHOTO_PRESENCE       = Storage::disk('s3')->url($path);
            $presence->DATE_PRESENCE        = date('Y-m-d H:i:s');
            $presence->save();

            return response([
                "status_code"       => 200,
                "status_message"    => 'Data berhasil disimpan!',
                "data"              => ['ID_PRESENCE' => $presence->ID_PRESENCE]
            ], 200);
        } catch (HttpResponseException $exp) {
            return response([
                'status_code'       => $exp->getCode(),
                'status_message'    => $exp->getMessage(),
            ], $exp->getCode());
        }
    }
}
