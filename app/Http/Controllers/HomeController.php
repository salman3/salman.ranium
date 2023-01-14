<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;

class HomeController extends Controller
{
    public function index(Request $request){

        $method = $request->method();
        if ($request->isMethod('post')) {
            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $end_date_range = Carbon::parse($start_date)->addDays(7);
            $chech_start_date = Carbon::parse($start_date);
            $chech_end_date = Carbon::parse($end_date);
            if ($chech_start_date->greaterThan($chech_end_date)) { 
                $error = "Start date is greater than end date.";
            }
            if (!$chech_end_date->lessThan($end_date_range)) {
                $error = "Please check asteroids data in any 7 days range.";
            }
            if(!empty($error)) {
                return redirect()->route('index')->with('error',$error);
            }
        }else{
            $start_date = '2015-09-07';
            $end_date = '2015-09-13';
        }
        
        $url = "https://api.nasa.gov/neo/rest/v1/feed?start_date=".$start_date."&end_date=".$end_date."&api_key=P9LeaTicEw1cxqZfDJmlbAOdo031RVO8kTTd212Z";

        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_URL => $url,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_RETURNTRANSFER => true  
        ));
        $response = curl_exec($ch);
        curl_close($ch);
            
        $response = json_decode($response, true);

        foreach ( (array) $response['near_earth_objects'] as $key => $value) {
            $date[$key] = $value;
            $asteroidsCountByDate[$key] = count($value);
            foreach ($date[$key] as $dateWiseDate) {
                $dateWiseDataArr[] = $dateWiseDate;
            }
        }

        foreach ($dateWiseDataArr as $data) {
            $asteroidId = $data['id'];
            $asteroidName[] =$data['name'];
            foreach ($data['estimated_diameter'] as $edKey => $value) {
                if ($edKey == 'kilometers') {
                    foreach ($value as $edmKey => $newValue) {
                        if ($edmKey == 'estimated_diameter_min') {
                            $diameterKm[] = $newValue;
                        }
                    }
                }
            }

            foreach ($data['close_approach_data'] as $specification) {
                foreach ($specification['relative_velocity'] as $vkey => $value) {
                    if ($vkey == 'kilometers_per_hour') {
                        $velocityKmph[$asteroidId] = $value;
                    }
                }
                foreach ($specification['miss_distance'] as $mdKey => $value) {
                    if ($mdKey == 'kilometers') {
                        $distanceKm[$asteroidId] = $value;
                    }
                }
            }
        }

        foreach (array_keys($dateWiseDataArr) as $key => $value) {
            $dateWiseDataArr[$value] = count($dateWiseDataArr[$value]);
        }

        arsort($velocityKmph);
        $fastestAseroid = reset($velocityKmph);
        $fastestAseroidId = key($velocityKmph);

        asort($distanceKm);
        $closestAseroid = reset($distanceKm);
        $closestAseroidId = key($distanceKm);

        $diameterKm = array_filter($diameterKm);
        if(count($diameterKm)) {
            $averageSizeOfAstroids = array_sum($diameterKm)/count($diameterKm);
        }
        
        $asteroidsCountByDateKeys = array_keys($asteroidsCountByDate);
        $asteroidsCountByDatevalues = array_values($asteroidsCountByDate);

        return view('index', compact('velocityKmph', 'distanceKm', 'fastestAseroid', 'fastestAseroidId', 'closestAseroid', 'closestAseroidId','asteroidsCountByDateKeys','asteroidsCountByDatevalues','averageSizeOfAstroids'));

    }
}
