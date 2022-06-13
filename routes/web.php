<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;

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
Route::get('/', 'AuthController@login');
Route::post('auth', 'AuthController@auth');
Route::get('logout', 'AuthController@logout');

Route::middleware(['checkRole:1'])->group(function(){
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
    // area
    Route::get('master/location/area', 'AreaController@index');
    Route::get('master/location/area/store', 'AreaController@store');
    Route::get('master/location/area/update', 'AreaController@update');
    Route::get('master/location/area/destroy', 'AreaController@destroy');
    // area
    Route::get('master/location/district', 'DistrictController@index');
    Route::get('master/location/district/store', 'DistrictController@store');
    Route::get('master/location/district/update', 'DistrictController@update');
    Route::get('master/location/district/destroy', 'DistrictController@destroy');

    //MASTER KATEGORI PRODUK
    Route::get('master/category-product', 'CategoryProductController@index');
    Route::get('master/category-product/store', 'CategoryProductController@store');
    Route::get('master/category-product/update', 'CategoryProductController@update');
    Route::get('master/category-product/destroy', 'CategoryProductController@destroy');

    // MASTER ROLE
    Route::get('master/role', 'RoleController@index');
    Route::get('master/role/store', 'RoleController@store');
    Route::get('master/role/update', 'RoleController@update');
    Route::get('master/role/destroy', 'RoleController@destroy');

    //MASTER PRODUK
    Route::get('master/product', 'ProductController@index');
    Route::get('master/product/store', 'ProductController@store');
    Route::get('master/product/update', 'ProductController@update');
    Route::get('master/product/destroy', 'ProductController@destroy');

    // MASTER USER
    Route::get('master/user', 'UserController@index');
    Route::get('master/user/store', 'UserController@store');
    Route::get('master/user/update', 'UserController@update');
    Route::get('master/user/destroy', 'UserController@destroy');
});