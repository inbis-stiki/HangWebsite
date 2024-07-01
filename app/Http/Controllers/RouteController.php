<?php

namespace App\Http\Controllers;

use App\Route;
use App\Users;
use App\Shop;
use App\logmd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Session;

class RouteController extends Controller{

    public function index()
    {
        $data['title']      = "Route";
        $data['sidebar']    = "master";
        $data['sidebar2']   = "route";
        $data['routes']      = DB::select('
            SELECT
                mr.*,
                u.NAME_USER,
                ms.NAME_SHOP
            FROM 
                md_route mr
            LEFT JOIN `user` u ON
                mr.ID_USER = u.ID_USER
            LEFT JOIN md_shop ms ON
                mr.ID_SHOP = ms.ID_SHOP
            GROUP BY
                mr.ROUTE_GROUP 
        ');

        return view('master.route.user_route', $data);
    }

    public function create()
    {
        $data['title']      = "Route";
        $data['sidebar']    = "master";
        $data['sidebar2']   = "route";
        $data['users']       = Users::all();
        $data['shops']       = Shop::take(20)->get();

        return view('master.route.route', $data);
    }

    public function searchShops(Request $request)
    {
        $search = $request->input('q');
        $shops = Shop::where('NAME_SHOP', 'LIKE', '%' . $search . '%')->get();
    
        return response()->json($shops);
    }

    public function store(Request $req)
    {
        $req->validate([
            'ID_USER' => 'required',
            'WEEK' => 'required|integer',
            'ID_SHOP' => 'required',
            'ROUTE_GROUP' => 'required|integer',
        ]);

        Route::create([
            'ID_USER' => $req->ID_USER,
            'WEEK' => $req->WEEK,
            'ID_SHOP' => $req->ID_SHOP,
            'ROUTE_GROUP' => $req->ROUTE_GROUP,
        ]);

        return redirect()->route('routes.index')->with('succ_msg', 'Route added successfully!');
    }

}