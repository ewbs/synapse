<?php
Route::group(['prefix' => 'jobs'], function() {
	Route::group(['prefix' => 'queue'], function() {
		Route::get(''                               ,['as'=>'jobsGetIndex'       ,'uses'=>'JobsController@getIndex']);
		Route::get('data'                           ,['as'=>'jobsGetData'        ,'uses'=>'JobsController@getData']);
		Route::get('view/{job}'                     ,['as'=>'jobsGetView'        ,'uses'=>'JobsController@getView']);
	});
	Route::group(['prefix' => 'failed'], function() {
		Route::get(''                               ,['as'=>'failedjobsGetIndex'       ,'uses'=>'JobsController@getFailedIndex']);
		Route::get('data'                           ,['as'=>'failedjobsGetData'        ,'uses'=>'JobsController@getFailedData']);
		Route::get('view/{failedjob}'               ,['as'=>'failedjobsGetView'        ,'uses'=>'JobsController@getFailedView']);
		Route::get('retry/{failedjob}'              ,['as'=>'failedjobsGetRetry'       ,'uses'=>'JobsController@getFailedRetry']);
		Route::post('retry/{failedjob}'             ,['as'=>'failedjobsPostRetry'      ,'uses'=>'JobsController@postFailedRetry'      ,'before' => 'csrf']);
	});
});