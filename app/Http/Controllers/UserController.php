<?php

namespace App\Http\Controllers;

use App\Users;
use App\Role;
use App\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(){
        $data['title']      = "User";
        $data['sidebar']    = "master";
        $data['sidebar2']   = "user";
        
        $data['users']      = DB::table('user')->select('*')->get();
        $data['roles']      = Role::whereNull('deleted_at')->get();
        $data['areas']      = Area::whereNull('deleted_at')->get();

        return view('master.user.user', $data);
    }

    public function store(Request $req){
        $validator = Validator::make($req->all(), [
            'username'      => 'required',
            'name'          => 'required:alpha',
            'email'         => 'required',
            'phone'         => 'required',
            'ktp'           => 'required',
            'password'      => 'required',
            'area'          => 'required',
            'role'          => 'required',
            'status'        => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
        ]);

        if($validator->fails()){
            return redirect('master/user')->withErrors($validator);
        }

        $locations      = DB::table('md_area')
        ->where('md_area.ID_AREA', $req->input('area'))
        ->join('md_regional', 'md_regional.ID_REGIONAL', '=', 'md_area.ID_REGIONAL')
        ->join('md_location', 'md_location.ID_LOCATION', '=', 'md_regional.ID_LOCATION')
        ->select('md_regional.ID_REGIONAL', 'md_location.ID_LOCATION')
        ->get();

        date_default_timezone_set("Asia/Bangkok");
        $user                   = new Users();
        $user->ID_USER          = substr(md5(time().rand(10, 99)), 0, 8);
        $user->USERNAME_USER    = $req->input('username');
        $user->ID_ROLE          = $req->input('role');
        $user->ID_AREA          = $req->input('area');
        $user->ID_REGIONAL      = $locations[0]->ID_REGIONAL;
        $user->ID_LOCATION      = $locations[0]->ID_LOCATION;
        $user->NAME_USER        = $req->input('name');
        $user->KTP_USER         = $req->input('ktp');
        $user->EMAIL_USER       = $req->input('email');
        $user->TELP_USER        = $req->input('phone');
        $user->PASS_USER        = hash('sha256', md5($req->input('password')));
        $user->deleted_at       = $req->input('status') == '1' ? NULL : date('Y-m-d H:i:s');
        $user->save();

        return redirect('master/user')->with('succ_msg', 'Berhasil menambah data user!');
    }

    public function update(Request $req){
        $validator = Validator::make($req->all(), [
            'id'            => 'required',
            'username'      => 'required',
            'name'          => 'required',
            'email'         => 'required',
            'phone'         => 'required',
            'ktp'           => 'required',
            'area'          => 'required',
            'role'          => 'required',
            'status'        => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
        ]);

        if($validator->fails()){
            return redirect('master/user')->withErrors($validator);
        }

        date_default_timezone_set("Asia/Bangkok");
        $user   = Users::find($req->input('id'));

        if($user->ID_AREA != $req->input('area')){
            $locations      = DB::table('md_area')
            ->where('md_area.ID_AREA', $req->input('area'))
            ->join('md_regional', 'md_regional.ID_REGIONAL', '=', 'md_area.ID_REGIONAL')
            ->join('md_location', 'md_location.ID_LOCATION', '=', 'md_regional.ID_LOCATION')
            ->select('md_regional.ID_REGIONAL', 'md_location.ID_LOCATION')
            ->get();            
            
            $user->ID_REGIONAL      = $locations[0]->ID_REGIONAL;
            $user->ID_LOCATION      = $locations[0]->ID_LOCATION;
        }

        $user->USERNAME_USER    = $req->input('username');
        $user->NAME_USER        = $req->input('name');
        $user->EMAIL_USER       = $req->input('email');
        $user->TELP_USER        = $req->input('phone');
        $user->KTP_USER         = $req->input('ktp');
        $user->ID_AREA          = $req->input('area');
        $user->ID_ROLE          = $req->input('role');
        $user->deleted_at       = $req->input('status') == '1' ? NULL : date('Y-m-d H:i:s');
        $user->save();

        return redirect('master/user')->with('succ_msg', 'Berhasil mengubah data user!');
    }

    public function destroy(Request $req){
        $validator = Validator::make($req->all(), [
            'id'        => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
        ]);

        if($validator->fails()){
            return redirect('master/user')->withErrors($validator);
        }

        $user = Users::find($req->input('id'));
        $user->delete();

        return redirect('master/user')->with('succ_msg', 'Berhasil menghapus data user!');
    }

}
