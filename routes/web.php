<?php

use Illuminate\Support\Facades\Route;

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
//Login
Route::get('login', 'AuthController@login');
Route::post('auth', 'AuthController@auth');
Route::get('logout', 'AuthController@logout');

Route::get('/', 'DashboardController@index');
Route::get('dashboard', 'DashboardController@index');

// MASTER LOCATION
// nasional
Route::get('master/location/national', 'LocationController@index');
Route::get('master/location/national/store', 'LocationController@store');
Route::get('master/location/national/update', 'LocationController@update');
Route::get('master/location/national/destroy', 'LocationController@destroy');
// regional
Route::get('master/location/regional', 'RegionalController@index');
Route::get('master/location/regional/store', 'RegionalController@store');
Route::get('master/location/regional/update', 'RegionalController@update');
Route::get('master/location/regional/destroy', 'RegionalController@destroy');

//MASTER KATEGORI PRODUK
Route::get('master/category-product', 'CategoryProductController@index');
Route::get('master/category-product/store', 'CategoryProductController@store');
Route::get('master/category-product/update', 'CategoryProductController@update');
Route::get('master/category-product/destroy', 'CategoryProductController@destroy');
