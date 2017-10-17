<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Entities\Veterinaria;

class VeterinariaController extends Controller {

	private $entity;

	public function __construct( Veterinaria $veterinaria ) {
		$this->entity = $veterinaria;
	}

	public function index( Request $request ){
		$result = $this->entity->get();

		return response()->json([ "data" => $result ]);
	}

	public function show( Request $request, $id ){
		$latitud = $request->lat;
		$longitud = $request->lng;

		$result = $this->entity->find( $id );
		if ( !is_null( $result ) ) {
			
			$result->setHidden(['category', 'loc', 'search']);
			
			$distanciaGeodesica = $this->distanciaGeodesica( $latitud, $longitud, $result->latitude, $result->longitude );
			$result["distance"] = array( "distance" => $distanciaGeodesica, "unit" => "km" );

			$result->name = ucwords( $result->name );
			$result->colonia = ucwords( $result->colonia );
			$result->mpio = ucfirst( $result->mpio );
			$result->address = ucfirst( $result->address );
			$result->descripcion = ucfirst( $result->descripcion );
			$result->estado = ucfirst( strtolower( $result->estado ) );

			$result->id = $result->_id;
			$result->lat= $result->latitude;
			$result->lng= $result->longitude;
			$result->services = $result->horarios;
			unset( $result->_id );
			unset( $result->latitude );
			unset( $result->longitude );
			unset( $result->horarios );
			
			return response()->json([ "data" => $result ], 200);
		}

		return response()->json([ 'error' => 'No se ha encontrado informacion para el identificador seleccionado.' ], 422);
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
