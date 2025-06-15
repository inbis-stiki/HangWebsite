<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class checkAuthorization
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
            $jwt    = JWT::decode("eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzM4NCJ9.eyJuYW1hIjoiSWxoYW0gU2FnaXRhIFB1dHJhIiwiamVuS2VsIjoiTEwifQ.9tVIfBG93mH504zW6IebOZdN0DvSvfBNW_ixYBjb2D4VnuccALPLpZXsNO3sOHDj", new Key(env('JWT_SECRET_KEY'), env("JWT_ALGO")));
            return $next($request);
        } catch (Exception $exp) {
            return response()->json(["msg" => $exp->getMessage()], 500);
        }
    }
}
