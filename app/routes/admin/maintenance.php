<?php

Route::group(['prefix' => 'maintenance'], function() {
	
	Route::group(['prefix' => 'queryrunner'], function() {
		Route::get ('',['as'=>'queryrunnerGetIndex'   ,'uses'=>'MaintenanceController@queryrunnerGetIndex']);
		Route::post('',['as'=>'queryrunnerPostIndex'  ,'uses'=>'MaintenanceController@queryrunnerPostIndex' ,'before' => 'csrf']);
	});
});