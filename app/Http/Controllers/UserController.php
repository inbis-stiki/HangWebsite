<?php

namespace App\Http\Controllers;

use App\Users;
use App\Role;
use App\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(){
        $data['title']      = "User";
        $data['sidebar']    = "master";
        $data['sidebar2']   = "user";
        $data['users']      = DB::table('user')
        ->join('md_role', 'md_role.ID_ROLE', '=', 'user.ID_ROLE')
        ->join('md_location', 'md_location.ID_LOCATION', '=', 'user.ID_LOCATION')
        ->join('md_regional', 'md_regional.ID_REGIONAL', '=', 'user.ID_REGIONAL')
        ->join('md_area', 'md_area.ID_AREA', '=', 'user.ID_AREA')
        ->select('user.*', 'md_location.NAME_LOCATION', 'md_regional.NAME_REGIONAL', 'md_area.NAME_AREA', 'md_role.NAME_ROLE')
        ->get();

        $data['roles']      = Role::whereNull('deleted_at')->get();
        $data['locations']  = Location::whereNull('deleted_at')->get();

        return view('master.user.user', $data);
    }

    public function getRegional(Request $request){
		$idnasional=$request->post('idnasional');
		$regional=DB::table('md_regional')->where('ID_LOCATION',$idnasional)->whereNull('deleted_at')->get();
		$html='<option selected disabled value="">Pilih Regional</option>';
		foreach($regional as $list){
			$html.='<option value="'.$list->ID_REGIONAL.'">'.$list->NAME_REGIONAL.'</option>';
		}
		echo $html;
	}

    public function getArea(Request $request){
		$idregional=$request->post('idregional');
		$area=DB::table('md_area')->where('ID_REGIONAL',$idregional)->whereNull('deleted_at')->get();
		$html='<option selected disabled value="">Pilih Area</option>';
		foreach($area as $list){
			$html.='<option value="'.$list->ID_AREA.'">'.$list->NAME_AREA.'</option>';
		}
		echo $html;
	}

    public function store(Request $req){
        $validator = Validator::make($req->all(), [
            'username'          => 'required',
            'name'              => 'required',
            'email'             => 'required',
            'phone'             => 'required',
            'ktp'               => 'required',
            'password'          => 'required',
            'nasional'          => 'required',
            'regional'          => 'required',
            'area'              => 'required',
            'role'              => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
        ]);

        if($validator->fails()){
            return redirect('master/user')->withErrors($validator);
        }

        $user                   = new Users();
        $user->ID_USER          = substr(md5(time()), 0, 8);
        $user->USERNAME_USER    = $req->input('username');
        $user->ID_ROLE          = $req->input('role');
        $user->ID_LOCATION      = $req->input('nasional');
        $user->ID_REGIONAL      = $req->input('regional');
        $user->ID_AREA          = $req->input('area');
        $user->KTP_USER         = $req->input('ktp');
        $user->NAME_USER        = $req->input('name');
        $user->PASS_USER        = hash('sha256', md5($req->input('password')));
        $user->EMAIL_USER       = $req->input('email');
        $user->TELP_USER        = $req->input('phone');
        $user->save();

        return redirect('master/user')->with('succ_msg', 'Berhasil menambah data user!');
    }

    public function update(Request $req){
        $validator = Validator::make($req->all(), [
            'id'                => 'required',
            'username'          => 'required',
            'name'              => 'required',
            'email'             => 'required',
            'phone'             => 'required',
            'ktp'               => 'required',
            'nasional'          => 'required',
            'regional'          => 'required',
            'area'              => 'required',
            'role'              => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
        ]);

        if($validator->fails()){
            return redirect('master/user')->withErrors($validator);
        }

        $user                   = Users::find($req->input('id'));
        $user->ID_USER          = substr(md5(time()), 0, 8);
        $user->USERNAME_USER    = $req->input('username');
        $user->ID_ROLE          = $req->input('role');
        $user->ID_LOCATION      = $req->input('nasional');
        $user->ID_REGIONAL      = $req->input('regional');
        $user->ID_AREA          = $req->input('area');
        $user->KTP_USER         = $req->input('ktp');
        $user->NAME_USER        = $req->input('name');
        $user->EMAIL_USER       = $req->input('email');
        $user->TELP_USER        = $req->input('phone');
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
