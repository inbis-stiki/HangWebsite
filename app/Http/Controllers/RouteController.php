<?php

namespace App\Http\Controllers;

use App\Route;
use App\Users;
use App\Shop;
use App\logmd;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Session;

class RouteController extends Controller
{

    public function index()
    {
        $data['title']      = "Rute";
        $data['sidebar']    = "master";
        $data['sidebar2']   = "route";
        $data['routes']      = DB::select('
            SELECT
                mr.*,
                u.NAME_USER,
                ms.NAME_SHOP
            FROM 
                md_split_route mr
            LEFT JOIN `user` u ON
                mr.ID_USER = u.ID_USER
            LEFT JOIN md_shop ms ON
                mr.ID_SHOP = ms.ID_SHOP
            GROUP BY
                mr.ID_USER,
                mr.ROUTE_GROUP 
            ORDER BY 
                mr.ROUTE_GROUP ASC
        ');

        return view('master.route.user_route', $data);
    }

    public function index_edit(Request $req)
    {
        $idUser = $req->input('id_user');
        $groupRoute = $req->input('route_group');
        $week = $req->input('week');

        $data['title']      = "Route";
        $data['sidebar']    = "master";
        $data['sidebar2']   = "route";
        $data['users']      = Users::all();
        $data['routes']     = DB::select('
            SELECT
                mr.*,
                u.NAME_USER,
                ms.NAME_SHOP
            FROM 
                md_split_route mr
            LEFT JOIN `user` u ON
                mr.ID_USER = u.ID_USER
            LEFT JOIN md_shop ms ON
                mr.ID_SHOP = ms.ID_SHOP
            GROUP BY
                mr.ID_USER,
                mr.ROUTE_GROUP 
            ORDER BY 
                mr.ROUTE_GROUP ASC
        ');

        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $data['user_route'] = DB::selectOne("
            SELECT
                mr.ID_USER ,
                mr.ROUTE_GROUP ,
                mr.WEEK ,
                u.NAME_USER ,
                ma.ID_AREA ,
                GROUP_CONCAT(mr.ID_SHOP SEPARATOR ';') AS ID_SHOP,
                GROUP_CONCAT(ms.NAME_SHOP SEPARATOR ';') AS NAME_SHOP ,
                GROUP_CONCAT(ma.NAME_AREA SEPARATOR ';') AS NAME_AREA
            FROM
                md_split_route mr
            LEFT JOIN md_shop ms ON
                ms.ID_SHOP = mr.ID_SHOP
            LEFT JOIN md_district md ON 
                md.ID_DISTRICT = ms.ID_DISTRICT
            LEFT JOIN md_area ma ON
                ma.ID_AREA = md.ID_AREA
            LEFT JOIN user u ON
                mr.ID_USER = u.ID_USER
            WHERE
                mr.ID_USER = '$idUser'
                AND
                mr.ROUTE_GROUP = $groupRoute
                AND
                mr.WEEK = $week
            GROUP BY 
                mr.ID_USER ,
                mr.ROUTE_GROUP ,
                mr.WEEK
        ");

        $data['user_data'] = DB::select("
            SELECT 
                u.*
            FROM
                user u
            WHERE
                u.ID_AREA = " . $data['user_route']->ID_AREA . "
        ");

        return view('master.route.route_edit', $data);
    }

    public function create()
    {
        $data['title']      = "Route";
        $data['sidebar']    = "master";
        $data['sidebar2']   = "route";
        $data['users']       = Users::all();

        return view('master.route.route', $data);
    }

    public function searchShops(Request $request)
    {
        $search = $request->input('q');
        $shops = DB::select("
            SELECT
                ms.ID_SHOP,
                ms.NAME_SHOP ,
                ma.NAME_AREA
            FROM 
                md_shop ms 
            LEFT JOIN md_district md ON 
                md.ID_DISTRICT = ms.ID_DISTRICT
            LEFT JOIN md_area ma ON
                ma.ID_AREA = md.ID_AREA
            WHERE
                ms.NAME_SHOP LIKE '%" . $search . "%'
                AND 
                ms.DELETED_AT IS NULL
            LIMIT 
                10
        ");

        return response()->json($shops);
    }

    public function store(Request $req)
    {
        try {
            foreach ($req->ID_SHOP as $idShop) {
                $data = [
                    'ID_USER' => $req->input('ID_USER'),
                    'WEEK' => $req->input('WEEK'),
                    'ID_SHOP' => $idShop,
                    'ROUTE_GROUP' => $req->input('ROUTE_GROUP'),
                ];

                Route::insert($data);
            }

            return redirect('master/rute')->with('succ_msg', 'Route added successfully!');
        } catch (Exception $err) {
            return redirect('master/rute')->with('err_msg', 'Route added failed!, error : ' . $err);
        }
    }

    public function update(Request $req)
    {
        try {
            DB::statement("SET FOREIGN_KEY_CHECKS=0;");
            $where = [
                'ID_USER' => $req->input('ID_USER'),
                'WEEK' => $req->input('WEEK'),
                'ROUTE_GROUP' => $req->input('ROUTE_GROUP'),
            ];
            Route::where($where)->update(['ID_USER' => $req->input('ID_USER_NEW')]);
            DB::statement("SET FOREIGN_KEY_CHECKS=1;");

            return redirect('master/rute')->with('succ_msg', 'Route updated successfully!');
        } catch (Exception $err) {
            return redirect('master/rute')->with('err_msg', 'Route updated failed!, error : ' . $err);
        }
    }

    public function updateOld(Request $req)
    {
        try {
            $data = [
                'ID_USER' => $req->input('ID_USER'),
                'WEEK' => $req->input('WEEK'),
                'ROUTE_GROUP' => $req->input('ROUTE_GROUP'),
            ];
            Route::where($data)->delete();

            foreach ($req->ID_SHOP as $idShop) {
                $data = [
                    'ID_USER' => $req->input('ID_USER'),
                    'WEEK' => $req->input('WEEK'),
                    'ID_SHOP' => $idShop,
                    'ROUTE_GROUP' => $req->input('ROUTE_GROUP'),
                ];

                Route::insert($data);
            }

            return redirect('master/rute')->with('succ_msg', 'Route updated successfully!');
        } catch (Exception $err) {
            return redirect('master/rute')->with('err_msg', 'Route updated failed!, error : ' . $err);
        }
    }
}
