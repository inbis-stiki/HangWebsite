<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\TransactionDetailToday;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardApi extends Controller
{
    // public function ProductSold(Request $req)
    // {
    //     try {
    //         

    //         $AllData = array();

    //         $DataHarian = DB::table("transaction")
    //             ->select(DB::raw("SUM(transaction.QTY_TRANS) as Harian"))
    //             ->whereDate('transaction.DATE_TRANS', '=', date('Y-m-d'))
    //             ->where('transaction.ID_USER', '=', $req->input("id_user"))
    //             ->first();

    //         $DataBulanan = DB::table("transaction")
    //             ->select(DB::raw("SUM(transaction.QTY_TRANS) as Bulanan"))
    //             ->where('transaction.DATE_TRANS', 'like', date('Y-m') . '%')
    //             ->where('transaction.ID_USER', '=', $req->input("id_user"))
    //             ->first();

    //         if ($DataBulanan->Bulanan == null) {
    //             array_push(
    //                 $AllData,
    //                 array(
    //                     'DAYS' => 0,
    //                     'MONTH' => 0
    //                 )
    //             );
    //         } else if ($DataHarian->Harian == null) {
    //             array_push(
    //                 $AllData,
    //                 array(
    //                     'DAYS' => 0,
    //                     'MONTH' => $DataBulanan->Bulanan
    //                 )
    //             );
    //         } else {
    //             array_push(
    //                 $AllData,
    //                 array(
    //                     'DAYS' => $DataHarian->Harian,
    //                     'MONTH' => $DataBulanan->Bulanan
    //                 )
    //             );
    //         }


    //         return response([
    //             "status_code"       => 200,
    //             "status_message"    => 'Data berhasil Diambil!',
    //             "data"              => $AllData
    //         ], 200);
    //     } catch (HttpResponseException $exp) {
    //         return response([
    //             'status_code'       => $exp->getCode(),
    //             'status_message'    => $exp->getMessage(),
    //         ], $exp->getCode());
    //     }
    // }

    // public function AverageProductSold(Request $req)
    // {
    //     try {
    //         

    //         $AllData = array();
    //         $firstDay = Carbon::now()->firstOfMonth();
    //         $DateNow = Carbon::now();
    //         $dateFrom = Carbon::createFromFormat('Y-m-d', $firstDay->toDateString());
    //         $dateTo = Carbon::createFromFormat('Y-m-d', $DateNow->toDateString());

    //         $DataBulanan = DB::table("transaction")
    //             ->select(DB::raw("SUM(transaction.QTY_TRANS) as Bulanan"))
    //             ->where('transaction.DATE_TRANS', 'like', date('Y-m') . '%')
    //             ->where('transaction.ID_USER', '=', $req->input("id_user"))
    //             ->first();

    //         $Average = $DataBulanan->Bulanan / ($dateFrom->diffInDays($dateTo) + 1);
    //         array_push(
    //             $AllData,
    //             array("AVERAGE" => round($Average))
    //         );

    //         return response([
    //             "status_code"       => 200,
    //             "status_message"    => 'Data berhasil Diambil!',
    //             "data"              => $AllData
    //         ], 200);
    //     } catch (HttpResponseException $exp) {
    //         return response([
    //             'status_code'       => $exp->getCode(),
    //             'status_message'    => $exp->getMessage(),
    //         ], $exp->getCode());
    //     }
    // }

    // public function SalesProgress(Request $req)
    // {
    //     try {
    //         

    //         $AllData = array();

    //         $DataBulanan = DB::table("transaction")
    //             ->select(DB::raw("SUM(transaction.QTY_TRANS) as Bulanan"))
    //             ->where('transaction.DATE_TRANS', 'like', date('Y-m') . '%')
    //             ->where('transaction.ID_USER', '=', $req->input("id_user"))
    //             ->first();

    //         $DataTarget = DB::table("user_target")
    //             ->where('user_target.ID_USER', '=', $req->input("id_user"))
    //             ->latest("user_target.ID_USER")->first();

    //         if (!empty($DataTarget)) {
    //             $Progress = ($DataBulanan->Bulanan / ($DataTarget->TOTALSALES_UT * 25)) * 100;

    //             array_push(
    //                 $AllData,
    //                 array("PROGRESS" => number_format((float)$Progress, 1, '.', ''))
    //             );
    //         } else {
    //             array_push(
    //                 $AllData,
    //                 array("PROGRESS" => 0)
    //             );
    //         }

    //         return response([
    //             "status_code"       => 200,
    //             "status_message"    => 'Data berhasil Diambil!',
    //             "data"              => $AllData
    //         ], 200);
    //     } catch (HttpResponseException $exp) {
    //         return response([
    //             'status_code'       => $exp->getCode(),
    //             'status_message'    => $exp->getMessage(),
    //         ], $exp->getCode());
    //     }
    // }

    // public function NotReachTarget(Request $req)
    // {
    //     try {
    //         

    //         $AllData = array();

    //         $DataHarian = DB::table("transaction")
    //             ->selectRaw("DATE(transaction.DATE_TRANS) as CnvrtDate, SUM(transaction.QTY_TRANS) as TotalPenjualan")
    //             ->where('transaction.ID_USER', '=', $req->input("id_user"))
    //             ->groupByRaw('DATE(transaction.DATE_TRANS)')
    //             ->orderBy('CnvrtDate', 'ASC')
    //             ->get();

    //         $DataTarget = DB::table("user_target")
    //             ->where('user_target.ID_USER', '=', $req->input("id_user"))
    //             ->latest("user_target.ID_USER")->first();

    //         if (!empty($DataTarget)) {
    //             $TotalHari = 0;
    //             foreach ($DataHarian as $Data) {
    //                 if ($Data->TotalPenjualan < $DataTarget->TOTALSALES_UT) {
    //                     $TotalHari++;
    //                 }
    //             }

    //             array_push(
    //                 $AllData,
    //                 array("TOTAL_HARI" => $TotalHari)
    //             );
    //         } else {
    //             array_push(
    //                 $AllData,
    //                 array("TOTAL_HARI" => 0)
    //             );
    //         }

    //         return response([
    //             "status_code"       => 200,
    //             "status_message"    => 'Data berhasil Diambil!',
    //             "data"              => $AllData
    //         ], 200);
    //     } catch (HttpResponseException $exp) {
    //         return response([
    //             'status_code'       => $exp->getCode(),
    //             'status_message'    => $exp->getMessage(),
    //         ], $exp->getCode());
    //     }
    // }

    public function AllApi(Request $req)
    {
        try {

            $AllData_temp = DB::table("dashboard_mobile")
                ->where('dashboard_mobile.ID_USER', '=', $req->input("id_user"))
                ->first();

            $targetUser = DB::table("user_target")
                ->where('user_target.ID_USER', '=', $req->input("id_user"))
                ->first();

            $transToday = TransactionDetailToday::getData($req->input('id_user'));
            $month = (int)date('j');

            $tdyUST         = (int)($transToday <> null) ? (($transToday[0]->UST <> null) ? $transToday[0]->UST : 0) : 0;
            $tdyNONUST      = (int)($transToday <> null) ? (($transToday[0]->NON_UST <> null) ? $transToday[0]->NON_UST : 0) : 0;
            $tdySeleraku    = (int)($transToday <> null) ? (($transToday[0]->SELERAKU <> null) ? $transToday[0]->SELERAKU : 0) : 0;
            $transToday     = $tdyUST+$tdyNONUST+$tdySeleraku;

            $realUST        = (int)($AllData_temp <> null) ? (($AllData_temp->REALUST_DM <> null) ? $AllData_temp->REALUST_DM : 0) : 0;
            $realNONUST     = (int)($AllData_temp <> null) ? (($AllData_temp->REALNONUST_DM <> null) ? $AllData_temp->REALNONUST_DM : 0) : 0;
            $realSeleraku   = (int)($AllData_temp <> null) ? (($AllData_temp->REALSELERAKU_DM <> null) ? $AllData_temp->REALSELERAKU_DM : 0) : 0;

            $tgtUST         = (int)$targetUser->SALESUST_UT;
            $tgtNONUST      = (int)$targetUser->SALESNONUST_UT;
            $tgtSeleraku    = (int)$targetUser->SALESSELERAKU_UT;
            $tgtTotal       = $tgtUST + $tgtNONUST + $tgtSeleraku;

            $totUST         = $tdyUST + $realUST;
            $totNONUST      = $tdyNONUST + $realNONUST;
            $totSeleraku    = $tdySeleraku + $realSeleraku;
            $totTrans       = $totUST + $totNONUST + $totSeleraku;

            $avgUST         = $totUST / $month;
            $avgNONUST      = $totNONUST / $month;
            $avgSeleraku    = $totSeleraku / $month;

            $progUST        = $totUST / ($tgtUST * 25) * 100;
            $progNONUST     = $totNONUST / ($tgtNONUST * 25) * 100;
            $progSeleraku   = $totSeleraku / ($tgtSeleraku * 25) * 100;
            $progTotal      = $totTrans / ($tgtTotal * 25) * 100;

            $AllData = array(
                'SPREADNIG' => ($AllData_temp <> null) ? (($AllData_temp->SPREADING_DM <> null) ? $AllData_temp->SPREADING_DM : 0) : 0,
                'UB_UBLP' => ($AllData_temp <> null) ? (($AllData_temp->UBUBLP_DM <> null) ? $AllData_temp->UBUBLP_DM : 0) : 0,
                'DAYS' => $transToday,
                'AVERAGE' => [
                    'UST '      => number_format((float)$avgUST, 1, '.', ''),
                    'NON_UST '  => number_format((float)$avgNONUST, 1, '.', ''),
                    'SELERAKU ' => number_format((float)$avgSeleraku, 1, '.', '')
                ],
                'OFF_TARGET' => ($AllData_temp <> null) ? (($AllData_temp->OFFTARGET_DM <> null) ? $AllData_temp->OFFTARGET_DM : 0) : 0,
                'PROGRESS' => number_format((float)$progTotal, 1, '.', ''),
                'PROGRESS_DETAIL' => [
                    'UST' => number_format((float)$progUST, 1, '.', ''),
                    'NON_UST' => number_format((float)$progNONUST, 1, '.', ''),
                    'SELERAKU' => number_format((float)$progSeleraku, 1, '.', '')
                ],
                'REAL_DETAIL' => [
                    'UST' => $totUST,
                    'NON_UST' => $totNONUST,
                    'SELERAKU' => $totSeleraku
                ],
                'TGT_DETAIL' => [
                    'UST' => $tgtUST,
                    'NON_UST' => $tgtNONUST,
                    'SELERAKU' => $tgtSeleraku
                ]
            );

            return response([
                "status_code"       => 200,
                "status_message"    => 'Data berhasil Diambil!',
                "data"              => [$AllData]
            ], 200);
        } catch (HttpResponseException $exp) {
            return response([
                'status_code'       => $exp->getCode(),
                'status_message'    => $exp->getMessage(),
            ], $exp->getCode());
        }
    }
}
