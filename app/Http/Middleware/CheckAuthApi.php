<?php

namespace App\Http\Middleware;

use App\Users;
use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class CheckAuthApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // dump($request->input());
        // dump("tes");
        try {
            $jwt = JWT::decode($request->header('Authorization'), new Key(env('JWT_SECRET_KEY'), env('JWT_ALGO')));
            $user = Users::find($jwt->ID_USER);
            if($jwt->ID_ROLE != '5' && $jwt->ID_ROLE != '6'){
                return response([
                    'status_code'       => 200,
                    'status_message'    => 'Anda tidak memiliki hak akses!',
                ], 200);
            }else if($user->sess_key != $jwt->sess_key){
                return response([
                    'status_code'       => 200,
                    'status_message'    => 'Autentikasi anda gagal, harap login kembali!',
                ], 200);
            }

            $request->request->add([
                'id_user' => $jwt->ID_USER,
                'id_area' => $jwt->ID_AREA,
                'id_regional' => $jwt->ID_REGIONAL,
                'id_location' => $jwt->ID_LOCATION
            ]); 
            return $next($request);
        } catch (Exception $exp) {
            return response([
                'status_code'       => 401,
                'status_message'    => 'Anda belum login!'
            ], 401);
        }

    }
}
