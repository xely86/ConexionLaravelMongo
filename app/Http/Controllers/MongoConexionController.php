<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\MongoConexion;

class MongoConexionController extends Controller {

	public function index(){
		$result = MongoConexion::with('categoria')->get();

		// $distancia = $this->distanciaGeodesica( 19.379727, -99.115568, 19.380233, -99.122391  );
		
		return response()->json([ "data" => $result ]);
	}

	private function distanciaGeodesica($lat1, $long1, $lat2, $long2){
		$degtorad = 0.01745329; 
		$radtodeg = 57.29577951; 

		$dlong = ($long1 - $long2); 
		$dvalue = (sin($lat1 * $degtorad) * sin($lat2 * $degtorad)) 
		+ (cos($lat1 * $degtorad) * cos($lat2 * $degtorad) 
		* cos($dlong * $degtorad)); 

		$dd = acos($dvalue) * $radtodeg; 

		$miles = ($dd * 69.16); 
		$km = ($dd * 111.302); 

		return $km; 
	}

}
