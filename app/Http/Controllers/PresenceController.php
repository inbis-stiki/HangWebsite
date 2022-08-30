<?php

namespace App\Http\Controllers;

use App\Presence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class PresenceController extends Controller
{
    public function index(Request $req){
        $data['title']          = "Presensi";
        $data['sidebar']        = "presence";
        $data['sidebar2']       = "";

        $id_regional  = $req->session()->get('regional');

        $data['presences']      = DB::table('presence')
        ->select('presence.*', 'user.NAME_USER', 'md_district.NAME_DISTRICT', 'md_area.NAME_AREA', 'md_regional.NAME_REGIONAL')
        ->join('user', 'user.ID_USER', '=', 'presence.ID_USER')
        ->join('md_district', 'md_district.ID_DISTRICT', '=', 'presence.ID_DISTRICT')
        ->join('md_area', 'md_area.ID_AREA', '=', 'md_district.ID_AREA')
        ->join('md_regional', 'md_regional.ID_REGIONAL', '=', 'md_area.ID_REGIONAL')
        ->orderBy('presence.DATE_PRESENCE', 'DESC')
        ->where('md_regional.ID_REGIONAL', '=', $id_regional)
        ->get();

        return view('master.presence.presence', $data);
    }
}