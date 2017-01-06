<?php
Route::group(['prefix' => 'ewbsactions'], function() {
	Route::get(''                                       ,['as'=>'ewbsactionsGetIndex'         ,'uses'=>'EwbsActionController@getIndex']);
	Route::get('data'                                   ,['as'=>'ewbsactionsGetData'          ,'uses'=>'EwbsActionController@getData']);
	Route::get('filtered-data'                          ,['as'=>'ewbsactionsGetFilteredData'  ,'uses'=>'EwbsActionController@getFilteredData']);
	Route::get('trash'                                  ,['as'=>'ewbsactionsGetTrash'         ,'uses'=>'EwbsActionController@getTrash']);
	Route::get('datatrash'                              ,['as'=>'ewbsactionsGetDataTrash'     ,'uses'=>'EwbsActionController@getDataTrash']);
	Route::get('{ewbs_action_wt}/restore'               ,['as'=>'ewbsactionsGetRestore'       ,'uses'=>'EwbsActionController@getRestore']);
	Route::post('{ewbs_action_wt}/restore'              ,['as'=>'ewbsactionsPostRestore'      ,'uses'=>'EwbsActionController@postRestore'       ,'before' => 'csrf']);
	
	Route::group(['prefix' => '{ewbs_action}'], function() {
		Route::get ('view'     ,['as'=>'ewbsactionsGetView'          ,'uses'=>'EwbsActionController@getView']);
		Route::get ('edit'     ,['as'=>'ewbsactionsGetEdit'          ,'uses'=>'EwbsActionController@getEdit']);
		Route::post('edit'    ,['as'=>'ewbsactionsPostEdit'         ,'uses'=>'EwbsActionController@postEdit'          ,'before' => 'csrf']);
		
		Route::group(['prefix' => 'subactions'], function() {
			Route::get ('data'     ,['as'=>'ewbsactionsSubGetData'      ,'uses'=>'EwbsActionController@subactionsGetData']);
			Route::get ('create'   ,['as'=>'ewbsactionsSubGetCreate'    ,'uses'=>'EwbsActionController@subactionsGetCreate']);
			Route::post('create'   ,['as'=>'ewbsactionsSubPostCreate'   ,'uses'=>'EwbsActionController@subactionsPostCreate'   ,'before' => 'csrf']);
			
			Route::group(['prefix' => '{ewbs_subaction}'], function() {
				Route::get ('history'  ,['as'=>'ewbsactionsSubGetHistory'   ,'uses'=>'EwbsActionController@subactionsGetHistory']);
				Route::get ('edit'     ,['as'=>'ewbsactionsSubGetEdit'      ,'uses'=>'EwbsActionController@subactionsGetEdit']);
				Route::post('edit'     ,['as'=>'ewbsactionsSubPostEdit'     ,'uses'=>'EwbsActionController@subactionsPostEdit'   ,'before' => 'csrf']);
				Route::get ('delete'   ,['as'=>'ewbsactionsSubGetDelete'    ,'uses'=>'EwbsActionController@subactionsGetDelete']);
				Route::post('delete'   ,['as'=>'ewbsactionsSubPostDelete'   ,'uses'=>'EwbsActionController@subactionsPostDelete' ,'before' => 'csrf']);
			});
		});
	});
});