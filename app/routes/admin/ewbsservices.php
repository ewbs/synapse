<?php
Route::group(['prefix' => 'ewbsservices'], function() {

	Route::get('', 								['as' => 'ewbsservicesGetIndex', 			'uses' => 'EwbsServicesController@getList']);
	Route::get('data', 							['as' => 'ewbsservicesGetData', 			'uses' => 'EwbsServicesController@getData']);
	Route::get('{ewbsservice}/view',			['as' => 'ewbsservicesGetView',				'uses' => 'EwbsServicesController@getView']);
	Route::get('create', 						['as' => 'ewbsservicesGetCreate', 			'uses' => 'EwbsServicesController@getCreate']);
	Route::post('create', 						['as' => 'ewbsservicesPostCreate', 			'uses' => 'EwbsServicesController@postCreate',			'before' => 'csrf']);
	Route::get('{ewbsservice}/edit', 			['as' => 'ewbsservicesGetEdit', 			'uses' => 'EwbsServicesController@getEdit']);
	Route::post('{ewbsservice}/edit',			['as' => 'ewbsservicesPostEdit', 			'uses' => 'EwbsServicesController@postEdit',			'before' => 'csrf']);
	Route::get('{ewbsservice}/delete',			['as' => 'ewbsservicesGetDelete', 			'uses' => 'EwbsServicesController@getDelete']);
	Route::post('{ewbsservice}/delete',			['as' => 'ewbsservicesPostDelete',			'uses' => 'EwbsServicesController@postDelete',			'before' => 'csrf']);
	Route::get('trash', 						['as' => 'ewbsservicesGetTrash', 			'uses' => 'EwbsServicesController@getTrash']);
	Route::get('datatrash',                     ['as' => 'ewbsservicesGetDataTrash',		'uses' => 'EwbsServicesController@getDataTrash']);
	Route::get('{ewbsservice_wt}/restore',		['as' => 'ewbsservicesGetRestore',			'uses' => 'EwbsServicesController@getRestore']);
	Route::post('{ewbsservice_wt}/restore',		['as' => 'ewbsservicesPostRestore',			'uses' => 'EwbsServicesController@postRestore',			'before' => 'csrf']);

});