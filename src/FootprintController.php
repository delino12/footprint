<?php

namespace Codedreamer\Footprint;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;

class FootprintController extends Controller
{
    /*
    |-----------------------------------------
    | SHOW INDEX VIEW
    |-----------------------------------------
    */
    public function index(Request $request){
    	// body
    	return view('footprint::index');	
    }

    /*
    |-----------------------------------------
    | FETCH ALL FOOTPRINT
    |-----------------------------------------
    */
    public function fetchAll(Request $request){
    	// body
    	$footprints = new FootPrint();
    	$data       = $footprints->fetchAll($request);

    	// return response
    	return response()->json($data, 200);	
    }

    /*
    |-----------------------------------------
    | FETCH ONE FOOTPRINT
    |-----------------------------------------
    */
    public function fetchOne(Request $request, $by){
    	// body
    	$request->user_id = $by;
    	$footprints = new FootPrint();
    	$data       = $footprints->fetchOne($request);

    	// return response
    	return response()->json($data, 200);	
    }


    /*
    |-----------------------------------------
    | DELETE ALL FOOTPRINT
    |-----------------------------------------
    */
    public function deleteAll(Request $request){
    	// body
    	$footprints = new FootPrint();
    	$data       = $footprints->deleteAll($request);

    	// return response
    	return response()->json($data, 200);	
    }

}
