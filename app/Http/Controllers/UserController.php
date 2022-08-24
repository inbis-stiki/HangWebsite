<?php

namespace App\Http\Controllers;

use App\Users;
use App\Role;
use App\Area;
use App\UserTarget;
use App\TargetSale;
use App\TargetActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Session;

class UserController extends Controller
{
    
    public function index(){
        $role = SESSION::get('role');
        if($role == 3){
            $location = SESSION::get('location');
            $data['users']  = DB::table('user')
            ->where('user.ID_LOCATION', '=' , $location)
            ->where('user.ID_ROLE', '>' , $role)
            ->join('md_role', 'user.ID_ROLE', '=', 'md_role.ID_ROLE')
            ->get();

            $data['roles']      = DB::table('md_role')
            ->where('ID_ROLE', '>' , $role)
            ->whereNull('deleted_at')->get('*');

            $data['areas']      = DB::table('md_area')
            ->join('md_regional', 'md_regional.ID_REGIONAL', '=', 'md_area.ID_REGIONAL')
            ->join('md_location', 'md_location.ID_LOCATION', '=', 'md_regional.ID_LOCATION')
            ->where('md_location.ID_LOCATION', '=' , $location)
            ->whereNull('md_area.deleted_at')->get();
        }else if($role == 4){
            $regional = SESSION::get('regional');
            $data['users']  = DB::table('user')
            ->where('user.ID_REGIONAL', '=' , $regional)
            ->where('user.ID_ROLE', '>' , $role)
            ->join('md_role', 'user.ID_ROLE', '=', 'md_role.ID_ROLE')
            ->get();

            $data['roles']      = DB::table('md_role')
            ->where('ID_ROLE', '>' , $role)
            ->whereNull('deleted_at')->get('*');

            $data['areas']      = DB::table('md_area')
            ->join('md_regional', 'md_regional.ID_REGIONAL', '=', 'md_area.ID_REGIONAL')
            ->join('md_location', 'md_location.ID_LOCATION', '=', 'md_regional.ID_LOCATION')
            ->where('md_regional.ID_REGIONAL', '=' , $regional)
            ->whereNull('md_area.deleted_at')->get();
        }
        // else if($role == 5){
        //     $regional = SESSION::get('regional');
        //     $area = SESSION::get('area');
        //     $data['users']  = DB::table('user')
        //     ->where('user.ID_AREA', '=' , $area)
        //     ->where('user.ID_ROLE', '>' , $role)
        //     ->join('md_role', 'user.ID_ROLE', '=', 'md_role.ID_ROLE')
        //     ->get();

        //     $data['roles']      = DB::table('md_role')
        //     ->where('ID_ROLE', '>' , $role)
        //     ->whereNull('deleted_at')->get('*');

        //     $data['areas']      = DB::table('md_area')
        //     ->join('md_regional', 'md_regional.ID_REGIONAL', '=', 'md_area.ID_REGIONAL')
        //     ->join('md_location', 'md_location.ID_LOCATION', '=', 'md_regional.ID_LOCATION')
        //     ->where('md_regional.ID_REGIONAL', '=' , $regional)
        //     ->whereNull('md_area.deleted_at')->get();
        // }
        else{
            $data['users']      = DB::table('user')->join('md_role', 'user.ID_ROLE', '=', 'md_role.ID_ROLE')->select('*')->get();
            $data['roles']      = Role::whereNull('deleted_at')->get();
            $data['areas']      = Area::whereNull('deleted_at')->get();
        }
        
        $data['title']      = "User";
        $data['sidebar']    = "master";
        $data['sidebar2']   = "user";

        return view('master.user.user', $data);
    }

    public function store(Request $req){
        $validator = Validator::make($req->all(), [        
            'username'      => 'required | unique:user,USERNAME_USER',  
            'email'         => 'required | unique:user,EMAIL_USER',  
            'phone'         => 'required | unique:user,TELP_USER',
            'ktp'           => 'required | unique:user,KTP_USER',
            'name'          => 'required ',
            'password'      => 'required',
            'area'          => 'required',
            'role'          => 'required',
            'status'        => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
            'unique' => 'Data :attribute telah digunakan!',
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

        if($user->ID_ROLE > 4){
            $this->generateUserTarget($user->ID_USER, $user->ID_REGIONAL);
        }
        $user->save();

        return redirect('master/user')->with('succ_msg', 'Berhasil menambah data user!');
    }

    public function generateUserTarget($id, $region){
        $now = date('Y-m-d');
        $getDataTargetSale      = DB::table('target_sale')
        ->where('ID_REGIONAL', $region)
        ->whereDate('START_PP', '<', $now)
        ->whereDate('END_PP', '>', $now)
        ->sum('QUANTITY');

        $getDataTargetActivity  = DB::table('target_activity')
        ->where('ID_REGIONAL', $region)
        ->whereDate('START_PP', '<', $now)
        ->whereDate('END_PP', '>', $now)
        ->sum('QUANTITY');

        $getArea = DB::table('md_area')
        ->where('ID_REGIONAL', '=', $region)
        ->get();

        $newUT                      = new UserTarget();
        $newUT->ID_USER             = $id;
        $newUT->TOTALACTIVITY_UT    = round($getDataTargetActivity / (count($getArea) * 3) / 25 , PHP_ROUND_HALF_UP);
        $newUT->TOTALSALES_UT       = round($getDataTargetSale / (count($getArea) * 3) / 25 , PHP_ROUND_HALF_UP);

        $newUT->save();
    }

    public function update(Request $req){
        $validator = Validator::make($req->all(), [
            'id'            => 'required',
            'username'      => [
                'required',
                Rule::unique('user', 'USERNAME_USER')->ignore($req->input('id'), 'ID_USER'),
            ],
            'name'          => 'required',
            'email'         => [
                'required',
                Rule::unique('user', 'EMAIL_USER')->ignore($req->input('id'), 'ID_USER'),
            ],
            'phone'         => [
                'required',
                Rule::unique('user', 'TELP_USER')->ignore($req->input('id'), 'ID_USER'),
            ],
            'ktp'           => [
                'required',
                Rule::unique('user', 'KTP_USER')->ignore($req->input('id'), 'ID_USER'),
            ],
            'area'          => 'required',
            'role'          => 'required',
            'status'        => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
            'unique' => 'Data :attribute telah digunakan!',
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