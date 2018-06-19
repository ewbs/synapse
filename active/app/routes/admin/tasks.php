<?php
Route::group(['prefix' => 'tasks'], function() {
	Route::get(''                    ,['as'=>'tasksGetIndex'      ,'uses'=>'TaskController@getIndex']);
	Route::get('data'                ,['as'=>'tasksGetData'       ,'uses'=>'TaskController@getData']);
	Route::get('create'              ,['as'=>'tasksGetCreate'     ,'uses'=>'TaskController@getCreate']);
	Route::post('create'             ,['as'=>'tasksPostCreate'    ,'uses'=>'TaskController@postCreate'    ,'before' => 'csrf']);
	Route::get('{task}/edit'         ,['as'=>'tasksGetEdit'       ,'uses'=>'TaskController@getEdit']);
	Route::post('{task}/edit'        ,['as'=>'tasksPostEdit'      ,'uses'=>'TaskController@postEdit'      ,'before' => 'csrf']);
	Route::get('{task}/delete'       ,['as'=>'tasksGetDelete'     ,'uses'=>'TaskController@getDelete']);
	Route::post('{task}/delete'      ,['as'=>'tasksPostDelete'    ,'uses'=>'TaskController@postDelete'    ,'before' => 'csrf']);
	Route::get('trash'               ,['as'=>'tasksGetTrash'      ,'uses'=>'TaskController@getTrash']);
	Route::get('datatrash'           ,['as'=>'tasksGetDataTrash'  ,'uses'=>'TaskController@getDataTrash']);
	Route::get('{task_wt}/restore'   ,['as'=>'tasksGetRestore'    ,'uses'=>'TaskController@getRestore']);
	Route::post('{task_wt}/restore'  ,['as'=>'tasksPostRestore'   ,'uses'=>'TaskController@postRestore'   ,'before' => 'csrf']);
});