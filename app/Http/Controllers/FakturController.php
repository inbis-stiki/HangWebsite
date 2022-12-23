<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\TransactionDaily;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FakturController extends Controller
{
    public function index(Request $req)
    {
        $data['title']          = "Faktur";
        $data['sidebar']        = "faktur";
        $data['sidebar2']       = "";

        return view('master.faktur.faktur', $data);
    }

    public function getAllFaktur(Request $req)
    {
        $id_user    = $req->session()->get('id_user');
        $tgl_trans  = $req->input('tglSearchtrans');
        $data_user     = DB::table('user')
            ->where('user.ID_USER', $id_user)
            ->leftjoin('md_area', 'md_area.ID_AREA', '=', 'user.ID_AREA')
            ->leftjoin('md_regional', 'md_regional.ID_REGIONAL', '=', 'user.ID_REGIONAL')
            ->leftjoin('md_location', 'md_location.ID_LOCATION', '=', 'user.ID_LOCATION')
            ->select('user.*', 'md_area.NAME_AREA', 'md_regional.NAME_REGIONAL', 'md_location.NAME_LOCATION')
            ->first();
        if ($data_user->ID_ROLE == 3 || $data_user->ID_ROLE == 4) {
            $data_fakturs        = DB::table('transaction_daily')
                ->where('transaction_daily.LOCATION_TD', $data_user->NAME_LOCATION)
                ->where('transaction_daily.DATEFACTUR_TD', 'like', $tgl_trans . '%')
                ->join('user', 'user.ID_USER', '=', 'transaction_daily.ID_USER')
                ->join('md_type', 'md_type.ID_TYPE', '=', 'transaction_daily.ID_TYPE')
                ->orderBy('transaction_daily.DATEFACTUR_TD', 'DESC')
                ->select('transaction_daily.*', 'user.NAME_USER', 'md_type.NAME_TYPE')
                ->get();
        } else {
            $data_fakturs        = DB::table('transaction_daily')
                ->where('transaction_daily.DATEFACTUR_TD', 'like', $tgl_trans . '%')
                ->join('user', 'user.ID_USER', '=', 'transaction_daily.ID_USER')
                ->join('md_type', 'md_type.ID_TYPE', '=', 'transaction_daily.ID_TYPE')
                ->orderBy('transaction_daily.DATEFACTUR_TD', 'DESC')
                ->select('transaction_daily.*', 'user.NAME_USER', 'md_type.NAME_TYPE')
                ->get();
        }

        $NewData_faktur = array();
        $counter_faktur = 0;
        foreach ($data_fakturs as $item) {
            $data = array(
                "NAME_USER" => $item->NAME_USER,
                "AREA" => $item->AREA_TD,
                "REGIONAL" => $item->REGIONAL_TD,
                "DATE" => date_format(date_create($item->DATEFACTUR_TD), 'j F Y'),
                "ACTION_BUTTON" => "<a href='detail/faktur?id_td=" . $item->ID_TD . "&id_user=" . $item->ID_USER . "&date=" . date_format(date_create($item->DATEFACTUR_TD), 'Y-m-d') . "'><button class='btn light btn-success'><i class='fa fa-circle-info'></i></button></a>"
            );

            $counter_faktur++;
            $data['NO'] = $counter_faktur;
            array_push($NewData_faktur, $data);
        }
        return response([
            'status_code'       => 200,
            'status_message'    => 'Data berhasil diambil!',
            'data'              => $NewData_faktur
        ], 200);
    }

    public function DetailFaktur(Request $req)
    {
        $data['title']          = "Detail Faktur";
        $data['sidebar']        = "faktur";

        $ID_FAKTUR = $req->input("id_td");
        $ID_USER = $req->input("id_user");
        $DATE = $req->input("date");

        $lastInvoice = DB::table('transaction_daily')
            ->join('user', 'user.ID_USER', '=', 'transaction_daily.ID_USER')
            ->join('md_type', 'md_type.ID_TYPE', '=', 'transaction_daily.ID_TYPE')
            ->orderBy('transaction_daily.DATEFACTUR_TD', 'DESC')
            ->select('transaction_daily.*', 'user.NAME_USER', 'md_type.NAME_TYPE')
            ->where('ISFINISHED_TD', '=', '1')
            ->where('ID_TD', '=', $ID_FAKTUR)
            ->latest('ID_TD')->first();

        $transaction = Transaction::where('ID_USER', '=', $ID_USER)
            ->where('DATE_TRANS', 'like', $DATE . '%')
            ->get();

        $dataT = array();

        foreach ($transaction as $t) {
            $ts_d = DB::table("transaction_detail")
                ->select('transaction_detail.*', 'md_product.NAME_PRODUCT')
                ->join('md_product', 'md_product.ID_PRODUCT', '=', 'transaction_detail.ID_PRODUCT')
                ->where('transaction_detail.ID_TRANS', '=', $t->ID_TRANS)
                ->get();

            $dataTsD = array();

            foreach ($ts_d as $tsdData) {
                array_push(
                    $dataTsD,
                    array(
                        "ID_TD" => $tsdData->ID_TD,
                        "ID_PRODUCT" => $tsdData->ID_PRODUCT,
                        "NAME_PRODUCT" => $tsdData->NAME_PRODUCT,
                        "QTY_TD" => $tsdData->QTY_TD
                    )
                );
            }

            array_push(
                $dataT,
                array(
                    "ID_TRANS" => $t->ID_TRANS,
                    "ID_TYPE" => $t->ID_TYPE,
                    "TRANSACTION_DETAIL" => $dataTsD
                )
            );
        }

        $TotalQty = array();
        foreach ($dataT as $item) {
            foreach ($item['TRANSACTION_DETAIL'] as $item2) {
                if (array_key_exists($item2['ID_PRODUCT'], $TotalQty)) {
                    $TotalQty[$item2['ID_PRODUCT']]['TOTAL'] += $item2['QTY_TD'];
                } else {
                    $TotalQty[$item2['ID_PRODUCT']] = array(
                        "NAME_PRODUCT" => $item2['NAME_PRODUCT'],
                        "TOTAL" => $item2['QTY_TD']
                    );
                }
            }
        }

        $detail_produk = array();
        foreach ($TotalQty as $Data3) {
            array_push(
                $detail_produk,
                array(
                    "NAME_PRODUCT" => $Data3['NAME_PRODUCT'],
                    "TOT_QTY_PROD" => $Data3['TOTAL']
                )
            );
        }

        $data['faktur'] = $lastInvoice;
        $data['detail'] = $detail_produk;

        return view('master.faktur.detail_faktur', $data);
    }
}

