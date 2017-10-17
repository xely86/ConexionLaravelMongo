<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Entities\Categoria;
use App\Entities\Veterinaria;

class CategoriaController extends Controller {

	private $entity;
	private $veterinaria;

	public function __construct( Categoria $categoria ) {
		$this->entity = $categoria;
		$this->veterinaria = app( Veterinaria::class );
	}

	public function index( Request $request ){
		$latitud = $request->lat;
		$longitud = $request->lng;
		$distanciaKmMax = ( $request->has( 'km' ) ) ? $request->km : 20;

		$categorias = $this->entity->get();
		$veterinarias = $this->veterinaria->where( 'activo', true )->get();
		$categorias->map(function( $categoria ) use( $veterinarias, $latitud, $longitud, $distanciaKmMax ) {
			$categoria->setHidden([ 'image', '_search' ]);
			$categoria->id = $categoria->_id;
			$categoria->name = $categoria->label;
			unset( $categoria->_id );
			unset( $categoria->label );
			$categoriaId = $categoria->id;
			$categoria->services = $veterinarias->filter(function( $value, $key ) use( $categoriaId, $latitud, $longitud, $distanciaKmMax ) {
				$value->setVisible([ 'id', 'name', 'colonia', 'lng', 'lat', 'mpio', 'address', 'logo', 'cp', 'estado', 'distance' ]);
				// $value->setHidden([ 'category', 'loc', 'search', 'sociales', 'responsable', 'descripcion' ]);
				$value->id = $value->_id;
				$value->lat = $value->latitude;
				$value->lng = $value->longitude;

				$value->name = ucwords( $value->name );
				/*$value->colonia = ucwords( $value->colonia );
				$value->mpio = ucfirst( $value->mpio );
				$value->address = ucfirst( $value->address );
				$value->estado = ucfirst( strtolower( $value->estado ) ); */

				$distanciaGeodesica = $this->distanciaGeodesica( $latitud, $longitud, $value->latitude, $value->longitude );
				$value["distance"] = array( "distance" => $distanciaGeodesica, "unit" => "km" );

				return ( is_array( $value->category ) && sizeof( $value->category ) != 0 && in_array( $categoriaId, $value->category ) && $distanciaGeodesica <= $distanciaKmMax );
			})->sortBy( 'distance.distance' )->values()->all();

			return array( "category" => $categoria );
		});

		$categorias = $categorias->filter(function( $value, $key ) {
			return ( is_array( $value->services ) && sizeof( $value->services ) != 0 );
		});

		$final = array();
		foreach ( $categorias as $categoria ) {
			array_push( $final, array( "category" => $categoria ) );
		}

		return response()->json([ "data" => $final ]);
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
