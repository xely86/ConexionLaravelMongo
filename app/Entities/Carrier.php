<?php

namespace App\Entities;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Carrier extends Eloquent {

    protected $connection = 'mongodb';
    protected $collection = 'carriers';
}
