<?php

namespace App\Http\Controllers;

use App\Area;
use App\logmd;
use App\Regional;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Session;

class AreaController extends Controller
{
    public function index(){
        $role = SESSION::get('role');
        if($role == 3){
            $location = SESSION::get('location');
            $data['areas']  = DB::table('md_area')
            ->where('md_regional.ID_LOCATION', '=' , $location)
            ->join('md_regional', 'md_regional.ID_REGIONAL', '=', 'md_area.ID_REGIONAL')
            ->join('md_location', 'md_location.ID_LOCATION', '=', 'md_regional.ID_LOCATION')
            ->select('md_area.*', 'md_regional.NAME_REGIONAL', 'md_location.NAME_LOCATION')
            ->get();
            $data['regionals']  = Regional::where('ID_LOCATION', '=' , $location)
            ->whereNull('deleted_at')->get();
        }else if($role == 4){
            $regional = SESSION::get('regional');
            $data['areas']  = DB::table('md_area')
            ->where('md_regional.ID_REGIONAL', '=' , $regional)
            ->join('md_regional', 'md_regional.ID_REGIONAL', '=', 'md_area.ID_REGIONAL')
            ->join('md_location', 'md_location.ID_LOCATION', '=', 'md_regional.ID_LOCATION')
            ->select('md_area.*', 'md_regional.NAME_REGIONAL', 'md_location.NAME_LOCATION')
            ->get();
            $data['regionals']  = Regional::where('ID_REGIONAL', '=' , $regional)
            ->whereNull('deleted_at')->get();
        }else{
            $data['areas']  = DB::table('md_area')
            ->join('md_regional', 'md_regional.ID_REGIONAL', '=', 'md_area.ID_REGIONAL')
            ->join('md_location', 'md_location.ID_LOCATION', '=', 'md_regional.ID_LOCATION')
            ->select('md_area.*', 'md_regional.NAME_REGIONAL', 'md_location.NAME_LOCATION')
            ->get();
            $data['regionals']  = Regional::whereNull('deleted_at')->get();
        }
        $data['title']          = "Area";
        $data['sidebar']        = "master";
        $data['sidebar2']       = "area";

        return view('master.location.area', $data);
    }
    public function store(Request $req){
        $validator = Validator::make($req->all(), [
            'area'      => 'required|unique:md_area,NAME_AREA',
            'regional'  => 'required',
            'status'    => 'required',
        ], [
            'required' => 'Data :attribute tidak boleh kosong!',
            'unique'   => 'Data :attribute telah terdaftar!'
        ]);

        if($validator->fails()){
            return redirect('master/location/area')->withErrors($validator);
        }


        
        $area = new Area();
        $area->ID_REGIONAL      = $req->input('regional');
        $area->NAME_AREA        = Str::upper($req->input('area'));
        $area->deleted_at       = $req->input('status') == '1' ? NULL : date('Y-m-d H:i:s');
        $area->save();

        return redirect('master/location/area')->with('succ_msg', 'Berhasil menambah data area!');
    }
    public function update(Request $req){
        $validator = Validator::make($req->all(), [
            'id'        => 'required',
            'area'      => 'required',
            'regional'  => 'required',
            'status'    => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!'
        ]);

        if($validator->fails()){
            return redirect('master/location/area')->withErrors($validator);
        }
        
        $area = Area::find($req->input('id'));

        $oldValues = $area->getOriginal();

        $area->ID_REGIONAL   = $req->input('regional');
        $area->NAME_AREA = Str::upper($req->input('area'));
        $area->deleted_at    = $req->input('status') == '1' ? NULL : date('Y-m-d H:i:s');
        
        $changedFields = array_keys($area->getDirty());
        $area->save();
        
        DB::statement("
            UPDATE
                `user` u
            SET
                u.ID_REGIONAL = (
                    SELECT 
                        ma.ID_REGIONAL 
                    FROM 
                        md_area ma 
                    WHERE 
                        ma.ID_AREA = u.ID_AREA
                ),
                u.ID_LOCATION = (
                    SELECT 
                        mr.ID_LOCATION 
                    FROM 
                        md_regional mr 
                    WHERE 
                        mr.ID_REGIONAL = (
                            SELECT 
                                ma.ID_REGIONAL 
                            FROM 
                                md_area ma 
                            WHERE 
                                ma.ID_AREA = u.ID_AREA
                        )
                )
            WHERE 
                u.ID_AREA = " . $req->input('id') . "
        ");

        $newValues = [];
        foreach($changedFields as $field) {
            $newValues[$field] = $area->getAttribute($field);
        }

        $id_userU = SESSION::get('id_user');

        if (!empty($newValues)) {
            DB::table('log_md')->insert([
                'UPDATED_BY' => $id_userU,
                'DETAIL' => 'Updating Area ' . (string)$req->input('id'),
                'OLD_VALUES' => json_encode(array_intersect_key($oldValues, $newValues)),
                'NEW_VALUES' => json_encode($newValues),
                'log_time' => now(),
            ]);
        }    

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
