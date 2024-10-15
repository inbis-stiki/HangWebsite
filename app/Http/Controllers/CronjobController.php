<?php

namespace App\Http\Controllers;

use App\ActivityCategory;
use App\Area;
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
use App\Rovscall;
use App\Rovscalldet;
use App\RangeRepeat;
use App\ReportRtHead;
use App\ReportRtDetail;
use App\ReportOmsetHead;
use App\ReportOmsetDetail;
use App\ReportAktivitasTRX;
use App\ReportPerformance;
use App\ReportRepeatOrder;
use App\Shop;
use App\Route;
use App\ReportShopHead;
use App\ReportShopDet;
use App\ReportRcatHead;
use App\ReportRcatDetail;
use App\SplitRoute;
use App\User;
use App\Users;
use Carbon\Carbon;
use Exception;
use App\TargetUser;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

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
    public function updateDashboardMobile()
    {
        try {
            $prodCategorys  = CategoryProduct::where('deleted_at', NULL)->get();
            $actCategorys   = ActivityCategory::where('deleted_at', NULL)->get();

            $month          = date('n', strtotime('-1 days'));
            $year           = date('Y', strtotime('-1 days'));
            $updated_at     = date('Y-m-d', strtotime('-1 days')) . " 23:59:59";

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
                                YEAR(t.DATE_TRANS) = '" . $year . "'
                                AND MONTH(t.DATE_TRANS) = '" . $month . "'
                                AND t.ID_USER = u.ID_USER 
                                AND t.ID_TRANS = td.ID_TRANS
                                AND td.ID_PC = " . $prodCategory->ID_PC . "
                    ), 0) as 'REAL" . strtoupper(str_replace(' ', '', str_replace('-', '', $prodCategory->NAME_PC))) . "_DM'
                ";
            }
            foreach ($actCategorys as $actCategory) {
                $queryCategory[] = "
                    COALESCE((
                        SELECT COUNT(*)
                        FROM `transaction` t
                        WHERE 
                            YEAR(t.DATE_TRANS) = " . $year . "
                            AND MONTH(t.DATE_TRANS) = " . $month . "
                            AND t.ID_USER = u.ID_USER 
                            AND t.TYPE_ACTIVITY = '" . $actCategory->NAME_AC . "'
                    ), 0) as 'REAL" . strtoupper(str_replace(' ', '', str_replace('-', '', $actCategory->NAME_AC))) . "_DM'
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
    public function updateSmyTransLocation()
    {
        try {
            $month          = date('n', strtotime('June'));
            $year           = date('Y', strtotime('-1 days'));
            $updated_at     = date('Y-m-d', strtotime('-1 days')) . " 23:59:59";

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
    public function updateSmyTransUser()
    {
        try {
            $prodCategorys  = CategoryProduct::where('deleted_at', NULL)->get();
            $actCategorys   = ActivityCategory::where('deleted_at', NULL)->get();

            $month          = date('n', strtotime('June'));
            $year           = date('Y', strtotime('-1 days'));
            $updated_at     = date('Y-m-d', strtotime('-1 days')) . " 23:59:59";

            DB::table('summary_trans_user')->where([['YEAR', "=", $year], ['MONTH', "=", "$month"]])->delete();

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
                                YEAR(t.DATE_TRANS) = '" . $year . "'
                                AND MONTH(t.DATE_TRANS) = '" . $month . "'
                                AND t.ID_USER = u.ID_USER 
                                AND t.ID_TRANS = td.ID_TRANS
                                AND td.ID_PC = " . $prodCategory->ID_PC . "
                    ), 0) as 'REAL" . strtoupper(str_replace(' ', '', str_replace('-', '', $prodCategory->NAME_PC))) . "_DM'
                ";
            }
            foreach ($actCategorys as $actCategory) {
                $queryCategory[] = "
                    COALESCE((
                        SELECT COUNT(*)
                        FROM `transaction` t
                        WHERE 
                            YEAR(t.DATE_TRANS) = " . $year . "
                            AND MONTH(t.DATE_TRANS) = " . $month . "
                            AND t.ID_USER = u.ID_USER 
                            AND t.TYPE_ACTIVITY = '" . $actCategory->NAME_AC . "'
                    ), 0) as 'REAL" . strtoupper(str_replace(' ', '', str_replace('-', '', $actCategory->NAME_AC))) . "_DM'
                ";
            }


            $queryCategory = $queryCategory != null ? implode(', ', $queryCategory) : "";
            $datas = [];
            $datas      = Cronjob::queryGetDashboardMobile($month, $year, $queryCategory, $tgtTotal);

            foreach ($datas as $data) {
                DB::table('summary_trans_user')->insert([
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
                    'updated_at'            => $updated_at,
                    'ID_ROLE'               => $data->ID_ROLE,
                    'ID_LOCATION'           => $data->ID_LOCATION,
                    'ID_REGIONAL'           => $data->ID_REGIONAL,
                    'ID_AREA'               => $data->ID_AREA,
                    'YEAR'                  => $year,
                    'MONTH'                 => $month,
                    'NAME_USER'             => $data->NAME_USER
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
    public function genRankRPO($yearMonth)
    {
        // $month          = date('n', strtotime('-1 days'));
        // $year           = date('Y', strtotime('-1 days'));
        // $updated_at     = date('Y-m-d', strtotime('-1 days'));

        $year = date_format(date_create($yearMonth), 'Y');
        $month = date_format(date_create($yearMonth), 'n');

        $datas['reports'] = Cronjob::queryGetRankLocation($year, $month, "Regional");
        app(ReportRanking::class)->generate_ranking_rpo($datas, $yearMonth);
    }
    public function genRankAsmen($yearMonth)
    {
        // $month          = date('n', strtotime('-1 days'));
        // $year           = date('Y', strtotime('-1 days'));
        // $updated_at     = date('Y-m-d', strtotime('-1 days'));

        $year = date_format(date_create($yearMonth), 'Y');
        $month = date_format(date_create($yearMonth), 'n');

        $datas['reports']   = Cronjob::queryGetRankLocation($year, $month, "Location");
        app(ReportRanking::class)->generate_ranking_asmen($datas, $yearMonth);
    }
    public function genRankAPOSPG()
    {
        $year       = date_format(date_create($_POST['date']), 'Y');
        $month      = date_format(date_create($_POST['date']), 'n');
        $yearMonth  = date_format(date_create($_POST['date']), 'Y-n');

        $datas['reportApos'] = Cronjob::queryGetRankUser($_POST['idRegional'], 5, $year, $month);
        $datas['reportSpgs'] = Cronjob::queryGetRankUser($_POST['idRegional'], 6, $year, $month);

        app(ReportRanking::class)->generate_ranking_apo_spg($datas, $yearMonth);
    }
    public function genTrendRPO($year)
    {
        $reports = Cronjob::queryGetTrend($year, 'Regional');
        app(ReportTrend::class)->generate_trend_rpo($reports, $year);
    }
    public function genTrendAsmen($year)
    {
        $reports = Cronjob::queryGetTrend($year, 'Location');
        app(ReportTrend::class)->generate_trend_asmen($reports, $year);
    }
    public function genTransDaily()
    {
        $nRegional  = Regional::find($_POST['idRegional'])->NAME_REGIONAL;
        $date       = date_format(date_create($_POST['date']), 'Y-m-d');
        $products   = Product::where('deleted_at', NULL)->orderBy('ORDER_GROUPING', 'ASC')->get();
        $querySumProd = [];
        $idUsers    = null;
        $noTransDaily = null;

        foreach ($products as $product) {
            $querySumProd[] = "
                COALESCE((
                    SELECT sum(td2.QTY_TD)
                    FROM transaction_detail td2
                    JOIN transaction t ON t.ID_TRANS  = td2.ID_TRANS  
                    JOIN md_type mt2 ON t.ID_TYPE = mt2.ID_TYPE
                    WHERE t.ID_TD = td.ID_TD
                    AND td2.ID_PRODUCT = " . $product->ID_PRODUCT . " AND mt.NAME_TYPE = mt2.NAME_TYPE
                ), 0) as '" . $product->CODE_PRODUCT . "'
            ";
        }

        $querySumProd = implode(',', $querySumProd);
        $transDaily   = Cronjob::queryGetTransactionDaily($querySumProd, $date, $nRegional);

        if ($transDaily != null) {
            $idUsers    = implode(',', array_map(function ($entry) {
                return "'" . $entry->ID_USER . "'";
            }, $transDaily));

            $noTransDaily = Users::getUserByRegional($_POST['idRegional'], $idUsers);
        }
        
        $dataProductGroup = DB::select("
            SELECT
                mp.* ,
                mg.NAME_GROUP AS GROUP_PRODUCT,
                COALESCE((
                    SELECT pp.PRICE_PP
                    FROM product_price pp
                    LEFT JOIN md_regional mr ON mr.ID_REGIONAL = pp.ID_REGIONAL
                    WHERE mr.NAME_REGIONAL = '$nRegional'
                    AND pp.ID_PRODUCT = mp.ID_PRODUCT
                ), 0) AS PRICE_PP
            FROM
                md_product mp
            LEFT JOIN md_product_category mpc ON mpc.ID_PC = mp.ID_PC
            LEFT JOIN md_grouping mg ON mg.ID_GROUP = mpc.ID_GROUP
            WHERE mp.deleted_at IS NULL
            ORDER BY mp.ORDER_GROUPING ASC
        ");

        $groupProduct = [];
        foreach ($dataProductGroup as $item) {
            $groupProduct['GROUP_' . $item->GROUP_PRODUCT][] = json_decode(json_encode($item), true);
        }

        $result = [];
        foreach ($transDaily as $trans) {
            foreach ($groupProduct as $groupKey => $products) {
                $userEntry = [
                    "ID_USER" => $trans->ID_USER,
                    "NAME_USER" => $trans->NAME_USER,
                    "NAME_TYPE" => $trans->NAME_TYPE,
                    "AREA_TD" => $trans->AREA_TD,
                    "DISTRICT" => $trans->DISTRICT,
                    "DETAIL_LOCATION" => $trans->DETAIL_LOCATION,
                    "NAME_ROLE" => $trans->NAME_ROLE
                ];
                
                $hasProductInGroup = false;
                $productQuantities = [];
                foreach ($products as $product) {
                    $productCode = $product['CODE_PRODUCT'];
                    if (isset($trans->$productCode)) {
                        $productQuantities[$productCode] = $trans->$productCode ?? "0";
                        $hasProductInGroup = true;
                    }
                }
                
                if ($hasProductInGroup) {
                    $userEntry = array_merge($userEntry, $productQuantities, [
                        "ISFINISHED_TD" => $trans->ISFINISHED_TD,
                        "TOTAL_TD" => $trans->TOTAL_TD
                    ]);
                    $result[$groupKey][] = $userEntry;
                }
            }
        }

        app(ReportTransaction::class)->generate_transaksi_harian_withGroup($result, $groupProduct, $noTransDaily, $nRegional, $date);
    }
    public function genRORPO($yearMonth)
    {
        set_time_limit(360);
        $year = date_format(date_create($yearMonth), 'Y');
        $month = date_format(date_create($yearMonth), 'n');
        $updated_at     = date('Y-m-d', strtotime('-1 days'));

        $rOs = Cronjob::queryGetRepeatOrder($year, $month);
        // dd($rOs);
        app(ReportRepeatOrder::class)->gen_ro_rpo($rOs, $updated_at, $year);
    }
    public function genROSHOP($yearMonth)
    {
        $year = date_format(date_create($yearMonth), 'Y');
        $month = date_format(date_create($yearMonth), 'n');
        $updated_at     = date('Y-m-d', strtotime('-1 days'));

        $key = 'finna_redis_key_roshop' . str_replace('-', '', $yearMonth);
        $redisCached = Redis::get($key);
        $rOs = [];
        if (isset($redisCached)) {
            $rOs = json_decode($redisCached, true);
        } else {
            $data = Cronjob::getallcat($year, $month);
            $exp = 20 * 24 * 60 * 60;
            Redis::setex($key, $exp, $data);
            $rOs = json_decode($data, true);
        }

        // dd($rOs);die;

        app(ReportRepeatOrder::class)->gen_ro_shop($rOs, $updated_at);
    }

    public function genROSHOPbyRange(Request $req)
    {
        if (empty($_GET['dateStart']) || empty($_GET['dateEnd'])) {
            return redirect('laporan/lpr-repeat')->with('err_msg', 'Inputan tanggal awal atau tanggal akhir tidak boleh kosong');
        } else {
            $dateStart = explode('-', $_GET['dateStart']);
            $startY = ltrim($dateStart[0], '0');
            $startM = ltrim($dateStart[1], '0');

            $dateEnd = explode('-', $_GET['dateEnd']);
            $endY = ltrim($dateEnd[0], '0');
            $endM = ltrim($dateEnd[1], '0');

            $idRegional =  $req->input('regional');

            $totalMonth = 0;
            for ($y = $startY; $y <= $endY; $y++) {
                for ($m = 1; $m <= 12; $m++) {
                    if ($y == $startY && $m < $startM) {
                        continue;
                    }
                    if ($y == $endY && $m > $endM) {
                        continue;
                    }
                    $totalMonth++;
                }
            }

            if ($idRegional === "null" || empty($idRegional)) {
                return redirect('laporan/lpr-repeat')->with('err_msg', 'Regional tidak boleh kosong');
            } elseif ($totalMonth > 12) {
                return redirect('laporan/lpr-repeat')->with('err_msg', 'Range bulanan tidak boleh lebih dari 12 bulan');
            } else {
                $rOs = Cronjob::queryGetShopByRange($startM, $startY, $endM, $endY, $idRegional);
                app(ReportRepeatOrder::class)->gen_ro_shop_range($rOs, $totalMonth);
            }
        }
    }
    
    public function genRORPOS($yearMonth)
    {
        set_time_limit(3600);
        $year = date_format(date_create($yearMonth), 'Y');
        $month = date_format(date_create($yearMonth), 'n');
        $updated_at     = date('Y-m-d', strtotime('-1 days'));

        $rOs = Cronjob::queryGetRepeatOrderShop($year, $month);

        // dd($rOs);die;

        $area = Cronjob::getreg($year, $month);

        // dd($area);die;

        if (!empty($rOs)) {
            foreach ($area as $reg) {
                $ReportHead        = new ReportShopHead();
                $unik = md5($reg->REGIONAL_TRANS . $year . $month);
                // if ($reg->REGIONAL_TRANS == 'SUM 1') {
                //     $unik = md5(str_replace('SUM 1', 'SUMATERA 1', $reg->REGIONAL_TRANS) . $year . $month);
                // }else{
                //     $unik = md5($reg->REGIONAL_TRANS . $year . $month);
                // }
                $ReportHead->ID_HEAD          = "REP_" . $unik;
                $ReportHead->ID_REGIONAL      = $reg->REGIONAL_TRANS;
                $ReportHead->BULAN            = $month;
                $ReportHead->TAHUN            = $year;
                $ReportHead->save();
            }

            foreach ($rOs as $item) {
                $ReportDet        = new ReportShopDet();
                // $unik2 = md5($item->REGIONAL_TRANS . $year . $month);
                if ($item->REGIONAL_TRANS == 'SUM 1') {
                    $unik2 = md5(str_replace('SUM 1', 'SUMATERA 1', $item->REGIONAL_TRANS) . $year . $month);
                } else {
                    $unik2 = md5($item->REGIONAL_TRANS . $year . $month);
                }
                $ReportDet->ID_HEAD               = "REP_" . $unik2;
                $ReportDet->NAME_AREA             = $item->NAME_AREA;
                $ReportDet->NAME_REGIONAL         = $item->NAME_REGIONAL;
                $ReportDet->NAME_DISTRICT         = $item->NAME_DISTRICT;
                $ReportDet->ID_SHOP               = $item->ID_SHOP;
                $ReportDet->NAME_SHOP             = $item->NAME_SHOP;
                $ReportDet->DETLOC_SHOP           = $item->DETLOC_SHOP;
                $ReportDet->TELP_SHOP             = $item->TELP_SHOP;
                $ReportDet->TYPE_SHOP             = $item->TYPE_SHOP;
                $ReportDet->OWNER_SHOP            = $item->OWNER_SHOP;
                $ReportDet->TOTAL_RO              = $item->TOTAL_TEST;
                $ReportDet->TOTAL_RO_PRODUCT      = $item->TOTAL_RO_PRODUCT;

                $ReportDet->save();
            }

            $ranges = DB::table('md_range_repeat')->select('ID_RANGE', 'START', 'END')->get();

            foreach ($ranges as $range) {
                $range_id = $range->ID_RANGE;
                $min_total_ro = $range->START;
                $max_total_ro = $range->END;

                // Execute the SQL query for the current range
                DB::table('report_shop_detail')
                    ->whereBetween('TOTAL_RO', [$min_total_ro, $max_total_ro])
                    ->update(['CATEGORY_RO' => $range_id]);
            }
        }

        // dd($rOs);die;
    }

    public function queryGetGenDataOmset(Request $req)
    {
        set_time_limit(3600);

        $year = 2024;
        $idpc = 2;

        $months = range(1, 12);

        $selects = [];
        foreach ($months as $month) {
            $selects[] = DB::raw("SUM(CASE WHEN head.BULAN = $month THEN det.TOTAL_OMSET ELSE 0 END) as MONTH{$month}_TOTAL_OMSET");
            $selects[] = DB::raw("SUM(CASE WHEN head.BULAN = $month THEN det.TOTAL_OUTLET ELSE 0 END) as MONTH{$month}_TOTAL_OUTLET");
        }

        $data = DB::table('report_omset_head as head')
            ->join('report_omset_detail as det', 'head.ID_HEAD', '=', 'det.ID_HEAD')
            ->join('user as u', 'det.ID_USER', '=', DB::raw('u.ID_USER COLLATE utf8mb4_general_ci'))
            ->join('md_product_category as mpc', 'det.ID_PC', '=', 'mpc.ID_PC')
            ->select(
                'mpc.NAME_PC',
                'det.ID_USER',
                'u.NAME_USER',
                ...$selects
            )
            ->whereNull('u.deleted_at')
            ->where(function ($query) use ($year) {
                $query->where('head.TAHUN', $year)
                    ->orWhereNull('head.TAHUN');
            })
            ->where('det.ID_PC', '=', $idpc)
            ->groupBy('det.ID_PC', 'det.ID_USER')
            ->orderBy('mpc.ID_PC')
            ->get()
            ->groupBy('NAME_PC');

        $result = $data->map(function ($users) {
            return $users->map(function ($row) {
                return [
                    'NAME_USER' => $row->NAME_USER,
                    'MONTH1_TOTAL_OMSET' => (float) $row->MONTH1_TOTAL_OMSET,
                    'MONTH1_TOTAL_OUTLET' => (float) $row->MONTH1_TOTAL_OUTLET,
                    'MONTH2_TOTAL_OMSET' => (float) $row->MONTH2_TOTAL_OMSET,
                    'MONTH2_TOTAL_OUTLET' => (float) $row->MONTH2_TOTAL_OUTLET,
                    'MONTH3_TOTAL_OMSET' => (float) $row->MONTH3_TOTAL_OMSET,
                    'MONTH3_TOTAL_OUTLET' => (float) $row->MONTH3_TOTAL_OUTLET,
                    'MONTH4_TOTAL_OMSET' => (float) $row->MONTH4_TOTAL_OMSET,
                    'MONTH4_TOTAL_OUTLET' => (float) $row->MONTH4_TOTAL_OUTLET,
                    'MONTH5_TOTAL_OMSET' => (float) $row->MONTH5_TOTAL_OMSET,
                    'MONTH5_TOTAL_OUTLET' => (float) $row->MONTH5_TOTAL_OUTLET,
                    'MONTH6_TOTAL_OMSET' => (float) $row->MONTH6_TOTAL_OMSET,
                    'MONTH6_TOTAL_OUTLET' => (float) $row->MONTH6_TOTAL_OUTLET,
                    'MONTH7_TOTAL_OMSET' => (float) $row->MONTH7_TOTAL_OMSET,
                    'MONTH7_TOTAL_OUTLET' => (float) $row->MONTH7_TOTAL_OUTLET,
                    'MONTH8_TOTAL_OMSET' => (float) $row->MONTH8_TOTAL_OMSET,
                    'MONTH8_TOTAL_OUTLET' => (float) $row->MONTH8_TOTAL_OUTLET,
                    'MONTH9_TOTAL_OMSET' => (float) $row->MONTH9_TOTAL_OMSET,
                    'MONTH9_TOTAL_OUTLET' => (float) $row->MONTH9_TOTAL_OUTLET,
                    'MONTH10_TOTAL_OMSET' => (float) $row->MONTH10_TOTAL_OMSET,
                    'MONTH10_TOTAL_OUTLET' => (float) $row->MONTH10_TOTAL_OUTLET,
                    'MONTH11_TOTAL_OMSET' => (float) $row->MONTH11_TOTAL_OMSET,
                    'MONTH11_TOTAL_OUTLET' => (float) $row->MONTH11_TOTAL_OUTLET,
                    'MONTH12_TOTAL_OMSET' => (float) $row->MONTH12_TOTAL_OMSET,
                    'MONTH12_TOTAL_OUTLET' => (float) $row->MONTH12_TOTAL_OUTLET,
                ];
            })->toArray(); // Convert the inner collection to array
        })->toArray(); // Convert the outer collection to array

        dd($result);

        return $result;
    }

    public function generateOmsetReport($idRegional, $yearMonth)
    {
        set_time_limit(3600);
        $year = date_format(date_create($yearMonth), 'Y');
        $month = date_format(date_create($yearMonth), 'n');
        // Get the data from the query
        $area = Cronjob::getregOmset($year, $month);

        $rOs = Cronjob::queryGetUserCatType($idRegional);

        foreach ($area as $row) {
            $unik = md5($row->REGIONAL_TRANS . $year . $month);
            $id_head = "REP_" . $unik;

            ReportOmsetHead::updateOrCreate(
                ['ID_HEAD' => $id_head],
                [
                    'ID_REGIONAL' => $row->REGIONAL_TRANS,
                    'BULAN' => $month,
                    'TAHUN' => $year,
                ]
            );
        }

        foreach ($rOs as $item) {

            if ($item->REGIONAL_TRANS == 'SUM 1') {
                $unik2 = md5(str_replace('SUM 1', 'SUMATERA 1', $item->REGIONAL_TRANS) . $year . $month);
            } else {
                $unik2 = md5($item->REGIONAL_TRANS . $year . $month);
            }

            ReportOmsetDetail::updateOrCreate(
                [
                    'ID_HEAD' => "REP_" . $unik2,
                    'NAME_AREA' => $item->NAME_AREA,
                    'ID_USER' => $item->ID_USER,
                    'ID_PC' => $item->ID_PC,
                    'TYPE_SHOP' => $item->TYPE_SHOP,
                ],
                [
                    'TOTAL_OMSET' => 0,
                    'TOTAL_OUTLET' => 0,
                    'last_updated' => now(),
                ]
            );
        }
    }

    public function generateUpdateOmset($idRegional, $yearMonth)
    {
        set_time_limit(3600);
        $year = date_format(date_create($yearMonth), 'Y');
        $month = date_format(date_create($yearMonth), 'n');

        $rOs = Cronjob::queryGetOmsetData($year, $month, $idRegional);

        foreach ($rOs as $item) {

            if ($item->REGIONAL_TRANS == 'SUM 1') {
                $unik2 = md5(str_replace('SUM 1', 'SUMATERA 1', $item->REGIONAL_TRANS) . $year . $month);
            } else {
                $unik2 = md5($item->REGIONAL_TRANS . $year . $month);
            }

            ReportOmsetDetail::updateOrCreate(
                [
                    'ID_HEAD' => "REP_" . $unik2,
                    'NAME_AREA' => $item->NAME_AREA,
                    'ID_USER' => $item->ID_USER,
                    'ID_PC' => $item->ID_PC,
                    'TYPE_SHOP' => $item->TYPE_SHOP,
                ],
                [
                    'TOTAL_OMSET' => $item->TOTAL_OMSET,
                    'TOTAL_OUTLET' => $item->TOTAL_OUTLET,
                    'last_updated' => now(),
                ]
            );
        }
    }

    public function genRORPOSDaily($inputDate = null)
    {
        set_time_limit(3600);
        
        $date = $inputDate ? date_create($inputDate) : date_create();
        $year = date_format($date, 'Y');
        $month = date_format($date, 'n');
        $day = date_format($date, 'd');

        $updated_at = date('Y-m-d', strtotime('-1 days'));
        
        $rOs = Cronjob::queryGetRepeatOrderShopDaily($date);
        
        $area = Cronjob::getreg($year, $month);
    
        if (!empty($rOs)) {
            foreach ($area as $reg) {
                $unik = md5($reg->REGIONAL_TRANS . $year . $month);
    
                ReportShopHead::updateOrCreate(
                    ['ID_HEAD' => "REP_" . $unik],
                    [
                        'ID_REGIONAL' => $reg->REGIONAL_TRANS,
                        'BULAN' => $month,
                        'TAHUN' => $year
                    ]
                );
            }
    
            foreach ($rOs as $item) {
                if ($item->REGIONAL_TRANS == 'SUM 1') {
                    $unik2 = md5(str_replace('SUM 1', 'SUMATERA 1', $item->REGIONAL_TRANS) . $year . $month);
                } else {
                    $unik2 = md5($item->REGIONAL_TRANS . $year . $month);
                }
    
                $existingReportDetail = ReportShopDet::where('ID_HEAD', "REP_" . $unik2)
                                                     ->where('ID_SHOP', $item->ID_SHOP)
                                                     ->first();
    
                if ($existingReportDetail) {
                    $existingReportDetail->TOTAL_RO += $item->TOTAL_TEST;
                    $existingReportDetail->TOTAL_RO_PRODUCT += $item->TOTAL_RO_PRODUCT;
                    $existingReportDetail->save();
                } else {
                    ReportShopDet::create([
                        'ID_HEAD'               => "REP_" . $unik2,
                        'NAME_AREA'             => $item->NAME_AREA,
                        'NAME_REGIONAL'         => $item->NAME_REGIONAL,
                        'NAME_DISTRICT'         => $item->NAME_DISTRICT,
                        'ID_SHOP'               => $item->ID_SHOP,
                        'NAME_SHOP'             => $item->NAME_SHOP,
                        'DETLOC_SHOP'           => $item->DETLOC_SHOP,
                        'TELP_SHOP'             => $item->TELP_SHOP,
                        'TYPE_SHOP'             => $item->TYPE_SHOP,
                        'OWNER_SHOP'            => $item->OWNER_SHOP,
                        'TOTAL_RO'              => $item->TOTAL_TEST,
                        'TOTAL_RO_PRODUCT'      => $item->TOTAL_RO_PRODUCT
                    ]);
                }
            }
    
            // Update CATEGORY_RO based on md_range_repeat ranges
            $ranges = DB::table('md_range_repeat')->select('ID_RANGE', 'START', 'END')->get();
            foreach ($ranges as $range) {
                DB::table('report_shop_detail')
                    ->whereBetween('TOTAL_RO', [$range->START, $range->END])
                    ->update(['CATEGORY_RO' => $range->ID_RANGE]);
            }
        }
    }

    public function genRORCAT($yearMonth)
    {
        set_time_limit(7200);
        $year = date_format(date_create($yearMonth), 'Y');
        $month = date_format(date_create($yearMonth), 'n');
        $updated_at = date('Y-m-d', strtotime('-1 days'));

        $rOs = Cronjob::queryGetRepeatOrderShopCat($year, $month);

        $area = Cronjob::getreg($year, $month);

        if (!empty($rOs)) {
            foreach ($area as $reg) {
                $unik = md5($reg->REGIONAL_TRANS . $year . $month);
                
                ReportRcatHead::updateOrInsert(
                    ['ID_HEAD' => "REP_" . $unik], // Conditions for update
                    [
                        'ID_REGIONAL' => $reg->REGIONAL_TRANS,
                        'BULAN' => $month,
                        'TAHUN' => $year
                    ]
                );
            }

            foreach ($rOs as $item) {
                if ($item->REGIONAL_TRANS == 'SUM 1') {
                    $unik2 = md5(str_replace('SUM 1', 'SUMATERA 1', $item->REGIONAL_TRANS) . $year . $month);
                } else {
                    $unik2 = md5($item->REGIONAL_TRANS . $year . $month);
                }

                ReportRcatDetail::updateOrInsert(
                    [
                        'ID_HEAD' => "REP_" . $unik2,
                        'ID_SHOP' => $item->ID_SHOP,
                    ], // Conditions for update
                    [
                        'NAME_AREA' => $item->NAME_AREA,
                        'NAME_REGIONAL' => $item->NAME_REGIONAL,
                        'NAME_DISTRICT' => $item->NAME_DISTRICT,
                        'NAME_SHOP' => $item->NAME_SHOP,
                        'DETLOC_SHOP' => $item->DETLOC_SHOP,
                        'TELP_SHOP' => $item->TELP_SHOP,
                        'TYPE_SHOP' => $item->TYPE_SHOP,
                        'OWNER_SHOP' => $item->OWNER_SHOP,
                        'TOTAL_RO' => $item->TOTAL_TEST,
                        'TOTAL_RO_PRODUCT' => $item->TOTAL_RO_PRODUCT,
                        'CATEGORY_SELLING' => $item->NAME_PC
                    ]
                );
            }

            $ranges = DB::table('md_range_repeat')->select('ID_RANGE', 'START', 'END')->get();

            foreach ($ranges as $range) {
                $range_id = $range->ID_RANGE;
                $min_total_ro = $range->START;
                $max_total_ro = $range->END;

                DB::table('report_recat_detail')
                    ->whereBetween('TOTAL_RO', [$min_total_ro, $max_total_ro])
                    ->update(['CATEGORY_RO' => $range_id]);
            }
        }
    }

    public function genROTEST()
    {
        $rOs = array(
            'JATIM 1' => [
                0 => [
                    'AREA' => 'SURABAYA 1',
                    'CATEGORY' => [
                        'NONUST' => [
                            'OMST2020' => 0,
                            'OMST2021' => 0,
                            'OMST2022' => 0,
                            'TGT' => 200,
                            'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20],
                            'RT2022' => 120,
                            'VSTGT' => 64
                        ],
                        'UGP' => [
                            'OMST2020' => 0,
                            'OMST2021' => 0,
                            'OMST2022' => 0,
                            'TGT' => 18,
                            'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20],
                            'RT2022' => 120,
                            'VSTGT' => 64
                        ],
                        'URD' => [
                            'OMST2020' => 0,
                            'OMST2021' => 0,
                            'OMST2022' => 0,
                            'TGT' => 20,
                            'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20],
                            'RT2022' => 120,
                            'VSTGT' => 64
                        ],
                        'UST' => [
                            'OMST2020' => 0,
                            'OMST2021' => 0,
                            'OMST2022' => 0,
                            'TGT' => 21,
                            'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20],
                            'RT2022' => 120,
                            'VSTGT' => 64
                        ]
                    ]
                ],
                1 => [
                    'AREA' => 'SURABAYA 2',
                    'CATEGORY' => [
                        'NONUST' => [
                            'OMST2020' => 0,
                            'OMST2021' => 0,
                            'OMST2022' => 0,
                            'TGT' => 200,
                            'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20],
                            'RT2022' => 120,
                            'VSTGT' => 64
                        ],
                        'UGP' => [
                            'OMST2020' => 0,
                            'OMST2021' => 0,
                            'OMST2022' => 0,
                            'TGT' => 200,
                            'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20],
                            'RT2022' => 120,
                            'VSTGT' => 64
                        ],
                        'URD' => [
                            'OMST2020' => 0,
                            'OMST2021' => 0,
                            'OMST2022' => 0,
                            'TGT' => 200,
                            'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20],
                            'RT2022' => 120,
                            'VSTGT' => 64
                        ],
                        'UST' => [
                            'OMST2020' => 0,
                            'OMST2021' => 0,
                            'OMST2022' => 0,
                            'TGT' => 200,
                            'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20],
                            'RT2022' => 120,
                            'VSTGT' => 64
                        ]
                    ]
                ]
            ],
            'JATIM 2' => [
                0 => [
                    'AREA' => 'MALANG',
                    'CATEGORY' => [
                        'NONUST' => [
                            'OMST2020' => 0,
                            'OMST2021' => 0,
                            'OMST2022' => 0,
                            'TGT' => 200,
                            'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20],
                            'RT2022' => 120,
                            'VSTGT' => 64
                        ],
                        'UGP' => [
                            'OMST2020' => 0,
                            'OMST2021' => 0,
                            'OMST2022' => 0,
                            'TGT' => 200,
                            'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20],
                            'RT2022' => 120,
                            'VSTGT' => 64
                        ],
                        'URD' => [
                            'OMST2020' => 0,
                            'OMST2021' => 0,
                            'OMST2022' => 0,
                            'TGT' => 200,
                            'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20],
                            'RT2022' => 120,
                            'VSTGT' => 64
                        ],
                        'UST' => [
                            'OMST2020' => 0,
                            'OMST2021' => 0,
                            'OMST2022' => 0,
                            'TGT' => 200,
                            'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20],
                            'RT2022' => 120,
                            'VSTGT' => 64
                        ]
                    ]
                ]
            ]
        );
        app(ReportRepeatOrder::class)->gen_ro_test($rOs);
    }
    public function genPerformance(Request $req)
    {
        set_time_limit(360);

        $dateStart = explode('-', $_GET['dateStart']);
        $year = ltrim($dateStart[0], '0');

        $id_pc =  $req->input('category');

        $rOs = Cronjob::queryPerformance($year, $id_pc);

        if ($id_pc == '2') {
            app(ReportPerformance::class)->gen_performance_nonust($rOs, $year);
        } elseif ($id_pc == '17') {
            app(ReportPerformance::class)->gen_performance_geprek($rOs, $year);
        } elseif ($id_pc == '16') {
            app(ReportPerformance::class)->gen_performance_rendang($rOs, $year);
        } elseif ($id_pc == '12') {
            app(ReportPerformance::class)->gen_performance_ust($rOs, $year);
        }
    }
    public function genPerformanceREKAP($yearReq)
    {
        set_time_limit(360);

        $year = date_format(date_create($yearReq), 'Y');
        $rOs = array(
            'JATIM 1' => [
                0 => [
                    'AREA' => 'SURABAYA 1',
                    'NON_UST' => [
                        'TOT_REAL' => [80, 12, 10],
                        'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20]
                    ],
                    'UGP' => [
                        'TOT_REAL' => [80, 12, 10],
                        'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20]
                    ],
                    'URD' => [
                        'TOT_REAL' => [80, 12, 10],
                        'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20]
                    ],
                    'UST' => [
                        'TOT_REAL' => [80, 12, 10],
                        'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20]
                    ]
                ],
                1 => [
                    'AREA' => 'SURABAYA 2',
                    'NON_UST' => [
                        'TOT_REAL' => [80, 12, 10],
                        'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20]
                    ],
                    'UGP' => [
                        'TOT_REAL' => [80, 12, 10],
                        'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20]
                    ],
                    'URD' => [
                        'TOT_REAL' => [80, 12, 10],
                        'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20]
                    ],
                    'UST' => [
                        'TOT_REAL' => [80, 12, 10],
                        'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20]
                    ]
                ],
                2 => [
                    'AREA' => 'SURABAYA 3',
                    'NON_UST' => [
                        'TOT_REAL' => [80, 12, 10],
                        'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20]
                    ],
                    'UGP' => [
                        'TOT_REAL' => [80, 12, 10],
                        'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20]
                    ],
                    'URD' => [
                        'TOT_REAL' => [80, 12, 10],
                        'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20]
                    ],
                    'UST' => [
                        'TOT_REAL' => [80, 12, 10],
                        'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20]
                    ]
                ],
            ],
            'JATIM 2' => [
                0 => [
                    'AREA' => 'MALANG 1',
                    'NON_UST' => [
                        'TOT_REAL' => [80, 12, 10],
                        'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20]
                    ],
                    'UGP' => [
                        'TOT_REAL' => [80, 12, 10],
                        'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20]
                    ],
                    'URD' => [
                        'TOT_REAL' => [80, 12, 10],
                        'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20]
                    ],
                    'UST' => [
                        'TOT_REAL' => [80, 12, 10],
                        'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20]
                    ]
                ],
                1 => [
                    'AREA' => 'MALANG 2',
                    'NON_UST' => [
                        'TOT_REAL' => [80, 12, 10],
                        'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20]
                    ],
                    'UGP' => [
                        'TOT_REAL' => [80, 12, 10],
                        'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20]
                    ],
                    'URD' => [
                        'TOT_REAL' => [80, 12, 10],
                        'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20]
                    ],
                    'UST' => [
                        'TOT_REAL' => [80, 12, 10],
                        'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20]
                    ]
                ]
            ],
        );

        $rOs = Cronjob::queryPerformanceAll($year);

        app(ReportPerformance::class)->gen_performance_rekap($rOs, $year);
    }
    public function genROVSTEST(Request $req)
    {

        $dateStart = explode('-', $_GET['yearStart']);
        $year = ltrim($dateStart[0], '0');
        $tipe_toko = $_GET['tipe_toko'];

        $rOs = Cronjob::queryROVSTESTq($year, $tipe_toko);

        app(ReportRepeatOrder::class)->gen_ro_vs_test($rOs);
    }
    public function genROTransToko(Request $req)
    {
        set_time_limit(3600);
        try {
            $yearReq = $_GET['year'];
            $region = $_GET['region'];
            $dateStart = explode('-', $yearReq);
            $year = ltrim($dateStart[0], '0');
            $rOs = Cronjob::queryRTSHOP($year, $region);
            // dd($rOs);

            if (!empty($rOs)) {
                return app(ReportRepeatOrder::class)->gen_ro_trans_toko($rOs);
            } else {
                return 0;
            }
        } catch (Exception $exp) {
            return 0;
        }
    }
    public function genRORutinToko(Request $req)
    {
        set_time_limit(3600);
        try {
            $dateStart = explode('-', $_GET['year']);
            $YearDate = ltrim($dateStart[0], '0');
            $tipeToko = $_GET['tipeToko'];
            $rOs = Cronjob::queryRTRUTIN($YearDate, $tipeToko);
            // dd($rOs);

            if (!empty($rOs)) {
                return app(ReportRepeatOrder::class)->gen_ro_rutin_toko($rOs, $tipeToko, $YearDate);
            }
        } catch (Exception $exp) {
            return 0;
        }
    }
    public function genROCATShopRange(Request $req)
    {
        if (empty($_GET['dateStart']) || empty($_GET['dateEnd'])) {
            return redirect('laporan/lpr-repeat')->with('err_msg', 'Inputan tanggal awal atau tanggal akhir tidak boleh kosong');
        } else {
            $dateStart = explode('-', $_GET['dateStart']);
            $startY = ltrim($dateStart[0], '0');
            $startM = ltrim($dateStart[1], '0');

            $dateEnd = explode('-', $_GET['dateEnd']);
            $endY = ltrim($dateEnd[0], '0');
            $endM = ltrim($dateEnd[1], '0');

            $idRegional =  $req->input('regional');

            $totalMonth = 0;
            for ($y = $startY; $y <= $endY; $y++) {
                for ($m = 1; $m <= 12; $m++) {
                    if ($y == $startY && $m < $startM) {
                        continue;
                    }
                    if ($y == $endY && $m > $endM) {
                        continue;
                    }
                    $totalMonth++;
                }
            }

            if ($idRegional === "null" || empty($idRegional)) {
                return redirect('laporan/lpr-repeat')->with('err_msg', 'Regional tidak boleh kosong');
            } elseif ($totalMonth > 12) {
                return redirect('laporan/lpr-repeat')->with('err_msg', 'Range bulanan tidak boleh lebih dari 12 bulan');
            } else {
                $rOs = Cronjob::queryGetShopCatByRange($startM, $startY, $endM, $endY, $idRegional);
                app(ReportRepeatOrder::class)->gen_ro_shop_range_by_cat($rOs, $totalMonth);
            }
        }
    }
    public function genRTPerShop($yearReq)
    {

        $dateStart = explode('-', $yearReq);
        $year = ltrim($dateStart[0], '0');

        $rOs = Cronjob::queryGetRepeatTransPerShop($year);

        foreach ($rOs as $regionName => $areas) {

            $regionUnik = md5($regionName . $year);

            $reportRtHeadData = [
                'NAME_REGIONAL' => $regionName,
                'YEAR' => $year,
            ];

            ReportRtHead::updateOrCreate(['ID_HEAD' => $regionUnik], $reportRtHeadData);

            foreach ($areas as $areaName => $shops) {
                foreach ($shops as $shopData) {

                    $reportRtDetailData = [
                        'ID_HEAD' => $regionUnik,
                        'NAME_SHOP' => $shopData['SHOP'],
                        'NAME_AREA' => $areaName,
                        'JANUARY' => $shopData['TRANS_COUNT'][0],
                        'FEBRUARY' => $shopData['TRANS_COUNT'][1],
                        'MARCH' => $shopData['TRANS_COUNT'][2],
                        'APRIL' => $shopData['TRANS_COUNT'][3],
                        'MAY' => $shopData['TRANS_COUNT'][4],
                        'JUNE' => $shopData['TRANS_COUNT'][5],
                        'JULY' => $shopData['TRANS_COUNT'][6],
                        'AUGUST' => $shopData['TRANS_COUNT'][7],
                        'SEPTEMBER' => $shopData['TRANS_COUNT'][8],
                        'OCTOBER' => $shopData['TRANS_COUNT'][9],
                        'NOVEMBER' => $shopData['TRANS_COUNT'][10],
                        'DECEMBER' => $shopData['TRANS_COUNT'][11],
                        'PERCENTAGE_CURRENT' => $shopData['PERCENTAGE_CURRENT_MONTH'],
                        'CAT_PERCENTAGE' => $shopData['CATEGORY'],
                        'TYPE_SHOP' => $shopData['TYPE_SHOP'],
                        'NAME_DISTRICT' => $shopData['NAME_DISTRICT'],
                    ];

                    ReportRtDetail::updateOrCreate(
                        ['ID_HEAD' => $regionUnik, 'NAME_SHOP' => $shopData['SHOP']],
                        $reportRtDetailData
                    );
                }
            }
        }
    }
    public function genROVSCALLIN($yearReq)
    {

        $year = date_format(date_create($yearReq), 'Y');
        $updated_at     = date('Y-m-d', strtotime('-1 days'));

        $rOs = Cronjob::queryROVSTEST($yearReq);

        $monthArray = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        foreach ($rOs as $regionName => $entries) {
            $regionUnik = md5($regionName . $year);

            // Insert or update Region
            Rovscall::firstOrCreate(
                ['ID_HEAD' => $regionUnik],
                ['ID_REGIONAL' => $regionName],
                ['TAHUN' => $year]
            );

            foreach ($entries as $entry) {
                $areaName = $entry['AREA'];
                $areaUnik = md5($areaName . $year);

                $regionUnik2 = md5($regionName . $year);

                $rtcallArray = $entry['RTCALL'];
                $rteffcallArray = $entry['RTEFFCALL'];
                $rtroArray = $entry['RTRO'];

                for ($i = 0; $i < count($monthArray); $i++) {
                    $month = $monthArray[$i];
                    $rtcallValue = $rtcallArray[$i];
                    $rteffcallValue = $rteffcallArray[$i];
                    $rtroValue = $rtroArray[$i];

                    // Insert RTCALL data
                    Rovscalldet::create([
                        'ID_HEAD' => $regionUnik2,
                        'ID_REGION' => $regionName,
                        'NAME_AREA' => $areaName,
                        'MONTH' => $month,
                        'VALUE' => $rtcallValue,
                        'TYPE' => 'RTCALL',
                    ]);

                    // Insert RTEFFCALL data
                    Rovscalldet::create([
                        'ID_HEAD' => $regionUnik2,
                        'ID_REGION' => $regionName,
                        'NAME_AREA' => $areaName,
                        'MONTH' => $month,
                        'VALUE' => $rteffcallValue,
                        'TYPE' => 'RTEFFCALL',
                    ]);

                    // Insert RTRO data
                    Rovscalldet::create([
                        'ID_HEAD' => $regionUnik2,
                        'ID_REGION' => $regionName,
                        'NAME_AREA' => $areaName,
                        'MONTH' => $month,
                        'VALUE' => $rtroValue,
                        'TYPE' => 'RTRO',
                    ]);
                }
            }
        }
    }
    public function genAktTRXAPO($yearReq)
    {
        set_time_limit(360);

        $rOs = Cronjob::queryGetAktTrxAPO($yearReq);

        // dd($rOs);die;

        app(ReportAktivitasTRX::class)->gen_akt_trx_apo($rOs);
    }
    public function Testing(ReportQuery $reportQuery)
    {
        return $reportQuery->TrendRpo();
    }
    public function updateDailyRankingAchievement()
    {

        $currDate       = date('Y-m-d', strtotime('-1 days'));
        $currDateTime   = date('Y-m-d', strtotime('-1 days')) . " 23:59:59";

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
        $currDateTime   = date('Y-m-d', strtotime('-1 days')) . " 23:59:59";
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
        // dd($formData);
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

    function splitRoutesForArea() {
        
        set_time_limit(3600);

        $areas = DB::table('md_route')
            ->join('md_shop', 'md_route.ID_SHOP', '=', 'md_shop.ID_SHOP')
            ->join('md_district', 'md_shop.ID_DISTRICT', '=', 'md_district.ID_DISTRICT')
            ->join('md_area', 'md_district.ID_AREA', '=', 'md_area.ID_AREA')
            ->select('md_area.ID_AREA', 'md_area.NAME_AREA')
            ->whereNull('md_area.deleted_at')
            ->whereNull('md_district.deleted_at')
            ->whereNull('md_shop.deleted_at')
            ->distinct()
            ->get();

        foreach ($areas as $area) {

            $users = DB::table('user')
                ->where('ID_AREA', $area->ID_AREA)
                ->whereNull('deleted_at')
                ->whereIn('ID_ROLE', [5, 6])
                ->pluck('ID_USER');

            $routes = DB::table('md_route')
                ->join('md_shop', 'md_route.ID_SHOP', '=', 'md_shop.ID_SHOP')
                ->join('md_district', 'md_shop.ID_DISTRICT', '=', 'md_district.ID_DISTRICT')
                ->where('md_district.ID_AREA', $area->ID_AREA)
                ->whereNull('md_shop.deleted_at')
                ->whereNull('md_district.deleted_at')
                ->select('md_route.ID_RUTE', 'md_route.ID_SHOP', 'md_route.ROUTE_GROUP')
                ->get();

            $userCount = count($users);
            $week = 1;
            $group = 1;
            $userIndex = 0;
            $routeCount = 0;
            $batch = [];

            foreach ($routes as $route) {
                $batch[] = [
                    'ID_USER' => $users[$userIndex],
                    'WEEK' => $week,
                    'ID_SHOP' => $route->ID_SHOP,
                    'ROUTE_GROUP' => $group,
                    'STATUS' => 0,
                ];

                $routeCount++;

                if ($routeCount % 30 == 0) {

                    DB::table('md_split_route')->insert($batch);

                    $batch = [];
                    $userIndex = ($userIndex + 1) % $userCount;

                    if ($userIndex == 0) {
                        $group++;
                        if ($group > 6) {
                            $group = 1;
                            $week++;
                        }
                    }
                }
            }

            // Insert any remaining routes that didn't fill a complete batch
            if (!empty($batch)) {
                DB::table('md_split_route')->insert($batch);
            }
        }
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