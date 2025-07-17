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
            $data['users']      = DB::table('user')
                                    ->join('md_role', 'user.ID_ROLE', '=', 'md_role.ID_ROLE')
                                    ->leftJoin(DB::raw('
                                        (SELECT ID_USER, MAX(DATE_TRANS) as latest_date FROM transaction GROUP BY ID_USER) t_latest_date
                                    '), 'user.ID_USER', '=', 't_latest_date.ID_USER')
                                    ->leftJoin('transaction as t_latest', function($join) {
                                        $join->on('user.ID_USER', '=', 't_latest.ID_USER')
                                            ->on('t_latest.DATE_TRANS', '=', 't_latest_date.latest_date');
                                    })
                                    ->select('user.*', "md_role.ID_ROLE", "md_role.NAME_ROLE", "t_latest.DATE_TRANS")
                                    ->get();
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
            'name'          => 'required',
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

        // DB::enableQueryLog();

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

        // dd(DB::getQueryLog());
        
        // dd($locations);

        if (empty($locations)) {
            return redirect('master/user')->withErrors('gagal menambah data user!');
        }

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
        $user->ALLOWED_TRANS    = $req->input('status_toko');
        $user->save();

        return redirect('master/user')->with('succ_msg', 'Berhasil menambah data user!');
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

        $oldValues = $user->getOriginal();

        $user->USERNAME_USER    = $req->input('username');
        $user->NAME_USER        = $req->input('name');
        $user->EMAIL_USER       = $req->input('email');
        $user->TELP_USER        = $req->input('phone');
        $user->KTP_USER         = $req->input('ktp');
        // $user->ID_AREA          = $req->input('area');
        $user->ID_ROLE          = $req->input('role');
        $user->deleted_at       = $req->input('status') == '1' ? NULL : date('Y-m-d H:i:s');
        $user->ALLOWED_TRANS    = $req->input('status_toko');

        $changedFields = array_keys($user->getDirty());
        $user->save();

        $newValues = [];
        foreach($changedFields as $field) {
            $newValues[$field] = $user->getAttribute($field);
        }

        $id_userU = SESSION::get('id_user');

        if (!empty($newValues)) {
            DB::table('log_md')->insert([
                'UPDATED_BY' => $id_userU,
                'DETAIL' => 'Updating User ' . (string)$req->input('id'),
                'OLD_VALUES' => json_encode(array_intersect_key($oldValues, $newValues)),
                'NEW_VALUES' => json_encode($newValues),
                'log_time' => now(),
            ]);
        }    

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
