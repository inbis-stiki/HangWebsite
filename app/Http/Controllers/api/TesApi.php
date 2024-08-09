<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Tes;
use App\Pickup;
use App\Product;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TesApi extends Controller
{
    public function index(){
        try {
            $tess = Tes::all();
            return response([
                'status_code'       => 200,
                'status_message'    => 'Data berhasil diambil!',
                'data'              => $tess
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
            $tes = Tes::find($id);
            if($tes == null){
                return response([
                    'status_code'       => 200,
                    'status_message'    => 'Data tidak ditemukan!',
                ], 200);
            }else{
                return response([
                    'status_code'       => 200,
                    'status_message'    => 'Data berhasil ditemukan!',
                    'data'              => $tes
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
                'id_product'    => 'required|numeric|exists:md_product,ID_PRODUCT',
                'name'          => 'required'
            ], [
                'required'  => 'Parameter :attribute tidak boleh kosong!',
                'numeric'   => 'Parameter :attribute harus bertipe angka!',
                'exists'    => 'Parameter :attribute tidak ditemukan!',
            ]);
    
            if($validator->fails()){
                return response([
                    "status_code"       => 400,
                    "status_message"    => $validator->errors()->first()
                ], 400);
            }

            $tes = new Tes();
            $tes->ID_PRODUCT    = $req->input('id_product');
            $tes->NAME_TES      = $req->input('name');
            $tes->save();

            return response([
                "status_code"       => 200,
                "status_message"    => 'Data berhasil disimpan!',
                "data"              => ['id_tes' => $tes->ID_TES]
            ], 200);
        } catch (HttpResponseException $exp) {
            return response([
                'status_code'       => $exp->getCode(),
                'status_message'    => $exp->getMessage(),
            ], $exp->getCode());
        }
    }
    public function update(Request $req){
        try {
            $validator = Validator::make($req->all(), [
                'id_tes'        => 'required|numeric|exists:tabletes,ID_TES',
                'id_product'    => 'required|numeric|exists:md_product,ID_PRODUCT',
                'name'          => 'required'
            ], [
                'required'  => 'Parameter :attribute tidak boleh kosong!',
                'numeric'   => 'Parameter :attribute harus bertipe angka!',
                'exists'    => 'Parameter :attribute tidak ditemukan!',
            ]);
    
            if($validator->fails()){
                return response([
                    "status_code"       => 400,
                    "status_message"    => $validator->errors()->first()
                ], 400);
            }

            $tes = Tes::find($req->input('id_tes'));
            $tes->ID_PRODUCT    = $req->input('id_product');
            $tes->NAME_TES      = $req->input('name');
            $tes->save();

            return response([
                "status_code"       => 200,
                "status_message"    => 'Data berhasil diubah!',
            ], 200);
        } catch (HttpResponseException $exp) {
            return response([
                'status_code'       => $exp->getCode(),
                'status_message'    => $exp->getMessage(),
            ], $exp->getCode());
        }
    }
    public function delete(Request $req){
        try {
            $validator = Validator::make($req->all(), [
                'id_tes'        => 'required|numeric|exists:tabletes,ID_TES'
            ], [
                'required'  => 'Parameter :attribute tidak boleh kosong!',
                'numeric'   => 'Parameter :attribute harus bertipe angka!',
                'exists'    => 'Parameter :attribute tidak ditemukan!',
            ]);
    
            if($validator->fails()){
                return response([
                    "status_code"       => 400,
                    "status_message"    => $validator->errors()->first()
                ], 400);
            }

            $tes = Tes::find($req->input('id_tes'));
            $tes->delete();

            return response([
                "status_code"       => 200,
                "status_message"    => 'Data berhasil dihapus!',
            ], 200);
        } catch (HttpResponseException $exp) {
            return response([
                'status_code'       => $exp->getCode(),
                'status_message'    => $exp->getMessage(),
            ], $exp->getCode());
        }
    }

    public function getpickup(Request $req)
    {
        $req->validate([
            'id_user' => 'required|string',
        ]);

        $id_user = $req->input('id_user');
        $pickup = Pickup::where('ID_USER', $id_user)
                        ->orderBy('TIME_PICKUP', 'desc')
                        ->first();

        if (!$pickup) {
            return response()->json(['message' => 'No pickup records found for the specified user.'], 404);
        }

        $products = explode(';', $pickup->ID_PRODUCT);
        $productNames = explode(';', $pickup->NAMEPRODUCT_PICKUP);
        $totals = explode(';', $pickup->TOTAL_PICKUP);
        $remainingStocks = explode(';', $pickup->REMAINING_STOCK);

        $data = [];

        foreach ($products as $index => $productId) {
            $data[] = [
                'Name Product' => $productNames[$index] ?? null,
                'Total Pickup' => $totals[$index] ?? null,
                'Remaining' => $remainingStocks[$index] ?? null
            ];
        }

        return response()->json($data);
    }
}
