<?php

namespace Codedreamer\Footprint;

use Illuminate\Support\ServiceProvider;

class FootprintServiceProvider extends ServiceProvider
{
	
	/**
	*-----------------------------------------
	* Bootstrap services
	* @return void
	*-----------------------------------------
	*/
	public function boot(){
		// body
		$db_path = __DIR__ . '/migrations/';
		$asset_path = __DIR__ . '/public';
		$this->loadMigrationsFrom(__DIR__.'/migrations');
		$this->loadRoutesFrom(__DIR__.'/routes.php');
		$this->loadViewsFrom(__DIR__.'/views', 'footprint');
		$this->publishes([
			__DIR__.'/views' => base_path('resources/views/codedreamer/footprints'),
		]);
		$this->publishes([$db_path => database_path('migrations')], 'migrations');
		$this->publishes([$asset_path => public_path('codedreamer')]);
	}

	/**
	*-----------------------------------------
	* Register services
	* @return void
	*-----------------------------------------
	*/
	public function register(){
		// body
		$this->app->bind('codedreamer-footprint', function(){
			return new Footprint();
		});
	}
}
