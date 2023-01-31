<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Transaction;
use App\Presence;
use App\TransactionDetail;
use App\TransactionImage;
use App\Location;
use App\Regional;
use App\Area;
use App\Datefunc;
use App\District;
use App\Pickup;
use App\Shop;
use App\TransactionDaily;
use App\TransactionDetailToday;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionApi extends Controller
{
    public function store(Request $req)
    {
        try {
            
            $validator = Validator::make($req->all(), [
                'id_shop'                   => 'required',
                'id_type'                   => 'required',
                'product.*.id_product'      => 'required|exists:md_product,ID_PRODUCT',
                'qty_trans'                 => 'required',
                'total_trans'               => 'required',
                'is_trans'                  => 'required',
                'long_trans'                => 'required',
                'lat_trans'                 => 'required'
            ], [
                'required'  => 'Parameter :attribute tidak boleh kosong!',
            ]);

            if ($validator->fails()) {
                return response([
                    "status_code"       => 400,
                    "status_message"    => $validator->errors()->first()
                ], 400);
            }

            $dateFunc = new Datefunc();
            $currDate = $dateFunc->currDate($req->input('long_trans'), $req->input('lat_trans'));

            $transaction        = new Transaction();
            $location           = new Location();
            $regional           = new Regional();
            $area               = new Area();

            $cekData    = Pickup::select('ID_PICKUP', 'ID_PRODUCT', 'REMAININGSTOCK_PICKUP')
                ->where([
                    ['ID_USER', '=', $req->input('id_user')],
                    ['ISFINISHED_PICKUP', '=', 0]
                ])->latest('ID_PICKUP')->first();

            $pecahIdproduk = explode(";", $cekData->ID_PRODUCT);
            $pecahRemainproduk = explode(";", $cekData->REMAININGSTOCK_PICKUP);
            $tdkLolos = 0;
            $Stok = array();
            $Sisa = array();

            for ($i = 0; $i < count($pecahIdproduk); $i++) {
                $Stok[$pecahIdproduk[$i]] = $pecahRemainproduk[$i];
            }

            foreach ($req->input('product') as $item) {
                if ($Stok[$item['id_product']] >= $item['qty_product']) {
                    $Sisa[$item['id_product']] = $Stok[$item['id_product']] - $item['qty_product'];
                } else {
                    $Sisa[$item['id_product']] = $Stok[$item['id_product']];
                    $tdkLolos++;
                }
            }

            $Stok2 = array();
            foreach ($Sisa as $stokKey => $itemStok) {
                array_push(
                    $Stok2,
                    array(
                        "ID_PRODUCT" => $stokKey,
                        "NEW_STOK" => $itemStok
                    )
                );
            }

            $id_district = Presence::select('ID_DISTRICT')
                ->whereDate('DATE_PRESENCE', date('Y-m-d'))->where('ID_USER',  $req->input("id_user"))
                ->first();

            if (empty($id_district)) {
                return response([
                    'status_code'       => 404,
                    'status_message'    => "Data kecamatan tidak ada!",
                ], 200);
            }

            $cekLokasi = District::select('ID_DISTRICT', 'ID_AREA','NAME_DISTRICT', 'ADDRESS_DISTRICT')
                ->where([
                    ['ID_AREA', '=', $req->input("id_area")],
                    ['ID_DISTRICT', '=', $id_district->ID_DISTRICT]
                ])->whereNull('deleted_at')->get();

            $transDaily = TransactionDaily::whereDate('DATE_TD', '=', date('Y-m-d'))
                ->where('ID_USER', '=', $req->input('id_user'))
                ->first();
    
            if ($cekLokasi->isNotEmpty()) {
                if ($tdkLolos == 0) {
                    $updatePickup = Pickup::find($cekData->ID_PICKUP);
                    $updatePickup->REMAININGSTOCK_PICKUP      = $this->UpdatePickup($Stok2, $pecahIdproduk, $pecahRemainproduk);
                    $updatePickup->save();

                    $shop = Shop::where('ID_SHOP', $req->input('id_shop'))->first();
                    $nameLoc    = $location::select('NAME_LOCATION')->where('ID_LOCATION', $req->input('id_location'))->first()->NAME_LOCATION;
                    $nameReg    = $regional::select('NAME_REGIONAL')->where('ID_REGIONAL', $req->input('id_regional'))->first()->NAME_REGIONAL;
                    $nameArea   = $area::select('NAME_AREA')->where('ID_AREA', $req->input('id_area'))->first()->NAME_AREA;
    
                    $typeAct = null;
                    if((int)$req->input('is_trans') == 1){
                        $typeAct = $shop->TYPE_SHOP;
                    }
                    
                    
                    $unik                           = md5($req->input('id_user') . "_" . $currDate);
                    $transaction->ID_TRANS          = "TRANS_" . $unik;
                    $transaction->ID_TD             = $transDaily->ID_TD;
                    $transaction->ID_USER           = $req->input('id_user');
                    $transaction->KECAMATAN         = strtoupper($cekLokasi[0]->NAME_DISTRICT);
                    $transaction->ID_SHOP           = $req->input('id_shop');
                    $transaction->ID_TYPE           = $req->input('id_type');
                    $transaction->LOCATION_TRANS    = $nameLoc;
                    $transaction->REGIONAL_TRANS    = $nameReg;
                    $transaction->QTY_TRANS     = $req->input('qty_trans');
                    $transaction->TOTAL_TRANS   = $req->input('total_trans');
                    $transaction->DATE_TRANS    = $currDate;
                    $transaction->LONG_TRANS    = $req->input('long_trans');
                    $transaction->LAT_TRANS    = $req->input('lat_trans');
                    $transaction->AREA_TRANS    = $nameArea;
                    $transaction->ISTRANS_TRANS     = $req->input('is_trans');
                    $transaction->TYPE_ACTIVITY     = $typeAct;
                    $transaction->save();
    
                    foreach ($req->input('product') as $item) {
                        TransactionDetail::insert([
                            [
                                'ID_TRANS'      => "TRANS_" . $unik,
                                'ID_SHOP'       => $req->input('id_shop'),
                                'ID_PRODUCT'    => $item['id_product'],
                                'ID_PC'         => $item['id_pc'],
                                'QTY_TD'        => $item['qty_product'],
                                'DATE_TD'       => $currDate,
                            ]
                        ]);

                        TransactionDetailToday::insert([
                            [
                                'ID_TRANS'          => "TRANS_" . $unik,
                                'ID_PRODUCT'        => $item['id_product'],
                                'ID_PC'             => $item['id_pc'],
                                'QTY_TD'            => $item['qty_product'],
                                'DATE_TD'           => $currDate,
                                'ID_USER'           => $req->input('id_user'),
                                'TYPE_ACTIVITY'     => $typeAct,
                                'LOCATION_TRANS'    => $nameLoc,
                                'REGIONAL_TRANS'    => $nameReg,
                                'AREA_TRANS'        => $nameArea,
                                'KECAMATAN'         => strtoupper($cekLokasi[0]->NAME_DISTRICT)
                            ]
                        ]);
                    }

                    Shop::where(['ID_SHOP' => $req->input('id_shop')])->update(['ISRECOMMEND_SHOP' => '0', 'LASTTRANS_SHOP' => $currDate]);
    
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
            }else{
                return response([
                    'status_code'       => 500,
                    'status_message'    => "Cek lokasi anda!",
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
        try {
            
            $validator = Validator::make($req->all(), [
                'id_type'                   => 'required',
                'product.*.id_product'      => 'required|exists:md_product,ID_PRODUCT',
                'qty_trans'                 => 'required',
                'total_trans'               => 'required',
                'name_district'             => 'required',
                'lat_trans'                 => 'required',
                'long_trans'                => 'required',
                'detail_loc'                => 'required',
                'is_trans'                  => 'required'
            ], [
                'required'  => 'Parameter :attribute tidak boleh kosong!',
            ]);

            if ($validator->fails()) {
                return response([
                    "status_code"       => 400,
                    "status_message"    => $validator->errors()->first()
                ], 400);
            }
            
            $dateFunc = new Datefunc();
            $currDate = $dateFunc->currDate($req->input('long_trans'), $req->input('lat_trans'));

            $cekDistrict = District::select('md_district.*')
                ->where('NAME_DISTRICT', '=', $req->input('name_district'))
                ->where('ISMARKET_DISTRICT', '=', 0)
                ->first();

            $cekArea  = area::select('md_area.*')
                ->where('ID_AREA', '=', $req->input('id_area'))
                ->where('ID_AREA', '=', $cekDistrict['ID_AREA'])
                ->where('deleted_at', '=', NULL)
                ->first();

            $transDaily = TransactionDaily::whereDate('DATE_TD', '=', date('Y-m-d'))
            ->where('ID_USER', '=', $req->input('id_user'))
            ->first();

            if ($cekArea != null) {

                $cekData    = Pickup::select('ID_PICKUP', 'ID_PRODUCT', 'REMAININGSTOCK_PICKUP')
                    ->where([
                        ['ID_USER', '=', $req->input('id_user')],
                        ['ISFINISHED_PICKUP', '=', 0]
                    ])->latest('ID_PICKUP')->first();

                $pecahIdproduk = explode(";", $cekData->ID_PRODUCT);
                $pecahRemainproduk = explode(";", $cekData->REMAININGSTOCK_PICKUP);
                $tdkLolos = 0;
                $Stok = array();
                $Sisa = array();

                for ($i = 0; $i < count($pecahIdproduk); $i++) {
                    $Stok[$pecahIdproduk[$i]] = $pecahRemainproduk[$i];
                }

                foreach ($req->input('product') as $item) {
                    if ($Stok[$item['id_product']] >= $item['qty_product']) {
                        $Sisa[$item['id_product']] = $Stok[$item['id_product']] - $item['qty_product'];
                    } else {
                        $Sisa[$item['id_product']] = $Stok[$item['id_product']];
                        $tdkLolos++;
                    }
                }

                $Stok2 = array();
                foreach ($Sisa as $stokKey => $itemStok) {
                    array_push(
                        $Stok2,
                        array(
                            "ID_PRODUCT" => $stokKey,
                            "NEW_STOK" => $itemStok
                        )
                    );
                }

                if ($tdkLolos == 0) {
                    $transaction        = new Transaction();
                    $location           = new Location();
                    $regional           = new Regional();
                    $area               = new Area();

                    $updatePickup = Pickup::find($cekData->ID_PICKUP);
                    $updatePickup->REMAININGSTOCK_PICKUP = $this->UpdatePickup($Stok2, $pecahIdproduk, $pecahRemainproduk);
                    $updatePickup->save();

                    $nameLoc    = $location::select('NAME_LOCATION')->where('ID_LOCATION', $req->input('id_location'))->first()->NAME_LOCATION;
                    $nameReg    = $regional::select('NAME_REGIONAL')->where('ID_REGIONAL', $req->input('id_regional'))->first()->NAME_REGIONAL;
                    $nameArea   = $area::select('NAME_AREA')->where('ID_AREA', $req->input('id_area'))->first()->NAME_AREA;
                    
                    $typeAct = null;
                    if((int)$req->input('is_trans') == 1){
                        $typeAct = "Aktivitas UB";
                    }

                    $unik                           = md5($req->input('id_user') . "_" . $currDate);
                    $transaction->ID_TRANS          = "TRANS_" . $unik;
                    $transaction->ID_TD             = $transDaily->ID_TD;
                    $transaction->KECAMATAN         = strtoupper($req->input('name_district'));
                    $transaction->ID_USER           = $req->input('id_user');
                    $transaction->ID_TYPE           = $req->input('id_type');
                    $transaction->LOCATION_TRANS    = $nameLoc;
                    $transaction->REGIONAL_TRANS    = $nameReg;
                    $transaction->QTY_TRANS         = $req->input('qty_trans');
                    $transaction->TOTAL_TRANS       = $req->input('total_trans');
                    $transaction->DATE_TRANS        = $currDate;
                    $transaction->AREA_TRANS        = $nameArea;
                    $transaction->LAT_TRANS         = $req->input('lat_trans');
                    $transaction->LONG_TRANS        = $req->input('long_trans');
                    $transaction->DETAIL_LOCATION   = $req->input('detail_loc');
                    $transaction->TYPE_ACTIVITY     = $typeAct;
                    $transaction->ISTRANS_TRANS     = $req->input('is_trans');
                    $transaction->save();

                    foreach ($req->input('product') as $item) {
                        $dataDetailTrans = array(
                            'ID_TRANS'      => "TRANS_" . $unik,
                            'ID_PRODUCT'    => $item['id_product'],
                            'ID_PC'         => $item['id_pc'],
                            'QTY_TD'        => $item['qty_product'],
                            'DATE_TD'       => $currDate,
                        );
                        TransactionDetail::insert($dataDetailTrans);

                        TransactionDetailToday::insert([
                            [
                                'ID_TRANS'          => "TRANS_" . $unik,
                                'ID_PRODUCT'        => $item['id_product'],
                                'ID_PC'             => $item['id_pc'],
                                'QTY_TD'            => $item['qty_product'],
                                'DATE_TD'           => $currDate,
                                'ID_USER'           => $req->input('id_user'),
                                'TYPE_ACTIVITY'     => $typeAct,
                                'LOCATION_TRANS'    => $nameLoc,
                                'REGIONAL_TRANS'    => $nameReg,
                                'AREA_TRANS'        => $nameArea,
                                'KECAMATAN'         => strtoupper($req->input('name_district')),
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
            } else {
                $cekArea  = area::select('md_area.*')
                    ->where('ID_AREA', '=', $req->input('id_area'))
                    ->first();
                return response([
                    "status_code"       => 200,
                    "status_message"    => 'Maaf Anda Tidak Bisa Melakukan UBLP di Luar Area ' . $cekArea['NAME_AREA'] . ' !'
                ], 200);
            }
        } catch (HttpResponseException $exp) {
            return response([
                'status_code'       => $exp->getCode(),
                'status_message'    => $exp->getMessage(),
            ], $exp->getCode());
        }
    }

    public function ubTransaction(Request $req)
    {
        try {
            
            $validator = Validator::make($req->all(), [
                'id_type'                   => 'required',
                'id_district'               => 'required',
                'product.*.id_product'      => 'required|exists:md_product,ID_PRODUCT',
                'qty_trans'                 => 'required',
                'total_trans'               => 'required',
                'lat_trans'                 => 'required',
                'long_trans'                => 'required',
                'is_trans'                  => 'required'

            ], [
                'required'  => 'Parameter :attribute tidak boleh kosong!',
            ]);

            if ($validator->fails()) {
                return response([
                    "status_code"       => 400,
                    "status_message"    => $validator->errors()->first()
                ], 400);
            }

            $dateFunc = new Datefunc();
            $currDate = $dateFunc->currDate($req->input('long_trans'), $req->input('lat_trans'));

            $transaction        = new Transaction();
            $location           = new Location();
            $regional           = new Regional();
            $area               = new Area();
            $district           = new District();

            $cekData    = Pickup::select('ID_PICKUP', 'ID_PRODUCT', 'REMAININGSTOCK_PICKUP')
                ->where([
                    ['ID_USER', '=', $req->input('id_user')],
                    ['ISFINISHED_PICKUP', '=', 0]
                ])->latest('ID_PICKUP')->first();

            $pecahIdproduk = explode(";", $cekData->ID_PRODUCT);
            $pecahRemainproduk = explode(";", $cekData->REMAININGSTOCK_PICKUP);
            $tdkLolos = 0;
            $Stok = array();
            $Sisa = array();

            for ($i = 0; $i < count($pecahIdproduk); $i++) {
                $Stok[$pecahIdproduk[$i]] = $pecahRemainproduk[$i];
            }

            foreach ($req->input('product') as $item) {
                if ($Stok[$item['id_product']] >= $item['qty_product']) {
                    $Sisa[$item['id_product']] = $Stok[$item['id_product']] - $item['qty_product'];
                } else {
                    $Sisa[$item['id_product']] = $Stok[$item['id_product']];
                    $tdkLolos++;
                }
            }

            $Stok2 = array();
            foreach ($Sisa as $stokKey => $itemStok) {
                array_push(
                    $Stok2,
                    array(
                        "ID_PRODUCT" => $stokKey,
                        "NEW_STOK" => $itemStok
                    )
                );
            }

            $id_district = Presence::select('ID_DISTRICT')
            ->whereDate('DATE_PRESENCE', date('Y-m-d'))->where('ID_USER',  $req->input("id_user"))
            ->first()->ID_DISTRICT;

            $cekLokasi = District::select('ID_DISTRICT', 'ID_AREA','NAME_DISTRICT', 'ADDRESS_DISTRICT')
            ->where([
                ['ID_AREA', '=', $req->input("id_area")],
                ['ID_DISTRICT', '=', $id_district]
            ])->whereNull('deleted_at')->get();

            $transDaily = TransactionDaily::whereDate('DATE_TD', '=', date('Y-m-d'))
            ->where('ID_USER', '=', $req->input('id_user'))
            ->first();

            if ($cekLokasi->isNotEmpty()) {
                if ($tdkLolos == 0) {

                    $updatePickup = Pickup::find($cekData->ID_PICKUP);
                    $updatePickup->REMAININGSTOCK_PICKUP      = $this->UpdatePickup($Stok2, $pecahIdproduk, $pecahRemainproduk);
                    $updatePickup->save();

                    $kecamatan  = District::where('ID_DISTRICT', '=', $id_district)->first();
                    $nameLoc    = $location::select('NAME_LOCATION')->where('ID_LOCATION', $req->input('id_location'))->first()->NAME_LOCATION;
                    $nameReg    = $regional::select('NAME_REGIONAL')->where('ID_REGIONAL', $req->input('id_regional'))->first()->NAME_REGIONAL;
                    $nameArea   = $area::select('NAME_AREA')->where('ID_AREA', $req->input('id_area'))->first()->NAME_AREA;

                    $typeAct = null;
                    if((int)$req->input('is_trans') == 1){
                        $typeAct = "Aktivitas UB";
                    }
    
                    $unik                           = md5($req->input('id_user') . "_" . $currDate);
                    $transaction->ID_TRANS          = "TRANS_" . $unik;
                    $transaction->ID_TD             = $transDaily->ID_TD;
                    $transaction->KECAMATAN         = strtoupper($kecamatan->NAME_DISTRICT);
                    $transaction->ID_USER           = $req->input('id_user');
                    $transaction->ID_TYPE           = $req->input('id_type');
                    $transaction->LOCATION_TRANS    = $nameLoc;
                    $transaction->REGIONAL_TRANS    = $nameReg;
                    $transaction->QTY_TRANS         = $req->input('qty_trans');
                    $transaction->TOTAL_TRANS       = $req->input('total_trans');
                    $transaction->DATE_TRANS        = $currDate;
                    $transaction->AREA_TRANS        = $nameArea;
                    $transaction->DISTRICT          = $district::select('NAME_DISTRICT')->where('ID_DISTRICT', $req->input('id_district'))->first()->NAME_DISTRICT;
                    $transaction->LAT_TRANS         = $req->input('lat_trans');
                    $transaction->LONG_TRANS        = $req->input('long_trans');
                    $transaction->TYPE_ACTIVITY     = $typeAct;
                    $transaction->ISTRANS_TRANS     = $req->input('is_trans');
                    $transaction->save();
    
                    foreach ($req->input('product') as $item) {
                        TransactionDetail::insert([
                            [
                                'ID_TRANS'      => "TRANS_" . $unik,
                                'ID_PRODUCT'    => $item['id_product'],
                                'ID_PC'         => $item['id_pc'],
                                'QTY_TD'        => $item['qty_product'],
                                'DATE_TD'       => $currDate,
                            ]
                        ]);

                        TransactionDetailToday::insert([
                            [
                                'ID_TRANS'          => "TRANS_" . $unik,
                                'ID_PRODUCT'        => $item['id_product'],
                                'ID_PC'             => $item['id_pc'],
                                'QTY_TD'            => $item['qty_product'],
                                'DATE_TD'           => $currDate,
                                'ID_USER'           => $req->input('id_user'),
                                'TYPE_ACTIVITY'     => $typeAct,
                                'LOCATION_TRANS'    => $nameLoc,
                                'REGIONAL_TRANS'    => $nameReg,
                                'AREA_TRANS'        => $nameArea,
                                'KECAMATAN'         => strtoupper($kecamatan->NAME_DISTRICT)
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
            }else{
                return response([
                    'status_code'       => 500,
                    'status_message'    => "Cek lokasi anda!",
                ], 500);
            }
        } catch (HttpResponseException $exp) {
            return response([
                'status_code'       => $exp->getCode(),
                'status_message'    => $exp->getMessage(),
            ], $exp->getCode());
        }
    }

    public function TransactionHistory(Request $req)
    {
        try {
            $tgl = $req->input('tanggal');
            $FrmtTgl = date('Y-m-d', strtotime($tgl));
            if ($tgl != NULL) {
                $dataTrans = Transaction::select('transaction.*', 'user.*', 'md_shop.*')
                    ->where('transaction.ID_USER', '=', $req->input('id_user'))
                    ->where('transaction.DATE_TRANS', 'LIKE', '%' . $FrmtTgl . '%')
                    ->leftjoin('user', 'user.ID_USER', '=', 'transaction.ID_USER')
                    ->leftjoin('md_shop', 'md_shop.ID_SHOP', '=', 'transaction.ID_SHOP')
                    ->orderBy('DATE_TRANS', 'DESC')
                    ->paginate(10);
            } else {
                $dataTrans = Transaction::select('transaction.*', 'user.*', 'md_shop.*')
                    ->where('transaction.ID_USER', '=', $req->input('id_user'))
                    ->leftjoin('user', 'user.ID_USER', '=', 'transaction.ID_USER')
                    ->leftjoin('md_shop', 'md_shop.ID_SHOP', '=', 'transaction.ID_SHOP')
                    ->orderBy('DATE_TRANS', 'DESC')
                    ->paginate(10);
            }

            $dataPagination = array();
            array_push(
                $dataPagination,
                array(
                    "TOTAL_DATA" => $dataTrans->total(),
                    "PAGE" => $dataTrans->currentPage(),
                    "TOTAL_PAGE" => $dataTrans->lastPage()
                )
            );

            $Trans = array();
            $counter = 0;
            foreach ($dataTrans->items() as $item) {
                $detTrans       = TransactionDetail::select('transaction_detail.*')
                    ->where('transaction_detail.ID_TRANS', $item->ID_TRANS)
                    ->get();

                $jml_qty = 0;
                foreach ($detTrans as $item2) {
                    $jml_qty += $item2->QTY_TD;
                }

                array_push(
                    $Trans,
                    array(
                        "ID_TRANS" => $item->ID_TRANS,
                        "USERNAME_USER" => $item->USERNAME_USER,
                        "DATE_TRANS" => $item->DATE_TRANS,
                        "JML_QTY_PRODUCT" => $jml_qty,
                    )
                );

                if ($item->ID_TYPE == 1) {
                    $Trans[$counter]['NAMA_TOKO'] = $item->NAME_SHOP;
                } else {
                    if ($item->ID_TYPE == 2) {
                        $Trans[$counter]['NAMA_PASAR'] = $item->DISTRICT;
                    } else {
                        $Trans[$counter]['NAMA_AREA'] = $item->AREA_TRANS;
                    }
                }
                $counter++;
            }

            return response([
                'status_code'       => 200,
                'status_message'    => 'Data berhasil diambil!',
                'data'              => $Trans,
                'status_pagination' => $dataPagination
            ], 200);
        } catch (HttpResponseException $exp) {
            return response([
                'status_code'       => $exp->getCode(),
                'status_message'    => $exp->getMessage(),
            ], $exp->getCode());
        }
    }

    public function DetailVisit(Request $req)
    {
        try {
            $dataTrans = Transaction::select('transaction.*', 'user.*', 'md_shop.*')
                ->where('transaction.ID_TRANS', '=', $req->input('id_trans'))
                ->leftjoin('user', 'user.ID_USER', '=', 'transaction.ID_USER')
                ->leftjoin('md_shop', 'md_shop.ID_SHOP', '=', 'transaction.ID_SHOP')
                ->orderBy('DATE_TRANS', 'DESC')
                ->first();

            $detTrans       = TransactionDetail::select('md_product.NAME_PRODUCT', 'transaction_detail.QTY_TD')
                ->where('transaction_detail.ID_TRANS', $dataTrans->ID_TRANS)
                ->leftjoin('md_product', 'md_product.ID_PRODUCT', '=', 'transaction_detail.ID_PRODUCT')
                ->get();

            $TransImage      = TransactionImage::select('transaction_image.*')
                ->where('transaction_image.ID_TRANS', $dataTrans->ID_TRANS)
                ->first();

            $Trans = array(
                "ID_TRANS" => $dataTrans->ID_TRANS,
                "ID_TYPE" => $dataTrans->ID_TYPE,
                "USERNAME_USER" => $dataTrans->USERNAME_USER,
                "DATE_TRANS" => $dataTrans->DATE_TRANS,
                "PRODUCT_TERJUAL" => $detTrans,
                "IMAGE" => array(
                    "URL" => explode(";", $TransImage->PHOTO_TI),
                    "DESC_IMAGE" => explode(";", $TransImage->DESCRIPTION_TI),
                )
            );

            if ($dataTrans->ID_TYPE == 1) {
                $Trans['NAME_SHOP'] = $dataTrans->NAME_SHOP;
                $Trans['DETAIL_ALAMAT'] = $dataTrans->DETLOC_SHOP;
            } else {
                $Trans['NAME_SHOP'] = null;
                if ($dataTrans->ID_TYPE == 2) {
                    $Trans['DETAIL_ALAMAT'] = $dataTrans->DISTRICT;
                } else {
                    $Trans['DETAIL_ALAMAT'] = $dataTrans->DETAIL_LOCATION;
                }
            }

            return response([
                'status_code'       => 200,
                'status_message'    => 'Data berhasil diambil!',
                'data'              => $Trans
            ], 200);
        } catch (HttpResponseException $exp) {
            return response([
                'status_code'       => $exp->getCode(),
                'status_message'    => $exp->getMessage(),
            ], $exp->getCode());
        }
    }

    public function UpdatePickup($Stok2, $pecahIdproduk, $pecahRemainproduk)
    {
        $newIdProd = array();
        foreach($Stok2 as $ItemNewStok){
            array_push(
                $newIdProd,
                $ItemNewStok['ID_PRODUCT']
            );
        }

        $newStok = array();
        $position = 0;
        for ($i = 0; $i < count($pecahIdproduk); $i++) {
            if (count($Stok2) == 1) {
                if ($Stok2[0]['ID_PRODUCT'] == $pecahIdproduk[$i]) {
                    array_push($newStok, $Stok2[0]['NEW_STOK']);
                } else {
                    array_push($newStok, (int)$pecahRemainproduk[$i]);
                }
            } else {
                if (in_array($pecahIdproduk[$i], $newIdProd)) {
                    array_push($newStok, $Stok2[$position]['NEW_STOK']);
                    $position++;
                } else {
                    array_push($newStok, $pecahRemainproduk[$i]);
                }
            }
        }

        return implode(";", $newStok);
    }
    public function checkUBUBLP(Request $req, $idType){
        try{
            $date = date('Y-m-d');
            $transUBUBLP = DB::select("
                SELECT t.*
                FROM `transaction` t
                WHERE 
                    DATE(t.DATE_TRANS) = '".$date."' 
                    AND t.ID_USER = '".$req->input('id_user')."'
                    AND t.ID_TYPE IN (2, 3)
            ");

            if($transUBUBLP != null){
                return response([
                    'status_code'       => 200,
                    'status_message'    => 'Anda telah melakukan UB/UBLP',
                    'status_allow'      => false
                ], 200);
            }else{
                return response([
                    'status_code'       => 200,
                    'status_message'    => 'Anda dapat melakukan UB/UBLP',
                    'status_allow'      => true
                ], 200);
            }
        }catch(HttpResponseException $exp){
            return response([
                'status_code'       => $exp->getCode(),
                'status_message'    => $exp->getMessage(),
            ], $exp->getCode());
        }
    }
}
