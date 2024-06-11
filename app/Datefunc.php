<?php

namespace App;

use Illuminate\Support\Facades\Http;

class Datefunc
{
    public function currDate($long, $lat)
    {
        do {
            // $response = Http::get('https://api.wheretheiss.at/v1/coordinates/' . $lat . ',' . $long);

            // $response = Http::get('https://vip.timezonedb.com/v2.1/get-time-zone?key=DHQJPS68JTER&format=json&by=position&lat='.$lat.'&lng='.$long);
            // sleep(3);

            // $response = Http::withOptions(['timeout' => 2])->retry(5,5000,null)->get('https://vip.timezonedb.com/v2.1/get-time-zone?key=DHQJPS68JTER&format=json&by=position&lat='.$lat.'&lng='.$long);
            $response = Http::withOptions(['timeout' => 2])->retry(5,5000,null)->get('https://zone.tjireng.my.id/api?lat='.$lat.'&lng='.$long);

        } while (!isset($response->json()['zoneName']));
        
        if (isset($response['zoneName'])) {
        
            $timezone = stripcslashes($response->json()['zoneName']);
            date_default_timezone_set($timezone);
            return date('Y-m-d H:i:s');
        }
    }
}
