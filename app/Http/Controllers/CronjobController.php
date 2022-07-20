<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Recomendation;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CronjobController extends Controller
{
    
    public function cronjob_store_rekomendasi(Request $req)
    {
        try {
            $DataUser = DB::table("user")
                ->select('user.*')
                ->get();
            foreach($DataUser as $ItemUser){
                $id_user = $ItemUser->ID_USER;

                $Area = DB::table("md_district")
                    ->select("md_district.ID_DISTRICT")
                    ->where('md_district.ID_AREA', '=', $ItemUser->ID_AREA)
                    ->first();

                $shop = DB::table("md_shop")
                    ->select("md_shop.*")
                    ->where('md_shop.ID_DISTRICT', '=', $Area->ID_DISTRICT)
                    ->get();

                $dataRespon = array();
                $jml_data = 0;
                foreach ($shop as $shopData) {

                    $dataTrans = DB::table("transaction")
                        ->select('transaction.*')
                        ->selectRaw('DATEDIFF("' . Carbon::now() . '", transaction.DATE_TRANS) AS DateDiff')
                        ->where('ID_USER', '=', $id_user)
                        ->where('ID_SHOP', '=', $shopData->ID_SHOP)
                        ->orderBy('transaction.DATE_TRANS', 'DESC')
                        ->first();

                    if ($dataTrans != null && $dataTrans->DateDiff >= 14) {
                        array_push(
                            $dataRespon,
                            array(
                                "ID_SHOP" => $shopData->ID_SHOP,
                                "PHOTO_SHOP" => $shopData->PHOTO_SHOP,
                                "NAME_SHOP" => $shopData->NAME_SHOP,
                                "OWNER_SHOP" => $shopData->OWNER_SHOP,
                                "DETLOC_SHOP" => $shopData->DETLOC_SHOP,
                                "LONG_SHOP" => $shopData->LONG_SHOP,
                                "LAT_SHOP" => $shopData->LAT_SHOP,
                                "LAST_VISITED" => $dataTrans->DateDiff
                            )
                        );

                        $CheckRecom = DB::table("recomendation")
                            ->select("recomendation.*")
                            ->where('recomendation.ID_USER', '=', $req->input("id_user"))
                            ->get();

                        if (!empty($CheckRecom)) {
                            DB::table("recomendation")
                                ->where('recomendation.ID_USER', '=', $req->input("id_user"))
                                ->delete();
                        }

                        foreach ($dataRespon as $RecomShop) {
                            $recomendation = new Recomendation();
                            $recomendation->ID_USER      = $id_user;
                            $recomendation->ID_SHOP      = $RecomShop['ID_SHOP'];
                            $recomendation->NAME_SHOP    = $RecomShop['NAME_SHOP'];
                            $recomendation->DETLOC_SHOP  = $RecomShop['DETLOC_SHOP'];
                            $recomendation->PHOTO_SHOP   = $RecomShop['PHOTO_SHOP'];
                            $recomendation->IDLE_RECOM   = $RecomShop['LAST_VISITED'];
                            $recomendation->LAT_SHOP     = $RecomShop['LAT_SHOP'];
                            $recomendation->LONG_SHOP    = $RecomShop['LONG_SHOP'];
                            $recomendation->DATE_RECOM   = date('Y-m-d');
                            $recomendation->save();
                        }
                        $jml_data++;
                    }
                }
            }

            return response([
                "status_code"       => 200,
                "status_message"    => 'Data berhasil disimpan!'
            ], 200);
        } catch (Exception $exp) {
            return response([
                'status_code'       => 500,
                'status_message'    => $exp->getMessage(),
            ], 500);
        }
    }
}

?>