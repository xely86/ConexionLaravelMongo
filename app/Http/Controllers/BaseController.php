<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model as Entity;
use Illuminate\Support\Facades\Validator;

use Illuminate\Database\QueryException;
use Exception;

class BaseController extends Controller {

	protected $entity;
	protected $only;
	protected $rules;
    protected $data;
    protected $errors;

    public function __construct( Entity $entity ) {
    	$this->entity = $entity;
    	$this->only = [];
    	$this->rules = [];
    }

    public function index( Request $request ) {
    	$result = $this->entity->get();

    	return response()->json( $result );
    }

    public function show( Request $request, $id ) {
    	$result = $this->entity->find( $id );

    	if ( !is_null( $result ) ) {
    		return response()->json( $result );
    	}

    	return response()->json([ 'error' => 'No se ha encontrado informacion para el identificador seleccionado.' ], 422);
    }

    public function store( Request $request ) {
        try {
            $this->onStore();
            $this->data = $request->all();

            if ( is_array( $this->only ) && sizeof( $this->only ) != 0 ) {
                $this->data = $request->only( $this->only );
            }

        	if ( is_array( $this->rules ) && sizeof( $this->rules ) != 0 ) {
        		if ( !$this->isValid() ) {
                    return response()->json( $this->errors, 422 );
                }
        	}

    		$result = $this->entity->fill( $this->data );
    		$result->save();

    		return response()->json( $result, 201 );
    	} catch( QueryException $qe ) {
    		return response()->json([ 'error' => $qe->getMessage() ], 500);
    	} catch( Exception $ex ) {
    		return response()->json([ 'error' => $ex->getMessage() ], 500);
    	}
    }

    public function update( Request $request, $id ) {
        try {
            $this->onUpdate();
            $result = $this->entity->find( $id );

            if ( is_null( $result ) ) {
                return response()->json([ 'error' => 'No se ha encontrado informacion para el identificador seleccionado.' ], 422);
            }
            
            $this->data = $request->all();

            if ( is_array( $this->only ) && sizeof( $this->only ) != 0 ) {
                $this->data = $request->only( $this->only );
            }

            $this->data[ 'id' ] = ( int )$result->id;
            var_dump( $this->data );

        	if ( is_array( $this->rules ) && sizeof( $this->rules ) != 0 ) {
        		if ( !$this->isValid() ) {
                    return response()->json( $this->errors, 422 );
                }
        	}

    		$result->fill( $this->data );
    		$result->save();

    		return response()->json( $result, 200 );
    	} catch( QueryException $qe ) {
    		return response()->json([ 'error' => $qe->getMessage() ], 500);
    	} catch( Exception $ex ) {
    		return response()->json([ 'error' => $ex->getMessage() ], 500);
    	}
    }

    public function destroy( Request $request, $id ) {
    	$result = $this->entity->find( $id );

    	if ( is_null( $result ) ) {
    		return response()->json([ 'error' => 'No se ha encontrado informacion para el identificador seleccionado.' ], 422);
    	}

    	try {
    		$result->delete();

    		return response()->make( '', 204 );
    	} catch( QueryException $qe ) {
    		return response()->json([ 'error' => $qe->getMessage() ], 500);
    	} catch( Exception $ex ) {
    		return response()->json([ 'error' => $ex->getMessage() ], 500);
    	}
    }

    public function isValid() {
        $validation = Validator::make( $this->data, $this->rules );

        if ( $validation->passes() ) {
            return true;
        }

        $this->errors = $validation->messages();

        return false;
    }

    public function onStore() {
        var_dump('Dentro de onStore');
    	$this->rules = [];
    	$this->only = [];
    }

    public function onUpdate() {
    	$this->rules = [];
    	$this->only = [];
    }

}
