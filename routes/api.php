<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('auth/login', 'api\AuthApi@login');

Route::middleware(['checkAuthApi'])->group(function(){
    // API TES
    Route::get("tes", 'api\TesApi@index');
    Route::get("tes/{any}", 'api\TesApi@detail');
    Route::post("tes", 'api\TesApi@store');
    Route::put("tes", 'api\TesApi@update');
    Route::delete("tes", 'api\TesApi@delete');
});
