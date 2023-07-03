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
use App\RangeRepeat;
use App\ReportAktivitasTRX;
use App\ReportPerformance;
use App\ReportRepeatOrder;
use App\Shop;
use App\ReportShopHead;
use App\ReportShopDet;
use App\User;
use App\Users;
use Carbon\Carbon;
use Exception;
use App\TargetUser;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

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
            $month          = date('n', strtotime('-1 days'));
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

            $month          = date('n', strtotime('-1 days'));
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
        $products       = Product::where('deleted_at', NULL)->orderBy('ORDER_PRODUCT', 'ASC')->get();
        $querySumProd   = [];
        $idUsers        = null;
        $noTransDaily   = null;

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
        $transDaily     = Cronjob::queryGetTransactionDaily($querySumProd, $date, $nRegional);
        // dd($transDaily);die;
        if ($transDaily != null) {
            $idUsers    = implode(',', array_map(function ($entry) {
                return "'" . $entry->ID_USER . "'";
            }, $transDaily));

            $noTransDaily   = Users::getUserByRegional($_POST['idRegional'], $idUsers);

            // dd($noTransDaily);die;
        }

        app(ReportTransaction::class)->generate_transaksi_harian($products, $transDaily, $noTransDaily, $nRegional, $date);
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

        $rOs = Cronjob::getallcat();

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
        }elseif ($id_pc == '17') {
            app(ReportPerformance::class)->gen_performance_geprek($rOs, $year);
        }elseif ($id_pc == '16') {
            app(ReportPerformance::class)->gen_performance_rendang($rOs, $year);
        }elseif ($id_pc == '12') {
            app(ReportPerformance::class)->gen_performance_ust($rOs, $year);
        }

    }
    public function genPerformanceGEPREK($yearReq)
    {
        $year = date_format(date_create($yearReq), 'Y');
        $rOs = array(
            'JATIM 1' => [
                0 => [
                    'AREA' => 'SURABAYA 1',
                    'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20],
                    'TOTAL_RT' => 20
                ],
                1 => [
                    'AREA' => 'SURABAYA 2',
                    'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20],
                    'TOTAL_RT' => 20
                ]
            ],
            'JATIM 2' => [
                0 => [
                    'AREA' => 'MALANG 1',
                    'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20],
                    'TOTAL_RT' => 11
                ],
                1 => [
                    'AREA' => 'MALANG 2',
                    'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20],
                    'TOTAL_RT' => 40
                ]
            ],
        );

        app(ReportPerformance::class)->gen_performance_geprek($rOs, $year);
    }
    public function genPerformanceRENDANG($yearReq)
    {
        $year = date_format(date_create($yearReq), 'Y');
        $rOs = array(
            'JATIM 1' => [
                0 => [
                    'AREA' => 'SURABAYA 1',
                    'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20],
                    'TOTAL_RT' => 20
                ],
                1 => [
                    'AREA' => 'SURABAYA 2',
                    'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20],
                    'TOTAL_RT' => 20
                ]
            ],
            'JATIM 2' => [
                0 => [
                    'AREA' => 'MALANG 1',
                    'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20],
                    'TOTAL_RT' => 11
                ],
                1 => [
                    'AREA' => 'MALANG 2',
                    'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20],
                    'TOTAL_RT' => 40
                ]
            ],
        );

        app(ReportPerformance::class)->gen_performance_rendang($rOs, $year);
    }
    public function genPerformanceUST($yearReq)
    {
        $year = date_format(date_create($yearReq), 'Y');
        $rOs = array(
            'JATIM 1' => [
                0 => [
                    'AREA' => 'SURABAYA 1',
                    'TOT_NONUST1' => 80,
                    'TOT_NONUST2' => 120,
                    'TOT_NONUST3' => 120,
                    'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20],
                    'TOTAL_RT' => 20
                ],
                1 => [
                    'AREA' => 'SURABAYA 2',
                    'TOT_NONUST1' => 80,
                    'TOT_NONUST2' => 120,
                    'TOT_NONUST3' => 120,
                    'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20],
                    'TOTAL_RT' => 20
                ]
            ],
            'JATIM 2' => [
                0 => [
                    'AREA' => 'MALANG 1',
                    'TOT_NONUST1' => 45,
                    'TOT_NONUST2' => 76,
                    'TOT_NONUST3' => 120,
                    'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20],
                    'TOTAL_RT' => 11
                ],
                1 => [
                    'AREA' => 'MALANG 2',
                    'TOT_NONUST1' => 34,
                    'TOT_NONUST2' => 700,
                    'TOT_NONUST3' => 120,
                    'MONTH' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20],
                    'TOTAL_RT' => 40
                ]
            ],
        );

        app(ReportPerformance::class)->gen_performance_ust($rOs, $year);
    }
    public function genPerformanceREKAP($yearReq)
    {
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

        // dd($rOs);

        app(ReportPerformance::class)->gen_performance_rekap($rOs, $year);
    }
    public function genROVSTEST(Request $req)
    {
        // $rOs = array(
        //     'JATIM 1' => [
        //         0 => [
        //             'AREA' => 'SURABAYA 1',
        //             'RTCALL' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20],
        //             'RTRO' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20]
        //         ],
        //         1 => [
        //             'AREA' => 'SURABAYA 2',
        //             'RTCALL' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20],
        //             'RTRO' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20]
        //         ]
        //     ],
        //     'JATIM 2' => [
        //         0 => [
        //             'AREA' => 'MALANG',
        //             'RTCALL' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20],
        //             'RTRO' => [0, 1, 4, 7, 9, 10, 22, 9, 10, 11, 19, 20]
        //         ]
        //     ]
        // );

        set_time_limit(600);

        $dateStart = explode('-', $_GET['yearStart']);
        $year = ltrim($dateStart[0], '0');

        $rOs = Cronjob::queryROVSTEST($year);
        // dd($rOs);

        app(ReportRepeatOrder::class)->gen_ro_vs_test($rOs);
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
