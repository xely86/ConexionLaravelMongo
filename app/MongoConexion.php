<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
// use Illuminate\Database\Eloquent\Model;

class MongoConexion extends Eloquent {
    
    protected $connection = 'mongodb';
    protected $collection = 'veterinarians';

    public function categoria() {
		return $this->belongsTo( Categoria::class );
	}
}
