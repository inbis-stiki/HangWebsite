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
        $data['spreadings']     = DB::table('transaction')
            ->where('transaction.ID_TYPE', '1')
            ->join('user', 'user.ID_USER', '=', 'transaction.ID_USER')
            ->join('md_shop', 'md_shop.ID_SHOP', '=', 'transaction.ID_SHOP')
            ->join('md_type', 'md_type.ID_TYPE', '=', 'transaction.ID_TYPE')
            ->join('md_district', 'md_district.ID_DISTRICT', '=', 'md_shop.ID_DISTRICT')
            ->orderBy('transaction.DATE_TRANS', 'DESC')
            ->select('transaction.*', 'user.NAME_USER', 'md_type.NAME_TYPE', 'md_shop.NAME_SHOP', 'md_shop.LONG_SHOP', 'md_shop.LAT_SHOP', 'md_district.NAME_DISTRICT')
            ->get();

        $data['ub']     = DB::table('transaction')
            ->where('transaction.ID_TYPE', '2')
            ->join('user', 'user.ID_USER', '=', 'transaction.ID_USER')
            ->join('md_type', 'md_type.ID_TYPE', '=', 'transaction.ID_TYPE')
            ->orderBy('transaction.DATE_TRANS', 'DESC')
            ->select('transaction.*', 'user.NAME_USER', 'md_type.NAME_TYPE')
            ->get();

        $data['ublps']          = DB::table('transaction')
            ->where('transaction.ID_TYPE', '3')
            ->join('user', 'user.ID_USER', '=', 'transaction.ID_USER')
            ->join('md_type', 'md_type.ID_TYPE', '=', 'transaction.ID_TYPE')
            ->orderBy('transaction.DATE_TRANS', 'DESC')
            ->select('user.NAME_USER', 'transaction.*', 'md_type.NAME_TYPE')
            ->get();

        return view('transaction/transaction', $data);
    }

    public function getTransactionDetailSpreading(Request $req)
    {
        $id_trans   = $req->input('id_trans');
        $data       = DB::table('transaction_detail')
            ->where('transaction_detail.ID_TRANS', $id_trans)
            ->join('md_shop', 'md_shop.ID_SHOP', '=', 'transaction_detail.ID_SHOP')
            ->join('md_product', 'md_product.ID_PRODUCT', '=', 'transaction_detail.ID_PRODUCT')
            ->select('transaction_detail.*', 'md_shop.NAME_SHOP', 'md_product.NAME_PRODUCT')
            ->get();

        $query       = DB::table('transaction_image')
            ->where('transaction_image.ID_TRANS', $id_trans)
            ->select('transaction_image.*')
            ->get();

        $ImageTrans = array();
        foreach ($query as $Image) {
            array_push(
                $ImageTrans,
                explode(";", $Image->PHOTO_TI)
            );
        }

        return response([
            'status_code'       => 200,
            'status_message'    => 'Data berhasil diambil!',
            'data'              => $data,
            'image_trans'      => $ImageTrans
        ], 200);
    }

    public function getTransactionDetailUB(Request $req)
    {
        $id_trans   = $req->input('id_trans');
        $data       = DB::table('transaction_detail')
            ->where('transaction_detail.ID_TRANS', $id_trans)
            ->join('md_shop', 'md_shop.ID_SHOP', '=', 'transaction_detail.ID_SHOP')
            ->join('md_product', 'md_product.ID_PRODUCT', '=', 'transaction_detail.ID_PRODUCT')
            ->select('transaction_detail.*', 'md_shop.NAME_SHOP', 'md_product.NAME_PRODUCT')
            ->get();

        $query       = DB::table('transaction_image')
            ->where('transaction_image.ID_TRANS', $id_trans)
            ->select('transaction_image.*')
            ->get();

        $ImageTrans = array();
        foreach ($query as $Image) {
            array_push(
                $ImageTrans,
                explode(";", $Image->PHOTO_TI)
            );
        }

        return response([
            'status_code'       => 200,
            'status_message'    => 'Data berhasil diambil!',
            'data'              => $data,
            'image_trans'      => $ImageTrans
        ], 200);
    }

    public function getTransactionDetailUBLP(Request $req)
    {
        $id_trans   = $req->input('id_trans');
        $data       = DB::table('transaction_detail')
            ->where('transaction_detail.ID_TRANS', $id_trans)
            ->join('transaction', 'transaction_detail.ID_TRANS', '=', 'transaction.ID_TRANS')
            ->join('md_product', 'md_product.ID_PRODUCT', '=', 'transaction_detail.ID_PRODUCT')
            ->select('transaction_detail.*', 'transaction.AREA_TRANS', 'md_product.NAME_PRODUCT')
            ->get();
        $query       = DB::table('transaction_image')
            ->where('transaction_image.ID_TRANS', $id_trans)
            ->select('transaction_image.*')
            ->get();

        $ImageTrans = array();
        foreach ($query as $Image) {
            array_push(
                $ImageTrans,
                explode(";", $Image->PHOTO_TI)
            );
        }

        return response([
            'status_code'       => 200,
            'status_message'    => 'Data berhasil diambil!',
            'data'              => $data,
            'image_trans'      => $ImageTrans
        ], 200);
    }
}
