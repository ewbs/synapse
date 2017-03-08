<?php
Route::group(['prefix' => 'users'], function() {
	Route::get(''                   ,['as'=>'usersGetIndex'      ,'uses'=>'AdminUsersController@getIndex']);
	Route::get('data'               ,['as'=>'usersGetData'       ,'uses'=>'AdminUsersController@getData']);
	Route::get('create'             ,['as'=>'usersGetCreate'     ,'uses'=>'AdminUsersController@getCreate']);
	Route::post('create'            ,['as'=>'usersPostCreate'    ,'uses'=>'AdminUsersController@postCreate'    ,'before' => 'csrf']);
	Route::get('{user}/edit'        ,['as'=>'usersGetEdit'       ,'uses'=>'AdminUsersController@getEdit']);
	Route::post('{user}/edit'       ,['as'=>'usersPostEdit'      ,'uses'=>'AdminUsersController@postEdit'      ,'before' => 'csrf']);
	Route::get('{user}/delete'      ,['as'=>'usersGetDelete'     ,'uses'=>'AdminUsersController@getDelete']);
	Route::post('{user}/delete'     ,['as'=>'usersPostDelete'    ,'uses'=>'AdminUsersController@postDelete'    ,'before' => 'csrf']);
	Route::get('trash'              ,['as'=>'usersGetTrash'      ,'uses'=>'AdminUsersController@getTrash']);
	Route::get('datatrash'          ,['as'=>'usersGetDataTrash'  ,'uses'=>'AdminUsersController@getDataTrash']);
	Route::get('{user_wt}/restore'  ,['as'=>'usersGetRestore'    ,'uses'=>'AdminUsersController@getRestore']);
	Route::post('{user_wt}/restore' ,['as'=>'usersPostRestore'   ,'uses'=>'AdminUsersController@postRestore'   ,'before' => 'csrf']);
});