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

Route::post("get/pickup", 'api\TesApi@getpickup');

Route::middleware(['checkAuthApi'])->group(function(){
    // API TES
    Route::get("tes", 'api\TesApi@index');
    Route::get("tes/{any}", 'api\TesApi@detail');
    Route::post("tes", 'api\TesApi@store');
    Route::put("tes", 'api\TesApi@update');
    Route::delete("tes", 'api\TesApi@delete');

    // API PRESENCE
    Route::get("presence", 'api\PresenceApi@index');
    Route::get("presence/detail", 'api\PresenceApi@detail');
    Route::post("presence", 'api\PresenceApi@store');

    // API PRODUCT
    Route::get("product", 'api\ProductApi@index');
    
    // API DISTRICT
    Route::get("district", 'api\DistrictApi@index');
    Route::get("list/district", 'api\DistrictApi@listDistrict');
    
    // API PICKUP
    Route::post("pickup", 'api\PickupApi@store');
    Route::get("pickup", 'api\PickupApi@pickup');
    Route::get("cek/pickup", 'api\PickupApi@cekPickup');

    //API SHOP
    Route::post("shop", 'api\ShopApi@store');
    Route::get("cek/shop", 'api\ShopApi@cekAllowedTrans');
    Route::get("shop", 'api\ShopApi@list_store');
    Route::get("shop/rec", 'api\ShopApi@list_store_rekomendasi');
    Route::get("shop/route", 'api\ShopApi@route');
    Route::post("newshop", 'api\ShopApi@new_list');
    Route::put("updateshop", 'api\ShopApi@saveShop_test');
  
    //API TRANSACTION
    Route::post("transaction", "api\TransactionApi@store");
    Route::post("transactionimage", "api\TransactionImageApi@store");
    Route::post("transaction/ublp", "api\TransactionApi@ublp");
    Route::post("transactionimage/ublp", "api\TransactionImageApi@ublp");
    Route::post("transaction/ub", "api\TransactionApi@ubTransaction");
    Route::get("transaction/checkUBUBLP/{any}", "api\TransactionApi@checkUBUBLP");
    Route::post("transactionimage/ub", "api\TransactionImageApi@ubImage");
    Route::get("transaction/history", "api\TransactionApi@TransactionHistory");
    Route::get("transaction/detail", "api\TransactionApi@DetailVisit");
    Route::post("newtransactionimage", "api\TransactionImageApi@newImage");

    //Faktur
    Route::get('invoice', 'api\InvoiceApi@listproduct');
    Route::post('invoice', 'api\InvoiceApi@storedata');
    Route::get("cek/invoice", 'api\InvoiceApi@cekFaktur');

    // API DASHBOARD
    Route::get("product/sold", 'api\DashboardApi@ProductSold');
    Route::get("product/average", 'api\DashboardApi@AverageProductSold');
    Route::get("sales/progress", 'api\DashboardApi@SalesProgress');
    Route::get("sales/not-reach-target", 'api\DashboardApi@NotReachTarget');
    Route::get("dashboard/all", 'api\DashboardApi@AllApi');

    // API RANKING
    Route::get("ranking/sale", 'api\RankingApi@rankingSale');
    Route::get("ranking/activity", 'api\RankingApi@rankingActivity');

    // API RUTE
    Route::get("route/shop", 'api\RouteApi@GetDataRute');
    Route::post("route/update", 'api\RouteApi@UpdateStatusRoute');

});