<?php
Route::group(['prefix' => 'jobs'], function() {
	Route::group(['prefix' => 'queue'], function() {
		Route::get(''                               ,['as'=>'jobsGetIndex'       ,'uses'=>'JobsController@getIndex']);
		Route::get('data'                           ,['as'=>'jobsGetData'        ,'uses'=>'JobsController@getData']);
		Route::get('view/{job}'                     ,['as'=>'jobsGetView'        ,'uses'=>'JobsController@getView']);
	});
	Route::group(['prefix' => 'failed'], function() {
		Route::get(''                               ,['as'=>'failedjobsGetIndex'       ,'uses'=>'FailedJobsController@getIndex']);
		Route::get('data'                           ,['as'=>'failedjobsGetData'        ,'uses'=>'FailedJobsController@getData']);
		Route::get('view/{failedjob}'               ,['as'=>'failedjobsGetView'        ,'uses'=>'FailedJobsController@getView']);
		Route::get('retry/{failedjob}'              ,['as'=>'failedjobsGetRetry'       ,'uses'=>'FailedJobsController@getRetry']);
		Route::post('retry/{failedjob}'             ,['as'=>'failedjobsPostRetry'      ,'uses'=>'FailedJobsController@postRetry'      ,'before' => 'csrf']);
	});
});