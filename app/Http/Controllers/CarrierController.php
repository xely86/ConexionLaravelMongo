<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Entities\Carrier;

class CarrierController extends Controller {
    
    private $entity;
    
    public function __construct( Carrier $carrier ) {
        $this->entity = $carrier;
    }

    public function show( Request $request, $id ){
        $result = $this->entity->find( $id );
        $result->setHidden([ 'profile', 'device', 'assigned' ]);
        
        return response()->json([ "data" => $result ], 200);
    }
    
}
