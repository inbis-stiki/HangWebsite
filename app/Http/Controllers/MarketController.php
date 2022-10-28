<?php

namespace App\Http\Controllers;

use App\Area;
use App\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Session;

class MarketController extends Controller
{
    public function index(){
        $role = SESSION::get('role');
        if($role == 3){
            $location = SESSION::get('location');
            $data['markets']  = DB::table('md_district')
            ->where('md_regional.ID_LOCATION', '=' , $location)
            ->where('ISMARKET_DISTRICT', '1')
            ->join('md_area', 'md_area.ID_AREA', '=', 'md_district.ID_AREA')
            ->join('md_regional', 'md_regional.ID_REGIONAL', '=', 'md_area.ID_REGIONAL')
            ->join('md_location', 'md_location.ID_LOCATION', '=', 'md_regional.ID_LOCATION')
            ->select('md_district.*', 'md_area.NAME_AREA')
            ->get();
            $data['areas']  = DB::table('md_area')
            ->join('md_regional', 'md_regional.ID_REGIONAL', '=', 'md_area.ID_REGIONAL')
            ->join('md_location', 'md_location.ID_LOCATION', '=', 'md_regional.ID_LOCATION')
            ->where('md_location.ID_LOCATION', '=' , $location)
            ->whereNull('md_area.deleted_at')->get();
        }else if($role == 4){
            $regional = SESSION::get('regional');
            $data['markets']  = DB::table('md_district')
            ->where('md_regional.ID_REGIONAL', '=' , $regional)
            ->where('ISMARKET_DISTRICT', '1')
            ->join('md_area', 'md_area.ID_AREA', '=', 'md_district.ID_AREA')
            ->join('md_regional', 'md_regional.ID_REGIONAL', '=', 'md_area.ID_REGIONAL')
            ->join('md_location', 'md_location.ID_LOCATION', '=', 'md_regional.ID_LOCATION')
            ->select('md_district.*', 'md_area.NAME_AREA')
            ->get();
            $data['areas']  = DB::table('md_area')
            ->join('md_regional', 'md_regional.ID_REGIONAL', '=', 'md_area.ID_REGIONAL')
            ->join('md_location', 'md_location.ID_LOCATION', '=', 'md_regional.ID_LOCATION')
            ->where('md_regional.ID_REGIONAL', '=' , $regional)
            ->whereNull('md_area.deleted_at')->get();
        }else{
            $data['markets']  = DB::table('md_district')
            ->where('ISMARKET_DISTRICT', '1')
            ->join('md_area', 'md_area.ID_AREA', '=', 'md_district.ID_AREA')
            ->join('md_regional', 'md_regional.ID_REGIONAL', '=', 'md_area.ID_REGIONAL')
            ->join('md_location', 'md_location.ID_LOCATION', '=', 'md_regional.ID_LOCATION')
            ->select('md_district.*', 'md_area.NAME_AREA')
            ->get();
            $data['areas']  = DB::table('md_area')
            ->join('md_regional', 'md_regional.ID_REGIONAL', '=', 'md_area.ID_REGIONAL')
            ->join('md_location', 'md_location.ID_LOCATION', '=', 'md_regional.ID_LOCATION')
            ->whereNull('md_area.deleted_at')->get();
        }
        $data['title']          = "Pasar";
        $data['sidebar']        = "master";
        $data['sidebar2']       = "pasar";

        return view('master.location.market', $data);
    }

    public function get_district_by_area(Request $req)
    {
        $ID_AREA = $req->input('id_area');
        $role = SESSION::get('role');
        if($role == 3){
            $location = SESSION::get('location');
            $data['district']  = DB::table('md_district')
            ->join('md_area', 'md_area.ID_AREA', '=','md_district.ID_AREA')
            ->join('md_regional', 'md_regional.ID_REGIONAL', '=', 'md_area.ID_REGIONAL')
            ->join('md_location', 'md_location.ID_LOCATION', '=', 'md_regional.ID_LOCATION')
            ->where('md_district.ID_AREA', '=' , $ID_AREA)
            ->where('md_location.ID_LOCATION', '=' , $location)
            ->where('md_district.ISMARKET_DISTRICT', '=', '0')
            ->whereNull('md_district.deleted_at')->get();
        }else if($role == 4){
            $regional = SESSION::get('regional');
            $data['district']  = DB::table('md_district')
            ->join('md_area', 'md_area.ID_AREA', '=', 'md_district.ID_AREA')
            ->join('md_regional', 'md_regional.ID_REGIONAL', '=', 'md_area.ID_REGIONAL')
            ->where('md_regional.ID_REGIONAL', '=' , $regional)
            ->where('md_district.ID_AREA', '=' , $ID_AREA)
            ->where('ISMARKET_DISTRICT', '0')
            ->whereNull('md_district.deleted_at')->get();
        }else{
            $data['district']  = DB::table('md_district')
            ->where('ISMARKET_DISTRICT', '0')
            ->where('md_district.ID_AREA', '=' , $ID_AREA)
            ->whereNull('md_district.deleted_at')->get();
        }

        return $data['district'];
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

        // $dist = District::where([
        //     ['NAME_DISTRICT', '=', $req->input('district')],
        //     ['ISMARKET_DISTRICT', '=', '1']
        // ])->exists();

        // if($dist == true){
        //     return redirect('master/location/market')->with('err_msg', 'Data pasar telah terdaftar');
        // }

        
        $district = new District();
        $district->ID_AREA              = $req->input('area');
        $district->NAME_DISTRICT        = $req->input('district');
        $district->ISMARKET_DISTRICT    = '1';
        $district->ISFOCUS_DISTRICT     = !empty($req->input('statusMarket')) ? '1' : '0';
        $district->deleted_at           = $req->input('status') == '1' ? NULL : date('Y-m-d H:i:s');
        $district->PARENT_DISTRICT      = $req->input('districtK');
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

        // $dist = District::where([
        //     ['NAME_DISTRICT', '=', $req->input('district')],
        //     ['ISMARKET_DISTRICT', '=', '1']
        // ])->exists();

        // if($dist == true){
        //     return redirect('master/location/market')->with('err_msg', 'Data pasar telah terdaftar');
        // }

        
        
        $district = District::find($req->input('id'));
        $district->ID_AREA          = $req->input('area');
        $district->NAME_DISTRICT    = $req->input('district');
        $district->ISFOCUS_DISTRICT = !empty($req->input('statusMarket')) ? '1' : '0';
        $district->deleted_at       = $req->input('status') == '1' ? NULL : date('Y-m-d H:i:s');
        $district->PARENT_DISTRICT  = $req->input('districtK');
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