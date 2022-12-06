<?php

namespace App\Http\Controllers;

use App\Product;
use App\CategoryProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class MonitoringController extends Controller
{
    public function index(Request $req)
    {
        $data['title']          = "Monitoring";
        $data['sidebar']        = "monitoring";
        $data['data_regional']  = DB::table('md_regional')->get();

        return view('master.monitoring.monitoring', $data);
    }

    public function monitoring_data()
    {
        $date = $_POST['date'];
        $regional = $_POST['area'];

        if ($regional != 0) {
            $temp_regional = array();
            
            $data_regional = DB::select('
                SELECT
                    md.ID_DISTRICT
                FROM
                    md_regional mr
                LEFT JOIN md_area ma ON
                    ma.ID_REGIONAL = mr.ID_REGIONAL
                LEFT JOIN md_district md ON 	
                    md.ID_AREA = ma.ID_AREA
                WHERE
                    mr.ID_REGIONAL = ' . $regional . '
            ');

            for ($i = 0; $i < count($data_regional); $i++) {
                if (!empty($data_regional[$i]->ID_DISTRICT)) {
                    $temp_regional[$i] = $data_regional[$i]->ID_DISTRICT;
                }
            }

            $data['PRESENCE'] = DB::select('
                SELECT(
                    SELECT
                        COUNT(p.ID_PRESENCE)
                    FROM
                        `user` u
                    JOIN presence p ON
                        p.ID_USER = u.ID_USER
                    WHERE
                        p.DATE_PRESENCE LIKE "' . $date . '%"
                        AND HOUR(p.DATE_PRESENCE) < 7
                        AND p.ID_DISTRICT IN (' . implode(',', $temp_regional) . ')
                    ORDER BY
                        p.DATE_PRESENCE DESC
                ) AS PRESENCE_1,    
                (
                    SELECT
                        COUNT(p.ID_PRESENCE)
                    FROM
                        `user` u
                    JOIN presence p ON
                        p.ID_USER = u.ID_USER
                    WHERE
                        p.DATE_PRESENCE LIKE "' . $date . '%"
                        AND HOUR(p.DATE_PRESENCE) >= 7 
                        AND HOUR(p.DATE_PRESENCE) <= 8
                        AND p.ID_DISTRICT IN (' . implode(',', $temp_regional) . ')
                    ORDER BY
                        p.DATE_PRESENCE DESC
                ) AS PRESENCE_2,    
                (
                    SELECT
                        COUNT(p.ID_PRESENCE)
                    FROM
                        `user` u
                    JOIN presence p ON
                        p.ID_USER = u.ID_USER
                    WHERE
                        p.DATE_PRESENCE LIKE "' . $date . '%"
                        AND HOUR(p.DATE_PRESENCE) > 8
                        AND p.ID_DISTRICT IN (' . implode(',', $temp_regional) . ')
                    ORDER BY
                        p.DATE_PRESENCE DESC
                ) AS PRESENCE_3
            ');
        } else {
            $data['PRESENCE'] = DB::select('
                SELECT(
                    SELECT
                        COUNT(p.ID_PRESENCE)
                    FROM
                        `user` u
                    JOIN presence p ON
                        p.ID_USER = u.ID_USER
                    WHERE
                        p.DATE_PRESENCE LIKE "' . $date . '%"
                        AND HOUR(p.DATE_PRESENCE) < 7
                    ORDER BY
                        p.DATE_PRESENCE DESC
                ) AS PRESENCE_1,    
                (
                    SELECT
                        COUNT(p.ID_PRESENCE)
                    FROM
                        `user` u
                    JOIN presence p ON
                        p.ID_USER = u.ID_USER
                    WHERE
                        p.DATE_PRESENCE LIKE "' . $date . '%"
                        AND HOUR(p.DATE_PRESENCE) >= 7 
                        AND HOUR(p.DATE_PRESENCE) <= 8
                    ORDER BY
                        p.DATE_PRESENCE DESC
                ) AS PRESENCE_2,    
                (
                    SELECT
                        COUNT(p.ID_PRESENCE)
                    FROM
                        `user` u
                    JOIN presence p ON
                        p.ID_USER = u.ID_USER
                    WHERE
                        p.DATE_PRESENCE LIKE "' . $date . '%"
                        AND HOUR(p.DATE_PRESENCE) > 8
                    ORDER BY
                        p.DATE_PRESENCE DESC
                ) AS PRESENCE_3
            ');
        }

        if ($regional != 0) {
            $data_regional_trans = DB::select('
                SELECT
                    mr.NAME_REGIONAL
                FROM
                    md_regional mr
                WHERE
                    mr.ID_REGIONAL = ' . $regional . '
            ');

            $temp_regional_trans = $data_regional_trans[0]->NAME_REGIONAL;

            $data['TRANS']['TRANS_1'] = DB::select('
                SELECT 
                    SUM(NEW_DATA.TOT_TRANS) AS TOT_TRANS
                FROM (
                    SELECT
                        COUNT(t.ID_TRANS) AS TOT_TRANS
                    FROM
                        `user` u
                    LEFT JOIN `transaction` t ON
                        t.ID_USER = u.ID_USER
                    WHERE
                        t.DATE_TRANS LIKE "'.$date.'%"
                        AND t.REGIONAL_TRANS = "' . $temp_regional_trans . '"
                    GROUP BY
                        DATE_FORMAT(t.DATE_TRANS, "%Y-%m-%d") 
                    HAVING
                        TOT_TRANS < 10
                    ORDER BY
                        t.DATE_TRANS DESC
                ) AS NEW_DATA
            ');
            $data['TRANS']['TRANS_2'] = DB::select('
                SELECT 
                    SUM(NEW_DATA.TOT_TRANS) AS TOT_TRANS
                FROM (
                    SELECT
                        COUNT(t.ID_TRANS) AS TOT_TRANS
                    FROM
                        `user` u
                    LEFT JOIN `transaction` t ON
                        t.ID_USER = u.ID_USER
                    WHERE
                        t.DATE_TRANS LIKE "'.$date.'%"
                        AND t.REGIONAL_TRANS = "' . $temp_regional_trans . '"
                    GROUP BY
                        DATE_FORMAT(t.DATE_TRANS, "%Y-%m-%d") 
                    HAVING
                        TOT_TRANS >= 11
                        AND 
                        TOT_TRANS <= 20
                    ORDER BY
                        t.DATE_TRANS DESC
                ) AS NEW_DATA
            ');
            $data['TRANS']['TRANS_3'] = DB::select('
                SELECT 
                    SUM(NEW_DATA.TOT_TRANS) AS TOT_TRANS
                FROM (
                    SELECT
                        COUNT(t.ID_TRANS) AS TOT_TRANS
                    FROM
                        `user` u
                    LEFT JOIN `transaction` t ON
                        t.ID_USER = u.ID_USER
                    WHERE
                        t.DATE_TRANS LIKE "'.$date.'%"
                    GROUP BY
                        DATE_FORMAT(t.DATE_TRANS, "%Y-%m-%d")
                        AND t.REGIONAL_TRANS = "' . $temp_regional_trans . '" 
                    HAVING
                        TOT_TRANS >= 21
                        AND 
                        TOT_TRANS <= 30
                    ORDER BY
                        t.DATE_TRANS DESC
                ) AS NEW_DATA
            ');
            $data['TRANS']['TRANS_4'] = DB::select('
                SELECT 
                    SUM(NEW_DATA.TOT_TRANS) AS TOT_TRANS
                FROM (
                    SELECT
                        COUNT(t.ID_TRANS) AS TOT_TRANS
                    FROM
                        `user` u
                    LEFT JOIN `transaction` t ON
                        t.ID_USER = u.ID_USER
                    WHERE
                        t.DATE_TRANS LIKE "'.$date.'%"
                        AND t.REGIONAL_TRANS = "' . $temp_regional_trans . '"
                    GROUP BY
                        DATE_FORMAT(t.DATE_TRANS, "%Y-%m-%d") 
                    HAVING
                        TOT_TRANS > 30
                    ORDER BY
                        t.DATE_TRANS DESC
                ) AS NEW_DATA
            ');
        } else {
            $data['TRANS']['TRANS_1'] = DB::select('
                SELECT 
                    SUM(NEW_DATA.TOT_TRANS) AS TOT_TRANS
                FROM (
                    SELECT
                        COUNT(t.ID_TRANS) AS TOT_TRANS
                    FROM
                        `user` u
                    LEFT JOIN `transaction` t ON
                        t.ID_USER = u.ID_USER
                    WHERE
                        t.DATE_TRANS LIKE "'.$date.'%"
                    GROUP BY
                        DATE_FORMAT(t.DATE_TRANS, "%Y-%m-%d") 
                    HAVING
                        TOT_TRANS < 10
                    ORDER BY
                        t.DATE_TRANS DESC
                ) AS NEW_DATA
            ');
            $data['TRANS']['TRANS_2'] = DB::select('
                SELECT 
                    SUM(NEW_DATA.TOT_TRANS) AS TOT_TRANS
                FROM (
                    SELECT
                        COUNT(t.ID_TRANS) AS TOT_TRANS
                    FROM
                        `user` u
                    LEFT JOIN `transaction` t ON
                        t.ID_USER = u.ID_USER
                    WHERE
                        t.DATE_TRANS LIKE "'.$date.'%"
                    GROUP BY
                        DATE_FORMAT(t.DATE_TRANS, "%Y-%m-%d") 
                    HAVING
                        TOT_TRANS >= 11
                        AND 
                        TOT_TRANS <= 20
                    ORDER BY
                        t.DATE_TRANS DESC
                ) AS NEW_DATA
            ');
            $data['TRANS']['TRANS_3'] = DB::select('
                SELECT 
                    SUM(NEW_DATA.TOT_TRANS) AS TOT_TRANS
                FROM (
                    SELECT
                        COUNT(t.ID_TRANS) AS TOT_TRANS
                    FROM
                        `user` u
                    LEFT JOIN `transaction` t ON
                        t.ID_USER = u.ID_USER
                    WHERE
                        t.DATE_TRANS LIKE "'.$date.'%"
                    GROUP BY
                        DATE_FORMAT(t.DATE_TRANS, "%Y-%m-%d") 
                    HAVING
                        TOT_TRANS >= 21
                        AND 
                        TOT_TRANS <= 30
                    ORDER BY
                        t.DATE_TRANS DESC
                ) AS NEW_DATA
            ');
            $data['TRANS']['TRANS_4'] = DB::select('
                SELECT 
                    SUM(NEW_DATA.TOT_TRANS) AS TOT_TRANS
                FROM (
                    SELECT
                        COUNT(t.ID_TRANS) AS TOT_TRANS
                    FROM
                        `user` u
                    LEFT JOIN `transaction` t ON
                        t.ID_USER = u.ID_USER
                    WHERE
                        t.DATE_TRANS LIKE "'.$date.'%"
                    GROUP BY
                        DATE_FORMAT(t.DATE_TRANS, "%Y-%m-%d") 
                    HAVING
                        TOT_TRANS > 30
                    ORDER BY
                        t.DATE_TRANS DESC
                ) AS NEW_DATA
            ');
        }


        return response([
            'status_code'       => 200,
            'status_message'    => 'Data berhasil diambil!',
            'data'              => $data
        ], 200);
    }

}
