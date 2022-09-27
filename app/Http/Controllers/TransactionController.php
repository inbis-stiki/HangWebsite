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
        $id_user    = $req->session()->get('id_user');
        $id_role    = $req->session()->get('role');

        $data_loc     = DB::table('user')
            ->where('user.ID_USER', $id_user)
            ->leftjoin('md_area', 'md_area.ID_AREA', '=', 'user.ID_AREA')
            ->leftjoin('md_regional', 'md_regional.ID_REGIONAL', '=', 'user.ID_REGIONAL')
            ->select('user.*', 'md_area.NAME_AREA', 'md_regional.NAME_REGIONAL')
            ->first();

        if ($id_type == 0) {
            $dataTrans     = DB::table('transaction')
                ->select('transaction.*', 'user.ID_USER', 'user.NAME_USER', 'md_type.NAME_TYPE', 'md_shop.LONG_SHOP', 'md_shop.LAT_SHOP', 'md_district.NAME_DISTRICT', 'md_area.NAME_AREA', 'md_regional.NAME_REGIONAL')
                ->selectRaw("DATE(transaction.DATE_TRANS) as CnvrtDate, COUNT(transaction.ID_TRANS) as TotalTrans")
                ->leftjoin('user', 'user.ID_USER', '=', 'transaction.ID_USER')
                ->leftjoin('md_shop', 'md_shop.ID_SHOP', '=', 'transaction.ID_SHOP')
                ->leftjoin('md_type', 'md_type.ID_TYPE', '=', 'transaction.ID_TYPE')
                ->leftjoin('md_district', 'md_district.ID_DISTRICT', '=', 'md_shop.ID_DISTRICT')
                ->leftjoin('md_area', 'md_area.ID_AREA', '=', 'user.ID_AREA')
                ->leftjoin('md_regional', 'md_regional.ID_REGIONAL', '=', 'user.ID_REGIONAL')
                ->where('transaction.DATE_TRANS', 'like', $tgl_trans . '%')
                ->groupByRaw("DATE(transaction.DATE_TRANS), transaction.ID_USER, transaction.ID_TYPE")
                ->orderBy('md_area.NAME_AREA', 'ASC')
                ->get();
        } else {
            $dataTrans     = DB::table('transaction')
                ->select('transaction.*', 'user.ID_USER', 'user.NAME_USER', 'md_type.NAME_TYPE', 'md_shop.LONG_SHOP', 'md_shop.LAT_SHOP', 'md_district.NAME_DISTRICT', 'md_area.NAME_AREA', 'md_regional.NAME_REGIONAL')
                ->selectRaw("DATE(transaction.DATE_TRANS) as CnvrtDate, COUNT(transaction.ID_TRANS) as TotalTrans")
                ->leftjoin('user', 'user.ID_USER', '=', 'transaction.ID_USER')
                ->leftjoin('md_shop', 'md_shop.ID_SHOP', '=', 'transaction.ID_SHOP')
                ->leftjoin('md_type', 'md_type.ID_TYPE', '=', 'transaction.ID_TYPE')
                ->leftjoin('md_district', 'md_district.ID_DISTRICT', '=', 'md_shop.ID_DISTRICT')
                ->leftjoin('md_area', 'md_area.ID_AREA', '=', 'user.ID_AREA')
                ->leftjoin('md_regional', 'md_regional.ID_REGIONAL', '=', 'user.ID_REGIONAL')
                ->where('transaction.ID_TYPE', '=', $id_type)
                ->where('transaction.DATE_TRANS', 'like', $tgl_trans . '%')
                ->groupByRaw("DATE(transaction.DATE_TRANS), transaction.ID_USER, transaction.ID_TYPE")
                ->orderBy('md_area.NAME_AREA', 'ASC')
                ->get();
        }

        $NewData_asmen = array();
        $NewData_rpo = array();
        $NewData_all = array();
        $i = 0;
        foreach ($dataTrans as $key => $item) {
            $i++;

            $data = array(
                "NO" => $i,
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
                $data['ACTION_BUTTON'] = '<a href="' . url("transaction/spread/?id_user=$item->ID_USER&date=" . date_format(date_create($item->DATE_TRANS), 'Y-m-d') . "&type=$item->ID_TYPE") . '"><button class="btn light btn-success"><i class="fa fa-circle-info"></i></button></a>';
            } else if ($item->ID_TYPE == 2) {
                $data['ACTION_BUTTON'] = '<a href="' . url("transaction/ub/?id_user=$item->ID_USER&date=" . date_format(date_create($item->DATE_TRANS), 'Y-m-d') . "&type=$item->ID_TYPE") . '"><button class="btn light btn-success"><i class="fa fa-circle-info"></i></button></a>';
            } else {
                $data['ACTION_BUTTON'] = '<a href="' . url("transaction/ublp/?id_user=$item->ID_USER&date=" . date_format(date_create($item->DATE_TRANS), 'Y-m-d') . "&type=$item->ID_TYPE") . '"><button class="btn light btn-success"><i class="fa fa-circle-info"></i></button></a>';
            }

            if ($id_role == 3 && ($key == 'REGIONAL_TRANS' && $item->REGIONAL_TRANS == $data_loc->NAME_REGIONAL)) {
                array_push($NewData_asmen, $data);
            } else if ($id_role == 4 && ($key == 'AREA_TRANS' && $item->AREA_TRANS == $data_loc->NAME_AREA)) {
                array_push($NewData_rpo, $data);
            } else {
                array_push($NewData_all, $data);
            }
        }

        if ($id_role == 3) {
            return response([
                'status_code'       => 200,
                'status_message'    => 'Data berhasil diambil!',
                'data'              => $NewData_asmen
            ], 200);
        } else if ($id_role == 4) {
            return response([
                'status_code'       => 200,
                'status_message'    => 'Data berhasil diambil!',
                'data'              => $NewData_rpo
            ], 200);
        } else {
            return response([
                'status_code'       => 200,
                'status_message'    => 'Data berhasil diambil!',
                'data'              => $NewData_all
            ], 200);
        }
    }
}
