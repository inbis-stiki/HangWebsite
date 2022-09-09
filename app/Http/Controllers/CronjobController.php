<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Recomendation;
use App\UserRankingSale;
use App\UserRankingActivity;
use App\ReportExcell;
use AWS\CRT\HTTP\Request;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class CronjobController extends Controller
{
    public function cronjob_store_rekomendasi()
    {
        try {
            DB::table("recomendation")->delete();

            $DataUser = DB::table("user")
                ->select('user.*')
                ->get();

            foreach ($DataUser as $ItemUser) {
                $id_user = $ItemUser->ID_USER;

                $Trans = DB::table("transaction")
                    ->select('transaction.*')
                    ->selectRaw('DATEDIFF("' . Carbon::now() . '", transaction.DATE_TRANS) AS DateDiff')
                    ->where('ID_USER', '=', $id_user)
                    ->where('ID_SHOP', '<>', NULL)
                    ->orderBy('DateDiff', 'ASC')
                    ->get();

                $dataRecom = array();
                foreach ($Trans as $dataTrans) {
                    $shopData = DB::table("md_shop")
                        ->select("md_shop.*")
                        ->where('md_shop.ID_SHOP', '=', $dataTrans->ID_SHOP)
                        ->first();

                    if ($dataTrans->DateDiff >= 14) {
                        $dataRespon = array(
                            "ID_USER" => $id_user,
                            "ID_SHOP" => $shopData->ID_SHOP,
                            "PHOTO_SHOP" => $shopData->PHOTO_SHOP,
                            "NAME_SHOP" => $shopData->NAME_SHOP,
                            "OWNER_SHOP" => $shopData->OWNER_SHOP,
                            "DETLOC_SHOP" => $shopData->DETLOC_SHOP,
                            "LONG_SHOP" => $shopData->LONG_SHOP,
                            "LAT_SHOP" => $shopData->LAT_SHOP,
                            "ID_DISTRICT" => $shopData->ID_DISTRICT,
                            "LAST_VISITED" => $dataTrans->DateDiff
                        );

                        array_push($dataRecom, $dataRespon);
                    }
                }

                foreach ($dataRecom as $RecomShop) {
                    $CheckRecom = DB::table("recomendation")
                        ->select("recomendation.*")
                        ->where('recomendation.ID_USER', '=', $RecomShop['ID_USER'])
                        ->where('recomendation.ID_SHOP', '=', $RecomShop['ID_SHOP'])
                        ->first();

                    if ($CheckRecom == null) {
                        $recomendation = new Recomendation();
                        $recomendation->ID_USER      = $RecomShop['ID_USER'];
                        $recomendation->ID_SHOP      = $RecomShop['ID_SHOP'];
                        $recomendation->NAME_SHOP    = $RecomShop['NAME_SHOP'];
                        $recomendation->DETLOC_SHOP  = $RecomShop['DETLOC_SHOP'];
                        $recomendation->PHOTO_SHOP   = $RecomShop['PHOTO_SHOP'];
                        $recomendation->IDLE_RECOM   = $RecomShop['LAST_VISITED'];
                        $recomendation->LAT_SHOP     = $RecomShop['LAT_SHOP'];
                        $recomendation->LONG_SHOP    = $RecomShop['LONG_SHOP'];
                        $recomendation->ID_DISTRICT  = $RecomShop['ID_DISTRICT'];
                        $recomendation->DATE_RECOM   = date('Y-m-d');
                        $recomendation->save();
                    }
                }
            }

            return response([
                "status_code"       => 200,
                "status_message"    => 'Data berhasil disimpan!'
            ], 200);
        } catch (Exception $exp) {
            return response([
                'status_code'       => 500,
                'status_message'    => $exp->getMessage(),
            ], 500);
        }
    }

    public function TestTemplate()
    {
        // app(ReportExcell::class)->generate_ranking_rpo();
        // app(ReportExcell::class)->generate_ranking_asmen();
        // app(ReportExcell::class)->generate_transaksi_harian();
        // app(ReportExcell::class)->generate_trend_asmen();
        app(ReportExcell::class)->generate_trend_rpo();
    }

    public function updateDailyRanking(){
        date_default_timezone_set("Asia/Bangkok");
        $currDate       = date('Y-m-d');
        $currDateTime   = date('Y-m-d H:i:s');
        $formData = [];

        $targetRegionals = $this->queryGetTargetRegional($currDate);

        foreach ($targetRegionals as $targetRegional) {
            if($targetRegional->MONTH_TARGET != NULL){
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
                                    AND mp.ID_PC = ".$targetUserMonthly->ID_PC."
                            ) 
                        ) as ".$namePC."
                    ";
                    $weightProdCat[$namePC] = $targetUserMonthly->PERCENTAGE_PC;
                    $targetProdCat[$namePC] = $targetUserMonthly->TARGET;
                }

                $transUsers = $this->queryGetTransUser($querySum, $currDate, $targetRegional);

                foreach ($transUsers as $transUser) {
                    $arrTemp['ID_USER']             = $transUser->ID_USER;
                    $arrTemp['ID_REGIONAL']         = $targetRegional->ID_REGIONAL;
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
                    if($weightProdCat['UST'] != 0) $avgCount++;
                    if($weightProdCat['NON_UST'] != 0) $avgCount++;
                    if($weightProdCat['Seleraku'] != 0) $avgCount++;

                    $arrTemp['AVERAGE'] = ((($arrTemp['VSTARGET_UST'] / 100) * ($weightProdCat['UST'] / 100)));
                    $arrTemp['AVERAGE'] += (($arrTemp['VSTARGET_NONUST'] / 100) * ($weightProdCat['NON_UST'] / 100));
                    $arrTemp['AVERAGE'] += (($arrTemp['VSTARGET_SELERAKU'] / 100) * ($weightProdCat['Seleraku'] / 100));
                    $arrTemp['AVERAGE'] = $arrTemp['AVERAGE'] / $avgCount;

                    $arrTemp['created_at']  = $currDateTime;
                    $formData[] = $arrTemp;
                }
            }
        }
        if($formData != null){
            UserRankingSale::insert($formData);
        }
        dd($formData);
    }

    //ACTIVITY RANK
    public function updateDailyRankingActivity(){
        date_default_timezone_set("Asia/Bangkok");
        $currDate       = date('Y-m-d');
        $currDateTime   = date('Y-m-d H:i:s');
        $formData = [];

        $targetRegionals = $this->queryGetTargetRegionalActivity($currDate);
        

        foreach ($targetRegionals as $targetRegional) {
            if($targetRegional->MONTH_TARGET != NULL){
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
                    if($weightActCat['AKTIVITAS_UB'] != 0) $avgCount++;
                    if($weightActCat['PEDAGANG_SAYUR'] != 0) $avgCount++;
                    if($weightActCat['RETAIL'] != 0) $avgCount++;

                    $arrTemp['AVERAGE'] = ((($arrTemp['VSTARGET_UB'] / 100) * ($weightActCat['AKTIVITAS_UB'] / 100)));
                    $arrTemp['AVERAGE'] += (($arrTemp['VSTARGET_PDGSAYUR'] / 100) * ($weightActCat['PEDAGANG_SAYUR'] / 100));
                    $arrTemp['AVERAGE'] += (($arrTemp['VSTARGET_RETAIL'] / 100) * ($weightActCat['RETAIL'] / 100));
                    $arrTemp['AVERAGE'] = $arrTemp['AVERAGE'] / $avgCount;

                    $arrTemp['created_at']  = $currDateTime;
                    $formData[] = $arrTemp;
                }
            }
        }
        if($formData != null){
            UserRankingActivity::insert($formData);
        }
        dd($formData);
    }

    public function queryGetTargetRegional($currDate){
        return DB::select("
            SELECT 
                mr.ID_REGIONAL, 
                mr.NAME_REGIONAL, 
                ma.ID_AREA ,
                COUNT(ma.ID_AREA) as TOTAL_AREA ,
                (
                    SELECT SUM(ts.QUANTITY) 
                    FROM target_sale ts 
                    WHERE 
                        DATE(ts.START_PP) <= '".$currDate."' 
                        AND DATE(ts.END_PP) >= '".$currDate."'
                        AND ts.ID_REGIONAL = ma.ID_REGIONAL 
                ) as MONTH_TARGET
            FROM md_area ma , md_regional mr 
            WHERE 
                ma.deleted_at IS NULL
                AND ma.ID_REGIONAL = mr.ID_REGIONAL 
            GROUP BY ma.ID_REGIONAL 
        ");
    }
    public function queryGetTargetUserMonthly($currDate, $targetRegional){
        return DB::select("
            SELECT 
                ts.ID_REGIONAL ,
                mpc.ID_PC ,
                mp.ID_PRODUCT ,
                mpc.NAME_PC ,
                mpc.PERCENTAGE_PC ,
                ROUND(((SUM(ts.QUANTITY) / ".$targetRegional->TOTAL_AREA.") / 3) / 25) as TARGET
            FROM 
                target_sale ts ,
                md_product mp ,
                md_product_category mpc 
            WHERE
                DATE(ts.START_PP) <= '".$currDate."' 
                AND DATE(ts.END_PP) >= '".$currDate."' 
                AND ts.ID_REGIONAL = ".$targetRegional->ID_REGIONAL."
                AND ts.ID_PRODUCT = mp.ID_PRODUCT 
                AND mp.ID_PC = mpc.ID_PC 
            GROUP BY mpc.ID_PC 
        ");
    }
    public function queryGetTransUser($querySum, $currDate, $targetRegional){
        return DB::select("
            SELECT 
                u.ID_USER ,
                u.NAME_USER ,
                u.ID_ROLE ,
                u.ID_AREA ,
                ma.NAME_AREA ,
                ".implode(', ', $querySum)."
            FROM 
                `transaction` t ,
                `user` u ,
                md_area ma 
            WHERE
                DATE(t.DATE_TRANS) = '".$currDate."'
                AND u.ID_REGIONAL = ".$targetRegional->ID_REGIONAL."
                AND t.ID_USER = u.ID_USER 
                AND ma.ID_AREA = u.ID_AREA 
            GROUP BY t.ID_USER 
        ");
    }

    //ACTIVITY RANK
    public function queryGetTargetRegionalActivity($currDate){
        return DB::select("
            SELECT 
            mr.ID_REGIONAL, 
            mr.NAME_REGIONAL, 
            ma.ID_AREA ,
            COUNT(ma.ID_AREA) as TOTAL_AREA ,
            (
                    SELECT SUM(ta.QUANTITY) 
                    FROM target_activity ta 
                    WHERE 
                            DATE(ta.START_PP) <= '".$currDate."' 
                            AND DATE(ta.END_PP) >= '".$currDate."'
                            AND ta.ID_REGIONAL = ma.ID_REGIONAL 
            ) as MONTH_TARGET
            FROM md_area ma , md_regional mr 
            WHERE 
                    ma.deleted_at IS NULL
                    AND ma.ID_REGIONAL = mr.ID_REGIONAL 
            GROUP BY ma.ID_REGIONAL 
        ");
    }
    public function queryGetTargetUserMonthlyActivity($currDate, $targetRegional){
        return DB::select("
            SELECT 
            ta.ID_REGIONAL ,
            mac.ID_AC ,
            mac.NAME_AC ,
            mac.PERCENTAGE_AC ,
            ROUND(((SUM(ta.QUANTITY) / ".$targetRegional->TOTAL_AREA.") / 3) / 25) as TARGET
            FROM 
                    target_activity ta ,
                    md_activity_category mac 
            WHERE
                    DATE(ta.START_PP) <= '".$currDate."' 
                    AND DATE(ta.END_PP) >= '".$currDate."' 
                    AND ta.ID_REGIONAL = ".$targetRegional->ID_REGIONAL."
                    AND ta.ID_ACTIVITY = mac.ID_AC 
            GROUP BY mac.ID_AC
        ");
    }
    public function queryGetTransUserActivity($currDate, $targetRegional){
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
                DATE(t.DATE_TRANS) = '".$currDate."'
                AND u.ID_REGIONAL = ".$targetRegional->ID_REGIONAL."
                AND t.ID_USER = u.ID_USER 
                AND ma.ID_AREA = u.ID_AREA 
            GROUP BY t.ID_USER 
        ");
    }
}
