<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\District;
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
}