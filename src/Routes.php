<?php

Route::get('footprint', 			'Codedreamer\Footprint\FootprintController@index')->name('footprint_index');
Route::get('footprint/all', 		'Codedreamer\Footprint\FootprintController@fetchAll')->name('footprint_all');
Route::get('footprint/one/{id}', 	'Codedreamer\Footprint\FootprintController@fetchOne')->name('footprint_by_id');
Route::delete('footprint',			'Codedreamer\Footprint\FootprintController@deleteAll')->name('delete_footprint');