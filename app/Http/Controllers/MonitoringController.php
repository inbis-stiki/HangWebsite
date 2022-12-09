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

    public function monitoring_data(Request $req)
    {
        $type = $req->input('type');
        $tgl_trans = $req->input('tgl_trans');

        $data_regional = DB::select('
            SELECT
                mr.NAME_REGIONAL
            FROM
                md_regional mr
        ');

        $All_Data = array();

        if ($type == 1) {
            $All_Data = array();
            
            $TRANS_1 = DB::select('
                SELECT
                    t.REGIONAL_TRANS,
                    COUNT(t.ID_TRANS) AS TOT_TRANS
                FROM
                    `transaction` t
                WHERE
                    t.DATE_TRANS LIKE "2022-12-07%"
                GROUP BY
                    t.REGIONAL_TRANS
                HAVING
                    TOT_TRANS < 10
                ORDER BY
                    t.DATE_TRANS DESC
            ');

            $TRANS_2 = DB::select('
                SELECT
                    t.REGIONAL_TRANS,
                    COUNT(t.ID_TRANS) AS TOT_TRANS
                FROM
                    `transaction` t
                WHERE
                    t.DATE_TRANS LIKE "2022-12-07%"
                GROUP BY
                    t.REGIONAL_TRANS
                HAVING
                    TOT_TRANS >= 11
                    AND 
                    TOT_TRANS <= 20
                ORDER BY
                    t.DATE_TRANS DESC
            ');
            
            $TRANS_3 = DB::select('
                SELECT
                    t.REGIONAL_TRANS,
                    COUNT(t.ID_TRANS) AS TOT_TRANS
                FROM
                    `transaction` t
                WHERE
                    t.DATE_TRANS LIKE "2022-12-07%"
                GROUP BY
                    t.REGIONAL_TRANS
                HAVING
                    TOT_TRANS >= 11
                    AND 
                    TOT_TRANS <= 30
                ORDER BY
                    t.DATE_TRANS DESC
            ');

            $TRANS_4 = DB::select('
                SELECT
                    t.REGIONAL_TRANS,
                    COUNT(t.ID_TRANS) AS TOT_TRANS
                FROM
                    `transaction` t
                WHERE
                    t.DATE_TRANS LIKE "2022-12-07%"
                GROUP BY
                    t.REGIONAL_TRANS
                HAVING
                    TOT_TRANS > 30
                ORDER BY
                    t.DATE_TRANS DESC
            ');

            $no = 0;
            for ($i = 0; $i < count($data_regional); $i++) {
                if ($data_regional[$i]->NAME_REGIONAL != "") {
                    $data = array(
                        "NO" => ++$no,
                        "NAME_REGIONAL" => $data_regional[$i]->NAME_REGIONAL,
                        "AKTIVITAS" => "TRANSAKSI",
                        "TRANS_1" => ((!empty($TRANS_1[$i])) ? $TRANS_1[$i]->TOT_TRANS : 0),
                        "TRANS_2" => ((!empty($TRANS_2[$i])) ? $TRANS_2[$i]->TOT_TRANS : 0),
                        "TRANS_3" => ((!empty($TRANS_3[$i])) ? $TRANS_3[$i]->TOT_TRANS : 0),
                        "TRANS_4" => ((!empty($TRANS_4[$i])) ? $TRANS_4[$i]->TOT_TRANS : 0)
                    );
                    array_push($All_Data, $data);
                }
            }
        } else {
            $All_Data = array();

            $PRESENCE_1 = DB::select('
                SELECT
                    mr.NAME_REGIONAL,
                    COUNT(p.ID_PRESENCE) TOT_PRESENCE
                FROM
                    presence p
                LEFT JOIN md_district md ON
                    p.ID_DISTRICT = md.ID_DISTRICT
                LEFT JOIN md_area ma ON 
                    md.ID_AREA = ma.ID_AREA 
                LEFT JOIN md_regional mr ON 
                    ma.ID_REGIONAL = mr.ID_REGIONAL 
                WHERE
                    p.DATE_PRESENCE LIKE "' . $tgl_trans . '%"
                    AND HOUR(p.DATE_PRESENCE) < 7
                GROUP BY 
                    mr.ID_REGIONAL
                ORDER BY
                    p.DATE_PRESENCE DESC
            ');

            $PRESENCE_2 = DB::select('
                SELECT
                    mr.NAME_REGIONAL,
                    COUNT(p.ID_PRESENCE) TOT_PRESENCE
                FROM
                    presence p
                LEFT JOIN md_district md ON
                    p.ID_DISTRICT = md.ID_DISTRICT
                LEFT JOIN md_area ma ON 
                    md.ID_AREA = ma.ID_AREA 
                LEFT JOIN md_regional mr ON 
                    ma.ID_REGIONAL = mr.ID_REGIONAL 
                WHERE
                    p.DATE_PRESENCE LIKE "' . $tgl_trans . '%"
                    AND HOUR(p.DATE_PRESENCE) >= 7
                    AND HOUR(p.DATE_PRESENCE) <=8
                GROUP BY 
                    mr.ID_REGIONAL
                ORDER BY
                    p.DATE_PRESENCE DESC
            ');

            $PRESENCE_3 = DB::select('
                SELECT
                    mr.NAME_REGIONAL,
                    COUNT(p.ID_PRESENCE) TOT_PRESENCE
                FROM
                    presence p
                LEFT JOIN md_district md ON
                    p.ID_DISTRICT = md.ID_DISTRICT
                LEFT JOIN md_area ma ON 
                    md.ID_AREA = ma.ID_AREA 
                LEFT JOIN md_regional mr ON 
                    ma.ID_REGIONAL = mr.ID_REGIONAL 
                WHERE
                    p.DATE_PRESENCE LIKE "' . $tgl_trans . '%"
                    AND HOUR(p.DATE_PRESENCE) > 8
                GROUP BY 
                    mr.ID_REGIONAL
                ORDER BY
                    p.DATE_PRESENCE DESC
            ');

            $no = 0;
            for ($i = 0; $i < count($data_regional); $i++) {
                $data = array(
                    "NO" => ++$no,
                    "NAME_REGIONAL" => $data_regional[$i]->NAME_REGIONAL,
                    "AKTIVITAS" => "PRESENCE",
                    "PRESENCE_1" => (!empty($PRESENCE_1[$i]) ? $PRESENCE_1[$i]->TOT_PRESENCE : 0),
                    "PRESENCE_2" => (!empty($PRESENCE_2[$i]) ? $PRESENCE_2[$i]->TOT_PRESENCE : 0),
                    "PRESENCE_3" => (!empty($PRESENCE_3[$i]) ? $PRESENCE_3[$i]->TOT_PRESENCE : 0)
                );
                array_push($All_Data, $data);
            }
        }


        return response([
            'status_code'       => 200,
            'status_message'    => 'Data berhasil diambil!',
            'data'              => $All_Data
        ], 200);
    }    
}
