<?php

namespace App\Http\Middleware;

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
        try {
            $jwt = JWT::decode($request->header('Authorization'), new Key(env('JWT_SECRET_KEY'), env('JWT_ALGO')));
            if($jwt->ID_ROLE != '5' && $jwt->ID_ROLE != '6'){
                return response([
                    'status_code'       => 200,
                    'status_message'    => 'Anda tidak memiliki hak akses!',
                ], 200);
            }
            return $next($request);
        } catch (Exception $exp) {
            return response([
                'status_code'       => 401,
                'status_message'    => 'Anda belum login!'
            ], 401);
        }

    }
}
