<?php

namespace App\Entities;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Categoria extends Eloquent {

    protected $connection = 'mongodb';
    protected $collection = 'category';
}
