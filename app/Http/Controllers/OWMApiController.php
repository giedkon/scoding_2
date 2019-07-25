<?php

namespace App\Http\Controllers;

use App\warning_email;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\location;

class OWMApiController extends Controller
{
    function getWeather(Request $request)
    {
        // Validacija koordinačių ir el. pašto
        // TODO UI parodymas, kad neteisingas el. paštas
        $request->validate([
            'lat' => 'required',
            'lng' => 'required',
            'email' => 'email']);

        // Sukuriamas Guzzle clientas
        $client = new Client();

        // Iš OWM Api pasiemame info pagal koordinates
        // !! Placeholder API Key
        $res = $client->get('http://api.openweathermap.org/data/2.5/weather',
            ['query' => [
                'lat' => $request->input('lat'),
                'lon' => $request->input('lng'),
                'units' => 'metric',
                'appid' => '886705b4c1182eb1c69f28eb8c520e20'
            ]]);

        // Gauta informacija perverčiama į masyvus
        $resBody = $res->getBody();
        $locInfo = json_decode($resBody);


//        try {
        // Išsaugoma pasirinkta vieta
        $this->saveLocation($locInfo);
//        } catch (\Exception $e) {
//            return $e->getMessage();
//        }


        // Jei pažymėta ">10m/s warning sent to email"
        // išsaugom el. pašta.

        if($request->input('emailCheck') == true ){
            $this->saveEmail($request->email, $locInfo->id, $locInfo->name);
        }

        // Grąžiname json'ą į view

        return $resBody;
    }

    // Metodas išsaugoti el. paštą
    function saveEmail($email, $location, $location_name) {
        if (!warning_email::where('email', '=', $email)->exists()) {
            $newEmail = new warning_email();
            $newEmail->email = $email;
            $newEmail->location = $location;
            $newEmail->location_name = $location_name;
            $newEmail->save();
        }
    }

    // Metodas išsaugoti miestą su informaciją
    function saveLocation($locInfo)
    {
        if (!location::where('OWM_ID', '=', $locInfo->id)->exists()) {
            $location = new location();

            $location->OWM_ID = $locInfo->id;
            $location->name = $locInfo->name;
            $location->latest_temp = $locInfo->main->temp;
            $location->latest_speed = $locInfo->wind->speed;
            $location->latest_direction = $locInfo->wind->deg;
            $location->save();
        }
    }
}
