<?php
// Catalogues de formulaires
Route::group(['prefix' => 'eforms'], function() {
	
	// Catalogue des Eforms
	Route::group(['prefix' => 'documented'], function() {
		Route::get (''         ,['as'=>'eformsGetIndex'    ,'uses'=>'EformController@getIndex']);
		Route::get ('data'     ,['as'=>'eformsGetData'     ,'uses'=>'EformController@getData']);
		Route::get ('create'   ,['as'=>'eformsGetCreate'   ,'uses'=>'EformController@getCreate']);
		Route::post('create'   ,['as'=>'eformsPostCreate'  ,'uses'=>'EformController@postCreate' ,'before' => 'csrf']);
		
		// Détail d'un Eform
		Route::group(['prefix' => '{eform}'], function() {
			Route::get ('view'        ,['as'=>'eformsGetView'            ,'uses'=>'EformController@getView']);
			Route::get ('edit'        ,['as'=>'eformsGetEdit'            ,'uses'=>'EformController@getEdit']);
			Route::post('edit'        ,['as'=>'eformsPostEdit'           ,'uses'=>'EformController@postEdit'          ,'before' => 'csrf']);
			Route::get ('delete'      ,['as'=>'eformsGetDelete'          ,'uses'=>'EformController@getDelete']);
			Route::post('delete'      ,['as'=>'eformsPostDelete'         ,'uses'=>'EformController@postDelete'        ,'before' => 'csrf']);
			Route::get ('revisions'   ,['as'=>'eformsRevisionsGetData'   ,'uses'=>'EformController@getRevisionsData']);
			
			// Actions liées à un Eform
			Route::group(['prefix' => 'actions'], function() {
				Route::get (''         ,['as'=>'eformsActionsGetIndex'     ,'uses'=>'EformController@actionsGetIndex']);
				Route::get ('data'     ,['as'=>'eformsActionsGetData'      ,'uses'=>'EformController@actionsGetData']);
				Route::get ('create'   ,['as'=>'eformsActionsGetCreate'    ,'uses'=>'EformController@actionsGetCreate']);
				Route::post('create'   ,['as'=>'eformsActionsPostCreate'   ,'uses'=>'EformController@actionsPostCreate' ,'before' => 'csrf']);
				
				Route::group(['prefix' => '{ewbs_action}'], function() {
					Route::get ('edit'      ,['as'=>'eformsActionsGetEdit'      ,'uses'=>'EformController@actionsGetEdit']);
					Route::post('edit'      ,['as'=>'eformsActionsPostEdit'     ,'uses'=>'EformController@actionsPostEdit'   ,'before' => 'csrf']);
					Route::get ('delete'    ,['as'=>'eformsActionsGetDelete'    ,'uses'=>'EformController@actionsGetDelete']);
					Route::post('delete'    ,['as'=>'eformsActionsPostDelete'   ,'uses'=>'EformController@actionsPostDelete' ,'before' => 'csrf']);
						
					// Révisions d'une action liée à un Eform
					Route::group(['prefix' => 'history'], function() {
						Route::get (''       ,['as'=>'eformsActionsGetHistory'       ,'uses'=>'EformController@actionsGetHistory']);
						Route::get ('data'   ,['as'=>'eformsActionsGetHistoryData'   ,'uses'=>'EformController@actionsGetHistoryData']);
						
						// Destruction d'une révision d'une action liée à un Eform
						Route::group(['prefix' => '{ewbs_action_revision_wt}/destroy'], function() {
							Route::get ('',   ['as'=>'eformsActionsGetDestroy'    ,'uses'=>'EformController@actionsGetDestroy']);
							Route::post('',   ['as'=>'eformsActionsPostDestroy'   ,'uses'=>'EformController@actionsPostDestroy' ,'before' => 'csrf']);
						});
					});
				});
			});
		});
		
		// Corbeille des Eforms
		Route::group(['prefix' => 'trash'], function() {
			Route::get (''      ,['as'=>'eformsGetTrash'       ,'uses'=>'EformController@getTrash']);
			Route::get ('data'  ,['as'=>'eformsGetDataTrash'   ,'uses'=>'EformController@getDataTrash']);
			
			// Restauration d'un Eform
			Route::group(['prefix' => '{eform_wt}'], function() {
				Route::get ('restore'   ,['as'=>'eformsGetRestore'    ,'uses'=>'EformController@getRestore']);
				Route::post('restore'   ,['as'=>'eformsPostRestore'   ,'uses'=>'EformController@postRestore' ,'before' => 'csrf']);
			});
		});
	});
	
	// Forms non documentés (NostraForms donc)
	Route::group(['prefix' => 'undocumented'], function() {
		Route::get (''            ,['as'=>'eformsUndocumentedGetIndex'   ,'uses'=>'EformController@undocumentedGetIndex']);
		Route::get ('data'        ,['as'=>'eformsUndocumentedGetData'    ,'uses'=>'EformController@undocumentedGetData']);
		
		// Détail d'un NostraForm
		Route::group(['prefix' => '{damus_form}'], function() {
			Route::get ('view'     ,['as'=>'eformsUndocumentedGetView'   ,'uses'=>'EformController@undocumentedGetView']);
			Route::get ('create'   ,['as'=>'eformsGetCreateFromDamus'    ,'uses'=>'EformController@undocumentedGetCreate']);
			Route::post('create'   ,['as'=>'eformsPostCreateFromDamus'   ,'uses'=>'EformController@undocumentedPostCreate']);
			Route::post('createvalidation'   ,['as'=>'eformsPostCreateFromDamusValidation'   ,'uses'=>'EformController@undocumentedPostCreateValidation']);
		});
	});
});