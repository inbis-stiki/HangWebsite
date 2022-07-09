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
        $id_user    = 3;

        $data_loc     = DB::table('user')
            ->where('user.ID_USER', $id_user)
            ->leftjoin('md_area', 'md_area.ID_AREA', '=', 'user.ID_AREA')
            ->leftjoin('md_location', 'md_location.ID_LOCATION', '=', 'user.ID_LOCATION')
            ->select('user.*', 'md_area.NAME_AREA', 'md_location.NAME_LOCATION')
            ->first();

        if ($id_type == 0) {
            $ub     = DB::table('transaction')
                ->leftjoin('user', 'user.ID_USER', '=', 'transaction.ID_USER')
                ->leftjoin('md_shop', 'md_shop.ID_SHOP', '=', 'transaction.ID_SHOP')
                ->leftjoin('md_type', 'md_type.ID_TYPE', '=', 'transaction.ID_TYPE')
                ->leftjoin('md_district', 'md_district.ID_DISTRICT', '=', 'md_shop.ID_DISTRICT')
                ->leftjoin('md_area', 'md_area.ID_AREA', '=', 'user.ID_AREA')
                ->leftjoin('md_location', 'md_location.ID_LOCATION', '=', 'user.ID_LOCATION')
                ->orderBy('transaction.DATE_TRANS', 'DESC')
                ->select('transaction.*', 'user.NAME_USER', 'md_type.NAME_TYPE', 'md_shop.LONG_SHOP', 'md_shop.LAT_SHOP', 'md_district.NAME_DISTRICT', 'md_area.NAME_AREA', 'md_location.NAME_LOCATION')
                ->get();
        } else {
            $ub     = DB::table('transaction')
                ->where('transaction.ID_TYPE', $id_type)
                ->leftjoin('user', 'user.ID_USER', '=', 'transaction.ID_USER')
                ->leftjoin('md_type', 'md_type.ID_TYPE', '=', 'transaction.ID_TYPE')
                ->leftjoin('md_district', 'md_district.ID_DISTRICT', '=', 'md_shop.ID_DISTRICT')
                ->leftjoin('md_area', 'md_area.ID_AREA', '=', 'user.ID_AREA')
                ->leftjoin('md_location', 'md_location.ID_LOCATION', '=', 'user.ID_LOCATION')
                ->orderBy('transaction.DATE_TRANS', 'DESC')
                ->select('transaction.*', 'user.NAME_USER', 'md_type.NAME_TYPE', 'md_shop.NAME_SHOP', 'md_district.NAME_DISTRICT', 'md_area.NAME_AREA', 'md_location.NAME_LOCATION')
                ->get();
        }

        $datRes = array();
        $i = 0;
        foreach ($ub as $item) {
            $i++;
            $data = array(
                "NO" => $i,
                "NAME_USER" => $item->NAME_USER,
                "AREA_TRANS" => $item->AREA_TRANS,
                "DATE_TRANS" => $item->DATE_TRANS,
                "NAME_TYPE" => $item->NAME_TYPE
            );

            if ($item->ID_TYPE == 1) {
                $data['ACTION_BUTTON'] = '<button class="btn light btn-success" onclick="showDetailSpread(`' . $item->ID_TRANS . '`)"><i class="fa fa-circle-info"></i></button>
                <a class="btn light btn-info" href="https://maps.google.com/maps?q=' . $item->LAT_SHOP . ',' . $item->LONG_SHOP . '&hl=es&z=14&amp;" target="_blank"><i class="fa fa-map-location-dot"></i></a>';
            } else if ($item->ID_TYPE == 2) {
                $data['ACTION_BUTTON'] = '<button class="btn light btn-success" onclick="showDetailUB(`' . $item->ID_TRANS . '`)"><i class="fa fa-circle-info"></i></button>
                <a class="btn light btn-info" href="https://maps.google.com/maps?q=' . $item->LAT_TRANS . ',' . $item->LONG_TRANS . '&hl=es&z=14&amp;" target="_blank"><i class="fa fa-map-location-dot"></i></a>';
            } else {
                $data['ACTION_BUTTON'] = '<button class="btn light btn-success" onclick="showDetailUBLP(`' . $item->ID_TRANS . '`)"><i class="fa fa-circle-info"></i></button>
                <a class="btn light btn-info" href="https://maps.google.com/maps?q=' . $item->LAT_TRANS . ',' . $item->LONG_TRANS . '&hl=es&z=14&amp;" target="_blank"><i class="fa fa-map-location-dot"></i></a>';
            }

            array_push($datRes, $data);
        }

        return response([
            'status_code'       => 200,
            'status_message'    => 'Data berhasil diambil!',
            'data'              => $datRes
        ], 200);
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
