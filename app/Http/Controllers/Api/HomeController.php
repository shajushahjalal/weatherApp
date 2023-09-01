<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\Weather;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Get Available Country List Where Cities are Exists
     */
    public function countryList(Request $request){  
       try{
            $countries = Country::join("cities", "cities.country_id", "=", "countries.id")
                ->select("countries.*")
                ->orderBy("name", "ASC")
                ->groupBy("name")
                ->get();
            return response([
                "status"    => true,
                "countries" => $countries,
            ], 200);
        }catch(Exception $e){
            return response([
                "status"    => false,
                "message"   => $e->getMessage(),
            ], 500);
        }
    }

    public function cityList(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                "country_id"    => ["required", "exists:countries,id"]
            ]);
            if($validator->fails()){
                return response([
                    "status"    => false,
                    "message" => $validator->errors()->first(),
                ], 400);
            }

            $cities = City::where("country_id", $request->country_id)
                ->select("cities.*")
                ->orderBy("cities.name", "ASC")
                ->get();

            return response([
                "status"    => true,
                "cities"    => $cities,
            ], 200);
        }catch(Exception $e){
            return response([
                "status"    => false,
                "message"   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * get Weither Details
     */
    public function weatherdetails(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                "city_id"    => ["nullable", "exists:cities,id"],
                "country_id" => ["nullable", "exists:countries,id"]
            ]);
            if($validator->fails()){
                return response([
                    "status"    => false,
                    "message" => $validator->errors()->first(),
                ], 400);
            }

            $qyery = Weather::join("cities", "cities.id", "=", "weather.city_id")
                ->join("countries", "countries.id", "cities.country_id");
                if( !empty($request->city_id) ){
                   $qyery->where("city_id", $request->city_id);
                }
                if( !empty($request->country_id) ){
                    $qyery->where("cities.country_id", $request->country_id);
                }
                $weithers = $qyery->select("weather.*", "cities.name as city_name", "countries.name as country")
                    ->orderBy("country", "ASC")
                    ->orderBy("id", "DESC")
                    ->paginate(25);

            return response([
                "status"    => true,
                "weithers"  => $weithers,
            ], 200);
            
        }catch(Exception $e){
            return response([
                "status"    => false,
                "message"   => $e->getMessage(),
            ], 500);
        }
    }
}
