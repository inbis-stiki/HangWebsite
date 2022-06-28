<?php

namespace App\Http\Controllers;

use App\Presence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class PresenceController extends Controller
{
    public function index(){
       

        $data['title']          = "Presensi";
        $data['sidebar']        = "presence";
        $data['sidebar2']       = "";
        $data['presences']      = DB::table('presence')
        ->join('user', 'user.ID_USER', '=', 'presence.ID_USER')
        ->join('md_district', 'md_district.ID_DISTRICT', '=', 'presence.ID_DISTRICT')
        ->join('md_area', 'md_area.ID_AREA', '=', 'md_district.ID_AREA')
        ->join('md_regional', 'md_regional.ID_REGIONAL', '=', 'md_area.ID_REGIONAL')
        ->join('md_type', 'md_type.ID_TYPE', '=', 'presence.ID_TYPE')
        ->orderBy('presence.DATE_PRESENCE', 'DESC')
        ->select('presence.*', 'user.NAME_USER', 'md_district.NAME_DISTRICT', 'md_area.NAME_AREA', 'md_regional.NAME_REGIONAL', 'md_type.NAME_TYPE')
        ->get();

        return view('presence', $data);
    }
}
