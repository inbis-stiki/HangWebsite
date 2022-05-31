<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
class TesController extends Controller
{
    public function tesJWTLoginDummy(){
        $jwt = JWT::encode(["nama" => "Ilham Sagita Putra", "jenKel" => "Ill"], env("JWT_ALGO"), env("JWT_SECRET_KEY"));
        dump($jwt);
    }
}
