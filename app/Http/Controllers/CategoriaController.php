<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Categoria;

class CategoriaController extends Controller {

	public function index(){
		$result = Categoria::with('veterinarias')->get();
		
		return response()->json([ "data" => $result ]);
	}

}
