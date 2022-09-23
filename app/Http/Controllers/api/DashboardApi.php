<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
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
    //         date_default_timezone_set("Asia/Bangkok");

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
    //         date_default_timezone_set("Asia/Bangkok");

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
    //         date_default_timezone_set("Asia/Bangkok");

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
    //         date_default_timezone_set("Asia/Bangkok");

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
            date_default_timezone_set("Asia/Bangkok");

            $AllData_temp = DB::table("dashboard_mobile")
                ->where('dashboard_mobile.ID_USER', '=', $req->input("id_user"))
                ->first();

            $firstDay = Carbon::now()->firstOfMonth();
            $DateNow = Carbon::now();
            $dateFrom = Carbon::createFromFormat('Y-m-d', $firstDay->toDateString());
            $dateTo = Carbon::createFromFormat('Y-m-d', $DateNow->toDateString());

            $AllData = array(
                'SPREADNIG' => ($AllData_temp <> null) ? (($AllData_temp->SPREADING_DM <> null) ? $AllData_temp->SPREADING_DM : 0) : 0,
                'UB_UBLP' => ($AllData_temp <> null) ? (($AllData_temp->UBUBLP_DM <> null) ? $AllData_temp->UBUBLP_DM : 0) : 0,
                'DAYS' => ($AllData_temp <> null) ? (($AllData_temp->LASTSALE_DM <> null) ? $AllData_temp->DAYLASTSALE_DM : 0) : 0,
                'MONTH' => [
                    "LAST_DAYS" => ($dateFrom->diffInDays($dateTo) + 1),
                    "TOTAL" => ($AllData_temp <> null) ? (($AllData_temp->DAYLASTSALE_DM <> null) ? $AllData_temp->LASTSALE_DM : 0) : 0
                ],
                'AVERAGE' => ($AllData_temp <> null) ? ((round($AllData_temp->AVERAGESALE_DM) <> null) ? round($AllData_temp->AVERAGESALE_DM) : 0) : 0,
                'PROGRESS' => ($AllData_temp <> null) ? ((number_format((float)$AllData_temp->PROGRESS_DM, 1, '.', '') <> null) ? number_format((float)$AllData_temp->PROGRESS_DM, 1, '.', '') : 0) : 0,
                'OFF_TARGET' => ($AllData_temp <> null) ? (($AllData_temp->OFFTARGET_DM <> null) ? $AllData_temp->OFFTARGET_DM : 0) : 0
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
