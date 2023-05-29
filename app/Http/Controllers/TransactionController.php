<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $data['title']          = "Transaksi";
        $data['sidebar']        = "transaksi";
        $data['sidebar2']       = "transaksi";

        return view('transaction/transaction', $data);
    }

    public function getAllTrans(Request $req)
    {
        $id_type    = $req->input('searchTrans');
        $tgl_trans  = $req->input('tglSearchtrans');
        $id_role    = $req->session()->get('role');
        $regional   = $req->session()->get('regional');

        $showRegional = "";
        $showType = "";
        if ($id_role == 3 || $id_role == 4 || $id_role == 5 || $id_role == 6) {
            $showRegional = "AND mr.ID_REGIONAL = $regional";
        }

        if ($id_type != 0) {
            $showType = "AND t.ID_TYPE = $id_type";
        }

        $perPage = $req->input('length');
        $start = $req->input('start');

        $dataTrans     = DB::select(
            DB::raw("
                SELECT
                    t.ID_TRANS ,
                    t.AREA_TRANS ,
                    t.REGIONAL_TRANS ,
                    t.DATE_TRANS ,
                    t.ID_TYPE ,
                    t.LOCATION_TRANS AS NAME_LOCATION,
                    t.REGIONAL_TRANS AS NAME_REGIONAL,
                    t.AREA_TRANS AS NAME_AREA,
                    t.KECAMATAN AS NAME_DISTRICT,   
                    u.`ID_USER`,
                    u.`NAME_USER`,
                    u.`ID_ROLE`,
                    mt.`NAME_TYPE`,
                    ms.`LONG_SHOP`,
                    ms.`LAT_SHOP`,
                    DATE(t.DATE_TRANS) AS CnvrtDate,
                    COUNT(t.ID_TRANS) AS TotalTrans
                FROM
                    `transaction` t
                LEFT JOIN `user` u ON
                    u.ID_USER = t.ID_USER
                LEFT JOIN `md_shop` ms ON
                    ms.ID_SHOP = t.ID_SHOP
                LEFT JOIN `md_type` mt ON
                    mt.ID_TYPE = t.ID_TYPE
                WHERE
                    t.`ISTRANS_TRANS` = 1
                    AND DATE(t.`DATE_TRANS`) = '" . $tgl_trans . "'
                    $showRegional
                    $showType
                GROUP BY
                    DATE(t.DATE_TRANS),
                    t.ID_USER,
                    t.ID_TYPE
                ORDER BY
                    NAME_AREA ASC
                LIMIT $start, $perPage
            ")
        );

        $NewData_all = array();
        $counter_all = $start;

        foreach ($dataTrans as $item) {
            $data = array(
                "NO" => ++$counter_all,
                "NAME_USER" => $item->NAME_USER,
                "ID_TRANS" => $item->ID_TRANS,
                "AREA_TRANS" => $item->AREA_TRANS,
                "REGIONAL_TRANS" => $item->REGIONAL_TRANS,
                "DATE_TRANS" => date_format(date_create($item->DATE_TRANS), 'j F Y'),
                "ID_TYPE" => $item->ID_TYPE,
                "JML_TRANS" => $item->TotalTrans,
                "NAME_TYPE" => $item->NAME_TYPE
            );

            if ($item->ID_TYPE == 1) {
                $data['ACTION_BUTTON'] = '<a href="' . url("transaction/spread/?id_user=$item->ID_USER&area=$item->AREA_TRANS&date=" . date_format(date_create($item->DATE_TRANS), 'Y-m-d') . "&type=$item->ID_TYPE") . '"><button class="btn light btn-success"><i class="fa fa-circle-info"></i></button></a>';
            } else if ($item->ID_TYPE == 2) {
                $data['ACTION_BUTTON'] = '<a href="' . url("transaction/ub/?id_user=$item->ID_USER&area=$item->AREA_TRANS&date=" . date_format(date_create($item->DATE_TRANS), 'Y-m-d') . "&type=$item->ID_TYPE") . '"><button class="btn light btn-success"><i class="fa fa-circle-info"></i></button></a>';
            } else {
                $data['ACTION_BUTTON'] = '<a href="' . url("transaction/ublp/?id_user=$item->ID_USER&area=$item->AREA_TRANS&date=" . date_format(date_create($item->DATE_TRANS), 'Y-m-d') . "&type=$item->ID_TYPE") . '"><button class="btn light btn-success"><i class="fa fa-circle-info"></i></button></a>';
            }

            array_push($NewData_all, $data);
        }

        return response([
            'status_code'       => 200,
            'status_message'    => 'Data berhasil diambil!',
            'data'              => $NewData_all
        ], 200);
    }
}
