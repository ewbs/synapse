<?php
Route::group(['prefix' => 'ministers'], function() {
	Route::get (''                       ,['as'=>'ministersGetIndex'       ,'uses'=>'MinistersController@getIndex']);
	Route::get ('data'                   ,['as'=>'ministersGetData'        ,'uses'=>'MinistersController@getData']);
	Route::get ('create'                 ,['as'=>'ministersGetCreate'      ,'uses'=>'MinistersController@getCreate']);
	Route::post('create'                 ,['as'=>'ministersPostCreate'     ,'uses'=>'MinistersController@postCreate'    ,'before' => 'csrf']);
	
	Route::get ('trash'                  ,['as'=>'ministersGetTrash'       ,'uses'=>'MinistersController@getTrash']);
	Route::get ('datatrash'              ,['as'=>'ministersGetDataTrash'   ,'uses'=>'MinistersController@getDataTrash']);
	Route::get ('{minister_wt}/restore'  ,['as'=>'ministersGetRestore'     ,'uses'=>'MinistersController@getRestore']);
	Route::post('{minister_wt}/restore'  ,['as'=>'ministersPostRestore'    ,'uses'=>'MinistersController@postRestore'   ,'before' => 'csrf']);
	
	Route::group(['prefix' => '{minister}'], function() {
		Route::get ('view'     ,['as'=>'ministersGetView'       ,'uses'=>'MinistersController@getView']);
		Route::get ('edit'     ,['as'=>'ministersGetEdit'       ,'uses'=>'MinistersController@getEdit']);
		Route::post('edit'     ,['as'=>'ministersPostEdit'      ,'uses'=>'MinistersController@postEdit'   ,'before' => 'csrf']);
		Route::get ('delete'   ,['as'=>'ministersGetDelete'     ,'uses'=>'MinistersController@getDelete']);
		Route::post('delete'   ,['as'=>'ministersPostDelete'    ,'uses'=>'MinistersController@postDelete' ,'before' => 'csrf']);
		
		Route::group(['prefix' => 'mandates'], function() {
			Route::get ('data'      ,['as'=>'ministersMandatesGetData'        ,'uses'=>'MinistersController@mandatesGetData']);
			Route::get ('create'    ,['as'=>'ministersMandatesGetCreate'      ,'uses'=>'MinistersController@mandatesGetCreate']);
			Route::post('create'    ,['as'=>'ministersMandatesPostCreate'     ,'uses'=>'MinistersController@mandatesPostCreate' ,'before' => 'csrf']);
			
			Route::group(['prefix' => '{mandate}'], function() {
				Route::get ('edit'      ,['as'=>'ministersMandatesGetEdit'        ,'uses'=>'MinistersController@mandatesGetEdit']);
				Route::post('edit'      ,['as'=>'ministersMandatesPostEdit'       ,'uses'=>'MinistersController@mandatesPostEdit'   ,'before' => 'csrf']);
				Route::get ('delete'    ,['as'=>'ministersMandatesGetDelete'      ,'uses'=>'MinistersController@mandatesGetDelete']);
				Route::post('delete'    ,['as'=>'ministersMandatesPostDelete'     ,'uses'=>'MinistersController@mandatesPostDelete' ,'before' => 'csrf']);
			});
		});
	});
});