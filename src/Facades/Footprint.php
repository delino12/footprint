<?php

namespace Codedreamer\Footprint\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Footprint
 */
class Footprint extends Facade
{
	
	/**
	*-----------------------------------------
	* Facade services
	* @return void
	*-----------------------------------------
	*/
	protected static function getFacadeAccessor(){
		return 'codedreamer-footprint';
	}	
}
