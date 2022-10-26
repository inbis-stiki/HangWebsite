<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index(){
        $data['title']      = "Role";
        $data['sidebar']    = "master";
        $data['sidebar2']   = "role";
        $data['roles']      = Role::all();
        return view('master.role.role', $data);
    }

    public function store(Request $req){
        $validator = Validator::make($req->all(), [
            'nama_role'             => 'required | unique:md_role,NAME_ROLE',
            'status'                => 'required',
        ], [
            'required' => 'Data :attribute tidak boleh kosong!',
            'unique' => 'Nama role telah digunakan!',
        ]);

        if($validator->fails()){
            return redirect('master/role')->withErrors($validator);
        }

        
        $role                   = new Role();
        $role->NAME_ROLE        = $req->input('nama_role');
        $role->deleted_at       = $req->input('status') == '1' ? NULL : date('Y-m-d H:i:s');
        $role->save();

        return redirect('master/role')->with('succ_msg', 'Berhasil menambah role!');
    }

    public function update(Request $req){
        $validator = Validator::make($req->all(), [
            'nama_role'             => 'required',
            'status'                => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
        ]);

        if($validator->fails()){
            return redirect('master/role')->withErrors($validator);
        }

        
        $role                   = Role::find($req->input('id'));
        $role->NAME_ROLE        = $req->input('nama_role');
        $role->deleted_at       = $req->input('status') == '1' ? NULL : date('Y-m-d H:i:s');
        $role->save();

        return redirect('master/role')->with('succ_msg', 'Berhasil mengubah data role!');
    }

    public function destroy(Request $req){
        $validator = Validator::make($req->all(), [
            'id'        => 'required',
        ], [
            'required' => 'Data tidak boleh kosong!',
        ]);

        if($validator->fails()){
            return redirect('master/role')->withErrors($validator);
        }

        $role = Role::find($req->input('id'));
        $role->delete();

        return redirect('master/role')->with('succ_msg', 'Berhasil menghapus role!');
    }

}
