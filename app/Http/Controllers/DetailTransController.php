<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailTransController extends Controller
{
    public function DetailSpread(Request $req)
    {
        $data['title']          = "Detail Spreading";
        $data['sidebar']        = "transaksi";

        $id_user  = $req->input('id_user');
        $date     = $req->input('date');
        $type     = $req->input('type');

        $transaction = DB::table('transaction')
            ->select('transaction.*', 'user.ID_USER', 'user.NAME_USER', 'md_shop.*')
            ->leftjoin('user', 'user.ID_USER', '=', 'transaction.ID_USER')
            ->leftjoin('md_shop', 'md_shop.ID_SHOP', '=', 'transaction.ID_SHOP')
            ->where('transaction.DATE_TRANS', 'like', $date . '%')
            ->where('transaction.ID_USER', '=', $id_user)
            ->where('transaction.ID_TYPE', '=', $type)
            ->get();

        $data['transaction'] = array();
        foreach ($transaction as $Item_ts) {
            $data_ts_detail = array();
            $transaction_detail       = DB::table('transaction_detail')
                ->where('transaction_detail.ID_TRANS', $Item_ts->ID_TRANS)
                ->join('transaction', 'transaction_detail.ID_TRANS', '=', 'transaction.ID_TRANS')
                ->join('md_product', 'md_product.ID_PRODUCT', '=', 'transaction_detail.ID_PRODUCT')
                ->select('transaction_detail.*', 'transaction.AREA_TRANS', 'md_product.NAME_PRODUCT')
                ->get();

            foreach ($transaction_detail as $ts_detail) {
                array_push(
                    $data_ts_detail,
                    $ts_detail
                );
            }

            $data_image_trans = array();
            $transaction_image       = DB::table('transaction_image')
                ->where('transaction_image.ID_TRANS', $Item_ts->ID_TRANS)
                ->select('transaction_image.*')
                ->get();

            foreach ($transaction_image as $Image) {
                array_push(
                    $data_image_trans,
                    explode(";", $Image->PHOTO_TI)
                );
            }

            array_push(
                $data['transaction'],
                array(
                    "LOCATION" => $Item_ts->NAME_SHOP,
                    "LAT_TRANS" => $Item_ts->LAT_SHOP,
                    "LONG_TRANS" => $Item_ts->LONG_SHOP,
                    "DATE_TRANS" => $Item_ts->DATE_TRANS,
                    "NAME_USER" => $Item_ts->NAME_USER,
                    "IMAGE" => $data_image_trans,
                    "DETAIL" => $data_ts_detail
                )
            );
        }

        // dump(count($data['transaction'][0]['IMAGE'][0]));exit;

        return view('transaction/detail_spread', $data);
    }

    public function DetailUB(Request $req)
    {
        $data['title']          = "Detail UB";
        $data['sidebar']        = "transaksi";
        $data['sidebar2']       = "transaksi";

        $id_user  = $req->input('id_user');
        $date     = $req->input('date');
        $type     = $req->input('type');

        $transaction = DB::table('transaction')
            ->select('transaction.*', 'user.ID_USER', 'user.NAME_USER')
            ->leftjoin('user', 'user.ID_USER', '=', 'transaction.ID_USER')
            ->where('transaction.DATE_TRANS', 'like', $date . '%')
            ->where('transaction.ID_USER', '=', $id_user)
            ->where('transaction.ID_TYPE', '=', $type)
            ->get();

        $data['transaction'] = array();
        foreach ($transaction as $Item_ts) {
            $data_ts_detail = array();
            $transaction_detail       = DB::table('transaction_detail')
                ->where('transaction_detail.ID_TRANS', $Item_ts->ID_TRANS)
                ->join('transaction', 'transaction_detail.ID_TRANS', '=', 'transaction.ID_TRANS')
                ->join('md_product', 'md_product.ID_PRODUCT', '=', 'transaction_detail.ID_PRODUCT')
                ->select('transaction_detail.*', 'transaction.AREA_TRANS', 'md_product.NAME_PRODUCT')
                ->get();

            foreach ($transaction_detail as $ts_detail) {
                array_push(
                    $data_ts_detail,
                    $ts_detail
                );
            }

            $data_image_trans = array();
            $transaction_image       = DB::table('transaction_image')
                ->where('transaction_image.ID_TRANS', $Item_ts->ID_TRANS)
                ->select('transaction_image.*')
                ->get();

            foreach ($transaction_image as $Image) {
                array_push(
                    $data_image_trans,
                    explode(";", $Image->PHOTO_TI)
                );
            }

            array_push(
                $data['transaction'],
                array(
                    "LOCATION" => $Item_ts->DISTRICT,
                    "LAT_TRANS" => $Item_ts->LAT_TRANS,
                    "LONG_TRANS" => $Item_ts->LONG_TRANS,
                    "DATE_TRANS" => $Item_ts->DATE_TRANS,
                    "NAME_USER" => $Item_ts->NAME_USER,
                    "IMAGE" => $data_image_trans,
                    "DETAIL" => $data_ts_detail
                )
            );
        }

        // dump(count($data['transaction'][0]['IMAGE'][0]));exit;

        return view('transaction/detail_ub', $data);
    }

    public function DetailUBLP(Request $req)
    {
        $data['title']          = "Detail UBLP";
        $data['sidebar']        = "transaksi";
        $data['sidebar2']       = "transaksi";

        $id_user  = $req->input('id_user');
        $date     = $req->input('date');
        $type     = $req->input('type');

        $transaction = DB::table('transaction')
            ->select('transaction.*', 'user.ID_USER', 'user.NAME_USER')
            ->leftjoin('user', 'user.ID_USER', '=', 'transaction.ID_USER')
            ->where('transaction.DATE_TRANS', 'like', $date . '%')
            ->where('transaction.ID_USER', '=', $id_user)
            ->where('transaction.ID_TYPE', '=', $type)
            ->get();

        $data['transaction'] = array();
        foreach ($transaction as $Item_ts) {
            $data_ts_detail = array();
            $transaction_detail       = DB::table('transaction_detail')
                ->where('transaction_detail.ID_TRANS', $Item_ts->ID_TRANS)
                ->join('transaction', 'transaction_detail.ID_TRANS', '=', 'transaction.ID_TRANS')
                ->join('md_product', 'md_product.ID_PRODUCT', '=', 'transaction_detail.ID_PRODUCT')
                ->select('transaction_detail.*', 'transaction.AREA_TRANS', 'md_product.NAME_PRODUCT')
                ->get();

            foreach ($transaction_detail as $ts_detail) {
                array_push(
                    $data_ts_detail,
                    $ts_detail
                );
            }

            $data_image_trans = array();
            $transaction_image       = DB::table('transaction_image')
                ->where('transaction_image.ID_TRANS', $Item_ts->ID_TRANS)
                ->select('transaction_image.*')
                ->get();

            foreach ($transaction_image as $Image) {
                array_push(
                    $data_image_trans,
                    explode(";", $Image->PHOTO_TI)
                );
            }

            array_push(
                $data['transaction'],
                array(
                    "LOCATION" => $Item_ts->DETAIL_LOCATION,
                    "LAT_TRANS" => $Item_ts->LAT_TRANS,
                    "LONG_TRANS" => $Item_ts->LONG_TRANS,
                    "DATE_TRANS" => $Item_ts->DATE_TRANS,
                    "NAME_USER" => $Item_ts->NAME_USER,
                    "IMAGE" => $data_image_trans,
                    "DETAIL" => $data_ts_detail
                )
            );
        }

        // dump(count($data['transaction'][0]['IMAGE'][0]));exit;

        return view('transaction/detail_ublp', $data);
    }
}
