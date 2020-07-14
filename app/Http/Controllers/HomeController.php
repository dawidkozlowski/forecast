<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;


class HomeController extends Controller
{
    public function index()
    {
        $apiKey = getenv('API_KEY');
        $city = getenv("CITY_NAME");
        $apiUrl = 'api.openweathermap.org/data/2.5/forecast?q='.$city.'&appid='.$apiKey;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);

        curl_close($ch);
        $forecast = json_decode($response);
        $tempAverage = 0;
        $tempList = [];
        foreach ($forecast->list as $step){
            if(isset($step->main->temp)){
                array_push($tempList, $step->main->temp);
            }
        }
        $windSpeed = $forecast->list[0]->wind->speed;
        $lastTemp = round($forecast->list[0]->main->temp-273.15, 1);
        $tempAverage = round((array_sum($tempList) / count($tempList))-273.15, 1);
        $sunrise = date("Y-m-d H:i:s", $forecast->city->sunrise+$forecast->city->timezone);
        $windDirection = $this->wind_cardinals($forecast->list[0]->wind->deg);

        return view('forecastview', [
            "forecast" => $forecast,
            "tempAverage" => $tempAverage,
            "sunrise" => $sunrise,
            "windDirection" => $windDirection,
            "lastTemp" => $lastTemp,
            "windSpeed" => $windSpeed
        ]);
    }

    function wind_cardinals($deg) {
        $cardinalDirections = array(
            'N' => array(348.75, 360),
            'N' => array(0, 11.25),
            'NNE' => array(11.25, 33.75),
            'NE' => array(33.75, 56.25),
            'ENE' => array(56.25, 78.75),
            'E' => array(78.75, 101.25),
            'ESE' => array(101.25, 123.75),
            'SE' => array(123.75, 146.25),
            'SSE' => array(146.25, 168.75),
            'S' => array(168.75, 191.25),
            'SSW' => array(191.25, 213.75),
            'SW' => array(213.75, 236.25),
            'WSW' => array(236.25, 258.75),
            'W' => array(258.75, 281.25),
            'WNW' => array(281.25, 303.75),
            'NW' => array(303.75, 326.25),
            'NNW' => array(326.25, 348.75)
        );
        foreach ($cardinalDirections as $dir => $angles) {
            if ($deg >= $angles[0] && $deg < $angles[1]) {
                $cardinal = $dir;
            }
        }
        return $cardinal;
    }
}
