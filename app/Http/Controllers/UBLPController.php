<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UBLPController extends Controller {
    public function index() {
        $data['title']          = "Transaksi UBLP";
        $data['sidebar']        = "transaksi";
        $data['sidebar2']       = "ublp";
        $data['ublps']          = DB::table('transaction')
            ->where('transaction.ID_TYPE', '3')
            ->join('user', 'user.ID_USER', '=', 'transaction.ID_USER')
            ->join('md_type', 'md_type.ID_TYPE', '=', 'transaction.ID_TYPE')        
            ->orderBy('transaction.DATE_TRANS', 'DESC')
            ->select('user.NAME_USER', 'transaction.*', 'md_type.NAME_TYPE')
            ->get();

        return view('transaction/ublp', $data);
    }

    public function getTransactionDetail(Request $req) {
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
}
