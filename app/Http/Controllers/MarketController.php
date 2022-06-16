<?php

namespace App\Http\Controllers;

use App\Area;
use App\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MarketController extends Controller
{
    public function index(){
        $data['title']          = "Pasar";
        $data['sidebar']        = "master";
        $data['sidebar2']       = "pasar";
        
        $data['markets']    = DB::table('md_district')
        ->join('md_area', 'md_area.ID_AREA', '=', 'md_district.ID_AREA')
        ->select('md_district.*', 'md_area.NAME_AREA')
        ->where('ISMARKET_DISTRICT', '1')
        ->get();
        $data['areas']      = Area::whereNull('deleted_at')->get();

        return view('master.location.market', $data);
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
            return redirect('master/location/market')->withErrors($validator);
        }

        $dist = District::where([
            ['NAME_DISTRICT', '=', $req->input('district')],
            ['ISMARKET_DISTRICT', '=', '1']
        ])->exists();

        if($dist == true){
            return redirect('master/location/market')->with('err_msg', 'Data pasar telah terdaftar');
        }

        date_default_timezone_set("Asia/Bangkok");
        $district = new District();
        $district->ID_AREA              = $req->input('area');
        $district->NAME_DISTRICT        = $req->input('district');
        $district->NAME_DISTRICT        = $req->input('district');
        $district->ISMARKET_DISTRICT    = '1';
        $district->ISFOCUS_DISTRICT     = !empty($req->input('statusMarket')) ? '1' : '0';
        $district->deleted_at           = $req->input('status') == '1' ? NULL : date('Y-m-d H:i:s');
        $district->save();

        return redirect('master/location/market')->with('succ_msg', 'Berhasil menambah data pasar!');
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
            return redirect('master/location/market')->withErrors($validator);
        }

        $dist = District::where([
            ['NAME_DISTRICT', '=', $req->input('district')],
            ['ISMARKET_DISTRICT', '=', '1']
        ])->exists();

        if($dist == true){
            return redirect('master/location/market')->with('err_msg', 'Data pasar telah terdaftar');
        }

        date_default_timezone_set("Asia/Bangkok");
        $district = District::find($req->input('id'));
        $district->ID_AREA          = $req->input('area');
        $district->NAME_DISTRICT    = $req->input('district');
        $district->ISFOCUS_DISTRICT = !empty($req->input('statusMarket')) ? '1' : '0';
        $district->deleted_at       = $req->input('status') == '1' ? NULL : date('Y-m-d H:i:s');
        $district->save();

        return redirect('master/location/market')->with('succ_msg', 'Berhasil mengubah data pasar!');
    }
    public function destroy(Request $req){
        $validator = Validator::make($req->all(), [
            'id'        => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
        ]);

        if($validator->fails()){
            return redirect('master/location/market')->withErrors($validator);
        }

        $district = District::find($req->input('id'));
        $district->delete();

        return redirect('master/location/market')->with('succ_msg', 'Berhasil menghapus data pasar!');
    }
}
