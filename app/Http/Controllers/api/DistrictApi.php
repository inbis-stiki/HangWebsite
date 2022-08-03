<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\District;
use App\Presence;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DistrictApi extends Controller
{
    
    public function index(Request $req){
        try {
            $idArea = $req->input("id_area");
            $district = District::where([
                ['ID_AREA', '=', $idArea],
                ['ISMARKET_DISTRICT', '=', '0']
            ])->whereNull('deleted_at')->get();

            return response([
                'status_code'       => 200,
                'status_message'    => 'Data berhasil diambil!',
                'data'              => $district
            ], 200);
        } catch (Exception $exp) {
            return response([
                'status_code'       => 500,
                'status_message'    => $exp->getMessage(),
            ], 500);
        }
    }

    public function listDistrict(Request $req){
        try {
            $id_district = Presence::select('ID_DISTRICT')
            ->whereDate('DATE_PRESENCE', date('Y-m-d'))->where('ID_USER',  $req->input("id_user"))
            ->first()->ID_DISTRICT;
            
            $parent_id = District::select('PARENT_DISTRICT')
            ->where([
                ['ID_DISTRICT', '=', $id_district]
            ])->whereNull('deleted_at')
            ->first()->PARENT_DISTRICT;

            $list = District::select('ID_DISTRICT', 'ID_AREA','NAME_DISTRICT', 'ADDRESS_DISTRICT')
            ->where([
                ['ID_AREA', '=', $req->input("id_area")],
                ['ISMARKET_DISTRICT', '=', '1'],
                ['ID_DISTRICT', '=', $parent_id]
            ])->whereNull('deleted_at')->get();

            return response([
                'status_code'       => 200,
                'status_message'    => 'Data berhasil diambil!',
                'data'              => $list
            ], 200);
        } catch (Exception $exp) {
            return response([
                'status_code'       => 500,
                'status_message'    => $exp->getMessage(),
            ], 500);
        }
    }
}