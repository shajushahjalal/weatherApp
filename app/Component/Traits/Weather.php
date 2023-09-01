<?php

namespace App\Component\Traits;

use App\Models\City;
use App\Models\Weather as ModelsWeather;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\isJson;

trait Weather{

    private $kelvin     = 273.15;
    private $api_key    = "4c7f1f68689243332f5672f3f5d973e0";
    private $api_url    = "https://api.openweathermap.org/data/2.5/weather";
    private $weather_arr= [];
    private $index      = 0; 


    

    /**
     * Get Weather Information
     */
    protected function fetchWeatherInformation(){
        $cities = City::orderBy("name", "ASC")->get();

        foreach($cities as $city){
            $this->getDataFromAPi($city);
        }
        
        ModelsWeather::insert($this->weather_arr);
    }

    /**
     * Get Data From Weither API
     */
    protected function getDataFromAPi($city){
        $api_url = $this->api_url . "?appid=" . $this->api_key . "&lat=" . $city->lat . "&lon=" . $city->lon;
        $response = Http::post($api_url);

        if( $response->ok() ){
            $data = json_decode($response->body());
            $this->prepareWeatherInfoArr($city, $data);
        }else{
            Log::info("Failed To Get Data From ".$city->name);
        }
    }

    /**
     * Get Geo Data
     */
    protected function getGeoData($city){
        $api_url = "http://api.openweathermap.org/geo/1.0/direct?q=$city->name&appid=".$this->api_key;
        $response = Http::get($api_url);
        return json_decode($response->body());
    }

    /**
     * Prepare Weither Info
     */
    public function prepareWeatherInfoArr($city, $data){
        $this->weather_arr[$this->index++] = [
            "city_id"           => $city->id,
            "coordinate"        => isset($data->coord->lat) ?  json_encode(["lat" => $data->coord->lat, "lon" => $data->coord->lat]) : null,
            "weather_condition" => $this->getWeatherCondition($data->weather),
            "temp_celsius"      => isset($data->main->temp) ? ($data->main->temp - $this->kelvin) : null,
            "temp_feels"        => isset($data->main->feels_like) ? ($data->main->feels_like - $this->kelvin) : null,
            "humidity"          => $data->main->humidity ?? null,
            "wind_speed"        => isset($data->wind->speed) ? ( number_format(($data->wind->speed * 3.6), 2, ".", "") ) : null,
            "created_at"        => now(),
            "updated_at"        => now(),
        ];
    }

    protected function getWeatherCondition($weather){
        $w_condition = "";
        foreach($weather as $_weather){
            $w_condition .= $_weather->description ?? ($_weather->name ?? "Not Found"). ", ";
        }
        trim($w_condition, ",");
        return $w_condition;
    }

}