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

        $id_user        = $req->input('id_user');
        $date           = $req->input('date');
        $type           = $req->input('type');

        $transaction = DB::table('transaction')
            ->select('transaction.ID_TRANS', 'transaction.DATE_TRANS', 'user.ID_USER', 'user.NAME_USER', 'md_shop.ID_SHOP', 'md_shop.DETLOC_SHOP', 'md_shop.NAME_SHOP', 'md_shop.LONG_SHOP', 'md_shop.LAT_SHOP')
            ->leftjoin('user', 'user.ID_USER', '=', 'transaction.ID_USER')
            ->leftjoin('md_shop', 'md_shop.ID_SHOP', '=', 'transaction.ID_SHOP')
            ->where('transaction.DATE_TRANS', 'like', $date . '%')
            ->where('transaction.ID_USER', '=', $id_user)
            ->where('transaction.ID_TYPE', '=', $type)
            ->where('transaction.ISTRANS_TRANS', 1)
            ->orderBy('transaction.DATE_TRANS', 'DESC')
            ->get();

        $data['transaction'] = array();
        $shop_id_trans = array();
        foreach ($transaction as $Item_ts) {
            $data_ts_detail = array();
            $transaction_detail       = DB::table('transaction_detail')
                ->select('transaction_detail.*', 'transaction.AREA_TRANS', 'md_product.NAME_PRODUCT')
                ->join('transaction', 'transaction_detail.ID_TRANS', '=', 'transaction.ID_TRANS')
                ->join('md_product', 'md_product.ID_PRODUCT', '=', 'transaction_detail.ID_PRODUCT')
                ->where('transaction_detail.ID_TRANS', $Item_ts->ID_TRANS)
                ->get();

            $TOT_PRODUCT = 0;
            foreach ($transaction_detail as $ts_detail) {
                $TOT_PRODUCT += $ts_detail->QTY_TD;
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
                    "ID_SHOP" => $Item_ts->ID_SHOP,
                    "NAME_SHOP" => $Item_ts->NAME_SHOP,
                    "DETLOC_SHOP" => $Item_ts->DETLOC_SHOP,
                    "LAT_TRANS" => $Item_ts->LAT_SHOP,
                    "LONG_TRANS" => $Item_ts->LONG_SHOP,
                    "DATE_TRANS" => $Item_ts->DATE_TRANS,
                    "NAME_USER" => $Item_ts->NAME_USER,
                    "IMAGE" => $data_image_trans,
                    "TOTAL" => $TOT_PRODUCT,
                    "DETAIL" => $data_ts_detail
                )
            );

            if (!in_array($Item_ts->ID_SHOP, $shop_id_trans)) {
                array_push($shop_id_trans, $Item_ts->ID_SHOP);
            }
        }
        $data['shop_trans'] = DB::table('md_shop')
            ->select('md_shop.ID_SHOP', 'md_shop.NAME_SHOP', 'md_shop.LONG_SHOP', 'md_shop.LAT_SHOP', 'transaction.ISTRANS_TRANS')
            ->selectRaw('(SELECT COUNT(t.ID_TRANS) FROM `transaction` t WHERE t.ID_TYPE = 1 AND t.ID_SHOP = `md_shop`.`ID_SHOP` GROUP BY t.ID_SHOP ) as TOT_TRANS, (SELECT SUM(td.QTY_TD) FROM `transaction` t LEFT JOIN transaction_detail td ON td.ID_TRANS = t.ID_TRANS WHERE td.ID_SHOP = `md_shop`.`ID_SHOP` AND t.ID_USER = "' . $id_user . '" GROUP BY t.ID_SHOP) as TOTAL')
            ->leftjoin('transaction', 'transaction.ID_SHOP', '=', 'md_shop.ID_SHOP')
            ->whereIn('md_shop.ID_SHOP', $shop_id_trans)
            ->groupBy('md_shop.ID_SHOP')
            ->get();

        $data['shop_no_trans'] = DB::table('md_shop')
        ->select('md_shop.ID_SHOP', 'md_shop.NAME_SHOP', 'md_shop.LONG_SHOP', 'md_shop.LAT_SHOP', 'transaction.ISTRANS_TRANS')
        ->selectRaw('(
            SELECT
                COUNT(t.ID_TRANS)
            FROM
                `transaction` t
            WHERE
                t.ID_TYPE = 1
                AND t.ID_SHOP = `md_shop`.`ID_SHOP`
            GROUP BY
                t.ID_SHOP
            ) as TOT_TRANS,
            (
                SELECT
                    SUM(td.QTY_TD)
                FROM
                    `transaction` t
                LEFT JOIN transaction_detail td ON
                        td.ID_TRANS = t.ID_TRANS
                WHERE
                    td.ID_SHOP = `md_shop`.`ID_SHOP`
                    AND t.ID_USER = "' . $id_user . '"
                GROUP BY
                    t.ID_SHOP
            ) as TOTAL')
        ->leftjoin('transaction', 'transaction.ID_SHOP', '=', 'md_shop.ID_SHOP')
        ->where('transaction.ISTRANS_TRANS', 1)
        ->whereNotIn('md_shop.ID_SHOP', $shop_id_trans)
        ->groupBy('md_shop.ID_SHOP')
        ->get();

        $data['shop_no_con2_trans'] = DB::table('md_shop')
        ->select('md_shop.ID_SHOP', 'md_shop.NAME_SHOP', 'md_shop.LONG_SHOP', 'md_shop.LAT_SHOP', 'transaction.ISTRANS_TRANS')
        ->selectRaw('(
            SELECT
                COUNT(t.ID_TRANS)
            FROM
                `transaction` t
            WHERE
                t.ID_TYPE = 1
                AND t.ID_SHOP = `md_shop`.`ID_SHOP`
            GROUP BY
                t.ID_SHOP
            ) as TOT_TRANS,
            (
                SELECT
                    SUM(td.QTY_TD)
                FROM
                    `transaction` t
                LEFT JOIN transaction_detail td ON
                        td.ID_TRANS = t.ID_TRANS
                WHERE
                    td.ID_SHOP = `md_shop`.`ID_SHOP`
                    AND t.ID_USER = "' . $id_user . '"
                GROUP BY
                    t.ID_SHOP
            ) as TOTAL')
        ->leftjoin('transaction', 'transaction.ID_SHOP', '=', 'md_shop.ID_SHOP')
        ->whereRaw("transaction.ISTRANS_TRANS = 0 OR transaction.ISTRANS_TRANS IS NULL")
        ->whereNotIn('md_shop.ID_SHOP', $shop_id_trans)
        ->groupBy('md_shop.ID_SHOP')
        ->get();

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
            ->orderBy('transaction.DATE_TRANS', 'DESC')
            ->get();

        $data['transaction'] = array();
        foreach ($transaction as $Item_ts) {
            $transaction_detail       = DB::table('transaction_detail')
                ->select('transaction_detail.QTY_TD', 'md_product.NAME_PRODUCT')
                ->leftJoin('md_product', 'md_product.ID_PRODUCT', '=', 'transaction_detail.ID_PRODUCT')
                ->whereRaw("transaction_detail.ID_TRANS = '$Item_ts->ID_TRANS'")
                ->get();

            $data_ts_detail = array();
            $TOT_PRODUCT = 0;
            foreach ($transaction_detail as $ts_detail) {
                $TOT_PRODUCT += $ts_detail->QTY_TD;
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
                    array(
                        "desc" => explode(";", $Image->DESCRIPTION_TI),
                        "image" => explode(";", $Image->PHOTO_TI)
                    )
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
                    "TOTAL" => $Item_ts->QTY_TRANS,
                    "DETAIL" => $transaction_detail
                )
            );
        }

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
            ->orderBy('transaction.DATE_TRANS', 'DESC')
            ->get();

        $data['transaction'] = array();
        foreach ($transaction as $Item_ts) {
            $transaction_detail       = DB::table('transaction_detail')
                ->select('transaction_detail.QTY_TD', 'md_product.NAME_PRODUCT')
                ->leftJoin('md_product', 'md_product.ID_PRODUCT', '=', 'transaction_detail.ID_PRODUCT')
                ->whereRaw("transaction_detail.ID_TRANS = '$Item_ts->ID_TRANS'")
                ->get();

            $data_ts_detail = array();
            $TOT_PRODUCT = 0;
            foreach ($transaction_detail as $ts_detail) {
                $TOT_PRODUCT += $ts_detail->QTY_TD;
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
                    array(
                        "desc" => explode(";", $Image->DESCRIPTION_TI),
                        "image" => explode(";", $Image->PHOTO_TI)
                    )
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
                    "TOTAL" => $Item_ts->QTY_TRANS,
                    "DETAIL" => $transaction_detail
                )
            );
        }

        return view('transaction/detail_ublp', $data);
    }
}
