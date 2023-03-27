<?php

namespace App\Http\Controllers;

use App\Users;
use App\Role;
use App\Area;
use App\Location;
use App\Regional;
use App\UserTarget;
use App\logmd;
use App\TargetSale;
use App\TargetActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Session;

class UserController extends Controller
{

    public function index()
    {
        $role = SESSION::get('role');
        if ($role == 3) {
            $location = SESSION::get('location');
            $data['users']  = DB::table('user')
                ->select('user.*', "md_role.ID_ROLE", "md_role.NAME_ROLE")
                ->where('user.ID_LOCATION', '=', $location)
                ->where('user.ID_ROLE', '>', $role)
                ->join('md_role', 'user.ID_ROLE', '=', 'md_role.ID_ROLE')
                ->get();

            $data['roles']      = DB::table('md_role')
                ->where('ID_ROLE', '>', $role)
                ->whereNull('deleted_at')->get('*');

            $data['areas']      = DB::table('md_area')
                ->join('md_regional', 'md_regional.ID_REGIONAL', '=', 'md_area.ID_REGIONAL')
                ->join('md_location', 'md_location.ID_LOCATION', '=', 'md_regional.ID_LOCATION')
                ->where('md_location.ID_LOCATION', '=', $location)
                ->whereNull('md_area.deleted_at')->get();

            $data['location']   = DB::table('md_location')
                ->whereNull('deleted_at')->get();

            $data['regional']   = DB::table('md_regional')
                ->whereNull('deleted_at')->get();
        } else if ($role == 4) {
            $regional = SESSION::get('regional');
            $data['users']  = DB::table('user')
                ->select('user.*', "md_role.ID_ROLE", "md_role.NAME_ROLE")
                ->where('user.ID_REGIONAL', '=', $regional)
                ->where('user.ID_ROLE', '>', $role)
                ->join('md_role', 'user.ID_ROLE', '=', 'md_role.ID_ROLE')
                ->get();

            $data['roles']      = DB::table('md_role')
                ->where('ID_ROLE', '>', $role)
                ->whereNull('deleted_at')->get('*');

            $data['areas']      = DB::table('md_area')
                ->join('md_regional', 'md_regional.ID_REGIONAL', '=', 'md_area.ID_REGIONAL')
                ->join('md_location', 'md_location.ID_LOCATION', '=', 'md_regional.ID_LOCATION')
                ->where('md_regional.ID_REGIONAL', '=', $regional)
                ->whereNull('md_area.deleted_at')->get();

            $data['location']   = DB::table('md_location')
                ->whereNull('deleted_at')->get();

            $data['regional']   = DB::table('md_regional')
                ->whereNull('deleted_at')->get();
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
        else {
            $data['users']      = DB::table('user')->join('md_role', 'user.ID_ROLE', '=', 'md_role.ID_ROLE')->select('user.*', "md_role.ID_ROLE", "md_role.NAME_ROLE")->get();
            $data['roles']      = Role::whereNull('deleted_at')->get();
            $data['areas']      = Area::whereNull('deleted_at')->get();
            $data['location']      = Location::whereNull('deleted_at')->get();
            $data['regional']      = Regional::whereNull('deleted_at')->get();
        }

        $data['title']      = "User";
        $data['sidebar']    = "master";
        $data['sidebar2']   = "user";

        return view('master.user.user', $data);
    }

    public function store(Request $req)
    {
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

        if ($validator->fails()) {
            return redirect('master/user')->withErrors($validator);
        }

        if ($req->input('role') == 1 || $req->input('role') == 2 || $req->input('role') == 3) {
            $locations      = DB::table('md_location')
                ->where('md_location.ID_LOCATION', $req->input('area'))
                ->join('md_regional', 'md_regional.ID_LOCATION', '=', 'md_location.ID_LOCATION')
                ->join('md_area', 'md_area.ID_REGIONAL', '=', 'md_regional.ID_REGIONAL')
                ->select('md_regional.ID_REGIONAL', 'md_location.ID_LOCATION', 'md_area.ID_AREA')
                ->get();
        } else if ($req->input('role') == 4) {
            $locations      = DB::table('md_regional')
                ->where('md_regional.ID_REGIONAL', $req->input('area'))
                ->join('md_location', 'md_location.ID_LOCATION', '=', 'md_regional.ID_LOCATION')
                ->join('md_area', 'md_area.ID_REGIONAL', '=', 'md_regional.ID_REGIONAL')
                ->select('md_regional.ID_REGIONAL', 'md_location.ID_LOCATION', 'md_area.ID_AREA')
                ->get();
        } else {
            $locations      = DB::table('md_area')
                ->where('md_area.ID_AREA', $req->input('area'))
                ->join('md_regional', 'md_regional.ID_REGIONAL', '=', 'md_area.ID_REGIONAL')
                ->join('md_location', 'md_location.ID_LOCATION', '=', 'md_regional.ID_LOCATION')
                ->select('md_regional.ID_REGIONAL', 'md_location.ID_LOCATION', 'md_area.ID_AREA')
                ->get();
        }
        // $locations      = DB::table('md_area')
        //     ->where('md_area.ID_AREA', $req->input('area'))
        //     ->join('md_regional', 'md_regional.ID_REGIONAL', '=', 'md_area.ID_REGIONAL')
        //     ->join('md_location', 'md_location.ID_LOCATION', '=', 'md_regional.ID_LOCATION')
        //     ->select('md_regional.ID_REGIONAL', 'md_location.ID_LOCATION', 'md_area.ID_AREA')
        //     ->get();

        $user                   = new Users();
        $user->ID_USER          = substr(md5(time() . rand(10, 99)), 0, 8);
        $user->USERNAME_USER    = $req->input('username');
        $user->ID_ROLE          = $req->input('role');

        if ($req->input('role') == 1 || $req->input('role') == 2 || $req->input('role') == 3) {
            $user->ID_AREA          = $locations[0]->ID_AREA;
            $user->ID_REGIONAL      = $locations[0]->ID_REGIONAL;
            $user->ID_LOCATION      = $req->input('area');
        } else if ($req->input('role') == 4) {
            $user->ID_AREA          = $locations[0]->ID_AREA;
            $user->ID_REGIONAL      = $req->input('area');
            $user->ID_LOCATION      = $locations[0]->ID_LOCATION;
        } else {
            $user->ID_AREA          = $req->input('area');
            $user->ID_REGIONAL      = $locations[0]->ID_REGIONAL;
            $user->ID_LOCATION      = $locations[0]->ID_LOCATION;
        }

        $user->NAME_USER        = $req->input('name');
        $user->KTP_USER         = $req->input('ktp');
        $user->EMAIL_USER       = $req->input('email');
        $user->TELP_USER        = $req->input('phone');
        $user->PASS_USER        = hash('sha256', md5($req->input('password')));
        $user->deleted_at       = $req->input('status') == '1' ? NULL : date('Y-m-d H:i:s');

        if ($user->ID_ROLE > 4) {
            $this->generateUserTarget($user->ID_USER, $user->ID_REGIONAL);
        }
        $user->save();

        return redirect('master/user')->with('succ_msg', 'Berhasil menambah data user!');
    }

    public function generateUserTarget($id, $region)
    {
        $now = date('Y-m-d');
        // $getDataTargetSale      = DB::table('target_sale')
        // ->where('ID_REGIONAL', $region)
        // ->whereDate('START_PP', '<', $now)
        // ->whereDate('END_PP', '>', $now)
        // ->sum('QUANTITY');

        $getDataTargetActivity  = DB::table('target_activity')
            ->where('ID_REGIONAL', $region)
            ->whereDate('START_PP', '<', $now)
            ->whereDate('END_PP', '>', $now)
            ->sum('QUANTITY');


        $getArea = DB::table('md_area')
            ->where('ID_REGIONAL', '=', $region)
            ->get();

        $getTargetSaleCategory = UserTarget::queryGetTargetSaleCategory(count($getArea), $region);
        $totUst         = round($getTargetSaleCategory[0]->TOTAL, PHP_ROUND_HALF_UP);
        $totNonUst      = round($getTargetSaleCategory[1]->TOTAL, PHP_ROUND_HALF_UP);
        $totSeleraku    = round($getTargetSaleCategory[2]->TOTAL, PHP_ROUND_HALF_UP);;

        $newUT                      = new UserTarget();
        $newUT->ID_USER             = $id;
        $newUT->TOTALACTIVITY_UT    = round($getDataTargetActivity / (count($getArea) * 3) / 25, PHP_ROUND_HALF_UP);
        $newUT->SALESUST_UT         = $totUst;
        $newUT->SALESNONUST_UT      = $totNonUst;
        $newUT->SALESSELERAKU_UT    = $totSeleraku;
        $newUT->TOTALSALES_UT       = $totUst + $totNonUst + $totSeleraku;

        $newUT->save();
    }

    public function update(Request $req)
    {
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

        if ($validator->fails()) {
            return redirect('master/user')->withErrors($validator);
        }


        $user   = Users::find($req->input('id'));

        if ($req->input('role') == 1 || $req->input('role') == 2 || $req->input('role') == 3) {
            if ($user->ID_LOCATION != $req->input('area')) {
                $locations      = DB::table('md_location')
                    ->where('md_location.ID_LOCATION', $req->input('area'))
                    ->join('md_regional', 'md_regional.ID_LOCATION', '=', 'md_location.ID_LOCATION')
                    ->join('md_area', 'md_area.ID_REGIONAL', '=', 'md_regional.ID_REGIONAL')
                    ->select('md_regional.ID_REGIONAL', 'md_location.ID_LOCATION', 'md_area.ID_AREA')
                    ->get();
                
                $user->ID_AREA          = $locations[0]->ID_AREA;
                $user->ID_REGIONAL      = $locations[0]->ID_REGIONAL;
                $user->ID_LOCATION      = $req->input('area');
            }
        } else if ($req->input('role') == 4) {
            if ($user->ID_REGIONAL != $req->input('area')) {
                $locations      = DB::table('md_regional')
                    ->where('md_regional.ID_REGIONAL', $req->input('area'))
                    ->join('md_location', 'md_location.ID_LOCATION', '=', 'md_regional.ID_LOCATION')
                    ->join('md_area', 'md_area.ID_REGIONAL', '=', 'md_regional.ID_REGIONAL')
                    ->select('md_regional.ID_REGIONAL', 'md_location.ID_LOCATION', 'md_area.ID_AREA')
                    ->get();

                $user->ID_AREA          = $locations[0]->ID_AREA;
                $user->ID_REGIONAL      = $req->input('area');
                $user->ID_LOCATION      = $locations[0]->ID_LOCATION;
            }
        } else {
            if ($user->ID_AREA != $req->input('area')) {
                $locations      = DB::table('md_area')
                ->where('md_area.ID_AREA', $req->input('area'))
                ->join('md_regional', 'md_regional.ID_REGIONAL', '=', 'md_area.ID_REGIONAL')
                ->join('md_location', 'md_location.ID_LOCATION', '=', 'md_regional.ID_LOCATION')
                ->select('md_regional.ID_REGIONAL', 'md_location.ID_LOCATION')
                ->get();
            
                $user->ID_AREA          = $req->input('area');
                $user->ID_REGIONAL      = $locations[0]->ID_REGIONAL;
                $user->ID_LOCATION      = $locations[0]->ID_LOCATION;
            }
        }

        $user->USERNAME_USER    = $req->input('username');
        $user->NAME_USER        = $req->input('name');
        $user->EMAIL_USER       = $req->input('email');
        $user->TELP_USER        = $req->input('phone');
        $user->KTP_USER         = $req->input('ktp');
        // $user->ID_AREA          = $req->input('area');
        $user->ID_ROLE          = $req->input('role');
        $user->deleted_at       = $req->input('status') == '1' ? NULL : date('Y-m-d H:i:s');
        $user->save();

        $id_userU = SESSION::get('id_user');
        $log                    = new logmd();
        $log->UPDATED_BY        = $id_userU;
        $log->DETAIL            = 'Updating user ' . (string)$req->input('id'); 
        $log->log_time          = now();
        $log->save();        

        return redirect('master/user')->with('succ_msg', 'Berhasil mengubah data user!');
    }

    public function destroy(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'id'        => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
        ]);

        if ($validator->fails()) {
            return redirect('master/user')->withErrors($validator);
        }

        $user = Users::find($req->input('id'));
        $user->delete();

        return redirect('master/user')->with('succ_msg', 'Berhasil menghapus data user!');
    }

    public function changePass(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'id'        => 'required',
            'pass'        => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
        ]);

        if ($validator->fails()) {
            return redirect('master/user')->withErrors($validator);
        }

        $user = Users::find($req->input('id'));
        $user->PASS_USER = hash('sha256', md5($req->input('pass')));
        $user->save();

        return redirect('master/user')->with('succ_msg', 'Berhasil mengubah password user!');
    }
}
