<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
//use Illuminate\Database\Eloquent\Model;

class Categoria extends Eloquent {

    protected $connection = 'mongodb';
    protected $collection = 'category';

    public function veterinarias() {
		return $this->hasMany( 'App\MongoConexion' );
	}
}
