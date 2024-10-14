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
Route::get('monitoringexcel', 'MonitoringExcel@index');
Route::get('pdgsayurapoexcel', 'MonitoringExcel@pdgSayur');
Route::get('testimage', 'ImageController@create');
Route::get('testexcel', 'ImageController@excel');
Route::post('testimage', 'ImageController@store');
Route::get('testimage/{image}', 'ImageController@show');
Route::get('testTemplate', 'CronjobController@TestTemplate');
Route::get('tesQuery', 'CronjobController@tesQuery');
Route::get('listDatePresence', 'CronjobController@listDatePresence');
Route::get('tes', 'CronjobController@TestTemplate');
Route::get('tes/tanggal', 'TestController@TestDate');
Route::get('tes/perform', 'TestController@TestPerformance');

//Login
Route::get('/', 'AuthController@login');
Route::get('/', 'AuthController@login')->name('login');
Route::post('auth', 'AuthController@auth');
Route::get('logout', 'AuthController@logout');
Route::get('cronjob/update-shop-recommendation', 'CronjobController@updateRecommendShop');
Route::get('cronjob/rangking', 'CronjobController@cronjob_template_rangking');
Route::get('cronjob/update-daily-rangking-ac/transactionhievement', 'CronjobController@updateDailyRankingAchievement');
Route::get('cronjob/update-daily-rangking-activity', 'CronjobController@updateDailyRankingActivity');
Route::get('cronjob/update-dashboard-mobile', 'CronjobController@updateDashboardMobile');
Route::get('cronjob/update-summary-trans-location', 'CronjobController@updateSmyTransLocation');
Route::get('cronjob/update-summary-trans-user', 'CronjobController@updateSmyTransUser');
Route::post('report/rank-apospg', 'CronjobController@genRankAPOSPG');
Route::get('report/rank-rpo/{yearMonth}', 'CronjobController@genRankRPO');
Route::get('report/rank-asmen/{yearMonth}', 'CronjobController@genRankAsmen');
Route::get('report/trend-rpo/{year}', 'CronjobController@genTrendRPO');
Route::get('report/trend-asmen/{year}', 'CronjobController@genTrendAsmen');
Route::post('report/trans-daily', 'CronjobController@genTransDaily');
Route::get('cronjob/gen-ro-rpo/{yearMonth}', 'CronjobController@genRORPO');
Route::get('cronjob/gen-ro-shop/{yearMonth}', 'CronjobController@genROSHOP');
Route::get('cronjob/gen-ro-shop-range', 'CronjobController@genROSHOPbyRange');
Route::get('cronjob/gen-ro-shopcat-range', 'CronjobController@genROCATShopRange');
Route::get('cronjob/gen-ro-shop-rcat/{yearMonth}', 'CronjobController@genRORCAT');
Route::get('cronjob/gen-ro-rpo-s/{yearMonth}', 'CronjobController@genRORPOS');
Route::get('cronjob/gen-ro-s-daily/{yearMonth}', 'CronjobController@genRORPOSDaily');
Route::get('cronjob/gen-omset-data/{idRegional}/{yearMonth}', 'CronjobController@generateOmsetReport');
Route::get('cronjob/gen-update-omset-data/{idRegional}/{yearMonth}', 'CronjobController@generateUpdateOmset');

Route::get('cronjob/gen-performance', 'CronjobController@genPerformance');
Route::get('cronjob/gen-performance-geprek', 'CronjobController@genPerformanceGEPREK');
Route::get('cronjob/gen-performance-rendang', 'CronjobController@genPerformanceRENDANG');
Route::get('cronjob/gen-performance-ust', 'CronjobController@genPerformanceUST');
Route::get('cronjob/gen-performance-rekap/{year}', 'CronjobController@genPerformanceREKAP');

Route::get('cronjob/aktivitasrpodapul', 'CronjobController@AktivitasRPODapul');
Route::get('cronjob/aktivitasrpolapul', 'CronjobController@AktivitasRPOLapul');
Route::get('cronjob/aktivitasasmen', 'CronjobController@AktivitasAsmen');
Route::get('cronjob/pencapaianasmen', 'CronjobController@PencapaianAsmen');
Route::get('cronjob/tesQuery', 'CronjobController@Testing');

Route::get('cronjob/gen-ro-test', 'CronjobController@genROTEST');
Route::get('cronjob/gen-ro-vs-test', 'CronjobController@genROVSTEST');
Route::get('cronjob/gen-ro-vs-test/{year}', 'CronjobController@genROVSCALLIN');
Route::get('cronjob/gen-akt-trx-apo/{year}', 'CronjobController@genAktTRXAPO');
Route::get('cronjob/gen-rt-per-shop/{year}', 'CronjobController@genRTPerShop');
Route::get('cronjob/gen-ro-trans-shop', 'CronjobController@genROTransToko');
Route::get('cronjob/gen-ro-rutin-shop', 'CronjobController@genRORutinToko');

Route::get('cronjob/split', 'CronjobController@splitRoutesForArea');

Route::get('shop/bydistrict', 'ShopController@ShopListByDistrict');

Route::group(['middleware' => ['checkLogin']], function () {
    // MASTER DASHBORAD
    Route::get('dashboard', 'DashboardController@index');
    Route::get('dashboard/ranking_activity', 'DashboardController@ranking_activity');
    Route::get('dashboard/ranking_sale', 'DashboardController@ranking_sale');
    // Dashboard LineChart
    Route::post('dashboard/trend_asmen', 'DashboardController@trend_asmen');
    Route::post('dashboard/trend_rpo', 'DashboardController@trend_rpo');
    Route::post('dashboard/trend_apo', 'DashboardController@trend_apo');
    // Dashboard BarChart
    Route::post('dashboard/aktivitas', 'DashboardController@total_activity');

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

    Route::get('master/location/market/getDistrict', 'MarketController@get_district_by_area');

    // MASTER HARGA REGIONAL
    Route::get('master/regional-price', 'RegionalPriceController@index');
    Route::post('master/regional-price/store', 'RegionalPriceController@store');
    Route::get('master/regional-price/update', 'RegionalPriceController@update');
    Route::get('master/regional-price/destroy', 'RegionalPriceController@destroy');
    Route::get('master/regional-price/download_template', 'RegionalPriceController@download_template');

    // MASTER TARGET AKTIVITAS
    Route::get('master/target-activity', 'TargetActivityController@index');
    Route::post('master/target-activity/store', 'TargetActivityController@store');
    Route::get('master/target-activity/update', 'TargetActivityController@update');
    Route::get('master/target-activity/destroy', 'TargetActivityController@destroy');
    Route::get('master/target-activity/download_template', 'TargetActivityController@download_template');

    // MASTER TARGET PENJUALAN
    Route::get('master/target-sale', 'TargetSaleController@index');
    Route::post('master/target-sale/store', 'TargetSaleController@store');
    Route::get('master/target-sale/update', 'TargetSaleController@update');
    Route::get('master/target-sale/destroy', 'TargetSaleController@destroy');
    Route::get('master/target-sale/download_template', 'TargetSaleController@download_template');

    //MASTER KATEGORI PRODUK
    Route::get('master/category-product', 'CategoryProductController@index');
    Route::get('master/category-product/store', 'CategoryProductController@store');
    Route::get('master/category-product/update', 'CategoryProductController@update');
    Route::get('master/category-product/destroy', 'CategoryProductController@destroy');
    Route::get('master/grouping/search', 'CategoryProductController@search');
    Route::post('master/grouping/store', 'CategoryProductController@storegroup');

    //MASTER PRODUK
    Route::get('master/product', 'ProductController@index');
    Route::post('master/product/store', 'ProductController@store');
    Route::post('master/product/update', 'ProductController@update');
    Route::get('master/product/destroy', 'ProductController@destroy');

    //MASTER SHOP
    Route::get('master/shop', 'ShopController@index');
    Route::post('master/shop/AllShop', 'ShopController@AllShop');
    Route::get('master/shop/store', 'ShopController@store');
    Route::post('master/shop/update', 'ShopController@update');
    Route::get('master/shop/destroy', 'ShopController@destroy');

    //MASTER ROUTE
    Route::get('master/rute', 'RouteController@index');
    Route::get('master/rute/edit', 'RouteController@index_edit');
    Route::get('master/rute/create', 'RouteController@create');
    Route::get('master/rute/search-shops', 'RouteController@searchShops');
    Route::post('master/rute/store', 'RouteController@store');
    Route::post('master/rute/update', 'RouteController@update');

    // MASTER USER
    Route::get('master/user', 'UserController@index');
    Route::post('master/user/store', 'UserController@store');
    Route::post('master/user/changePass', 'UserController@changePass');
    Route::get('master/user/update', 'UserController@update');
    Route::get('master/user/destroy', 'UserController@destroy');

    // MASTER ROLE
    Route::group(['middleware' => ['checkRole:1']], function () {
        Route::get('master/role', 'RoleController@index');
        Route::get('master/role/store', 'RoleController@store');
        Route::get('master/role/update', 'RoleController@update');
        Route::get('master/role/destroy', 'RoleController@destroy');
    });

    // MASTER MONITORING
    Route::get('monitoring', 'MonitoringController@index');
    Route::get('monitoring/download-presence-daily-pdf', 'MonitoringController@downloadPresenceDaily_pdf');
    Route::get('monitoring/download-presence-daily-xlsx', 'MonitoringController@downloadPresenceDaily_xlsx');
    Route::get('monitoring/download-presence-monthly-pdf', 'MonitoringController@downloadPresenceMonthly_pdf');
    Route::get('monitoring/download-presence-monthly-xlsx', 'MonitoringController@downloadPresenceMonthly_xlsx');
    Route::post('monitoring/monitoring-data', 'MonitoringController@monitoring_data');

    // PRESENCE
    Route::get('presence', 'PresenceController@index');
    Route::post('presence/AllPresence', 'PresenceController@getAllPresence');

    // TRANSAKSI
    Route::get('transaction', 'TransactionController@index');
    Route::get('transaction/spread', 'DetailTransController@DetailSpread');
    Route::get('transaction/ub', 'DetailTransController@DetailUB');
    Route::get('transaction/ublp', 'DetailTransController@DetailUBLP');
    Route::post('master/transaction/Alltransaction', 'TransactionController@getAllTrans');

    //FAKTUR
    Route::get('faktur', 'FakturController@index');
    Route::get('detail/faktur', 'FakturController@DetailFaktur');
    Route::post('master/faktur/AllFaktur', 'FakturController@getAllFaktur');

    //MASTER ACTIVITY CATEGORY
    Route::get('master/activity-category', 'ActivityCategoryController@index');
    Route::get('master/activity-category/update', 'ActivityCategoryController@update');

    // OMSET
    Route::post('master/all-omset-data', 'ReportOmsetController@pipeline_datatable');
    
    //LAPORAN
    Route::get('laporan/lpr-shop', 'ReportShopController@index');
    Route::post('laporan/lpr-shop/download', 'ReportShopController@download');
    Route::get('laporan/lpr-shop/{any}', 'ReportShopController@get');
    Route::get('laporan/lpr-ranking', 'ReportRankingController@index');
    Route::get('laporan/lpr-trend', 'ReportTrendController@index');
    Route::get('laporan/lpr-repeat', 'ReportRepeatController@index');
    Route::get('laporan/lpr-repeat-cat', 'ReportRepeatController@index_repeat_cat');
    Route::get('laporan/lpr-transaction', 'ReportTransactionController@index');
    Route::get('laporan/lpr-performance', 'ReportPerformanceController@index');
    Route::get('laporan/lpr-omset', 'ReportOmsetController@index');
});
