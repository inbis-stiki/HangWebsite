<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Transaction;
use App\TransactionDetail;
use App\Location;
use App\Regional;
use App\Area;
use App\Pickup;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;

class TransactionApi extends Controller
{
    public function store(Request $req)
    {
        try {
            date_default_timezone_set("Asia/Bangkok");
            $validator = Validator::make($req->all(), [
                'id_shop'                   => 'required',
                'id_type'                   => 'required',
                'product.*.id_product'      => 'required|exists:md_product,ID_PRODUCT',
                'qty_trans'                 => 'required',
                'total_trans'               => 'required',
            ], [
                'required'  => 'Parameter :attribute tidak boleh kosong!',
            ]);

            if ($validator->fails()) {
                return response([
                    "status_code"       => 400,
                    "status_message"    => $validator->errors()->first()
                ], 400);
            }

            $transaction        = new Transaction();
            $location           = new Location();
            $regional           = new Regional();
            $area               = new Area();

            $cekData    = Pickup::select('ID_PICKUP', 'ID_PRODUCT', 'REMAININGSTOCK_PICKUP')
                ->where([
                    ['ID_USER', '=', $req->input('id_user')],
                    ['ISFINISHED_PICKUP', '=', 0]
                ])->first();

            $pecahIdproduk = explode(";", $cekData->ID_PRODUCT);
            $pecahRemainproduk = explode(";", $cekData->REMAININGSTOCK_PICKUP);

            $tdkLolos = 0;
            $idproduct = array();
            $totalpickup = array();
            $sisa = array();

            foreach ($req->input('product') as $item) {
                array_push($totalpickup, $item['qty_product']);
                array_push($idproduct, $item['id_product']);
            }

            $tempe = array();
            $index = 0;
            foreach ($pecahIdproduk as $item) {
                $tempe[$item] = $pecahRemainproduk[$index];
                $index++;
            }

            foreach ($req->input('product') as $item) {
                if ($tempe[$item['id_product']] >= $item['qty_product']) {
                    array_push($sisa, $tempe[$item['id_product']] - $item['qty_product']);
                } else {
                    $tdkLolos++;
                }
            }

            if ($tdkLolos == 0) {
                // echo "<br>Lolos dan masuk transaksi";
                $remain = implode(";", $sisa);

                $updatePickup = Pickup::find($cekData->ID_PICKUP);
                $updatePickup->REMAININGSTOCK_PICKUP      = $remain;
                $updatePickup->save();

                $unik                           = md5($req->input('id_user') . "_" . date('Y-m-d H:i:s'));
                $transaction->ID_TRANS          = "TRANS_" . $unik;
                $transaction->ID_USER           = $req->input('id_user');
                $transaction->ID_SHOP           = $req->input('id_shop');
                $transaction->ID_TYPE           = $req->input('id_type');
                $transaction->LOCATION_TRANS    = $location::select('NAME_LOCATION')->where('ID_LOCATION', $req->input('id_location'))->first()->NAME_LOCATION;
                $transaction->REGIONAL_TRANS    = $regional::select('NAME_REGIONAL')->where('ID_REGIONAL', $req->input('id_regional'))->first()->NAME_REGIONAL;
                $transaction->QTY_TRANS     = $req->input('qty_trans');
                $transaction->TOTAL_TRANS   = $req->input('total_trans');
                $transaction->DATE_TRANS    = date('Y-m-d H:i:s');
                $transaction->AREA_TRANS    = $area::select('NAME_AREA')->where('ID_AREA', $req->input('id_area'))->first()->NAME_AREA;
                $transaction->save();

                foreach ($req->input('product') as $item) {
                    TransactionDetail::insert([
                        [
                            'ID_TRANS'      => "TRANS_" . $unik,
                            'ID_SHOP'       => $req->input('id_shop'),
                            'ID_PRODUCT'    => $item['id_product'],
                            'QTY_TD'        => $item['qty_product'],
                            'DATE_TD'       => date('Y-m-d H:i:s'),
                        ]
                    ]);
                }

                return response([
                    "status_code"       => 200,
                    "status_message"    => 'Data berhasil disimpan!',
                    "data"              => ['ID_TRANS' => $transaction->ID_TRANS]
                ], 200);
            } else {
                return response([
                    "status_code"       => 200,
                    "status_message"    => 'Cek Qty anda dengan stok sisa!'
                ], 200);
            }
        } catch (HttpResponseException $exp) {
            return response([
                'status_code'       => $exp->getCode(),
                'status_message'    => $exp->getMessage(),
            ], $exp->getCode());
        }
    }

    public function ublp(Request $req)
    {
        date_default_timezone_set("Asia/Bangkok");
        $validator = Validator::make($req->all(), [
            'id_shop'                   => 'required',
            'id_type'                   => 'required',
            'product.*.id_product'      => 'required|exists:md_product,ID_PRODUCT',
            'qty_trans'                 => 'required',
            'total_trans'               => 'required',
            'nama_area'                 => 'required'
        ], [
            'required'  => 'Parameter :attribute tidak boleh kosong!',
        ]);

        if ($validator->fails()) {
            return response([
                "status_code"       => 400,
                "status_message"    => $validator->errors()->first()
            ], 400);
        }

        $cekData    = Pickup::select('ID_PICKUP', 'ID_PRODUCT', 'REMAININGSTOCK_PICKUP')
            ->where([
                ['ID_USER', '=', $req->input('id_user')],
                ['ISFINISHED_PICKUP', '=', 0]
            ])->first();

        $pecahIdproduk = explode(";", $cekData->ID_PRODUCT);
        $pecahRemainproduk = explode(";", $cekData->REMAININGSTOCK_PICKUP);

        $Stok = array();
        for ($i = 0; $i < count($pecahIdproduk); $i++) {
            $Stok[$pecahIdproduk[$i]] = $pecahRemainproduk[$i];
        }

        $cekArea  = area::select('deleted_at')
            ->where([
                ['NAME_AREA', '=', $req->input('nama_area')],
                ['ID_AREA', '=', $req->input('id_area')]
            ])->first();

        if ($cekArea != null) {
            $tdkLolos = 0;
            $sisa = array();
            foreach ($req->input('product') as $item) {
                if ($Stok[$item['id_product']] >= $item['qty_product']) {
                    array_push(
                        $sisa,
                        array(
                            'ID_PRODUK' => $item['id_product'],
                            'STOK_SISA' => $Stok[$item['id_product']] - $item['qty_product']
                        )
                    );
                } else {
                    $tdkLolos++;
                }
            }

            if ($tdkLolos == 0) {
                $transaction        = new Transaction();
                $location           = new Location();
                $regional           = new Regional();
                $area               = new Area();

                $newStok = array();
                foreach ($sisa as $itemStok) {
                    array_push($newStok, $itemStok['STOK_SISA']);
                }

                $remain = implode(";", $newStok);

                $updatePickup = Pickup::find($cekData->ID_PICKUP);
                $updatePickup->REMAININGSTOCK_PICKUP = $remain;
                $updatePickup->save();

                $unik                           = md5($req->input('id_user') . "_" . date('Y-m-d H:i:s'));
                $transaction->ID_TRANS          = "TRANS_" . $unik;
                $transaction->ID_USER           = $req->input('id_user');
                $transaction->ID_TYPE           = $req->input('id_type');
                $transaction->LOCATION_TRANS    = $location::select('NAME_LOCATION')->where('ID_LOCATION', $req->input('id_location'))->first()->NAME_LOCATION;
                $transaction->REGIONAL_TRANS    = $regional::select('NAME_REGIONAL')->where('ID_REGIONAL', $req->input('id_regional'))->first()->NAME_REGIONAL;
                $transaction->QTY_TRANS         = $req->input('qty_trans');
                $transaction->TOTAL_TRANS       = $req->input('total_trans');
                $transaction->DATE_TRANS        = date('Y-m-d H:i:s');
                $transaction->AREA_TRANS        = $area::select('NAME_AREA')->where('ID_AREA', $req->input('id_area'))->first()->NAME_AREA;
                $transaction->save();

                foreach ($req->input('product') as $item) {
                    $dataDetailTrans = array(
                        'ID_TRANS'      => "TRANS_" . $unik,
                        'ID_PRODUCT'    => $item['id_product'],
                        'QTY_TD'        => $item['qty_product'],
                        'DATE_TD'       => date('Y-m-d H:i:s'),
                    );
                    TransactionDetail::insert($dataDetailTrans);
                }

                return response([
                    "status_code"       => 200,
                    "status_message"    => 'Data berhasil disimpan!',
                    "data"              => ['ID_TRANS' => $transaction->ID_TRANS]
                ], 200);
            } else {
                return response([
                    "status_code"       => 200,
                    "status_message"    => 'Cek Qty anda dengan stok sisa!'
                ], 200);
            }
        } else {
            return response([
                "status_code"       => 200,
                "status_message"    => 'Maaf Anda Tidak Bisa Melakukan UBLP di Daerah Ini!'
            ], 200);
        }
    }
}
