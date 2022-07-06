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
// TES IMAGE S3
Route::get('testimage', 'ImageController@create');
Route::post('testimage', 'ImageController@store');
Route::get('testimage/{image}', 'ImageController@show');


//Login
Route::get('/', 'AuthController@login');
Route::post('auth', 'AuthController@auth');
Route::get('logout', 'AuthController@logout');

// Route::group(['middleware' => ['checkRole:1']], function() {
    Route::get('dashboard', 'DashboardController@index');

    // MASTER LOCATION
    // nasional
    Route::get('master/location/national', 'LocationController@index');
    Route::post('master/location/national/store', 'LocationController@store');
    Route::post('master/location/national/update', 'LocationController@update');
    Route::post('master/location/national/destroy', 'LocationController@destroy');
    // regional
    Route::get('master/location/regional', 'RegionalController@index');
    Route::post('master/location/regional/store', 'RegionalController@store');
    Route::post('master/location/regional/update', 'RegionalController@update');
    Route::post('master/location/regional/destroy', 'RegionalController@destroy');
    // area
    Route::get('master/location/area', 'AreaController@index');
    Route::post('master/location/area/store', 'AreaController@store');
    Route::post('master/location/area/update', 'AreaController@update');
    Route::post('master/location/area/destroy', 'AreaController@destroy');
    // area
    Route::get('master/location/district', 'DistrictController@index');
    Route::post('master/location/district/store', 'DistrictController@store');
    Route::post('master/location/district/update', 'DistrictController@update');
    Route::post('master/location/district/destroy', 'DistrictController@destroy');
    // market
    Route::get('master/location/market', 'MarketController@index');
    Route::post('master/location/market/store', 'MarketController@store');
    Route::post('master/location/market/update', 'MarketController@update');
    Route::post('master/location/market/destroy', 'MarketController@destroy');

    // MASTER HARGA REGIONAL
    Route::get('master/regional-price', 'RegionalPriceController@index');
    Route::post('master/regional-price/store', 'RegionalPriceController@store');
    Route::get('master/regional-price/update', 'RegionalPriceController@update');
    Route::get('master/regional-price/destroy', 'RegionalPriceController@destroy');
    Route::get('master/regional-price/download_template', 'RegionalPriceController@download_template');

    // MASTER  REGIONAL
    Route::get('master/targetregional', 'TargetRegionalController@index');
    Route::get('master/targetregional/store', 'TargetRegionalController@store');
    Route::get('master/targetregional/update', 'TargetRegionalController@update');
    Route::get('master/targetregional/destroy', 'TargetRegionalController@destroy');

    //MASTER KATEGORI PRODUK
    Route::get('master/category-product', 'CategoryProductController@index');
    Route::get('master/category-product/store', 'CategoryProductController@store');
    Route::get('master/category-product/update', 'CategoryProductController@update');
    Route::get('master/category-product/destroy', 'CategoryProductController@destroy');

    // MASTER ROLE
    Route::group(['middleware' => ['checkRole:1']], function() {
        Route::get('master/role', 'RoleController@index');
        Route::get('master/role/store', 'RoleController@store');
        Route::get('master/role/update', 'RoleController@update');
        Route::get('master/role/destroy', 'RoleController@destroy');
    });

    //MASTER PRODUK
    Route::get('master/product', 'ProductController@index');
    Route::get('master/product/store', 'ProductController@store');
    Route::get('master/product/update', 'ProductController@update');
    Route::get('master/product/destroy', 'ProductController@destroy');

    //MASTER SHOP
    Route::get('master/shop', 'ShopController@index');
    Route::get('master/shop/store', 'ShopController@store');
    Route::get('master/shop/update', 'ShopController@update');
    Route::get('master/shop/destroy', 'ShopController@destroy');

    // MASTER USER
    Route::get('master/user', 'UserController@index');
    Route::post('master/user/store', 'UserController@store');
    Route::get('master/user/update', 'UserController@update');
    Route::get('master/user/destroy', 'UserController@destroy');

    // PRESENCE
    Route::get('presence', 'PresenceController@index');

    // SPREADING
    Route::get('transaction/spreading', 'SpreadingController@index');
   
    // UB
    Route::get('transaction/ub', 'UBController@index');
    Route::post('transactionDetail', 'UBController@getTransactionDetail');

    // UBLP
    Route::get('transaction/ublp', 'UBLPController@index');
    Route::post('transactionUBLPDetail', 'UBLPController@getTransactionDetail');