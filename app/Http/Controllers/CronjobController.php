<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Recomendation;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class CronjobController extends Controller
{

    public function cronjob_store_rekomendasi()
    {
        try {
            DB::table("recomendation")->delete();

            $DataUser = DB::table("user")
                ->select('user.*')
                ->get();

            foreach ($DataUser as $ItemUser) {
                $id_user = $ItemUser->ID_USER;

                $Trans = DB::table("transaction")
                    ->select('transaction.*')
                    ->selectRaw('DATEDIFF("' . Carbon::now() . '", transaction.DATE_TRANS) AS DateDiff')
                    ->where('ID_USER', '=', $id_user)
                    ->where('ID_SHOP', '<>', NULL)
                    ->orderBy('DateDiff', 'ASC')
                    ->get();

                $dataRecom = array();
                foreach ($Trans as $dataTrans) {
                    $shopData = DB::table("md_shop")
                        ->select("md_shop.*")
                        ->where('md_shop.ID_SHOP', '=', $dataTrans->ID_SHOP)
                        ->first();

                    if ($dataTrans->DateDiff >= 14) {
                        $dataRespon = array(
                            "ID_USER" => $id_user,
                            "ID_SHOP" => $shopData->ID_SHOP,
                            "PHOTO_SHOP" => $shopData->PHOTO_SHOP,
                            "NAME_SHOP" => $shopData->NAME_SHOP,
                            "OWNER_SHOP" => $shopData->OWNER_SHOP,
                            "DETLOC_SHOP" => $shopData->DETLOC_SHOP,
                            "LONG_SHOP" => $shopData->LONG_SHOP,
                            "LAT_SHOP" => $shopData->LAT_SHOP,
                            "ID_DISTRICT" => $shopData->ID_DISTRICT,
                            "LAST_VISITED" => $dataTrans->DateDiff
                        );

                        array_push($dataRecom, $dataRespon);
                    }
                }

                foreach ($dataRecom as $RecomShop) {
                    $CheckRecom = DB::table("recomendation")
                        ->select("recomendation.*")
                        ->where('recomendation.ID_USER', '=', $RecomShop['ID_USER'])
                        ->where('recomendation.ID_SHOP', '=', $RecomShop['ID_SHOP'])
                        ->first();

                    if ($CheckRecom == null) {
                        $recomendation = new Recomendation();
                        $recomendation->ID_USER      = $RecomShop['ID_USER'];
                        $recomendation->ID_SHOP      = $RecomShop['ID_SHOP'];
                        $recomendation->NAME_SHOP    = $RecomShop['NAME_SHOP'];
                        $recomendation->DETLOC_SHOP  = $RecomShop['DETLOC_SHOP'];
                        $recomendation->PHOTO_SHOP   = $RecomShop['PHOTO_SHOP'];
                        $recomendation->IDLE_RECOM   = $RecomShop['LAST_VISITED'];
                        $recomendation->LAT_SHOP     = $RecomShop['LAT_SHOP'];
                        $recomendation->LONG_SHOP    = $RecomShop['LONG_SHOP'];
                        $recomendation->ID_DISTRICT  = $RecomShop['ID_DISTRICT'];
                        $recomendation->DATE_RECOM   = date('Y-m-d');
                        $recomendation->save();
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
