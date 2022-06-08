<?php

use Illuminate\Support\Facades\Route;
use Firebase\JWT\JWT;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Auth
Route::get('login', 'AuthController@login');
Route::post('auth', 'AuthController@auth');
Route::get('logout', 'AuthController@logout');


// Dashboard
Route::get('/dashboard', 'DashboardController@index');
Route::get('/dashboard/lokasi/region', 'DashboardController@lokasi_region');
Route::get('/dashboard/lokasi/lokasi', 'DashboardController@lokasi_lokasi');
Route::get('/dashboard/lokasi/area', 'DashboardController@lokasi_area');
Route::get('/dashboard/lokasi/kecamatan', 'DashboardController@lokasi_kecamatan');
Route::get('/dashboard/lokasi/pasar', 'DashboardController@lokasi_pasar');
