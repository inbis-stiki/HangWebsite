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
    public function ProductSold(Request $req)
    {
        try {
            date_default_timezone_set("Asia/Bangkok");

            $AllData = array();

            $DataHarian = DB::table("transaction")
                ->select(DB::raw("SUM(transaction.QTY_TRANS) as Harian"))
                ->whereDate('transaction.DATE_TRANS', '=', date('Y-m-d'))
                ->where('transaction.ID_USER', '=', $req->input("id_user"))
                ->first();

            $DataBulanan = DB::table("transaction")
                ->select(DB::raw("SUM(transaction.QTY_TRANS) as Bulanan"))
                ->where('transaction.DATE_TRANS', 'like', date('Y-m') . '%')
                ->where('transaction.ID_USER', '=', $req->input("id_user"))
                ->first();

            if ($DataBulanan->Bulanan == null) {
                array_push(
                    $AllData,
                    array(
                        'Hari' => 0,
                        'Bulan' => 0
                    )
                );
            }else if ($DataHarian->Harian == null) {
                array_push(
                    $AllData,
                    array(
                        'Hari' => 0,
                        'Bulan' => $DataBulanan->Bulanan
                    )
                );
            }else{
                array_push(
                    $AllData,
                    array(
                        'Hari' => $DataHarian->Harian,
                        'Bulan' => $DataBulanan->Bulanan
                    )
                );
            }
            

            return response([
                "status_code"       => 200,
                "status_message"    => 'Data berhasil Diambil!',
                "data"              => $AllData
            ], 200);
        } catch (HttpResponseException $exp) {
            return response([
                'status_code'       => $exp->getCode(),
                'status_message'    => $exp->getMessage(),
            ], $exp->getCode());
        }
    }

    public function AverageProductSold(Request $req)
    {
        try {
            date_default_timezone_set("Asia/Bangkok");

            $AllData = array();
            $firstDay = Carbon::now()->firstOfMonth();  
            $DateNow = Carbon::now();
            $dateFrom = Carbon::createFromFormat('Y-m-d',$firstDay->toDateString());
            $dateTo = Carbon::createFromFormat('Y-m-d',$DateNow->toDateString());
            
            $DataBulanan = DB::table("transaction")
                ->select(DB::raw("SUM(transaction.QTY_TRANS) as Bulanan"))
                ->where('transaction.DATE_TRANS', 'like', date('Y-m') . '%')
                ->where('transaction.ID_USER', '=', $req->input("id_user"))
                ->get();

            $Average = $DataBulanan[0]->Bulanan / $dateFrom->diffInDays($dateTo);
            array_push(
                $AllData,
                array("average_produk_terjual" => $Average)
            );

            return response([
                "status_code"       => 200,
                "status_message"    => 'Data berhasil Diambil!',
                "data"              => $AllData
            ], 200);
        } catch (HttpResponseException $exp) {
            return response([
                'status_code'       => $exp->getCode(),
                'status_message'    => $exp->getMessage(),
            ], $exp->getCode());
        }
    }

    public function SalesProgress(Request $req)
    {
        try {
            date_default_timezone_set("Asia/Bangkok");

            $AllData = array();
            
            $DataHarian = DB::table("transaction")
                ->where('transaction.ID_USER', '=', $req->input("id_user"))
                ->get();

            $DataTarget = DB::table("user_target")
                ->where('user_target.ID_USER', '=', $req->input("id_user"))
                ->latest("user_target.ID_USER")->first();

            $Progress = (count($DataHarian) / ($DataTarget->TOTALSALES_UT * 25))*100;

            array_push(
                $AllData,
                array("progress" => $Progress."%")
            );

            return response([
                "status_code"       => 200,
                "status_message"    => 'Data berhasil Diambil!',
                "data"              => $AllData
            ], 200);
        } catch (HttpResponseException $exp) {
            return response([
                'status_code'       => $exp->getCode(),
                'status_message'    => $exp->getMessage(),
            ], $exp->getCode());
        }
    }

    public function NotReachTarget(Request $req)
    {
        try {
            date_default_timezone_set("Asia/Bangkok");

            $AllData = array();
            
            $DataHarian = DB::table("transaction")
                ->selectRaw("DATE(transaction.DATE_TRANS) as CnvrtDate, SUM(transaction.QTY_TRANS) as TotalPenjualan")
                ->where('transaction.ID_USER', '=', $req->input("id_user"))
                ->groupByRaw('DATE(transaction.DATE_TRANS)')
                ->orderBy('CnvrtDate', 'ASC')
                ->get();

            $DataTarget = DB::table("user_target")
                ->where('user_target.ID_USER', '=', $req->input("id_user"))
                ->latest("user_target.ID_USER")->first();

            $TotalHari = 0;
            foreach($DataHarian as $Data){
                if ($Data->TotalPenjualan < $DataTarget->TOTALSALES_UT) {
                    $TotalHari++;
                }
            }

            array_push(
                $AllData,
                array("TotalHari" => $TotalHari)
            );

            return response([
                "status_code"       => 200,
                "status_message"    => 'Data berhasil Diambil!',
                "data"              => $AllData
            ], 200);
        } catch (HttpResponseException $exp) {
            return response([
                'status_code'       => $exp->getCode(),
                'status_message'    => $exp->getMessage(),
            ], $exp->getCode());
        }
    }
}
