<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Product;
use App\Route;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RouteApi extends Controller
{
    public function GetDataRute(Request $request) {
        try {
            $id_user = $request->input('id_user');
            $latitude = $request->get('lat_user'); // "-7.965769846888459"
            $longitude = $request->get('lng_user'); // "112.60750389398623"
            // $id_user = "cd333716";
    
            $route = DB::table('md_split_route as r')
                ->join('md_shop as s', 'r.ID_SHOP', '=', 's.ID_SHOP')
                ->select('r.ID_SPLIT AS ID_RUTE', 'r.ID_USER', 'r.WEEK', 'r.ID_SHOP', 'r.ROUTE_GROUP', 'r.STATUS', 's.*')
                ->selectRaw("ST_DISTANCE_SPHERE(
                    point('$longitude', '$latitude'),
                    point(s.LONG_SHOP, s.LAT_SHOP)
                )  AS DISTANCE_SHOP")
                ->where('r.ID_USER', $id_user)
                ->where('r.WEEK', function($query) use ($id_user) {
                    $query->select(DB::raw('MIN(WEEK)'))
                          ->from('md_split_route')
                          ->where('ID_USER', $id_user);
                })
                ->where('r.ROUTE_GROUP', function($query) use ($id_user) {
                    $query->select(DB::raw('MIN(ROUTE_GROUP)'))
                          ->from('md_split_route')
                          ->where('ID_USER', $id_user)
                          ->where('WEEK', function($subquery) use ($id_user) {
                              $subquery->select(DB::raw('MIN(WEEK)'))
                                       ->from('md_split_route')
                                       ->where('ID_USER', $id_user);
                          })
                          ->where('STATUS', 0);
                })
                ->where('r.STATUS', '=', 0)
                ->orderBy('r.WEEK', 'ASC')
                ->orderBy('r.ROUTE_GROUP', 'ASC')
                ->orderBy('DISTANCE_SHOP', 'ASC')
                ->paginate(10);

            $dataPagination = array();
            array_push(
                $dataPagination,
                array(
                    "TOTAL_DATA" => $route->total(),
                    "PAGE" => $route->currentPage(),
                    "TOTAL_PAGE" => $route->lastPage()
                )
            );
    
            return response([
                'status_code'       => 200,
                'status_message'    => 'Data berhasil diambil!',
                'data'              => $route->items(),
                'status_pagination' => $dataPagination
            ], 200);
        } catch (Exception $exp) {
            return response([
                'status_code'       => 500,
                'status_message'    => $exp->getMessage(),
            ], 500);
        }
    }

    public function UpdateStatusRoute(Request $request)
    {
        try {
            $id_route = $request->input('id_route');
    
            $route = Route::find($id_route);
            $route->STATUS      = '1';
            $route->save(); 
    
            return response([
                'status_code'       => 200,
                'status_message'    => 'Data berhasil diupdate!',
                'data'              => ['ID_ROUTE' => $route->ID_RUTE]
            ], 200);
        } catch (Exception $exp) {
            return response([
                'status_code'       => 500,
                'status_message'    => $exp->getMessage(),
            ], 500);
        }
    }
}
