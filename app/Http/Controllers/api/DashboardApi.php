<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DashboardApi extends Controller
{
    public function ProdukTerjual(Request $req)
    {
        try {
            date_default_timezone_set("Asia/Bangkok");

            $AllData = array();

            $DataHarian = DB::table("transaction")
                ->select(DB::raw("SUM(transaction.QTY_TRANS) as Harian"))
                ->whereDate('transaction.DATE_TRANS', '=', date('Y-m-d'))
                ->where('transaction.ID_USER', '=', $req->input("id_user"))
                ->get();

            $DataBulanan = DB::table("transaction")
                ->select(DB::raw("SUM(transaction.QTY_TRANS) as Bulanan"))
                ->where('transaction.DATE_TRANS', 'like', date('Y-m') . '%')
                ->where('transaction.ID_USER', '=', $req->input("id_user"))
                ->get();

            if ($DataBulanan[0]->Bulanan == null) {
                array_push(
                    $AllData,
                    array(
                        'Harian' => 0,
                        'Bulanan' => 0
                    )
                );
            }else if ($DataHarian[0]->Harian == null) {
                array_push(
                    $AllData,
                    array(
                        'Harian' => 0,
                        'Bulanan' => $DataBulanan[0]->Bulanan
                    )
                );
            }else{
                array_push(
                    $AllData,
                    array(
                        'Harian' => $DataHarian[0]->Harian,
                        'Bulanan' => $DataBulanan[0]->Bulanan
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
}
