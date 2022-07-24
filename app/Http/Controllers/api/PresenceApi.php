<?php

namespace App\Http\Controllers\api;

use App\District;
use App\Http\Controllers\Controller;
use App\Presence;
use App\TransactionDaily;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PresenceApi extends Controller
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

    public function detail(Request $req){
        date_default_timezone_set("Asia/Bangkok");
        try {
            $idUser = $req->input("id_user");
            $presence = Presence::whereDate('DATE_PRESENCE', date('Y-m-d'))->where('ID_USER',  $idUser)->get();

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
                'kecamatan'        => 'required|string|exists:md_district,NAME_DISTRICT',
                'longitude'        => 'required|string',
                'latitude'         => 'required|string',
                'id_type'          => 'required|numeric|exists:md_type,ID_TYPE',
                'image'            => 'required|image'
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

            $cek = Presence::where('ID_USER', '=', ''.$req->input('id_user').'')
            ->where('ID_TYPE', '=', $req->input('id_type'))
            ->whereDate('DATE_PRESENCE', '=', date('Y-m-d'))
            ->exists();

            $idDistrik = District::select("ID_DISTRICT")
            ->where([
                ['NAME_DISTRICT', '=', $req->input('kecamatan')],
                ['ISMARKET_DISTRICT', '=', '0']
            ])->whereNull('deleted_at')->first();
            
            if ($idDistrik == null) {
                return response([
                    "status_code"       => 200,
                    "status_message"    => 'Distrik tidak tersedia'
                ], 200);
            }

            if ($cek == true) {
                return response([
                    "status_code"       => 200,
                    "status_message"    => 'Anda sudah absen'
                ], 200);
            }else{
                $presence = new Presence();
                $presence->ID_USER              = $req->input('id_user');
                $presence->ID_TYPE              = $req->input('id_type');
                $presence->ID_DISTRICT          = $idDistrik->ID_DISTRICT;
                $presence->LONG_PRESENCE        = $req->input('longitude');
                $presence->LAT_PRESENCE         = $req->input('latitude');
                $presence->PHOTO_PRESENCE       = Storage::disk('s3')->url($path);
                $presence->DATE_PRESENCE        = date('Y-m-d H:i:s');
                $presence->save();

                // $transDaily = new TransactionDaily();
                // $transDaily->ID_USER = $req->input('id_user');
                // $transDaily->DATE_TD = date("Y-m-d H:i:s");
                // $transDaily->save();

                return response([
                    "status_code"       => 200,
                    "status_message"    => 'Data berhasil disimpan!',
                    "data"              => ['ID_PRESENCE' => $presence->ID_PRESENCE]
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
