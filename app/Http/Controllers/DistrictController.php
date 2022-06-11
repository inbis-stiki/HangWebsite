<?php

namespace App\Http\Controllers;

use App\Area;
use App\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DistrictController extends Controller
{
    public function index(){
        $data['title']          = "Kecamatan";
        $data['sidebar']        = "master";
        $data['sidebar2']       = "kecamatan";
        
        $data['districts']      = DB::table('md_district')
        ->join('md_area', 'md_area.ID_AREA', '=', 'md_district.ID_AREA')
        ->select('md_district.*', 'md_area.NAME_AREA')
        ->where('ISMARKET_DISTRICT', '0')
        ->get();
        $data['areas']  = Area::whereNull('deleted_at')->get();

        return view('master.location.district', $data);
    }
    public function store(Request $req){
        $validator = Validator::make($req->all(), [
            'district'      => 'required',
            'area'          => 'required',
            'status'        => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
        ]);

        if($validator->fails()){
            return redirect('master/location/district')->withErrors($validator);
        }


        date_default_timezone_set("Asia/Bangkok");
        $district = new District();
        $district->ID_AREA              = $req->input('area');
        $district->NAME_DISTRICT        = $req->input('district');
        $district->ISMARKET_DISTRICT    = '0';
        $district->ISFOCUS_DISTRICT     = '0';
        $district->deleted_at           = $req->input('status') == '1' ? NULL : date('Y-m-d H:i:s');
        $district->save();

        return redirect('master/location/district')->with('succ_msg', 'Berhasil menambah data kecamatan!');
    }
    public function update(Request $req){
        $validator = Validator::make($req->all(), [
            'id'        => 'required',
            'district'  => 'required',
            'area'      => 'required',
            'status'    => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
        ]);

        if($validator->fails()){
            return redirect('master/location/district')->withErrors($validator);
        }

        date_default_timezone_set("Asia/Bangkok");
        $district = District::find($req->input('id'));
        $district->ID_AREA          = $req->input('area');
        $district->NAME_DISTRICT    = $req->input('district');
        $district->deleted_at       = $req->input('status') == '1' ? NULL : date('Y-m-d H:i:s');
        $district->save();

        return redirect('master/location/district')->with('succ_msg', 'Berhasil mengubah data kecamatan!');
    }
    public function destroy(Request $req){
        $validator = Validator::make($req->all(), [
            'id'        => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
        ]);

        if($validator->fails()){
            return redirect('master/location/district')->withErrors($validator);
        }

        $district = District::find($req->input('id'));
        $district->delete();

        return redirect('master/location/district')->with('succ_msg', 'Berhasil menghapus data kecamatan!');
    }
}
