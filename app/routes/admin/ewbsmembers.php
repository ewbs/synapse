<?php
Route::group(['prefix' => 'ewbsmembers'], function() {
	Route::get(''                            ,['as'=>'ewbsmembersGetIndex'       ,'uses'=>'EwbsMembersController@getIndex']);
	Route::get('data'                        ,['as'=>'ewbsmembersGetData'        ,'uses'=>'EwbsMembersController@getData']);
	Route::get('create'                      ,['as'=>'ewbsmembersGetCreate'      ,'uses'=>'EwbsMembersController@getCreate']);
	Route::post('create'                     ,['as'=>'ewbsmembersPostCreate'     ,'uses'=>'EwbsMembersController@postCreate',  'before' => 'csrf']);
	Route::get('{ewbs_member}/edit'          ,['as'=>'ewbsmembersGetEdit'        ,'uses'=>'EwbsMembersController@getEdit']);
	Route::post('{ewbs_member}/edit'         ,['as'=>'ewbsmembersPostEdit'       ,'uses'=>'EwbsMembersController@postEdit',    'before' => 'csrf']);
	Route::get('{ewbs_member}/delete'        ,['as'=>'ewbsmembersGetDelete'      ,'uses'=>'EwbsMembersController@getDelete']);
	Route::post('{ewbs_member}/delete'       ,['as'=>'ewbsmembersPostDelete'     ,'uses'=>'EwbsMembersController@postDelete',  'before' => 'csrf']);
	Route::get('trash'                       ,['as'=>'ewbsmembersGetTrash'       ,'uses'=>'EwbsMembersController@getTrash']);
	Route::get('datatrash'                   ,['as'=>'ewbsmembersGetDataTrash'   ,'uses'=>'EwbsMembersController@getDataTrash']);
	Route::get('{ewbs_member_wt}/restore'    ,['as'=>'ewbsmembersGetRestore'     ,'uses'=>'EwbsMembersController@getRestore']);
	Route::post('{ewbs_member_wt}/restore'   ,['as'=>'ewbsmembersPostRestore'    ,'uses'=>'EwbsMembersController@postRestore', 'before' => 'csrf']);
});