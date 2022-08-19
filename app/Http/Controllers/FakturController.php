<?php

namespace App\Http\Controllers;

use App\TransactionDaily;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FakturController extends Controller
{
    public function index()
    {
        $data['title']          = "Faktur";
        $data['sidebar']        = "faktur";
        $data['sidebar2']       = "";
        $data['fakturs']        = DB::table('transaction_daily')
            ->join('user', 'user.ID_USER', '=', 'transaction_daily.ID_USER')
            ->join('md_type', 'md_type.ID_TYPE', '=', 'transaction_daily.ID_TYPE')
            ->orderBy('transaction_daily.DATE_TD', 'DESC')
            ->select('transaction_daily.*', 'user.NAME_USER', 'md_type.NAME_TYPE')
            ->get();

        return view('master.faktur.faktur', $data);
    }

    public function DetailFaktur(Request $req)
    {
        $data['title']          = "Detail Faktur";
        $data['sidebar']        = "faktur";

        $ID_Faktur    = $req->input('id_td');
        $ID_USER = $req->input("id_user");
        $ID_REGIONAL = $req->input("id_regional");

        $data['faktur']        = DB::table('transaction_daily')
            ->where('transaction_daily.ID_TD', $ID_Faktur)
            ->join('user', 'user.ID_USER', '=', 'transaction_daily.ID_USER')
            ->join('md_type', 'md_type.ID_TYPE', '=', 'transaction_daily.ID_TYPE')
            ->orderBy('transaction_daily.DATE_TD', 'DESC')
            ->select('transaction_daily.*', 'user.NAME_USER', 'md_type.NAME_TYPE')
            ->first();

        $lastInvoice = TransactionDaily::where('ID_USER', '=', '' . $ID_USER . '')
            ->where('ISFINISHED_TD', '=', '1')
            ->latest('ID_TD')->first();

        $dt = new DateTime($lastInvoice->DATE_TD);
        $date = $dt->format('Y-m-d');

        $ts = DB::table("transaction")
            ->join('md_type', 'md_type.ID_TYPE', '=', 'transaction.ID_TYPE')
            ->where('transaction.ID_USER', '=', $ID_USER)
            ->whereDate('transaction.DATE_TRANS', '=', $date)
            ->get();

        $dataT = array();

        foreach ($ts as $t) {
            $ts_d = DB::table("transaction_detail")
                ->join('product_price', 'product_price.ID_PRODUCT', '=', 'transaction_detail.ID_PRODUCT')
                ->join('md_product', 'md_product.ID_PRODUCT', '=', 'transaction_detail.ID_PRODUCT')
                ->where('transaction_detail.ID_TRANS', '=', $t->ID_TRANS)
                ->where('product_price.ID_REGIONAL', '=', $ID_REGIONAL)
                ->get();

            $THargaDetail = 0;
            $dataTsD = array();

            foreach ($ts_d as $tsdData) {
                $THargaDetail += ($tsdData->QTY_TD * $tsdData->PRICE_PP);
                array_push(
                    $dataTsD,
                    array(
                        "ID_TD" => $tsdData->ID_TD,
                        "ID_PRODUCT" => $tsdData->ID_PRODUCT,
                        "NAME_PRODUCT" => $tsdData->NAME_PRODUCT,
                        "QTY_TD" => $tsdData->QTY_TD,
                        "PRICE_PP" => $tsdData->PRICE_PP
                    )
                );
            }

            array_push(
                $dataT,
                array(
                    "ID_TRANS" => $t->ID_TRANS,
                    "ID_TYPE" => $t->ID_TYPE,
                    "TOT_HARGA_TRANS" => $THargaDetail,
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

        $productQty = array();
        foreach ($TotalQty as $Data3) {
            array_push(
                $productQty,
                array(
                    "NAME_PRODUCT" => $Data3['NAME_PRODUCT'],
                    "TOT_QTY_PROD" => $Data3['TOTAL']
                )
            );
        }

        $THargaFaktur = 0;
        $typetr = "";
        foreach ($dataT as $Faktur) {
            $THargaFaktur += $Faktur['TOT_HARGA_TRANS'];
            if ($Faktur['ID_TYPE'] != 1) {
                $typetr = $Faktur['ID_TYPE'];
            } else {
                $typetr = 1;
            }
        }

        // $dataFaktur = array();
        // array_push(
        //     $dataFaktur,
        //     array(
        //         "TOT_HARGA_FAKTUR" => $THargaFaktur,
        //         "ID_TYPE" => $typetr,
        //         "TOTAL_QTY_FAKTUR" => $productQty,
        //         "FAKTUR" => $dataT
        //     )
        // );


        return view('master.faktur.detail_faktur', $data);
    }
}
