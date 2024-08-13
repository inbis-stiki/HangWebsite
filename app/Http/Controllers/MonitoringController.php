<?php

namespace App\Http\Controllers;

use App\Product;
use App\CategoryProduct;
use App\Regional;
use App\ReportPresence;
use Carbon\Carbon;
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
        $tgl_trans = date('Y-m-d');

        $data_regional = DB::select('
            SELECT
                mr.NAME_REGIONAL,
                COUNT(u.ID_USER) AS JML
            FROM 
                md_regional mr 
            LEFT JOIN `user` u ON
                u.ID_REGIONAL = mr.ID_REGIONAL 
                AND
                u.ID_ROLE IN (5, 6)
            WHERE 
                u.deleted_at IS NULL
                AND
                mr.deleted_at IS NULL
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
                            tdt.ID_USER,
                            tdt.REGIONAL_TRANS,
                            COUNT(tdt.ID_TRANS) <= 10 AS DATA_TEMP_1,
                            (COUNT(tdt.ID_TRANS) BETWEEN 11 AND 15) AS DATA_TEMP_2,
                            (COUNT(tdt.ID_TRANS) BETWEEN 16 AND 20) AS DATA_TEMP_3,
                            (COUNT(tdt.ID_TRANS) BETWEEN 21 AND 25) AS DATA_TEMP_4,
                            COUNT(tdt.ID_TRANS) >= 26 AS DATA_TEMP_5
                        FROM 
                            transaction_detail_today tdt
                        LEFT JOIN `transaction` t ON
                            t.ID_TRANS COLLATE utf8mb4_unicode_ci = tdt.ID_TRANS
                            AND
                            t.ISTRANS_TRANS = 1
                            AND 
                            t.ID_TYPE = 1
                            AND 
                            DATE(t.DATE_TRANS) = "' . $tgl_trans . '"
                        GROUP BY
                            tdt.ID_USER
                    ) tb_temp ON
                    tb_temp.REGIONAL_TRANS COLLATE utf8mb4_unicode_ci = mr2.NAME_REGIONAL
                WHERE
                    mr2.deleted_at IS NULL
                GROUP BY
                    tb_temp.REGIONAL_TRANS
                ORDER BY
                    mr2.NAME_REGIONAL ASC
            ');

            $NO_TRANS = DB::select('
                SELECT
                    mr2.NAME_REGIONAL,
                    IFNULL(tb_temp.NO_TRANS, 0) AS DATA_NO_TRANS
                FROM
                    md_regional mr2
                LEFT JOIN 
                    (
                        SELECT
                            mr.NAME_REGIONAL,
                            COUNT(u.ID_USER) AS NO_TRANS,
                            p.DATE_PRESENCE
                        FROM
                            `user` u
                        LEFT JOIN presence p ON
                            p.ID_USER = u.ID_USER
                        RIGHT JOIN md_regional mr ON 
                            mr.ID_REGIONAL = u.ID_REGIONAL
                            AND
                            mr.deleted_at IS NULL
                        WHERE
                            u.ID_ROLE IN (5, 6)
                            AND 
                            DATE(p.DATE_PRESENCE) = "' . $tgl_trans . '"
                        GROUP BY 
                            u.ID_REGIONAL
                        ORDER BY 
                            mr.NAME_REGIONAL ASC
                    ) tb_temp ON
                    tb_temp.NAME_REGIONAL COLLATE utf8mb4_unicode_ci = mr2.NAME_REGIONAL
                WHERE
                    mr2.deleted_at IS NULL
                GROUP BY
                    tb_temp.NAME_REGIONAL
                ORDER BY
                    mr2.NAME_REGIONAL ASC
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
                        "TRANS_1" => $trans_1 . " (" . ((!empty($data_regional[$i]->JML)) ? round(($trans_1 / $data_regional[$i]->JML) * 100) : 0) . "%)",
                        "TRANS_2" => $trans_2 . " (" . ((!empty($data_regional[$i]->JML)) ? round(($trans_2 / $data_regional[$i]->JML) * 100) : 0) . "%)",
                        "TRANS_3" => $trans_3 . " (" . ((!empty($data_regional[$i]->JML)) ? round(($trans_3 / $data_regional[$i]->JML) * 100) : 0) . "%)",
                        "TRANS_4" => $trans_4 . " (" . ((!empty($data_regional[$i]->JML)) ? round(($trans_4 / $data_regional[$i]->JML) * 100) : 0) . "%)",
                        "TRANS_5" => $trans_5 . " (" . ((!empty($data_regional[$i]->JML)) ? round(($trans_5 / $data_regional[$i]->JML) * 100) : 0) . "%)",
                        "NO_TRANS" => $trans_6 . " (" . ((!empty($data_regional[$i]->JML)) ? round(($percentage_trans_6 / $data_regional[$i]->JML) * 100) : 0) . "%)"
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
                            SUM((p.DATE_PRESENCE BETWEEN "' . $tgl_trans . ' 01:00:00" AND "' . $tgl_trans . ' 07:00:59")) AS DATA_TEMP_1,
                            SUM((p.DATE_PRESENCE BETWEEN "' . $tgl_trans . ' 07.01:00" AND "' . $tgl_trans . ' 07:15:59")) AS DATA_TEMP_2,
                            SUM((p.DATE_PRESENCE BETWEEN "' . $tgl_trans . ' 07:16:00" AND "' . $tgl_trans . ' 07:30:59")) AS DATA_TEMP_3,
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
                            DATE(p.DATE_PRESENCE) = "' . $tgl_trans . '"
                        GROUP BY 
                            u.ID_REGIONAL
                    ) tb_temp ON 
                    tb_temp.ID_REGIONAL = mr2.ID_REGIONAL
                WHERE
                    mr2.deleted_at IS NULL
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
                    "PRESENCE_1" => $pres_1 . " (" . ((!empty($data_regional[$i]->JML)) ? round(($pres_1 / $data_regional[$i]->JML) * 100) : 0) . "%)",
                    "PRESENCE_2" => $pres_2 . " (" . ((!empty($data_regional[$i]->JML)) ? round(($pres_2 / $data_regional[$i]->JML) * 100) : 0) . "%)",
                    "PRESENCE_3" => $pres_3 . " (" . ((!empty($data_regional[$i]->JML)) ? round(($pres_3 / $data_regional[$i]->JML) * 100) : 0) . "%)",
                    "PRESENCE_4" => $pres_4 . " (" . ((!empty($data_regional[$i]->JML)) ? round(($pres_4 / $data_regional[$i]->JML) * 100) : 0) . "%)",
                    "PRESENCE_5" => $pres_5 . " (" . ((!empty($data_regional[$i]->JML)) ? round(($pres_5 / $data_regional[$i]->JML) * 100) : 0) . "%)"
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
    public function downloadPresenceMonthly(Request $req)
    {
        $dateRequsest = $req->input('dateReq');
        $year = explode('-', $dateRequsest)[0];
        $month = explode('-', $dateRequsest)[1];
        // dd($dateRequsest);
        $regionals          = Regional::where('deleted_at', NULL)->get();
        $sundays            = [];
        $totDate = date('t', strtotime("$year-$month-01"));
        $currDate = date('j', strtotime("$year-$month-" . max(date('j'), $totDate)));
        $yearMonth = date('Y-m-', strtotime("$year-$month-01"));

        $date = Carbon::createFromDate($year, $month, 1);
        Carbon::setLocale('id');
        $formattedMonth = $date->translatedFormat('F');

        $queryDatePresence  = "";
        $presences          = [];


        for ($x = 1; $x <= $totDate; $x++) {
            if ($x > (int)$currDate) break;
            if (date_format(date_create($yearMonth . $x), "w") == "0") $sundays[$x] = true; // check if sunday
            $queryDatePresence .= "
                , COALESCE ((
                    SELECT 'M'
                    FROM presence p2
                    WHERE DATE(p2.DATE_PRESENCE) = '" . $yearMonth . $x . "' AND p2.ID_USER = u.ID_USER
                ), 'A') as TGL" . $x . "
            ";
        }

        $index = 0;
        foreach ($regionals as $regional) {
            $datas = DB::select("
                SELECT 
                    u.NAME_USER ,
                    ma.NAME_AREA ,
                    mr.NAME_ROLE
                    " . $queryDatePresence . "
                FROM `user` u
                INNER JOIN md_area ma 
                    ON 
                        u.ID_REGIONAL = " . $regional->ID_REGIONAL . " 
                        AND u.ID_ROLE IN (5, 6)
                        AND u.deleted_at IS NULL 
                        AND ma.ID_AREA = u.ID_AREA 
                INNER JOIN md_role mr 
                    ON mr.ID_ROLE = u.ID_ROLE 
                ORDER BY ma.NAME_AREA ASC , u.ID_ROLE ASC
            ");

            $presences[$index]['NAME_REGIONAL'] = $regional->NAME_REGIONAL;
            $presences[$index++]['PRESENCES']   = $datas;
        }

        $report = new ReportPresence();
        $report->generateMonthly($presences, $totDate, $sundays, $year, $formattedMonth);
    }
    public function downloadPresenceDaily()
    {
        $regionals          = Regional::where('deleted_at', NULL)->get();
        $sundays            = [];
        $totDate            = date('t');
        $currDate           = date('Y-m-d');
        // $currDate           = '2024-08-01';
        $queryDatePresence  = "";
        $presences          = [];

        $index = 0;
        foreach ($regionals as $regional) {
            $presences[$index]['PRESENCES1'] = DB::select("
                SELECT u.NAME_USER , mr.NAME_ROLE , ma.NAME_AREA , TIME(p.DATE_PRESENCE) as TIME
                FROM presence p 
                INNER JOIN `user` u
                ON
                    DATE(p.DATE_PRESENCE) = '" . $currDate . "'
                    AND TIME(p.DATE_PRESENCE) < '07:01'
                    AND p.ID_USER = u.ID_USER 
                    AND u.ID_REGIONAL = " . $regional->ID_REGIONAL . "
                INNER JOIN md_area ma 
                ON ma.ID_AREA = u.ID_AREA
                INNER JOIN md_role mr 
                ON mr.ID_ROLE = u.ID_ROLE 
                ORDER BY TIME(p.DATE_PRESENCE) ASC
            ");
            $presences[$index]['PRESENCES2'] = DB::select("
                SELECT u.NAME_USER , mr.NAME_ROLE , ma.NAME_AREA , TIME(p.DATE_PRESENCE) as TIME
                FROM presence p 
                INNER JOIN `user` u
                ON
                    DATE(p.DATE_PRESENCE) = '" . $currDate . "'
                    AND TIME(p.DATE_PRESENCE) BETWEEN '07:01' AND '07:15'
                    AND p.ID_USER = u.ID_USER 
                    AND u.ID_REGIONAL = " . $regional->ID_REGIONAL . "
                INNER JOIN md_area ma 
                ON ma.ID_AREA = u.ID_AREA
                INNER JOIN md_role mr 
                ON mr.ID_ROLE = u.ID_ROLE 
                ORDER BY TIME(p.DATE_PRESENCE) ASC
            ");
            $presences[$index]['PRESENCES3'] = DB::select("
                SELECT u.NAME_USER , mr.NAME_ROLE , ma.NAME_AREA , TIME(p.DATE_PRESENCE) as TIME
                FROM presence p 
                INNER JOIN `user` u
                ON
                    DATE(p.DATE_PRESENCE) = '" . $currDate . "'
                    AND TIME(p.DATE_PRESENCE) BETWEEN '07:16' AND '07:30'
                    AND p.ID_USER = u.ID_USER 
                    AND u.ID_REGIONAL = " . $regional->ID_REGIONAL . "
                INNER JOIN md_area ma 
                ON ma.ID_AREA = u.ID_AREA
                INNER JOIN md_role mr 
                ON mr.ID_ROLE = u.ID_ROLE 
                ORDER BY TIME(p.DATE_PRESENCE) ASC
            ");
            $presences[$index]['PRESENCES4'] = DB::select("
                SELECT u.NAME_USER , mr.NAME_ROLE , ma.NAME_AREA , TIME(p.DATE_PRESENCE) as TIME
                FROM presence p 
                INNER JOIN `user` u
                ON
                    DATE(p.DATE_PRESENCE) = '" . $currDate . "'
                    AND TIME(p.DATE_PRESENCE) > '07:30'
                    AND p.ID_USER = u.ID_USER 
                    AND u.ID_REGIONAL = " . $regional->ID_REGIONAL . "
                INNER JOIN md_area ma 
                ON ma.ID_AREA = u.ID_AREA
                INNER JOIN md_role mr 
                ON mr.ID_ROLE = u.ID_ROLE 
                ORDER BY TIME(p.DATE_PRESENCE) ASC
            ");
            $presences[$index]['PRESENCES5'] = DB::select("
                SELECT u.NAME_USER , mr.NAME_ROLE , ma.NAME_AREA 
                FROM `user` u 
                INNER JOIN md_area ma 
                ON 
                    u.ID_REGIONAL = " . $regional->ID_REGIONAL . " 
                    AND u.ID_ROLE IN (5, 6)
                    AND u.deleted_at IS NULL
                    AND ma.ID_AREA = u.ID_AREA
                INNER JOIN md_role mr 
                ON mr.ID_ROLE = u.ID_ROLE 
                WHERE u.ID_USER NOT IN (
                    SELECT p.ID_USER 
                    FROM presence p 
                    INNER JOIN `user` u2 
                    ON 
                        DATE(p.DATE_PRESENCE) = '" . $currDate . "' 
                        AND u.ID_REGIONAL = " . $regional->ID_REGIONAL . "
                )
                ORDER BY ma.NAME_AREA ASC, mr.ID_ROLE ASC
            ");

            $presences[$index++]['NAME_REGIONAL'] = $regional->NAME_REGIONAL;
        }

        $report = new ReportPresence();
        $report->generateDaily($presences, $totDate, $sundays);
    }
}
