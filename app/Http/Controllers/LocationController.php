<?php

namespace App\Http\Controllers;

use App\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;

class LocationController extends Controller
{
    public function index(){
        $data['title']          = "Nasional";
        $data['nationals']      = Location::all();
        $data['sidebar']        = "master";
        $data['sidebar2']       = "location";
        return view('master.location.location', $data);
    }
    public function store(Request $req){
        $validator = Validator::make($req->all(), [
            'name'      => 'required|unique:md_location,NAME_LOCATION',
            'status'    => 'required',
        ], [
            'required'  => 'Data :attribute tidak boleh kosong!',
            'unique'    => 'Data :attribute telah terdaftar!'
        ]);

        if($validator->fails()){
            return redirect('master/location/national')->withErrors($validator);
        }


        
        $location = new Location();
        $location->NAME_LOCATION = $req->input('name');
        $location->deleted_at    = $req->input('status') == '1' ? NULL : date('Y-m-d H:i:s');
        $location->save();

        return redirect('master/location/national')->with('succ_msg', 'Berhasil menambah data nasional!');
    }
    public function update(Request $req){
        $validator = Validator::make($req->all(), [
            'id'        => 'required',
            'name'      => 'required|unique:md_location,NAME_LOCATION',
            'status'    => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
            'unique'   => 'Data :attribute telah terdaftar!'
        ]);

        if($validator->fails()){
            return redirect('master/location/national')->withErrors($validator);
        }

        
        $location = Location::find($req->input('id'));
        $location->NAME_LOCATION = $req->input('name');
        $location->deleted_at    = $req->input('status') == '1' ? NULL : date('Y-m-d H:i:s');
        $location->save();

        return redirect('master/location/national')->with('succ_msg', 'Berhasil mengubah data nasional!');
    }
    public function destroy(Request $req){
        $validator = Validator::make($req->all(), [
            'id'        => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
        ]);

        if($validator->fails()){
            return redirect('master/location/national')->withErrors($validator);
        }

        $location = Location::find($req->input('id'));
        $location->delete();

        return redirect('master/location/national')->with('succ_msg', 'Berhasil menghapus data nasional!');
    }
}