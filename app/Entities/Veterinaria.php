<?php

namespace App\Entities;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Veterinaria extends Eloquent {
    
    protected $connection = 'mongodb';
    protected $collection = 'veterinarians';

}
