<?php
Route::group(['prefix' => 'administrations'], function() {
	Route::get(''                               ,['as'=>'administrationsGetIndex'       ,'uses'=>'AdministrationsController@getIndex']);
	Route::get('data'                           ,['as'=>'administrationsGetData'        ,'uses'=>'AdministrationsController@getData']);
	Route::get('create'                         ,['as'=>'administrationsGetCreate'      ,'uses'=>'AdministrationsController@getCreate']);
	Route::post('create'                        ,['as'=>'administrationsPostCreate'     ,'uses'=>'AdministrationsController@postCreate'    ,'before' => 'csrf']);
	Route::get('{administration}/edit'          ,['as'=>'administrationsGetEdit'        ,'uses'=>'AdministrationsController@getEdit']);
	Route::post('{administration}/edit'         ,['as'=>'administrationsPostEdit'       ,'uses'=>'AdministrationsController@postEdit'      ,'before' => 'csrf']);
	Route::get('{administration}/delete'        ,['as'=>'administrationsGetDelete'      ,'uses'=>'AdministrationsController@getDelete']);
	Route::post('{administration}/delete'       ,['as'=>'administrationsPostDelete'     ,'uses'=>'AdministrationsController@postDelete'    ,'before' => 'csrf']);
	Route::get('trash'                          ,['as'=>'administrationsGetTrash'       ,'uses'=>'AdministrationsController@getTrash']);
	Route::get('datatrash'                      ,['as'=>'administrationsGetDataTrash'   ,'uses'=>'AdministrationsController@getDataTrash']);
	Route::get('{administration_wt}/restore'    ,['as'=>'administrationsGetRestore'     ,'uses'=>'AdministrationsController@getRestore']);
	Route::post('{administration_wt}/restore'   ,['as'=>'administrationsPostRestore'    ,'uses'=>'AdministrationsController@postRestore'   ,'before' => 'csrf']);
});