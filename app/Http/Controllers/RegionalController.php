<?php

namespace App\Http\Controllers;

use App\Location;
use App\Regional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RegionalController extends Controller
{
    public function index(){
        $data['title']          = "Regional";
        $data['sidebar']        = "master";
        $data['sidebar2']       = "location";
        
        $data['regionals']      = DB::table('md_regional')
        ->join('md_location', 'md_location.ID_LOCATION', '=', 'md_regional.ID_LOCATION')
        ->select('md_regional.*', 'md_location.NAME_LOCATION')
        ->get();
        $data['locations']  = Location::whereNull('deleted_at')->get();

        return view('master.location.regional', $data);
    }
    public function store(Request $req){
        $validator = Validator::make($req->all(), [
            'regional'  => 'required',
            'location'  => 'required',
            'status'    => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
        ]);

        if($validator->fails()){
            return redirect('master/location/regional')->withErrors($validator);
        }


        date_default_timezone_set("Asia/Bangkok");
        $regional = new Regional();
        $regional->ID_LOCATION   = $req->input('location');
        $regional->NAME_REGIONAL = $req->input('regional');
        $regional->deleted_at    = $req->input('status') == '1' ? NULL : date('Y-m-d H:i:s');
        $regional->save();

        return redirect('master/location/regional')->with('succ_msg', 'Berhasil menambah data regional!');
    }
    public function update(Request $req){
        $validator = Validator::make($req->all(), [
            'id'        => 'required',
            'regional'  => 'required',
            'location'  => 'required',
            'status'    => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
        ]);

        if($validator->fails()){
            return redirect('master/location/regional')->withErrors($validator);
        }

        date_default_timezone_set("Asia/Bangkok");
        $regional = Regional::find($req->input('id'));
        $regional->ID_LOCATION   = $req->input('location');
        $regional->NAME_REGIONAL = $req->input('regional');
        $regional->deleted_at    = $req->input('status') == '1' ? NULL : date('Y-m-d H:i:s');
        $regional->save();

        return redirect('master/location/regional')->with('succ_msg', 'Berhasil mengubah data regional!');
    }
    public function destroy(Request $req){
        $validator = Validator::make($req->all(), [
            'id'        => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
        ]);

        if($validator->fails()){
            return redirect('master/location/regional')->withErrors($validator);
        }

        $regional = Regional::find($req->input('id'));
        $regional->delete();

        return redirect('master/location/regional')->with('succ_msg', 'Berhasil menghapus data regional!');
    }
}
