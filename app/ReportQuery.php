<?php

namespace App;

use App\ActivityCategory;
use App\CategoryProduct;
use Illuminate\Support\Facades\DB;

class ReportQuery
{
    public function AktivitasRPODapul()
    {
        $month = date('n');
        $data = array();
        $user_regionals = DB::select("
        SELECT
        (
            SELECT
                u.NAME_USER
            FROM
                `user` u
            WHERE
                u.ID_ROLE = 4 
                AND u.ID_REGIONAL = mr.ID_REGIONAL
                LIMIT 1
        ) AS NAME_USER,
        mr.NAME_REGIONAL,
        mr.ID_REGIONAL
        FROM
            md_regional mr,
            md_location ml
        WHERE
            ml.ISINSIDE_LOCATION = 1
            AND mr.ID_LOCATION = ml.ID_LOCATION           
        ");

        foreach ($user_regionals as $user_regional) {
            $activity_rankings = DB::select("
            SELECT 
            (
                SELECT
                    QUANTITY
                    FROM
                    target_activity ta
                    WHERE
                        ID_ACTIVITY = 1
                        AND ID_REGIONAL = usa.ID_REGIONAL
            ) AS TARGET_UB, 
            SUM(usa.REAL_UB) AS REAL_UB, 
            usa.VSTARGET_UB, 
            (
                SELECT
                    QUANTITY
                    FROM
                    target_activity ta
                    WHERE
                        ID_ACTIVITY = 2
                        AND ID_REGIONAL = usa.ID_REGIONAL
            ) AS TARGET_PDGSAYUR, 
            SUM(usa.REAL_PDGSAYUR) AS REAL_PDGSAYUR, 
            usa.VSTARGET_PDGSAYUR, 
            (
                SELECT
                    QUANTITY
                    FROM
                    target_activity ta
                    WHERE
                        ID_ACTIVITY = 3
                        AND ID_REGIONAL = usa.ID_REGIONAL
            ) AS TARGET_RETAIL, 
            SUM(usa.REAL_RETAIL) AS REAL_RETAIL, 
            usa.VSTARGET_RETAIL, 
            usa.AVERAGE, 
            usa.ID_USER_RANKACTIVITY
            FROM
                user_ranking_activity AS usa,
                md_regional AS mr
            WHERE
                usa.ID_REGIONAL	= " . $user_regional->ID_REGIONAL . "
                AND usa.ID_REGIONAL = mr.ID_REGIONAL
                AND MONTH(DATE(usa.created_at)) = " . $month . "
            GROUP BY
                usa.ID_REGIONAL
            ");

            $temp['NAME_USER']          = $user_regional->NAME_USER;
            $temp['NAME_AREA']          = $user_regional->NAME_REGIONAL;
            $temp['TARGET_UB']          = !empty($activity_rankings[0]->TARGET_UB) ? $activity_rankings[0]->TARGET_UB : "-";
            $temp['REAL_UB']            = !empty($activity_rankings[0]->REAL_UB) ? $activity_rankings[0]->REAL_UB : "-";
            $temp['VSTARGET_UB']        = $temp['REAL_UB'] != "-" && $temp['TARGET_UB'] != "-" ? ($temp['REAL_UB'] / $temp['TARGET_UB']) * 100 : "-";
            $temp['TARGET_PDGSAYUR']    = !empty($activity_rankings[0]->TARGET_PDGSAYUR) ? $activity_rankings[0]->TARGET_PDGSAYUR : "-";
            $temp['REAL_PDGSAYUR']      = !empty($activity_rankings[0]->REAL_PDGSAYUR) ? $activity_rankings[0]->REAL_PDGSAYUR : "-";
            $temp['VSTARGET_PDGSAYUR']  = $temp['REAL_PDGSAYUR'] != "-" && $temp['TARGET_PDGSAYUR'] != "-" ? ($temp['REAL_PDGSAYUR'] / $temp['TARGET_PDGSAYUR']) * 100 : "-";
            $temp['TARGET_RETAIL']      = !empty($activity_rankings[0]->TARGET_RETAIL) ? $activity_rankings[0]->TARGET_RETAIL : "-";
            $temp['REAL_RETAIL']        = !empty($activity_rankings[0]->REAL_RETAIL) ? $activity_rankings[0]->REAL_RETAIL : "-";
            $temp['VSTARGET_RETAIL']    = $temp['REAL_RETAIL'] != "-" && $temp['TARGET_RETAIL'] != "-" ? ($temp['REAL_RETAIL'] / $temp['TARGET_RETAIL']) * 100 : "-";

            $weightActCat = ActivityCategory::where('deleted_at', NULL)->get()->toArray();
            $avgCount = 0;
            if ($weightActCat[0]["PERCENTAGE_AC"] != 0) $avgCount++;
            if ($weightActCat[1]["PERCENTAGE_AC"] != 0) $avgCount++;
            if ($weightActCat[2]["PERCENTAGE_AC"] != 0) $avgCount++;

            $temp['AVERAGE'] = (((float)$temp['VSTARGET_UB'] / 100) * ((float)$weightActCat[0]["PERCENTAGE_AC"] / 100));
            $temp['AVERAGE'] += (((float)$temp['VSTARGET_PDGSAYUR'] / 100) * ((float)$weightActCat[1]["PERCENTAGE_AC"] / 100));
            $temp['AVERAGE'] += (((float)$temp['VSTARGET_RETAIL'] / 100) * ((float)$weightActCat[2]["PERCENTAGE_AC"] / 100));
            $temp['AVERAGE'] = $temp['AVERAGE'] / $avgCount;

            $data[] = $temp;
        }
        usort($data, function ($a, $b) {
            return strnatcmp($b['AVERAGE'], $a['AVERAGE']);
        });
        foreach ($data as $key => $value) {
            $data[$key]['ID_USER_RANKSALE'] = $key + 1;
        }
        return $data;
    }

    public function PencapaianRPODapul()
    {
        $month = date('n');
        $data = array();
        $user_areas = DB::select("
        SELECT
        (
            SELECT
                u.NAME_USER
            FROM
                `user` u
            WHERE
                u.ID_ROLE = 4 
                AND u.ID_REGIONAL = mr.ID_REGIONAL
                LIMIT 1
        ) AS NAME_USER,
        mr.NAME_REGIONAL,
        mr.ID_REGIONAL
        FROM
            md_regional mr,
            md_location ml
        WHERE
            ml.ISINSIDE_LOCATION = 1
            AND mr.ID_LOCATION = ml.ID_LOCATION
        ");
    
        foreach ($user_areas as $user_area) {
            $pcpRpoDapul = DB::select("
            SELECT
            (
            SELECT
                QUANTITY 
            FROM
                target_sale ts,
                md_product mp 
            WHERE
                mp.ID_PC = 12 
                AND ts.ID_PRODUCT = mp.ID_PRODUCT 
                AND ID_REGIONAL = urs.ID_REGIONAL 
                LIMIT 1 
            ) AS TARGET_UST,
            SUM( urs.REAL_UST ) AS REAL_UST,
            urs.VSTARGET_UST,
            (
            SELECT
                QUANTITY 
            FROM
                target_sale ts,
                md_product mp 
            WHERE
                mp.ID_PC = 2 
                AND ts.ID_PRODUCT = mp.ID_PRODUCT 
                AND ID_REGIONAL = urs.ID_REGIONAL 
                LIMIT 1 
            ) AS TARGET_NONUST,
            SUM( urs.REAL_NONUST ) AS REAL_NONUST,
            urs.VSTARGET_NONUST,
            (
            SELECT
                QUANTITY 
            FROM
                target_sale ts,
                md_product mp 
            WHERE
                mp.ID_PC = 3 
                AND ts.ID_PRODUCT = mp.ID_PRODUCT 
                AND ID_REGIONAL = urs.ID_REGIONAL 
                LIMIT 1 
            ) AS TARGET_SELERAKU,
            SUM( urs.REAL_SELERAKU ) AS REAL_SELERAKU,
            urs.VSTARGET_SELERAKU,
            urs.AVERAGE,
            urs.ID_USER_RANKSALE 
            FROM
                user_ranking_sale AS urs,
                md_regional AS mr 
            WHERE
                urs.ID_REGIONAL = ".$user_area->ID_REGIONAL." 
                AND urs.ID_REGIONAL = mr.ID_REGIONAL 
                AND MONTH (
                DATE( urs.created_at )) = ".$month." 
            GROUP BY
                urs.ID_REGIONAL
            ");
    
            $temp['NAME_USER']          = $user_area->NAME_USER;
            $temp['NAME_AREA']          = $user_area->NAME_REGIONAL;
            $temp['TARGET_UST']         = !empty($pcpRpoDapul[0]->TARGET_UST) ? $pcpRpoDapul[0]->TARGET_UST : "-";
            $temp['REAL_UST']           = !empty($pcpRpoDapul[0]->REAL_UST) ? $pcpRpoDapul[0]->REAL_UST : "-";
            $temp['VSTARGET_UST']       = $temp['REAL_UST'] != "-" && $temp['TARGET_UST'] != "-" ? ($temp['REAL_UST'] / $temp['TARGET_UST']) * 100 : "-";
            $temp['TARGET_NONUST']      = !empty($pcpRpoDapul[0]->TARGET_NONUST) ? $pcpRpoDapul[0]->TARGET_NONUST : "-";
            $temp['REAL_NONUST']        = !empty($pcpRpoDapul[0]->REAL_NONUST) ? $pcpRpoDapul[0]->REAL_NONUST : "-";
            $temp['VSTARGET_NONUST']    = $temp['REAL_NONUST'] != "-" && $temp['TARGET_NONUST'] != "-" ? ($temp['REAL_NONUST'] / $temp['TARGET_NONUST']) * 100 : "-";
            $temp['TARGET_SELERAKU']    = !empty($pcpRpoDapul[0]->TARGET_SELERAKU) ? $pcpRpoDapul[0]->TARGET_SELERAKU : "-";
            $temp['REAL_SELERAKU']      = !empty($pcpRpoDapul[0]->REAL_SELERAKU) ? $pcpRpoDapul[0]->REAL_SELERAKU : "-";
            $temp['VSTARGET_SELERAKU']  = $temp['REAL_SELERAKU'] != "-" && $temp['TARGET_SELERAKU'] != "-" ? ($temp['REAL_SELERAKU'] / $temp['TARGET_SELERAKU']) * 100 : "-";
    
            $weightProdCat = CategoryProduct::where('deleted_at', NULL)->get()->toArray();
            $avgCount = 0;
            if ($weightProdCat[0]["PERCENTAGE_PC"] != 0) $avgCount++;
            if ($weightProdCat[1]["PERCENTAGE_PC"] != 0) $avgCount++;
            if ($weightProdCat[2]["PERCENTAGE_PC"] != 0) $avgCount++;
    
            $temp['AVERAGE'] = (((float)$temp['VSTARGET_UST'] / 100) * ((float)$weightProdCat[0]["PERCENTAGE_PC"] / 100));
            $temp['AVERAGE'] += (((float)$temp['VSTARGET_NONUST'] / 100) * ((float)$weightProdCat[1]["PERCENTAGE_PC"] / 100));
            $temp['AVERAGE'] += (((float)$temp['VSTARGET_SELERAKU'] / 100) * ((float)$weightProdCat[2]["PERCENTAGE_PC"] / 100));
            $temp['AVERAGE'] = $temp['AVERAGE'] / $avgCount;
    
            $data[] = $temp;
        }
        usort($data, function($a, $b){
            return strnatcmp($b['AVERAGE'], $a['AVERAGE']);
        });
        foreach($data as $key => $value){
            $data[$key]['ID_USER_RANKSALE'] = $key+1;
        }
        return $data;
    }

    public function AktivitasRPOLapul()
    {
        $month = date('n');
        $data = array();
        $user_regionals = DB::select("
        SELECT
        (
            SELECT
                u.NAME_USER
            FROM
                `user` u
            WHERE
                u.ID_ROLE = 4 
                AND u.ID_REGIONAL = mr.ID_REGIONAL
                LIMIT 1
        ) AS NAME_USER,
        mr.NAME_REGIONAL,
        mr.ID_REGIONAL
        FROM
            md_regional mr,
            md_location ml
        WHERE
            ml.ISINSIDE_LOCATION = 0
            AND mr.ID_LOCATION = ml.ID_LOCATION           
        ");

        foreach ($user_regionals as $user_regional) {
            $activity_rankings = DB::select("
            SELECT 
            (
                SELECT
                    QUANTITY
                    FROM
                    target_activity ta
                    WHERE
                        ID_ACTIVITY = 1
                        AND ID_REGIONAL = usa.ID_REGIONAL
            ) AS TARGET_UB, 
            SUM(usa.REAL_UB) AS REAL_UB, 
            usa.VSTARGET_UB, 
            (
                SELECT
                    QUANTITY
                    FROM
                    target_activity ta
                    WHERE
                        ID_ACTIVITY = 2
                        AND ID_REGIONAL = usa.ID_REGIONAL
            ) AS TARGET_PDGSAYUR, 
            SUM(usa.REAL_PDGSAYUR) AS REAL_PDGSAYUR, 
            usa.VSTARGET_PDGSAYUR, 
            (
                SELECT
                    QUANTITY
                    FROM
                    target_activity ta
                    WHERE
                        ID_ACTIVITY = 3
                        AND ID_REGIONAL = usa.ID_REGIONAL
            ) AS TARGET_RETAIL, 
            SUM(usa.REAL_RETAIL) AS REAL_RETAIL, 
            usa.VSTARGET_RETAIL, 
            usa.AVERAGE, 
            usa.ID_USER_RANKACTIVITY
            FROM
                user_ranking_activity AS usa,
                md_regional AS mr
            WHERE
                usa.ID_REGIONAL	= " . $user_regional->ID_REGIONAL . "
                AND usa.ID_REGIONAL = mr.ID_REGIONAL
                AND MONTH(DATE(usa.created_at)) = " . $month . "
            GROUP BY
                usa.ID_REGIONAL
            ");

            $temp['NAME_USER']          = $user_regional->NAME_USER;
            $temp['NAME_AREA']          = $user_regional->NAME_REGIONAL;
            $temp['TARGET_UB']          = !empty($activity_rankings[0]->TARGET_UB) ? $activity_rankings[0]->TARGET_UB : "-";
            $temp['REAL_UB']            = !empty($activity_rankings[0]->REAL_UB) ? $activity_rankings[0]->REAL_UB : "-";
            $temp['VSTARGET_UB']        = $temp['REAL_UB'] != "-" && $temp['TARGET_UB'] != "-" ? ($temp['REAL_UB'] / $temp['TARGET_UB']) * 100 : "-";
            $temp['TARGET_PDGSAYUR']    = !empty($activity_rankings[0]->TARGET_PDGSAYUR) ? $activity_rankings[0]->TARGET_PDGSAYUR : "-";
            $temp['REAL_PDGSAYUR']      = !empty($activity_rankings[0]->REAL_PDGSAYUR) ? $activity_rankings[0]->REAL_PDGSAYUR : "-";
            $temp['VSTARGET_PDGSAYUR']  = $temp['REAL_PDGSAYUR'] != "-" && $temp['TARGET_PDGSAYUR'] != "-" ? ($temp['REAL_PDGSAYUR'] / $temp['TARGET_PDGSAYUR']) * 100 : "-";
            $temp['TARGET_RETAIL']      = !empty($activity_rankings[0]->TARGET_RETAIL) ? $activity_rankings[0]->TARGET_RETAIL : "-";
            $temp['REAL_RETAIL']        = !empty($activity_rankings[0]->REAL_RETAIL) ? $activity_rankings[0]->REAL_RETAIL : "-";
            $temp['VSTARGET_RETAIL']    = $temp['REAL_RETAIL'] != "-" && $temp['TARGET_RETAIL'] != "-" ? ($temp['REAL_RETAIL'] / $temp['TARGET_RETAIL']) * 100 : "-";

            $weightActCat = ActivityCategory::where('deleted_at', NULL)->get()->toArray();
            $avgCount = 0;
            if ($weightActCat[0]["PERCENTAGE_AC"] != 0) $avgCount++;
            if ($weightActCat[1]["PERCENTAGE_AC"] != 0) $avgCount++;
            if ($weightActCat[2]["PERCENTAGE_AC"] != 0) $avgCount++;

            $temp['AVERAGE'] = (((float)$temp['VSTARGET_UB'] / 100) * ((float)$weightActCat[0]["PERCENTAGE_AC"] / 100));
            $temp['AVERAGE'] += (((float)$temp['VSTARGET_PDGSAYUR'] / 100) * ((float)$weightActCat[1]["PERCENTAGE_AC"] / 100));
            $temp['AVERAGE'] += (((float)$temp['VSTARGET_RETAIL'] / 100) * ((float)$weightActCat[2]["PERCENTAGE_AC"] / 100));
            $temp['AVERAGE'] = $temp['AVERAGE'] / $avgCount;

            $data[] = $temp;
        }
        usort($data, function ($a, $b) {
            return strnatcmp($b['AVERAGE'], $a['AVERAGE']);
        });
        foreach ($data as $key => $value) {
            $data[$key]['ID_USER_RANKSALE'] = $key + 1;
        }
        return $data;
    }

    public function PencapaianRPOLapul()
    {
        $month = date('n');
        $data = array();
        $user_areas = DB::select("
        SELECT
        (
            SELECT
                u.NAME_USER
            FROM
                `user` u
            WHERE
                u.ID_ROLE = 4 
                AND u.ID_REGIONAL = mr.ID_REGIONAL
                LIMIT 1
        ) AS NAME_USER,
        mr.NAME_REGIONAL,
        mr.ID_REGIONAL
        FROM
            md_regional mr,
            md_location ml
        WHERE
            ml.ISINSIDE_LOCATION = 0
            AND mr.ID_LOCATION = ml.ID_LOCATION
        ");
    
        foreach ($user_areas as $user_area) {
            $pcpRpoLapul = DB::select("
            SELECT
            (
            SELECT
                QUANTITY 
            FROM
                target_sale ts,
                md_product mp 
            WHERE
                mp.ID_PC = 12 
                AND ts.ID_PRODUCT = mp.ID_PRODUCT 
                AND ID_REGIONAL = urs.ID_REGIONAL 
                LIMIT 1 
            ) AS TARGET_UST,
            SUM( urs.REAL_UST ) AS REAL_UST,
            urs.VSTARGET_UST,
            (
            SELECT
                QUANTITY 
            FROM
                target_sale ts,
                md_product mp 
            WHERE
                mp.ID_PC = 2 
                AND ts.ID_PRODUCT = mp.ID_PRODUCT 
                AND ID_REGIONAL = urs.ID_REGIONAL 
                LIMIT 1 
            ) AS TARGET_NONUST,
            SUM( urs.REAL_NONUST ) AS REAL_NONUST,
            urs.VSTARGET_NONUST,
            (
            SELECT
                QUANTITY 
            FROM
                target_sale ts,
                md_product mp 
            WHERE
                mp.ID_PC = 3 
                AND ts.ID_PRODUCT = mp.ID_PRODUCT 
                AND ID_REGIONAL = urs.ID_REGIONAL 
                LIMIT 1 
            ) AS TARGET_SELERAKU,
            SUM( urs.REAL_SELERAKU ) AS REAL_SELERAKU,
            urs.VSTARGET_SELERAKU,
            urs.AVERAGE,
            urs.ID_USER_RANKSALE 
            FROM
                user_ranking_sale AS urs,
                md_regional AS mr 
            WHERE
                urs.ID_REGIONAL = ".$user_area->ID_REGIONAL." 
                AND urs.ID_REGIONAL = mr.ID_REGIONAL 
                AND MONTH (
                DATE( urs.created_at )) = ".$month." 
            GROUP BY
                urs.ID_REGIONAL
            ");
    
            $temp['NAME_USER']          = $user_area->NAME_USER;
            $temp['NAME_AREA']          = $user_area->NAME_REGIONAL;
            $temp['TARGET_UST']         = !empty($pcpRpoLapul[0]->TARGET_UST) ? $pcpRpoLapul[0]->TARGET_UST : "-";
            $temp['REAL_UST']           = !empty($pcpRpoLapul[0]->REAL_UST) ? $pcpRpoLapul[0]->REAL_UST : "-";
            $temp['VSTARGET_UST']       = $temp['REAL_UST'] != "-" && $temp['TARGET_UST'] != "-" ? ($temp['REAL_UST'] / $temp['TARGET_UST']) * 100 : "-";
            $temp['TARGET_NONUST']      = !empty($pcpRpoLapul[0]->TARGET_NONUST) ? $pcpRpoLapul[0]->TARGET_NONUST : "-";
            $temp['REAL_NONUST']        = !empty($pcpRpoLapul[0]->REAL_NONUST) ? $pcpRpoLapul[0]->REAL_NONUST : "-";
            $temp['VSTARGET_NONUST']    = $temp['REAL_NONUST'] != "-" && $temp['TARGET_NONUST'] != "-" ? ($temp['REAL_NONUST'] / $temp['TARGET_NONUST']) * 100 : "-";
            $temp['TARGET_SELERAKU']    = !empty($pcpRpoLapul[0]->TARGET_SELERAKU) ? $pcpRpoLapul[0]->TARGET_SELERAKU : "-";
            $temp['REAL_SELERAKU']      = !empty($pcpRpoLapul[0]->REAL_SELERAKU) ? $pcpRpoLapul[0]->REAL_SELERAKU : "-";
            $temp['VSTARGET_SELERAKU']  = $temp['REAL_SELERAKU'] != "-" && $temp['TARGET_SELERAKU'] != "-" ? ($temp['REAL_SELERAKU'] / $temp['TARGET_SELERAKU']) * 100 : "-";
    
            $weightProdCat = CategoryProduct::where('deleted_at', NULL)->get()->toArray();
            $avgCount = 0;
            if ($weightProdCat[0]["PERCENTAGE_PC"] != 0) $avgCount++;
            if ($weightProdCat[1]["PERCENTAGE_PC"] != 0) $avgCount++;
            if ($weightProdCat[2]["PERCENTAGE_PC"] != 0) $avgCount++;
    
            $temp['AVERAGE'] = (((float)$temp['VSTARGET_UST'] / 100) * ((float)$weightProdCat[0]["PERCENTAGE_PC"] / 100));
            $temp['AVERAGE'] += (((float)$temp['VSTARGET_NONUST'] / 100) * ((float)$weightProdCat[1]["PERCENTAGE_PC"] / 100));
            $temp['AVERAGE'] += (((float)$temp['VSTARGET_SELERAKU'] / 100) * ((float)$weightProdCat[2]["PERCENTAGE_PC"] / 100));
            $temp['AVERAGE'] = $temp['AVERAGE'] / $avgCount;
    
            $data[] = $temp;
        }
        usort($data, function($a, $b){
            return strnatcmp($b['AVERAGE'], $a['AVERAGE']);
        });
        foreach($data as $key => $value){
            $data[$key]['ID_USER_RANKSALE'] = $key+1;
        }
        return $data;
    }

    public function AktivitasAsmen()
    {
        $month = date('n');
        $user_asmens = DB::select("
        SELECT
            ml.ID_LOCATION,
            ml.NAME_LOCATION,
            (
            SELECT
                        u.NAME_USER
                    FROM
                        `user` u
                    WHERE
                        u.ID_ROLE = 3 
                        AND u.ID_LOCATION = ml.ID_LOCATION
                        LIMIT 1
            ) AS NAME_USER
        FROM
            md_location ml
        ");

        foreach ($user_asmens as $user_asmen) {
            $transAsmen = DB::select("
            SELECT 
            (
                    SELECT
                            QUANTITY
                            FROM
                            target_activity ta
                            WHERE
                                    ID_ACTIVITY = 1
                                    AND ID_REGIONAL = usa.ID_REGIONAL
                                    AND usa.ID_LOCATION = ml.ID_LOCATION
            ) AS TARGET_UB, 
            SUM(usa.REAL_UB) AS REAL_UB, 
            usa.VSTARGET_UB, 
            (
                    SELECT
                            QUANTITY
                            FROM
                            target_activity ta
                            WHERE
                                    ID_ACTIVITY = 2
                                    AND ID_REGIONAL = usa.ID_REGIONAL
                                    AND usa.ID_LOCATION = ml.ID_LOCATION
            ) AS TARGET_PDGSAYUR, 
            SUM(usa.REAL_PDGSAYUR) AS REAL_PDGSAYUR, 
            usa.VSTARGET_PDGSAYUR, 
            (
                    SELECT
                            QUANTITY
                            FROM
                            target_activity ta
                            WHERE
                                    ID_ACTIVITY = 3
                                    AND ID_REGIONAL = usa.ID_REGIONAL
                                    AND usa.ID_LOCATION = ml.ID_LOCATION
            ) AS TARGET_RETAIL, 
            SUM(usa.REAL_RETAIL) AS REAL_RETAIL, 
            usa.VSTARGET_RETAIL, 
            usa.AVERAGE, 
            usa.ID_USER_RANKACTIVITY
            FROM
                    user_ranking_activity AS usa,
                    md_location AS ml
            WHERE
                    usa.ID_LOCATION = " . $user_asmen->ID_LOCATION . "
                    AND usa.ID_LOCATION = ml.ID_LOCATION
                    AND MONTH(DATE(usa.created_at)) = " . $month . "
            GROUP BY
                    usa.ID_LOCATION
            ");

            $temp['NAME_USER']          = $user_asmen->NAME_USER;
            $temp['NAME_AREA']          = $user_asmen->NAME_LOCATION;
            $temp['TARGET_UB']          = !empty($transAsmen[0]->TARGET_UB) ? $transAsmen[0]->TARGET_UB : "-";
            $temp['REAL_UB']            = !empty($transAsmen[0]->REAL_UB) ? $transAsmen[0]->REAL_UB : "-";
            $temp['VSTARGET_UB']        = $temp['REAL_UB'] != "-" && $temp['TARGET_UB'] != "-" ? ($temp['REAL_UB'] / $temp['TARGET_UB']) * 100 : "-";
            $temp['TARGET_PDGSAYUR']    = !empty($transAsmen[0]->TARGET_PDGSAYUR) ? $transAsmen[0]->TARGET_PDGSAYUR : "-";
            $temp['REAL_PDGSAYUR']      = !empty($transAsmen[0]->REAL_PDGSAYUR) ? $transAsmen[0]->REAL_PDGSAYUR : "-";
            $temp['VSTARGET_PDGSAYUR']  = $temp['REAL_PDGSAYUR'] != "-" && $temp['TARGET_PDGSAYUR'] != "-" ? ($temp['REAL_PDGSAYUR'] / $temp['TARGET_PDGSAYUR']) * 100 : "-";
            $temp['TARGET_RETAIL']      = !empty($transAsmen[0]->TARGET_RETAIL) ? $transAsmen[0]->TARGET_RETAIL : "-";
            $temp['REAL_RETAIL']        = !empty($transAsmen[0]->REAL_RETAIL) ? $transAsmen[0]->REAL_RETAIL : "-";
            $temp['VSTARGET_RETAIL']    = $temp['REAL_RETAIL'] != "-" && $temp['TARGET_RETAIL'] != "-" ? ($temp['REAL_RETAIL'] / $temp['TARGET_RETAIL']) * 100 : "-";

            $weightActCat = ActivityCategory::where('deleted_at', NULL)->get()->toArray();
            $avgCount = 0;
            if ($weightActCat[0]["PERCENTAGE_AC"] != 0) $avgCount++;
            if ($weightActCat[1]["PERCENTAGE_AC"] != 0) $avgCount++;
            if ($weightActCat[2]["PERCENTAGE_AC"] != 0) $avgCount++;

            $temp['AVERAGE'] = (((float)$temp['VSTARGET_UB'] / 100) * ((float)$weightActCat[0]["PERCENTAGE_AC"] / 100));
            $temp['AVERAGE'] += (((float)$temp['VSTARGET_PDGSAYUR'] / 100) * ((float)$weightActCat[1]["PERCENTAGE_AC"] / 100));
            $temp['AVERAGE'] += (((float)$temp['VSTARGET_RETAIL'] / 100) * ((float)$weightActCat[2]["PERCENTAGE_AC"] / 100));
            $temp['AVERAGE'] = $temp['AVERAGE'] / $avgCount;

            $data[] = $temp;
        }
        usort($data, function ($a, $b) {
            return strnatcmp($b['AVERAGE'], $a['AVERAGE']);
        });
        foreach ($data as $key => $value) {
            $data[$key]['ID_USER_RANKSALE'] = $key + 1;
        }
        return $data;
    }

    public function PencapaianAsmen()
    {
        $month = date('n');
        $user_asmens = DB::select("
        SELECT
            ml.ID_LOCATION,
            ml.NAME_LOCATION,
            (
            SELECT
                        u.NAME_USER
                    FROM
                        `user` u
                    WHERE
                        u.ID_ROLE = 3 
                        AND u.ID_LOCATION = ml.ID_LOCATION
                        LIMIT 1
            ) AS NAME_USER
        FROM
            md_location ml
        ");

        foreach ($user_asmens as $user_asmen) {
            $pcpAsmen = DB::select("
            SELECT 
            (
                    SELECT
                            QUANTITY
                            FROM
                            target_sale ts,
                            md_product mp
                            WHERE
                                    mp.ID_PC = 12
                                    AND ts.ID_PRODUCT = mp.ID_PRODUCT
                                    AND ID_REGIONAL = urs.ID_REGIONAL
                                    AND urs.ID_LOCATION = ml.ID_LOCATION
                                    LIMIT 1
            ) AS TARGET_UST, 
            SUM(urs.REAL_UST) AS REAL_UST, 
            urs.VSTARGET_UST, 
            (
                    SELECT
                            QUANTITY
                            FROM
                            target_sale ts,
                            md_product mp
                            WHERE
                                    mp.ID_PC = 2
                                    AND ts.ID_PRODUCT = mp.ID_PRODUCT
                                    AND ID_REGIONAL = urs.ID_REGIONAL
                                    AND urs.ID_LOCATION = ml.ID_LOCATION
                                    LIMIT 1
            ) AS TARGET_NONUST, 
            SUM(urs.REAL_NONUST) AS REAL_NONUST, 
            urs.VSTARGET_NONUST, 
            (
                    SELECT
                            QUANTITY
                            FROM
                            target_sale ts,
                            md_product mp
                            WHERE
                                    mp.ID_PC = 3
                                    AND ts.ID_PRODUCT = mp.ID_PRODUCT
                                    AND ID_REGIONAL = urs.ID_REGIONAL
                                    AND urs.ID_LOCATION = ml.ID_LOCATION
                                    LIMIT 1
            ) AS TARGET_SELERAKU, 
            SUM(urs.REAL_SELERAKU) AS REAL_SELERAKU, 
            urs.VSTARGET_SELERAKU, 
            urs.AVERAGE, 
            urs.ID_USER_RANKSALE
            FROM
                    user_ranking_sale AS urs,
                    md_location AS ml
            WHERE
                    urs.ID_LOCATION = " . $user_asmen->ID_LOCATION . "
                    AND urs.ID_LOCATION = ml.ID_LOCATION
                    AND MONTH(DATE(urs.created_at)) = " . $month . "
            GROUP BY
                    urs.ID_LOCATION
            ");

            $temp['NAME_USER']          = $user_asmen->NAME_USER;
            $temp['NAME_AREA']          = $user_asmen->NAME_LOCATION;
            $temp['TARGET_UST']         = !empty($pcpAsmen[0]->TARGET_UST) ? $pcpAsmen[0]->TARGET_UST : "-";
            $temp['REAL_UST']           = !empty($pcpAsmen[0]->REAL_UST) ? $pcpAsmen[0]->REAL_UST : "-";
            $temp['VSTARGET_UST']       = $temp['REAL_UST'] != "-" && $temp['TARGET_UST'] != "-" ? ($temp['REAL_UST'] / $temp['TARGET_UST']) * 100 : "-";
            $temp['TARGET_NONUST']      = !empty($pcpAsmen[0]->TARGET_NONUST) ? $pcpAsmen[0]->TARGET_NONUST : "-";
            $temp['REAL_NONUST']        = !empty($pcpAsmen[0]->REAL_NONUST) ? $pcpAsmen[0]->REAL_NONUST : "-";
            $temp['VSTARGET_NONUST']    = $temp['REAL_NONUST'] != "-" && $temp['TARGET_NONUST'] != "-" ? ($temp['REAL_NONUST'] / $temp['TARGET_NONUST']) * 100 : "-";
            $temp['TARGET_SELERAKU']    = !empty($pcpAsmen[0]->TARGET_SELERAKU) ? $pcpAsmen[0]->TARGET_SELERAKU : "-";
            $temp['REAL_SELERAKU']      = !empty($pcpAsmen[0]->REAL_SELERAKU) ? $pcpAsmen[0]->REAL_SELERAKU : "-";
            $temp['VSTARGET_SELERAKU']  = $temp['REAL_SELERAKU'] != "-" && $temp['TARGET_SELERAKU'] != "-" ? ($temp['REAL_SELERAKU'] / $temp['TARGET_SELERAKU']) * 100 : "-";

            $weightProdCat = CategoryProduct::where('deleted_at', NULL)->get()->toArray();
            $avgCount = 0;
            if ($weightProdCat[0]["PERCENTAGE_PC"] != 0) $avgCount++;
            if ($weightProdCat[1]["PERCENTAGE_PC"] != 0) $avgCount++;
            if ($weightProdCat[2]["PERCENTAGE_PC"] != 0) $avgCount++;

            $temp['AVERAGE'] = (((float)$temp['VSTARGET_UST'] / 100) * ((float)$weightProdCat[0]["PERCENTAGE_PC"] / 100));
            $temp['AVERAGE'] += (((float)$temp['VSTARGET_NONUST'] / 100) * ((float)$weightProdCat[1]["PERCENTAGE_PC"] / 100));
            $temp['AVERAGE'] += (((float)$temp['VSTARGET_SELERAKU'] / 100) * ((float)$weightProdCat[2]["PERCENTAGE_PC"] / 100));
            $temp['AVERAGE'] = $temp['AVERAGE'] / $avgCount;

            $data[] = $temp;
        }
        usort($data, function ($a, $b) {
            return strnatcmp($b['AVERAGE'], $a['AVERAGE']);
        });
        foreach ($data as $key => $value) {
            $data[$key]['ID_USER_RANKSALE'] = $key + 1;
        }
        return $data;
    }
}
