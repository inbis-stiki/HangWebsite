<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Users;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
 
class AuthApi extends Controller
{
    public function login(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'username'  => 'required',
            'password'  => 'required'
        ], [
            'required' => 'Parameter :attribute tidak boleh kosong!'
        ]);

        if ($validator->fails()) {
            return response([
                "status_code"       => 400,
                "status_message"    => $validator->errors()->first()
            ], 400);
        }

        $user = Users::where([
                    ['USERNAME_USER', '=', $req->input('username')],
                    ['PASS_USER', '=', hash('sha256', md5($req->input('password')))],
                    ['deleted_at', '=', null]
                ])
                ->first();

        if ($user == null) {
            return response([
                'status_code'       => 200,
                'status_message'    => 'Username atau password salah!',
            ], 200);
        }

        if ($user->BANNED == 1) {
            return response([
                'status_code'       => 403,
                'status_message'    => 'Akun anda terkena pelanggaran!',
            ], 403);
        }

        $user->sess_key = md5(rand(100, 999));
        $user->save();

        $jwt = JWT::encode($user->toArray(), env("JWT_SECRET_KEY"), env("JWT_ALGO"));

        return response([
            'status_code'       => 200,
            'status_message'    => 'Selamat anda berhasil login!',
            'data'              => ['jwt' => $jwt],
        ], 200);
    }

    public function ban(Request $req)
    {
        try {
            $user = Users::where([
                ['ID_USER', '=', $req->input('id_user')]
            ])->first();

            $user->BANNED    = '1';
            $user->save();

            return response([
                'status_code'       => 401,
                'status_message'    => 'Data anda terdeteksi sebagai data abnormal, silahkan menghubungi RPO anda!',
                'data'              => []
            ], 200);
        } catch (Exception $exp) {
            return response([
                'status_code'       => 500,
                'status_message'    => $exp->getMessage(),
            ], 500);
        }
    }
}
