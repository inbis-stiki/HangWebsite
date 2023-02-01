<?php

namespace App;

use Illuminate\Support\Facades\Http;

class Datefunc
{
    public function currDate($long, $lat)
    {
        do {
            // $response = Http::get('https://api.wheretheiss.at/v1/coordinates/' . $lat . ',' . $long);
            $response = Http::get('http://api.geonames.org/timezoneJSON?lat='.$lat.'&lng='.$long.'&username=firnasreyhan');
            sleep(1);
        } while (!isset($response->json()['timezoneId']));
        
        if (isset($response['timezoneId'])) {
            date_default_timezone_set($response->json()['timezoneId']);
            return date('Y-m-d H:i:s');
        }
    }
}
