<?php
namespace App;

use Illuminate\Support\Facades\Http;

class Datefunc{
    public function currDate($long, $lat){
        $response = Http::get('https://api.wheretheiss.at/v1/coordinates/'.$lat.','.$long);
        date_default_timezone_set($response->json()['timezone_id']);

        return date('Y-m-d H:i:s');
    }
}