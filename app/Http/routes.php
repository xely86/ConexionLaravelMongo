<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group( [ 'prefix' => 'api/mobile/v1' ], function(){
	// veterinarias
	Route::get( 'veterinaria', 'VeterinariaController@index' );
	Route::get( 'services/{id}', 'VeterinariaController@show' );

	// Categorias
	Route::get( 'services', 'CategoriaController@index' );

	// Carriers
	Route::get( 'carrier/{id}', 'CarrierController@show' );

});

Route::group( [ 'prefix' => 'api/cms/v1' ], function(){
	// veterinarias
	Route::get( 'veterinaria', 'VeterinariaController@index' );
	Route::get( 'veterinaria/{id}', 'VeterinariaController@show' );

	// Categorias
	Route::get( 'categoria', 'CategoriaController@index' );

});
