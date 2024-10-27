<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ReportOmsetHead;
use App\ReportOmsetDetail;
use PHPUnit\Framework\Constraint\Count;
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
            $data['data_regional']  = DB::table('md_regional')
                ->where('md_regional.ID_LOCATION', '=', $id_location)
                ->get();
        } else if ($id_role == 4) {
            $data['data_regional']  = DB::table('md_regional')
                ->where('md_regional.ID_REGIONAL', '=', $id_regional)
                ->get();
        } else {
            $data['data_regional']  = DB::table('md_regional')->get();
        }

        // dd($data);
        $data['shop_prod'] = $this->shop_prod_kat();

        // $year = 2024;
        // $data['result'] = DB::table('user as u')
        //     ->select(
        //         'u.NAME_USER',
        //         DB::raw('SUM(CASE WHEN h.BULAN = 1 THEN d.TOTAL_OMSET ELSE 0 END) AS MONTH1_TOTAL_OMSET'),
        //         DB::raw('SUM(CASE WHEN h.BULAN = 1 THEN d.TOTAL_OUTLET ELSE 0 END) AS MONTH1_TOTAL_OUTLET'),
        //         DB::raw('SUM(CASE WHEN h.BULAN = 2 THEN d.TOTAL_OMSET ELSE 0 END) AS MONTH2_TOTAL_OMSET'),
        //         DB::raw('SUM(CASE WHEN h.BULAN = 2 THEN d.TOTAL_OUTLET ELSE 0 END) AS MONTH2_TOTAL_OUTLET'),
        //         DB::raw('SUM(CASE WHEN h.BULAN = 3 THEN d.TOTAL_OMSET ELSE 0 END) AS MONTH3_TOTAL_OMSET'),
        //         DB::raw('SUM(CASE WHEN h.BULAN = 3 THEN d.TOTAL_OUTLET ELSE 0 END) AS MONTH3_TOTAL_OUTLET'),
        //         DB::raw('SUM(CASE WHEN h.BULAN = 4 THEN d.TOTAL_OMSET ELSE 0 END) AS MONTH4_TOTAL_OMSET'),
        //         DB::raw('SUM(CASE WHEN h.BULAN = 4 THEN d.TOTAL_OUTLET ELSE 0 END) AS MONTH4_TOTAL_OUTLET'),
        //         DB::raw('SUM(CASE WHEN h.BULAN = 5 THEN d.TOTAL_OMSET ELSE 0 END) AS MONTH5_TOTAL_OMSET'),
        //         DB::raw('SUM(CASE WHEN h.BULAN = 5 THEN d.TOTAL_OUTLET ELSE 0 END) AS MONTH5_TOTAL_OUTLET'),
        //         DB::raw('SUM(CASE WHEN h.BULAN = 6 THEN d.TOTAL_OMSET ELSE 0 END) AS MONTH6_TOTAL_OMSET'),
        //         DB::raw('SUM(CASE WHEN h.BULAN = 6 THEN d.TOTAL_OUTLET ELSE 0 END) AS MONTH6_TOTAL_OUTLET'),
        //         DB::raw('SUM(CASE WHEN h.BULAN = 7 THEN d.TOTAL_OMSET ELSE 0 END) AS MONTH7_TOTAL_OMSET'),
        //         DB::raw('SUM(CASE WHEN h.BULAN = 7 THEN d.TOTAL_OUTLET ELSE 0 END) AS MONTH7_TOTAL_OUTLET'),
        //         DB::raw('SUM(CASE WHEN h.BULAN = 8 THEN d.TOTAL_OMSET ELSE 0 END) AS MONTH8_TOTAL_OMSET'),
        //         DB::raw('SUM(CASE WHEN h.BULAN = 8 THEN d.TOTAL_OUTLET ELSE 0 END) AS MONTH8_TOTAL_OUTLET'),
        //         DB::raw('SUM(CASE WHEN h.BULAN = 9 THEN d.TOTAL_OMSET ELSE 0 END) AS MONTH9_TOTAL_OMSET'),
        //         DB::raw('SUM(CASE WHEN h.BULAN = 9 THEN d.TOTAL_OUTLET ELSE 0 END) AS MONTH9_TOTAL_OUTLET'),
        //         DB::raw('SUM(CASE WHEN h.BULAN = 10 THEN d.TOTAL_OMSET ELSE 0 END) AS MONTH10_TOTAL_OMSET'),
        //         DB::raw('SUM(CASE WHEN h.BULAN = 10 THEN d.TOTAL_OUTLET ELSE 0 END) AS MONTH10_TOTAL_OUTLET'),
        //         DB::raw('SUM(CASE WHEN h.BULAN = 11 THEN d.TOTAL_OMSET ELSE 0 END) AS MONTH11_TOTAL_OMSET'),
        //         DB::raw('SUM(CASE WHEN h.BULAN = 11 THEN d.TOTAL_OUTLET ELSE 0 END) AS MONTH11_TOTAL_OUTLET'),
        //         DB::raw('SUM(CASE WHEN h.BULAN = 12 THEN d.TOTAL_OMSET ELSE 0 END) AS MONTH12_TOTAL_OMSET'),
        //         DB::raw('SUM(CASE WHEN h.BULAN = 12 THEN d.TOTAL_OUTLET ELSE 0 END) AS MONTH12_TOTAL_OUTLET')
        //     )
        //     ->leftJoin('report_omset_detail as d', DB::raw('u.ID_USER COLLATE utf8mb4_general_ci'), '=', 'd.ID_USER')
        //     ->leftJoin('report_omset_head as h', 'd.ID_HEAD', '=', 'h.ID_HEAD')
        //     ->whereNull('u.deleted_at')
        //     ->where(function ($query) use ($year) {
        //         $query->where('h.TAHUN', $year)
        //             ->orWhereNull('h.TAHUN');
        //     })
        //     ->groupBy('u.ID_USER', 'u.NAME_USER')
        //     ->orderBy('u.ID_USER')
        //     ->get();
        // dd($data['result']);

        return view('laporan.omset.omset', $data);
    }

    public function shop_prod_kat()
    {
        $data = DB::select("
            SELECT
                mts.ID_TYPE AS ID_CAT,
                mts.NAME_TYPE COLLATE utf8mb4_general_ci AS NAMA ,
                'SHOP_CATEGORY' AS `TYPE`
            FROM 
                md_type_shop mts 
            UNION ALL
            SELECT 
                mpc.ID_PC AS ID_CAT,
                mpc.NAME_PC COLLATE utf8mb4_general_ci AS NAMA ,
                'PRODUCT_CATEGORY' AS `TYPE`
            FROM 
                md_product_category mpc
        ");

        $groupedData = [];
        foreach ($data as $item) {
            $groupedData[$item->TYPE][] = $item;
        }

        return $groupedData;
    }

    public function pipeline_datatable(Request $req)
    {
        $regional   = $req->input('regional');
        $shopProduct  = $req->input('shopProduct');
        $typeshopProduct  = $req->input('typeShopProduct');
        $search     = $req->input('search')['value'];

        $condition = [];
        if (!empty($regional)) {
            $regionalArray = [];
            foreach (explode(';', $regional) as $item) {
                $regionalArray[] = "'" . $item . "'";
            }
            $regCnvrt = implode(',', $regionalArray);

            $condition[] = "h.ID_REGIONAL IN ($regCnvrt)";
        }
        if (!empty($typeshopProduct) && !empty($shopProduct)) {
            if ($typeshopProduct == 'SHOP_CATEGORY') {
                $condition[] = "d.TYPE_SHOP = '$shopProduct'";
            }
            if ($typeshopProduct == 'PRODUCT_CATEGORY') {
                $condition[] = "mpc.NAME_PC = '$shopProduct'";
            }
        }
        if (!empty($search)) {
            $condition[] = "u.NAME_USER LIKE '%$search%'";
        }

        if (!empty($condition)) {
            $conditionWhere = implode(' AND ', $condition);
        }

        $start = $req->input('start');
        $perPage = $req->input('length');
        $draw = $req->input('draw');

        $year = 2024;
        $result = DB::table('user as u')
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
            ->leftjoin('md_product_category as mpc', 'd.ID_PC', '=', 'mpc.ID_PC')
            ->whereNull('u.deleted_at')
            ->where(function ($query) use ($year) {
                $query->where('h.TAHUN', $year)
                    ->orWhereNull('h.TAHUN');
            })
            ->groupBy('u.ID_USER', 'u.NAME_USER')
            ->orderBy('u.ID_USER');

        if (!empty($condition)) {
            $result->whereRaw($conditionWhere);
        }

        $resultAll = count($result->get());
        $resultLimit = $result
            ->offset($start)
            ->limit($perPage)
            ->get();

        $NewData_all = array();
        $counter_all = $start;

        foreach ($resultLimit as $item) {
            ++$counter_all;
            $data = array(
                "NAME_USER" => $item->NAME_USER,
            );

            for ($index = 1; $index <= 12; $index++) {
                $data['TOTAL_OMSET_' . $index] = $item->{'MONTH' . $index . '_TOTAL_OMSET'};
                $data['JML_OUTLET_' . $index] = $item->{'MONTH' . $index . '_TOTAL_OUTLET'};
            }
            array_push($NewData_all, $data);
        }

        return response([
            'status_code'       => 200,
            'status_message'    => 'Data berhasil diambil!',
            'draw'              => intval($draw),
            'recordsFiltered'   => ($counter_all != 0) ? $resultAll : 0,
            'recordsTotal'      => ($counter_all != 0) ? $resultAll : 0,
            'data'              => $NewData_all
        ], 200);
    }
}
