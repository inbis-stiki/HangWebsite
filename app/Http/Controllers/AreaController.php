<?php

namespace App\Http\Controllers;

use App\Area;
use App\Regional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{
    public function index(){
        $data['title']          = "Area";
        $data['sidebar']        = "master";
        $data['sidebar2']       = "area";
        
        $data['areas']      = DB::table('md_area')
        ->join('md_regional', 'md_regional.ID_REGIONAL', '=', 'md_area.ID_REGIONAL')
        ->select('md_area.*', 'md_regional.NAME_REGIONAL')
        ->get();
        $data['regionals']  = Regional::whereNull('deleted_at')->get();

        return view('master.location.area', $data);
    }
    public function store(Request $req){
        $validator = Validator::make($req->all(), [
            'area'      => 'required',
            'regional'  => 'required',
            'status'    => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
        ]);

        if($validator->fails()){
            return redirect('master/location/area')->withErrors($validator);
        }


        date_default_timezone_set("Asia/Bangkok");
        $area = new Area();
        $area->ID_REGIONAL      = $req->input('regional');
        $area->NAME_AREA        = $req->input('area');
        $area->deleted_at       = $req->input('status') == '1' ? NULL : date('Y-m-d H:i:s');
        $area->save();

        return redirect('master/location/area')->with('succ_msg', 'Berhasil menambah data area!');
    }
    public function update(Request $req){
        $validator = Validator::make($req->all(), [
            'id'        => 'required',
            'area'  => 'required',
            'regional'  => 'required',
            'status'    => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
        ]);

        if($validator->fails()){
            return redirect('master/location/area')->withErrors($validator);
        }

        date_default_timezone_set("Asia/Bangkok");
        $area = Area::find($req->input('id'));
        $area->ID_REGIONAL   = $req->input('regional');
        $area->NAME_AREA = $req->input('area');
        $area->deleted_at    = $req->input('status') == '1' ? NULL : date('Y-m-d H:i:s');
        $area->save();

        return redirect('master/location/area')->with('succ_msg', 'Berhasil mengubah data area!');
    }
    public function destroy(Request $req){
        $validator = Validator::make($req->all(), [
            'id'        => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
        ]);

        if($validator->fails()){
            return redirect('master/location/area')->withErrors($validator);
        }

        $area = Area::find($req->input('id'));
        $area->delete();

        return redirect('master/location/area')->with('succ_msg', 'Berhasil menghapus data area!');
    }
}
