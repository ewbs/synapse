<?php
Route::group(['prefix' => 'roles'], function() {
	Route::get(''                ,['as'=>'rolesGetIndex'     ,'uses'=>'AdminRolesController@getIndex']);
	Route::get('data'            ,['as'=>'rolesGetData'      ,'uses'=>'AdminRolesController@getData']);
	Route::get('create'          ,['as'=>'rolesGetCreate'    ,'uses'=>'AdminRolesController@getCreate']);
	Route::post('create'         ,['as'=>'rolesPostCreate'   ,'uses'=>'AdminRolesController@postCreate' ,'before' => 'csrf']);
	Route::get('{role}/edit'     ,['as'=>'rolesGetEdit'      ,'uses'=>'AdminRolesController@getEdit']);
	Route::post('{role}/edit'    ,['as'=>'rolesPostEdit'     ,'uses'=>'AdminRolesController@postEdit'   ,'before' => 'csrf']);
	Route::get('{role}/delete'   ,['as'=>'rolesGetDelete'    ,'uses'=>'AdminRolesController@getDelete']);
	Route::post('{role}/delete'  ,['as'=>'rolesPostDelete'   ,'uses'=>'AdminRolesController@postDelete' ,'before' => 'csrf']);
});