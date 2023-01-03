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
                mr.NAME_REGIONAL,
                COUNT(u.ID_USER) AS JML
            FROM 
                md_regional mr 
            LEFT JOIN `user` u ON
                u.ID_REGIONAL = mr.ID_REGIONAL 
                AND
                (u.ID_ROLE = 5 OR u.ID_ROLE = 6)
            WHERE 
                u.deleted_at IS NULL
            GROUP BY 
                u.ID_REGIONAL
            ORDER BY 
                mr.NAME_REGIONAL ASC
        ');

        $All_Data = array();

        if ($type == 1) {
            $All_Data = array();

            $TRANS = DB::select('
                SELECT
                    mr2.NAME_REGIONAL,
                    IFNULL(SUM(tb_temp.DATA_TEMP_1), 0) AS DATA_1,
                    IFNULL(SUM(tb_temp.DATA_TEMP_2), 0) AS DATA_2,
                    IFNULL(SUM(tb_temp.DATA_TEMP_3), 0) AS DATA_3,
                    IFNULL(SUM(tb_temp.DATA_TEMP_4), 0) AS DATA_4,
                    IFNULL(SUM(tb_temp.DATA_TEMP_5), 0) AS DATA_5
                FROM
                    md_regional mr2
                LEFT JOIN 
                                    (
                    SELECT
                        t.ID_USER,
                        t.REGIONAL_TRANS,
                        COUNT(t.ID_TRANS) <= 10 AS DATA_TEMP_1,
                        (COUNT(t.ID_TRANS) BETWEEN 11 AND 15) AS DATA_TEMP_2,
                        (COUNT(t.ID_TRANS) BETWEEN 16 AND 20) AS DATA_TEMP_3,
                        (COUNT(t.ID_TRANS) BETWEEN 21 AND 25) AS DATA_TEMP_4,
                        COUNT(t.ID_TRANS) >= 26 AS DATA_TEMP_5
                    FROM
                        `transaction` t
                    WHERE
                        DATE(t.DATE_TRANS) = "' . $tgl_trans . '" 
                        AND 
                        t.ISTRANS_TRANS = 1
                        AND 
                        t.ID_TYPE = 1
                    GROUP BY
                        t.ID_USER
                                    ) tb_temp ON
                    tb_temp.REGIONAL_TRANS = mr2.NAME_REGIONAL
                GROUP BY
                    tb_temp.REGIONAL_TRANS
                ORDER BY
                    mr2.NAME_REGIONAL ASC
            ');

            $NO_TRANS = DB::select('
                SELECT
                    mr.NAME_REGIONAL,
                    COUNT(u.ID_USER) AS DATA_NO_TRANS,
                    p.DATE_PRESENCE
                FROM
                    `user` u
                LEFT JOIN presence p ON
                    p.ID_USER = u.ID_USER
                LEFT JOIN md_regional mr ON 
                    mr.ID_REGIONAL = u.ID_REGIONAL
                WHERE
                    u.ID_ROLE IN (5, 6)
                    AND 
                    DATE(p.DATE_PRESENCE) = "' . $tgl_trans . '" 
                GROUP BY 
                    u.ID_REGIONAL
                ORDER BY 
                    mr.NAME_REGIONAL ASC
            ');

            $no = 0;
            for ($i = 0; $i < count($data_regional); $i++) {
                if ($data_regional[$i]->NAME_REGIONAL != "") {
                    $trans_1 = (!empty($TRANS[$i]) ? $TRANS[$i]->DATA_1 : 0);
                    $trans_2 = (!empty($TRANS[$i]) ? $TRANS[$i]->DATA_2 : 0);
                    $trans_3 = (!empty($TRANS[$i]) ? $TRANS[$i]->DATA_3 : 0);
                    $trans_4 = (!empty($TRANS[$i]) ? $TRANS[$i]->DATA_4 : 0);
                    $trans_5 = (!empty($TRANS[$i]) ? $TRANS[$i]->DATA_5 : 0);
                    $percentage_trans_6 = ($data_regional[$i]->JML - ($trans_1 + $trans_2 + $trans_3 + $trans_4));
                    $trans_6 = (!empty($NO_TRANS[$i]) ? $NO_TRANS[$i]->DATA_NO_TRANS : 0);
                    $data = array(
                        "NO" => ++$no,
                        "NAME_REGIONAL" => $data_regional[$i]->NAME_REGIONAL,
                        "TRANS_1" => $trans_1 . " (" . round(($trans_1 / $data_regional[$i]->JML) * 100) . "%)",
                        "TRANS_2" => $trans_2 . " (" . round(($trans_2 / $data_regional[$i]->JML) * 100) . "%)",
                        "TRANS_3" => $trans_3 . " (" . round(($trans_3 / $data_regional[$i]->JML) * 100) . "%)",
                        "TRANS_4" => $trans_4 . " (" . round(($trans_4 / $data_regional[$i]->JML) * 100) . "%)",
                        "TRANS_5" => $trans_5 . " (" . round(($trans_5 / $data_regional[$i]->JML) * 100) . "%)",
                        "NO_TRANS" => $trans_6 . " (" . round(($percentage_trans_6 / $data_regional[$i]->JML) * 100) . "%)"
                    );
                    array_push($All_Data, $data);
                }
            }
        } else {
            $All_Data = array();

            $PRESENCE = DB::select('
                SELECT
                    mr2.NAME_REGIONAL,
                    IFNULL(tb_temp.DATA_TEMP_1, 0) AS DATA_1,
                    IFNULL(tb_temp.DATA_TEMP_2, 0) AS DATA_2,
                    IFNULL(tb_temp.DATA_TEMP_3, 0) AS DATA_3,
                    IFNULL(tb_temp.DATA_TEMP_4, 0) AS DATA_4
                FROM 
                md_regional mr2
                LEFT JOIN 
                    (
                        SELECT
                            u.ID_REGIONAL,
                            SUM((p.DATE_PRESENCE BETWEEN "' . $tgl_trans . ' 01:00:00" AND "' . $tgl_trans . ' 07:01:00")) AS DATA_TEMP_1,
                            SUM((p.DATE_PRESENCE BETWEEN "' . $tgl_trans . ' 07.01:00" AND "' . $tgl_trans . ' 07:15:00")) AS DATA_TEMP_2,
                            SUM((p.DATE_PRESENCE BETWEEN "' . $tgl_trans . ' 07:16:00" AND "' . $tgl_trans . ' 07:30:00")) AS DATA_TEMP_3,
                            SUM((p.DATE_PRESENCE BETWEEN "' . $tgl_trans . ' 07:31:00" AND "' . $tgl_trans . ' 23:00:00")) AS DATA_TEMP_4
                        FROM
                            `user` u
                        LEFT JOIN presence p ON 
                            p.ID_USER = u.ID_USER
                        WHERE 
                            u.ID_ROLE IN (5, 6)
                            AND 
                            u.deleted_at IS NULL
                            AND 
                            p.DATE_PRESENCE LIKE "' . $tgl_trans . '%"
                        GROUP BY 
                            u.ID_REGIONAL
                    ) tb_temp ON 
                    tb_temp.ID_REGIONAL = mr2.ID_REGIONAL
                ORDER BY 
                    mr2.NAME_REGIONAL ASC
            ');

            $no = 0;
            for ($i = 0; $i < count($data_regional); $i++) {
                $pres_1 = (!empty($PRESENCE[$i]) ? $PRESENCE[$i]->DATA_1 : 0);
                $pres_2 = (!empty($PRESENCE[$i]) ? $PRESENCE[$i]->DATA_2 : 0);
                $pres_3 = (!empty($PRESENCE[$i]) ? $PRESENCE[$i]->DATA_3 : 0);
                $pres_4 = (!empty($PRESENCE[$i]) ? $PRESENCE[$i]->DATA_4 : 0);
                $pres_5 = ($data_regional[$i]->JML - ($pres_1 + $pres_2 + $pres_3 + $pres_4));
                $data = array(
                    "NO" => ++$no,
                    "NAME_REGIONAL" => $data_regional[$i]->NAME_REGIONAL,
                    "PRESENCE_1" => $pres_1 . " (" . round(($pres_1 / $data_regional[$i]->JML) * 100) . "%)",
                    "PRESENCE_2" => $pres_2 . " (" . round(($pres_2 / $data_regional[$i]->JML) * 100) . "%)",
                    "PRESENCE_3" => $pres_3 . " (" . round(($pres_3 / $data_regional[$i]->JML) * 100) . "%)",
                    "PRESENCE_4" => $pres_4 . " (" . round(($pres_4 / $data_regional[$i]->JML) * 100) . "%)",
                    "PRESENCE_5" => $pres_5 . " (" . round(($pres_5 / $data_regional[$i]->JML) * 100) . "%)"
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
