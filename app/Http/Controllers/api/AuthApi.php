<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthApi extends Controller
{
    public function login(){
        $user['nama']       = "Ilham Sagita Putra";
        $user['jenKel']     = "LL";
        // $user['iat']        = time();
        // $user['exp']        = time() + 86400;
        $jwt = JWT::encode($user, env("JWT_SECRET_KEY"), env("JWT_ALGO"));

        return response()->json($jwt);
        // dump($jwt);
    }
    public function siswa(){
        // try {
        //     sdfosdif;
        //     return response()->json(['status_code' => 200, 'status_msg' => "sdjhfsdf"], 200);
        // } catch (HttpException $exp) {
        //     return response()->json(['status_code' => $exp->getStatusCode(), 'status_msg' => $exp->getMessage()], 404);
        // }
    }
}
