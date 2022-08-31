<?php

namespace App\Http\Controllers;

use App\Presence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class PresenceController extends Controller
{
    public function index()
    {
        $data['title']          = "Presensi";
        $data['sidebar']        = "presence";
        $data['sidebar2']       = "";

        return view('master.presence.presence', $data);
    }

    public function getAllPresence(Request $req)
    {
        $id_role  = $req->session()->get('role');
        $id_regional  = $req->session()->get('regional');
        $tgl_presence  = $req->input('tglSearchPresence');

        if ($id_role != 2) {
            $data_presence      = DB::table('presence')
                ->select('presence.*', 'user.NAME_USER', 'md_district.NAME_DISTRICT', 'md_area.NAME_AREA', 'md_regional.NAME_REGIONAL')
                ->join('user', 'user.ID_USER', '=', 'presence.ID_USER')
                ->join('md_district', 'md_district.ID_DISTRICT', '=', 'presence.ID_DISTRICT')
                ->join('md_area', 'md_area.ID_AREA', '=', 'md_district.ID_AREA')
                ->join('md_regional', 'md_regional.ID_REGIONAL', '=', 'md_area.ID_REGIONAL')
                ->orderBy('presence.DATE_PRESENCE', 'DESC')
                ->where('md_regional.ID_REGIONAL', '=', $id_regional)
                ->where('presence.DATE_PRESENCE', 'like', $tgl_presence . '%')
                ->get();
        } else {
            $data_presence      = DB::table('presence')
                ->select('presence.*', 'user.NAME_USER', 'md_district.NAME_DISTRICT', 'md_area.NAME_AREA', 'md_regional.NAME_REGIONAL')
                ->join('user', 'user.ID_USER', '=', 'presence.ID_USER')
                ->join('md_district', 'md_district.ID_DISTRICT', '=', 'presence.ID_DISTRICT')
                ->join('md_area', 'md_area.ID_AREA', '=', 'md_district.ID_AREA')
                ->join('md_regional', 'md_regional.ID_REGIONAL', '=', 'md_area.ID_REGIONAL')
                ->orderBy('presence.DATE_PRESENCE', 'DESC')
                ->where('presence.DATE_PRESENCE', 'like', $tgl_presence . '%')
                ->get();
        }

        $NewData_all = array();
        $i = 0;
        foreach ($data_presence as $presence) {
            $i++;

            $data = array(
                "NO" => $i,
                "NAME_USER" => $presence->NAME_USER,
                "NAME_AREA" => $presence->NAME_AREA,
                "NAME_DISTRICT" => $presence->NAME_DISTRICT,
                "DATE_PRESENCE" => date_format(date_create($presence->DATE_PRESENCE), 'j F Y'),
                "ACTION_BUTTON" => '<button class="btn light btn-success"  onclick="showPresence(' . $presence->PHOTO_PRESENCE . ', ' . $presence->NAME_USER . ', ' . $presence->NAME_DISTRICT . ', ' . date_format(date_create($presence->DATE_PRESENCE), 'j F Y H:i') . ', ' . $presence->NAME_AREA . ', ' . $presence->NAME_REGIONAL . ')"><i class="fa fa-circle-info"></i></button><a class="btn light btn-info" href="https://maps.google.com/maps?q=' . $presence->LAT_PRESENCE . ',' . $presence->LONG_PRESENCE . '&hl=es&z=14&amp;" target="_blank"><i class="fa fa-map-location-dot"></i></a>'
            );
            array_push($NewData_all, $data);
        }

        return response([
            'status_code'       => 200,
            'status_message'    => 'Data berhasil diambil!',
            'data'              => $NewData_all
        ], 200);
    }
}
