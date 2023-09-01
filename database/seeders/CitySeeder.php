<?php

namespace Database\Seeders;

use App\Component\Traits\Weather;
use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CitySeeder extends Seeder
{
    use Weather;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        City::insert([
            [
                "country_id"    => 224,
                "name"          => "Abu Dhabi",
                "lat"           => 24.4539,
                "lon"           => 54.3773
            ],
            [
                "country_id"    => 224,
                "name"          => "Dubai",
                "lat"           => 25.2048,
                "lon"           => 55.2708
            ],
            [
                "country_id"    => 224,
                "name"          => "Sharjah",
                "lat"           => 25.3570,
                "lon"           => 55.4035
            ],
            [
                "country_id"    => 225,
                "name"          => "London",
                "lat"           => 0.1276,
                "lon"           => 51.5072
            ],
            [
                "country_id"    => 226,
                "name"          => "New York",
                "lat"           => 40.7128,
                "lon"           => 74.0060
            ],
            [
                "country_id"    => 107,
                "name"          => "Tokyo",
                "lat"           => 35.6762,
                "lon"           => 139.6503
            ],
        ]);
        $this->verifyGeoPoint();
    }

    protected function verifyGeoPoint(){
        $cities = City::all();
        foreach($cities as $city){
            $response = $this->getGeoData($city);
            if( is_array($response) ){
                $city->lat = $response[0]->lat;
                $city->lon = $response[0]->lon;
                $city->save();
                
            }else{
                Log::info(json_encode($response));
                $city->delete();
            }
        }
    }
}
