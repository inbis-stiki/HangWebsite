<?php

namespace App\Http\Controllers;

use App\ActivityCategory;
use App\CategoryProduct;
use App\Cronjob;
use App\Http\Controllers\Controller;
use App\Product;
use App\Recomendation;
use App\Regional;
use App\ReportQuery;
use App\UserRankingSale;
use App\UserRankingActivity;
use App\ReportRanking;
use App\ReportTransaction;
use App\ReportTrend;
use App\Shop;
use App\User;
use App\Users;
use Carbon\Carbon;
use Exception;
use App\TargetUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CronjobController extends Controller
{
    public function updateRecommendShop()
    {
        try {
            Shop::whereRaw("
                ISRECOMMEND_SHOP = '0'
                AND LASTTRANS_SHOP < DATE_SUB(CURDATE(), INTERVAL 14 DAY)
            ")->update(['ISRECOMMEND_SHOP' => 1]);
            
            return response([
                "status_code"       => 200,
                "status_message"    => 'Data berhasil diubah!'
            ], 200);
        } catch (Exception $exp) {
            return response([
                'status_code'       => 500,
                'status_message'    => $exp->getMessage(),
            ], 500);
        }
    }
    public function updateDashboardMobile(){
        try {
            $prodCategorys  = CategoryProduct::where('deleted_at', NULL)->get();
            $actCategorys   = ActivityCategory::where('deleted_at', NULL)->get();

            $month          = date('n', strtotime('-1 days'));
            $year           = date('Y', strtotime('-1 days'));
            $updated_at     = date('Y-m-d', strtotime('-1 days'))." 23:59:59";

            DB::table('dashboard_mobile')->delete();
            DB::table('transaction_detail_today')->delete();

            $queryCategory = [];
            
            $tgtUser        = app(TargetUser::class)->getUser();
            $tgtUST         = $tgtUser['prods']['UST'];
            $tgtNONUST      = $tgtUser['prods']['NONUST'];
            $tgtSeleraku    = $tgtUser['prods']['Seleraku'];
            $tgtRendang     = $tgtUser['prods']['Rendang'];
            $tgtGeprek      = $tgtUser['prods']['Geprek'];
            $tgtTotal       = $tgtUST + $tgtNONUST + $tgtSeleraku + $tgtRendang + $tgtGeprek;
            foreach ($prodCategorys as $prodCategory) {
                $queryCategory[] = "
                    COALESCE((
                    SELECT SUM(td.QTY_TD)
                        FROM 
                            `transaction` t
                        INNER JOIN transaction_detail td
                            ON 
                                YEAR(t.DATE_TRANS) = '".$year."'
                                AND MONTH(t.DATE_TRANS) = '".$month."'
                                AND t.ID_USER = u.ID_USER 
                                AND t.ID_TRANS = td.ID_TRANS
                                AND td.ID_PC = ".$prodCategory->ID_PC."
                    ), 0) as 'REAL".strtoupper(str_replace(' ', '', str_replace('-', '', $prodCategory->NAME_PC)))."_DM'
                ";
            }
            foreach ($actCategorys as $actCategory) {
                $queryCategory[] = "
                    COALESCE((
                        SELECT COUNT(*)
                        FROM `transaction` t
                        WHERE 
                            YEAR(t.DATE_TRANS) = ".$year."
                            AND MONTH(t.DATE_TRANS) = ".$month."
                            AND t.ID_USER = u.ID_USER 
                            AND t.TYPE_ACTIVITY = '".$actCategory->NAME_AC."'
                    ), 0) as 'REAL".strtoupper(str_replace(' ', '', str_replace('-', '', $actCategory->NAME_AC)))."_DM'
                ";
            }

            
            $queryCategory = $queryCategory != null ? implode(', ', $queryCategory) : "";
            $datas = [];
            $datas      = Cronjob::queryGetDashboardMobile($month, $year, $queryCategory, $tgtTotal);
            
            foreach ($datas as $data) {
                DB::table('dashboard_mobile')->insert([
                    'ID_USER'               => $data->ID_USER,
                    'UBUBLP_DM'             => $data->UBUBLP_DM,
                    'SPREADING_DM'          => $data->SPREADING_DM,
                    'OFFTARGET_DM'          => $data->OFFTARGET_DM,
                    'REALUST_DM'            => $data->REALUST_DM,
                    'REALNONUST_DM'         => $data->REALNONUST_DM,
                    'REALSELERAKU_DM'       => $data->REALSELERAKU_DM,
                    'REALRENDANG_DM'        => $data->REALRENDANG_DM,
                    'REALGEPREK_DM'         => $data->REALGEPREK_DM,
                    'REALACTUB_DM'          => $data->REALAKTIVITASUB_DM,
                    'REALACTPS_DM'          => $data->REALPEDAGANGSAYUR_DM,
                    'REALACTRETAIL_DM'      => $data->REALRETAIL_DM,
                    'updated_at'            => $updated_at
                ]);
            }
            
            
            return response([
                "status_code"       => 200,
                "status_message"    => 'Data berhasil diinsert!',
                "data"  => $datas
            ], 200);
        } catch (Exception $exp) {
            return response([
                'status_code'       => 500,
                'status_message'    => $exp->getMessage(),
            ], 500);
        }
    }
    public function updateSmyTransLocation(){
        try {
            $month          = date('n', strtotime('-1 days'));
            $year           = date('Y', strtotime('-1 days'));
            $updated_at     = date('Y-m-d', strtotime('-1 days'))." 23:59:59";

            DB::table('summary_trans_location')->where('YEAR_STL', $year)->where('MONTH_STL', $month)->delete();


            $datas = [];
            $datas  = Cronjob::queryGetSmyTransLocation($year, $month);
            
            foreach ($datas as $data) {
                DB::table('summary_trans_location')->insert([
                    'LOCATION_STL'      => $data->LOCATION_TRANS,
                    'REGIONAL_STL'      => $data->REGIONAL_TRANS,
                    'AREA_STL'          => $data->AREA_TRANS,
                    'REALUST_STL'       => $data->REALUST_STL,
                    'REALNONUST_STL'    => $data->REALNONUST_STL,
                    'REALSELERAKU_STL'  => $data->REALSELERAKU_STL,
                    'REALRENDANG_STL'   => $data->REALRENDANG_STL,
                    'REALGEPREK_STL'    => $data->REALGEPREK_STL,
                    'REALACTUB_STL'     => $data->REALACTUB_STL,
                    'REALACTPS_STL'     => $data->REALACTPS_STL,
                    'REALACTRETAIL_STL' => $data->REALACTRETAIL_STL,
                    'MONTH_STL'         => $month,
                    'YEAR_STL'          => $year,
                    'updated_at'        => $updated_at
                ]);
            }

            return response([
                "status_code"       => 200,
                "status_message"    => 'Data berhasil diinsert!',
                "data"  => $datas
            ], 200);
        } catch (Exception $exp) {
            return response([
                'status_code'       => 500,
                'status_message'    => $exp->getMessage(),
            ], 500);
        }
    }
    public function genRankRPO(){
        $month          = date('n', strtotime('-1 days'));
        $year           = date('Y', strtotime('-1 days'));
        $updated_at     = date('Y-m-d', strtotime('-1 days'));

        $datas['reports'] = Cronjob::queryGetRankLocation($year, $month, "Regional");
        app(ReportRanking::class)->generate_ranking_rpo($datas, $updated_at);
    }
    public function genRankAsmen(){
        $month          = date('n', strtotime('-1 days'));
        $year           = date('Y', strtotime('-1 days'));
        $updated_at     = date('Y-m-d', strtotime('-1 days'));

        $datas['reports']   = Cronjob::queryGetRankLocation($year, $month, "Location");
        app(ReportRanking::class)->generate_ranking_asmen($datas, $updated_at);
    }
    public function genRankAPOSPG(){
        $updated_at     = date('Y-m-d', strtotime('-1 days'));

        $datas['reportApos'] = Cronjob::queryGetRankUser(7, 5);
        $datas['reportSpgs'] = Cronjob::queryGetRankUser(7, 6);

        app(ReportRanking::class)->generate_ranking_apo_spg($datas, $updated_at);
    }
    public function genTrendRPO(){
        $year           = date('Y', strtotime('-1 days'));
        $updated_at     = date('Y-m-d', strtotime('-1 days'));

        $reports = Cronjob::queryGetTrend($year, 'Regional');
        app(ReportTrend::class)->generate_trend_rpo($reports, $updated_at);
    }
    public function genTrendAsmen(){
        $year           = date('Y', strtotime('-1 days'));
        $updated_at     = date('Y-m-d', strtotime('-1 days'));

        $reports = Cronjob::queryGetTrend($year, 'Location');
        app(ReportTrend::class)->generate_trend_asmen($reports, $updated_at);
    }
    public function genTransDaily(){
        $products       = Product::get();
        $querySumProd   = [];
        $idUsers        = null;
        $noTransDaily   = null;

        foreach ($products as $product) {
            $querySumProd[] = "
                COALESCE((
                    SELECT SUM(td2.QTY_TD) 
                    FROM transaction_detail td2
                    WHERE td2.ID_TRANS IN (
                        SELECT t.ID_TRANS 
                        FROM `transaction` t 
                        WHERE t.ID_TD = td.ID_TD 
                    ) AND td2.ID_PRODUCT = ".$product->ID_PRODUCT."
                ), 0) as '".$product->CODE_PRODUCT."'
            ";
        }

        $querySumProd = implode(',', $querySumProd);
        $transDaily     = Cronjob::queryGetTransactionDaily($querySumProd, "2023-01-15", "JABODETABEK");
        if($transDaily != null){
            $idUsers    = implode(',', array_map(function ($entry){
                return "'".$entry->ID_USER."'";
            }, $transDaily));

            $noTransDaily   = Users::getUserByRegional(3, $idUsers);
        }

        print_r(app(ReportTransaction::class)->generate_transaksi_harian($products, $transDaily, $noTransDaily, "JABODETABEK"));
    }

    public function TestTemplate(ReportQuery $reportQuery)
    {
        // app(ReportRanking::class)->generate_ranking_rpo($reportQuery->AktivitasRPOLapul(), $reportQuery->AktivitasRPODapul(), $reportQuery->PencapaianRPOLapul(), $reportQuery->PencapaianRPODapul());
        // app(ReportRanking::class)->generate_ranking_asmen($reportQuery->AktivitasAsmen(), $reportQuery->PencapaianAsmen());
        // app(ReportRanking::class)->generate_ranking_apo_spg();

        // app(ReportTransaction::class)->set_data_transaction();

        
        // $regionals  = Regional::where('deleted_at', NULL)->get();
        // Http::get(url('cronjob/generate-transaction/'."JATIM 1"));
        // foreach ($regionals as $regional) {
        // }
        

        // dd($transDaily);
        // dd($noTransDaily);

        // $regionals  = Regional::where('deleted_at', NULL)->get();
        

        // dd(date("Y-m-d H:i:s")); // returns a date in UTC timezone
        
        app(ReportRanking::class)->generate_ranking_rpo();

        // app(ReportTrend::class)->generate_trend_asmen();
        // app(ReportTrend::class)->generate_trend_rpo();
    }

    public function Testing(ReportQuery $reportQuery){
        return $reportQuery->TrendRpo();
    }

    public function updateDailyRankingAchievement()
    {
        
        $currDate       = date('Y-m-d', strtotime('-1 days'));
        $currDateTime   = date('Y-m-d', strtotime('-1 days'))." 23:59:59";

        $formData = [];
        $targetRegionals = $this->queryGetTargetRegional($currDate);

        foreach ($targetRegionals as $targetRegional) {
            if ($targetRegional->MONTH_TARGET != NULL) {
                $querySum       = [];
                $weightProdCat  = [];
                $targetProdCat  = [];
                $targetUserMonthlys = $this->queryGetTargetUserMonthly($currDate, $targetRegional);

                foreach ($targetUserMonthlys as $targetUserMonthly) {
                    $namePC = str_replace("-", "_", $targetUserMonthly->NAME_PC);
                    $querySum[] = "
                        SUM(
                            (
                                SELECT SUM(td.QTY_TD)
                                FROM transaction_detail td , md_product mp
                                WHERE 
                                    td.ID_TRANS = t.ID_TRANS  
                                    AND td.ID_PRODUCT = mp.ID_PRODUCT 
                                    AND mp.ID_PC = " . $targetUserMonthly->ID_PC . "
                            ) 
                        ) as " . $namePC . "
                    ";
                    $weightProdCat[$namePC] = $targetUserMonthly->PERCENTAGE_PC;
                    $targetProdCat[$namePC] = $targetUserMonthly->TARGET;
                }

                $transUsers = $this->queryGetTransUser($querySum, $currDate, $targetRegional);

                foreach ($transUsers as $transUser) {
                    $arrTemp['ID_USER']             = $transUser->ID_USER;
                    $arrTemp['ID_REGIONAL']         = $targetRegional->ID_REGIONAL;
                    $arrTemp['ID_LOCATION']         = $targetRegional->ID_LOCATION;
                    $arrTemp['ID_AREA']             = $transUser->ID_AREA;
                    $arrTemp['NAME_REGIONAL']       = $targetRegional->NAME_REGIONAL;
                    $arrTemp['NAME_AREA']           = $transUser->NAME_AREA;
                    $arrTemp['NAME_USER']           = $transUser->NAME_USER;
                    $arrTemp['ID_ROLE']             = $transUser->ID_ROLE;
                    $arrTemp['TARGET_UST']          = $targetProdCat['UST'];
                    $arrTemp['REAL_UST']            = $transUser->UST != NULL ? $transUser->UST : 0;
                    $arrTemp['VSTARGET_UST']        = ($arrTemp['REAL_UST'] / $arrTemp['TARGET_UST']) * 100;
                    $arrTemp['TARGET_NONUST']       = $targetProdCat['NON_UST'];
                    $arrTemp['REAL_NONUST']         = $transUser->NON_UST != NULL ? $transUser->NON_UST : 0;
                    $arrTemp['VSTARGET_NONUST']     = ($arrTemp['REAL_NONUST'] / $arrTemp['TARGET_NONUST']) * 100;
                    $arrTemp['TARGET_SELERAKU']     = $targetProdCat['Seleraku'];
                    $arrTemp['REAL_SELERAKU']       = $transUser->Seleraku != NULL ? $transUser->Seleraku : 0;
                    $arrTemp['VSTARGET_SELERAKU']   = ($arrTemp['REAL_SELERAKU'] / $arrTemp['TARGET_SELERAKU']) * 100;
                    $arrTemp['WEIGHT_UST']          = $weightProdCat['UST'];
                    $arrTemp['WEIGHT_NONUST']       = $weightProdCat['NON_UST'];
                    $arrTemp['WEIGHT_SELERAKU']     = $weightProdCat['Seleraku'];

                    $avgCount = 0;
                    if ($weightProdCat['UST'] != 0) $avgCount++;
                    if ($weightProdCat['NON_UST'] != 0) $avgCount++;
                    if ($weightProdCat['Seleraku'] != 0) $avgCount++;

                    $arrTemp['AVERAGE'] = ((($arrTemp['VSTARGET_UST'] / 100) * ($weightProdCat['UST'] / 100)));
                    $arrTemp['AVERAGE'] += (($arrTemp['VSTARGET_NONUST'] / 100) * ($weightProdCat['NON_UST'] / 100));
                    $arrTemp['AVERAGE'] += (($arrTemp['VSTARGET_SELERAKU'] / 100) * ($weightProdCat['Seleraku'] / 100));
                    $arrTemp['AVERAGE'] = $arrTemp['AVERAGE'] / $avgCount;

                    $arrTemp['created_at']  = $currDateTime;
                    $formData[] = $arrTemp;
                }
            }
        }
        if ($formData != null) {
            UserRankingSale::insert($formData);
        }
    }

    //ACTIVITY RANK
    public function updateDailyRankingActivity()
    {
        
        $currDate       = date('Y-m-d', strtotime('-1 days'));
        $currDateTime   = date('Y-m-d', strtotime('-1 days'))." 23:59:59";
        $formData = [];

        $targetRegionals = $this->queryGetTargetRegionalActivity($currDate);


        foreach ($targetRegionals as $targetRegional) {
            if ($targetRegional->MONTH_TARGET != NULL) {
                $weightActCat  = [];
                $targetActCat  = [];
                $targetUserMonthlys = $this->queryGetTargetUserMonthlyActivity($currDate, $targetRegional);

                foreach ($targetUserMonthlys as $targetUserMonthly) {
                    $nameAC = str_replace(" ", "_", $targetUserMonthly->NAME_AC);
                    $weightActCat[$nameAC] = $targetUserMonthly->PERCENTAGE_AC;
                    $targetActCat[$nameAC] = $targetUserMonthly->TARGET;
                }

                $transUsers = $this->queryGetTransUserActivity($currDate, $targetRegional);

                foreach ($transUsers as $transUser) {
                    $arrTemp['ID_USER']             = $transUser->ID_USER;
                    $arrTemp['ID_REGIONAL']         = $targetRegional->ID_REGIONAL;
                    $arrTemp['ID_LOCATION']         = $targetRegional->ID_LOCATION;
                    $arrTemp['ID_AREA']             = $transUser->ID_AREA;
                    $arrTemp['NAME_REGIONAL']       = $targetRegional->NAME_REGIONAL;
                    $arrTemp['NAME_AREA']           = $transUser->NAME_AREA;
                    $arrTemp['NAME_USER']           = $transUser->NAME_USER;
                    $arrTemp['ID_ROLE']             = $transUser->ID_ROLE;
                    $arrTemp['TARGET_UB']           = $targetActCat['AKTIVITAS_UB'];
                    $arrTemp['REAL_UB']             = $transUser->AKTIVITAS_UB != NULL ? $transUser->AKTIVITAS_UB : 0;
                    $arrTemp['VSTARGET_UB']         = ($arrTemp['REAL_UB'] / $arrTemp['TARGET_UB']) * 100;
                    $arrTemp['TARGET_PDGSAYUR']     = $targetActCat['PEDAGANG_SAYUR'];
                    $arrTemp['REAL_PDGSAYUR']       = $transUser->PEDAGANG_SAYUR != NULL ? $transUser->PEDAGANG_SAYUR : 0;
                    $arrTemp['VSTARGET_PDGSAYUR']   = ($arrTemp['REAL_PDGSAYUR'] / $arrTemp['TARGET_PDGSAYUR']) * 100;
                    $arrTemp['TARGET_RETAIL']       = $targetActCat['RETAIL'];
                    $arrTemp['REAL_RETAIL']         = $transUser->RETAIL != NULL ? $transUser->RETAIL : 0;
                    $arrTemp['VSTARGET_RETAIL']     = ($arrTemp['REAL_RETAIL'] / $arrTemp['TARGET_RETAIL']) * 100;
                    $arrTemp['WEIGHT_UB']           = $weightActCat['AKTIVITAS_UB'];
                    $arrTemp['WEIGHT_PDGSAYUR']     = $weightActCat['PEDAGANG_SAYUR'];
                    $arrTemp['WEIGHT_RETAIL']       = $weightActCat['RETAIL'];

                    $avgCount = 0;
                    if ($weightActCat['AKTIVITAS_UB'] != 0) $avgCount++;
                    if ($weightActCat['PEDAGANG_SAYUR'] != 0) $avgCount++;
                    if ($weightActCat['RETAIL'] != 0) $avgCount++;

                    $arrTemp['AVERAGE'] = ((($arrTemp['VSTARGET_UB'] / 100) * ($weightActCat['AKTIVITAS_UB'] / 100)));
                    $arrTemp['AVERAGE'] += (($arrTemp['VSTARGET_PDGSAYUR'] / 100) * ($weightActCat['PEDAGANG_SAYUR'] / 100));
                    $arrTemp['AVERAGE'] += (($arrTemp['VSTARGET_RETAIL'] / 100) * ($weightActCat['RETAIL'] / 100));
                    $arrTemp['AVERAGE'] = $arrTemp['AVERAGE'] / $avgCount;

                    $arrTemp['created_at']  = $currDateTime;
                    $formData[] = $arrTemp;
                }
            }
        }
        if ($formData != null) {
            UserRankingActivity::insert($formData);
        }
        dd($formData);
    }

    public function queryGetTargetRegional($currDate)
    {
        return DB::select("
            SELECT 
                mr.ID_REGIONAL, 
                ml.ID_LOCATION,
                mr.NAME_REGIONAL, 
                ma.ID_AREA ,
                COUNT(ma.ID_AREA) as TOTAL_AREA ,
                (
                    SELECT SUM(ts.QUANTITY) 
                    FROM target_sale ts 
                    WHERE 
                        DATE(ts.START_PP) <= '" . $currDate . "' 
                        AND DATE(ts.END_PP) >= '" . $currDate . "'
                        AND ts.ID_REGIONAL = ma.ID_REGIONAL 
                ) as MONTH_TARGET
            FROM md_area ma , md_regional mr , md_location ml
            WHERE 
                ma.deleted_at IS NULL
                AND ma.ID_REGIONAL = mr.ID_REGIONAL 
                AND mr.ID_LOCATION = ml.ID_LOCATION
            GROUP BY ma.ID_REGIONAL 
        ");
    }
    public function queryGetTargetUserMonthly($currDate, $targetRegional)
    {
        return DB::select("
            SELECT 
                ts.ID_REGIONAL ,
                mpc.ID_PC ,
                mp.ID_PRODUCT ,
                mpc.NAME_PC ,
                mpc.PERCENTAGE_PC ,
                ROUND(((SUM(ts.QUANTITY) / " . $targetRegional->TOTAL_AREA . ") / 3) / 25) as TARGET
            FROM 
                target_sale ts ,
                md_product mp ,
                md_product_category mpc 
            WHERE
                DATE(ts.START_PP) <= '" . $currDate . "' 
                AND DATE(ts.END_PP) >= '" . $currDate . "' 
                AND ts.ID_REGIONAL = " . $targetRegional->ID_REGIONAL . "
                AND ts.ID_PRODUCT = mp.ID_PRODUCT 
                AND mp.ID_PC = mpc.ID_PC 
            GROUP BY mpc.ID_PC 
        ");
    }
    public function queryGetTransUser($querySum, $currDate, $targetRegional)
    {
        return DB::select("
            SELECT 
                u.ID_USER ,
                u.NAME_USER ,
                u.ID_ROLE ,
                u.ID_AREA ,
                ma.NAME_AREA ,
                " . implode(', ', $querySum) . "
            FROM 
                `transaction` t ,
                `user` u ,
                md_area ma 
            WHERE
                DATE(t.DATE_TRANS) = '" . $currDate . "'
                AND u.ID_REGIONAL = " . $targetRegional->ID_REGIONAL . "
                AND t.ID_USER = u.ID_USER 
                AND ma.ID_AREA = u.ID_AREA 
            GROUP BY t.ID_USER 
        ");
    }

    //ACTIVITY RANK
    public function queryGetTargetRegionalActivity($currDate)
    {
        return DB::select("
            SELECT 
            mr.ID_REGIONAL, 
            ml.ID_LOCATION,
            mr.NAME_REGIONAL, 
            ma.ID_AREA ,
            COUNT(ma.ID_AREA) as TOTAL_AREA ,
            (
                    SELECT SUM(ta.QUANTITY) 
                    FROM target_activity ta 
                    WHERE 
                            DATE(ta.START_PP) <= '" . $currDate . "' 
                            AND DATE(ta.END_PP) >= '" . $currDate . "'
                            AND ta.ID_REGIONAL = ma.ID_REGIONAL 
            ) as MONTH_TARGET
            FROM md_area ma , md_regional mr , md_location ml
            WHERE 
                    ma.deleted_at IS NULL
                    AND ma.ID_REGIONAL = mr.ID_REGIONAL 
                    AND mr.ID_LOCATION = ml.ID_LOCATION
            GROUP BY ma.ID_REGIONAL 
        ");
    }
    public function queryGetTargetUserMonthlyActivity($currDate, $targetRegional)
    {
        return DB::select("
            SELECT 
            ta.ID_REGIONAL ,
            mac.ID_AC ,
            mac.NAME_AC ,
            mac.PERCENTAGE_AC ,
            ROUND(((SUM(ta.QUANTITY) / " . $targetRegional->TOTAL_AREA . ") / 3) / 25) as TARGET
            FROM 
                    target_activity ta ,
                    md_activity_category mac 
            WHERE
                    DATE(ta.START_PP) <= '" . $currDate . "' 
                    AND DATE(ta.END_PP) >= '" . $currDate . "' 
                    AND ta.ID_REGIONAL = " . $targetRegional->ID_REGIONAL . "
                    AND ta.ID_ACTIVITY = mac.ID_AC 
            GROUP BY mac.ID_AC
        ");
    }
    public function queryGetTransUserActivity($currDate, $targetRegional)
    {
        return DB::select("
        SELECT 
                u.ID_USER ,
                u.NAME_USER ,
                u.ID_ROLE ,
                u.ID_AREA ,
                ma.NAME_AREA ,
                SUM(
                    (
                        SELECT SUM(td.QTY_TD)
                            FROM transaction_detail td
                            WHERE 
                                td.ID_TRANS = t.ID_TRANS
                                AND t.ID_TYPE IN (2,3)
                    ) 
                ) as AKTIVITAS_UB ,
                SUM(
                    (
                        SELECT SUM(td.QTY_TD)
                            FROM transaction_detail td
                            , md_shop ms
                            WHERE 
                                td.ID_TRANS = t.ID_TRANS
                                AND td.ID_SHOP = t.ID_SHOP
                                AND t.ID_TYPE = 1
                                AND ms.ID_SHOP = t.ID_SHOP
                                AND ms.TYPE_SHOP = 'Pedagang Sayur'
                    ) 
                ) as PEDAGANG_SAYUR ,
                SUM(
                    (
                        SELECT SUM(td.QTY_TD)
                            FROM transaction_detail td
                            , md_shop ms
                            WHERE 
                                td.ID_TRANS = t.ID_TRANS
                                AND td.ID_SHOP = t.ID_SHOP
                                AND t.ID_TYPE = 1
                                AND ms.ID_SHOP = t.ID_SHOP
                                AND ms.TYPE_SHOP = 'Retail'
                    ) 
                ) as RETAIL
            FROM 
                `transaction` t ,
                `user` u ,
                md_area ma 
            WHERE
                DATE(t.DATE_TRANS) = '" . $currDate . "'
                AND u.ID_REGIONAL = " . $targetRegional->ID_REGIONAL . "
                AND t.ID_USER = u.ID_USER 
                AND ma.ID_AREA = u.ID_AREA 
            GROUP BY t.ID_USER 
        ");
    }
    public function tesQuery()
    {
        $data       = array();
        $currDate   = '2022-08-30';
        $idRegional = 7;

        $users = DB::select("
            SELECT 
                u.ID_USER ,
                u.NAME_USER , 
                mr.NAME_ROLE as ROLE_USER,
                mre.NAME_REGIONAL ,
                ma.NAME_AREA 
            FROM 
                `user` u,
                md_regional mre ,
                md_area ma ,
                md_role mr 
            WHERE
                u.ID_REGIONAL = '" . $idRegional . "'
                AND mr.ID_ROLE = u.ID_ROLE 
                AND mr.NAME_ROLE IN('APO', 'SALES')
                AND mre.ID_REGIONAL = u.ID_REGIONAL 
                AND ma.ID_AREA = u.ID_AREA 
            ORDER BY ma.ID_AREA  ASC, mr.NAME_ROLE ASC
        ");

        foreach ($users as $user) {
            $products = Product::where('deleted_at', NULL)->get();
            $sumQuery = [];
            foreach ($products as $product) {
                $sumQuery[] = "
                    (
                        SELECT SUM(td2.QTY_TD) as TOTAL
                        FROM transaction t, transaction_detail td2 
                        WHERE 
                            t.ID_USER = '" . $user->ID_USER . "'
                            AND DATE(t.DATE_TRANS) = '" . $currDate . "'
                            AND t.ID_TRANS = td2.ID_TRANS 
                            AND td2.ID_PRODUCT = " . $product->ID_PRODUCT . "
                    ) AS '" . $product->CODE_PRODUCT . "'
                ";
            }

            $trans = DB::select("
                SELECT 
                    mt.NAME_TYPE as TYPE,
                    td.ID_TD ,
                    " . implode(',', $sumQuery) . ",
                    (
                        SELECT SUM(td2.QTY_TD) as TOTAL
                        FROM transaction t, transaction_detail td2
                        WHERE
                            t.ID_USER = '" . $user->ID_USER . "'
                            AND DATE(t.DATE_TRANS) = '" . $currDate . "'
                            AND t.ID_TRANS = td2.ID_TRANS 
                    ) AS TOTAL_DISPLAY
                FROM 
                    transaction_daily td ,
                    md_type mt  
                WHERE 
                    td.ID_USER = '" . $user->ID_USER . "'
                    AND DATE(td.DATE_TD) = '" . $currDate . "'
                    AND mt.ID_TYPE = td.ID_TYPE 
            ");

            $temp['DATE_TRANS']     = '30 August 2022';
            $temp['NAME_USER']      = $user->NAME_USER;
            $temp['ROLE_USER']      = $user->ROLE_USER;
            $temp['AREA_TRANS']     = $user->ROLE_USER;
            $temp['TYPE']           = !empty($trans[0]->TYPE) ? $trans[0]->TYPE : '-';
            $temp['UST']            = !empty($trans[0]->UST20) ? $trans[0]->UST20 : '-';
            $temp['USU']            = !empty($trans[0]->USU18) ? $trans[0]->USU18 : '-';
            $temp['USP']            = !empty($trans[0]->USP20) ? $trans[0]->USP20 : '-';
            $temp['USI']            = !empty($trans[0]->USI20) ? $trans[0]->USI20 : '-';
            $temp['USTR']           = !empty($trans[0]->USTR18) ? $trans[0]->USTR18 : '-';
            $temp['USB']            = !empty($trans[0]->USB20) ? $trans[0]->USB20 : '-';
            $temp['USK']            = !empty($trans[0]->USK20) ? $trans[0]->USK20 : '-';
            $temp['USR']            = !empty($trans[0]->USR20) ? $trans[0]->USR20 : '-';
            $temp['UBNG']           = '-';
            $temp['FSU']            = !empty($trans[0]->FSU60) ? $trans[0]->FSU60 : '-';
            $temp['FSB']            = !empty($trans[0]->FSB60) ? $trans[0]->FSB60 : '-';
            $temp['TOTAL_DISPLAY']  = !empty($trans[0]->TOTAL_DISPLAY) ? $trans[0]->TOTAL_DISPLAY : '-';


            $data[] = $temp;
        }
        // dump();

        dd($data);
    }
}
