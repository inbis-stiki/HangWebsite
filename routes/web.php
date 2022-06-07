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

// Login
Route::get('/login', 'DashboardController@login');


// Dashboard
Route::get('/dashboard', 'DashboardController@index');
