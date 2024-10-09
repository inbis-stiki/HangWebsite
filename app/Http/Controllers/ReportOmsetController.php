<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ReportOmsetHead;
use App\ReportOmsetDetail;
use Session;

class ReportOmsetController extends Controller
{
    public function index(Request $req)
    {
        $user = $req->session()->get('id_user');
        $data['title']          = "Data Omset";
        $data['sidebar']        = "omset";
        $data['sidebar2']       = "";

        $id_role  = $req->session()->get('role');
        $id_location    = $req->session()->get('location');
        $id_regional    = $req->session()->get('regional');

        if ($id_role == 3) {
            $data['data_regional']  = DB::table('md_area')
                ->leftjoin('md_regional', 'md_regional.ID_REGIONAL', '=', 'md_area.ID_REGIONAL')
                ->where('md_regional.ID_LOCATION', '=', $id_location)
                ->get();
        } else if ($id_role == 4) {
            $data['data_regional']  = DB::table('md_area')
                ->leftjoin('md_regional', 'md_regional.ID_REGIONAL', '=', 'md_area.ID_REGIONAL')
                ->where('md_regional.ID_REGIONAL', '=', $id_regional)
                ->get();
        } else {
            $data['data_regional']  = DB::table('md_area')->get();
        }

        $year = 2024;
        $data['result'] = DB::table('user as u')
            ->select(
                'u.NAME_USER',
                DB::raw('SUM(CASE WHEN h.BULAN = 1 THEN d.TOTAL_OMSET ELSE 0 END) AS MONTH1_TOTAL_OMSET'),
                DB::raw('SUM(CASE WHEN h.BULAN = 1 THEN d.TOTAL_OUTLET ELSE 0 END) AS MONTH1_TOTAL_OUTLET'),
                DB::raw('SUM(CASE WHEN h.BULAN = 2 THEN d.TOTAL_OMSET ELSE 0 END) AS MONTH2_TOTAL_OMSET'),
                DB::raw('SUM(CASE WHEN h.BULAN = 2 THEN d.TOTAL_OUTLET ELSE 0 END) AS MONTH2_TOTAL_OUTLET'),
                DB::raw('SUM(CASE WHEN h.BULAN = 3 THEN d.TOTAL_OMSET ELSE 0 END) AS MONTH3_TOTAL_OMSET'),
                DB::raw('SUM(CASE WHEN h.BULAN = 3 THEN d.TOTAL_OUTLET ELSE 0 END) AS MONTH3_TOTAL_OUTLET'),
                DB::raw('SUM(CASE WHEN h.BULAN = 4 THEN d.TOTAL_OMSET ELSE 0 END) AS MONTH4_TOTAL_OMSET'),
                DB::raw('SUM(CASE WHEN h.BULAN = 4 THEN d.TOTAL_OUTLET ELSE 0 END) AS MONTH4_TOTAL_OUTLET'),
                DB::raw('SUM(CASE WHEN h.BULAN = 5 THEN d.TOTAL_OMSET ELSE 0 END) AS MONTH5_TOTAL_OMSET'),
                DB::raw('SUM(CASE WHEN h.BULAN = 5 THEN d.TOTAL_OUTLET ELSE 0 END) AS MONTH5_TOTAL_OUTLET'),
                DB::raw('SUM(CASE WHEN h.BULAN = 6 THEN d.TOTAL_OMSET ELSE 0 END) AS MONTH6_TOTAL_OMSET'),
                DB::raw('SUM(CASE WHEN h.BULAN = 6 THEN d.TOTAL_OUTLET ELSE 0 END) AS MONTH6_TOTAL_OUTLET'),
                DB::raw('SUM(CASE WHEN h.BULAN = 7 THEN d.TOTAL_OMSET ELSE 0 END) AS MONTH7_TOTAL_OMSET'),
                DB::raw('SUM(CASE WHEN h.BULAN = 7 THEN d.TOTAL_OUTLET ELSE 0 END) AS MONTH7_TOTAL_OUTLET'),
                DB::raw('SUM(CASE WHEN h.BULAN = 8 THEN d.TOTAL_OMSET ELSE 0 END) AS MONTH8_TOTAL_OMSET'),
                DB::raw('SUM(CASE WHEN h.BULAN = 8 THEN d.TOTAL_OUTLET ELSE 0 END) AS MONTH8_TOTAL_OUTLET'),
                DB::raw('SUM(CASE WHEN h.BULAN = 9 THEN d.TOTAL_OMSET ELSE 0 END) AS MONTH9_TOTAL_OMSET'),
                DB::raw('SUM(CASE WHEN h.BULAN = 9 THEN d.TOTAL_OUTLET ELSE 0 END) AS MONTH9_TOTAL_OUTLET'),
                DB::raw('SUM(CASE WHEN h.BULAN = 10 THEN d.TOTAL_OMSET ELSE 0 END) AS MONTH10_TOTAL_OMSET'),
                DB::raw('SUM(CASE WHEN h.BULAN = 10 THEN d.TOTAL_OUTLET ELSE 0 END) AS MONTH10_TOTAL_OUTLET'),
                DB::raw('SUM(CASE WHEN h.BULAN = 11 THEN d.TOTAL_OMSET ELSE 0 END) AS MONTH11_TOTAL_OMSET'),
                DB::raw('SUM(CASE WHEN h.BULAN = 11 THEN d.TOTAL_OUTLET ELSE 0 END) AS MONTH11_TOTAL_OUTLET'),
                DB::raw('SUM(CASE WHEN h.BULAN = 12 THEN d.TOTAL_OMSET ELSE 0 END) AS MONTH12_TOTAL_OMSET'),
                DB::raw('SUM(CASE WHEN h.BULAN = 12 THEN d.TOTAL_OUTLET ELSE 0 END) AS MONTH12_TOTAL_OUTLET')
            )
            ->leftJoin('report_omset_detail as d', DB::raw('u.ID_USER COLLATE utf8mb4_general_ci'), '=', 'd.ID_USER')
            ->leftJoin('report_omset_head as h', 'd.ID_HEAD', '=', 'h.ID_HEAD')
            ->whereNull('u.deleted_at')
            ->where(function ($query) use ($year) {
                $query->where('h.TAHUN', $year)
                      ->orWhereNull('h.TAHUN');
            })
            ->groupBy('u.ID_USER', 'u.NAME_USER')
            ->orderBy('u.ID_USER')
            ->get();
        // dd($data['result']);

        return view('laporan.omset.omset', $data);
    }
}