<?php

// Catalogue des démarches
Route::group(['prefix' => 'demarches'], function() {
	Route::get(''                          ,['as'=>'demarchesGetIndex'              ,'uses'=>'DemarcheController@getIndex']);
	Route::get ('create'   				 ,['as'=>'demarchesGetCreate_'   		  ,'uses'=>'DemarcheController@getCreate_']);
	Route::post('create'   				 ,['as'=>'demarchesPostCreate'  		  ,'uses'=>'DemarcheController@postCreate_' ,'before' => 'csrf']);
	Route::get('data'                      ,['as'=>'demarchesGetData'               ,'uses'=>'DemarcheController@getData']);
	Route::get('filtered-data'             ,['as'=>'demarchesGetFilteredData'       ,'uses'=>'DemarcheController@getDataFiltered']);
	Route::get('filteredcharges-data'      ,['as'=>'demarchesGetFilteredChargesData','uses'=>'DemarcheController@getDataFilteredCharges']);
	Route::get('datahtml'                  ,['as'=>'demarchesGetDataHtml'           ,'uses'=>'DemarcheController@getDataHtml']);
	Route::get('{damus_demarcheId}/create' ,['as'=>'demarchesGetCreate'             ,'uses'=>'DemarcheController@getCreateDemarcheNostra']);
	Route::post('export'                   ,['as'=>'demarchesPostExport'            ,'uses'=>'DemarcheController@PostExport']);
	Route::get('trash'                     ,['as'=>'demarchesGetTrash'              ,'uses'=>'DemarcheController@getTrash']);
	Route::get('datatrash'                 ,['as'=>'demarchesGetDataTrash'          ,'uses'=>'DemarcheController@getDataTrash']);
	Route::get('{demarche}/integrate-forms-to-synapse' ,['as'=>'demarchesIntegrateFormsNostraToSynapse' ,'uses'=>'DemarcheController@integrateFormsNostraToSynapse']);
	Route::post('{demarche}/integrate-forms-to-synapse/validation' ,['as'=>'demarchesIntegrateFormsNostraToSynapsePost' ,'uses'=>'DemarcheController@integrateFormsNostraToSynapsePost']);


	// Détail d'une démarche
	Route::group(['prefix' => '{demarche}'], function() {
		
		Route::get ('view'       ,['as'=>'demarchesGetView'       ,'uses'=>'DemarcheController@getView']);
		Route::get ('edit'       ,['as'=>'demarchesGetEdit'       ,'uses'=>'DemarcheController@getEdit']);
		Route::post('edit'       ,['as'=>'demarchesPostEdit'      ,'uses'=>'DemarcheController@postEdit'      ,'before' => 'csrf']);
		Route::get ('delete'      ,['as'=>'demarchesGetDelete'    ,'uses'=>'DemarcheController@getDelete']);
		Route::post('delete'      ,['as'=>'demarchesPostDelete'   ,'uses'=>'DemarcheController@postDelete'        ,'before' => 'csrf']);

		// Traitement du SCM
		Route::group(['prefix' => 'scm'], function() {
			// Download du SCM
			Route::group(['prefix' => 'download'], function() {
				Route::get(''   ,['as'=>'demarchesGetDownload'   ,'uses'=>'DemarcheController@scmDownloadGetIndex']);
				Route::group(['prefix' => '{demarche_scm}'], function() {
					Route::get (''         ,['as'=>'demarchesGetSCMDownload'          ,'uses'=>'DemarcheController@scmDownloadGetFromXLS']);
					Route::get ('delete'   ,['as'=>'demarchesGetDeleteSCMDownload'    ,'uses'=>'DemarcheController@scmDownloadGetDelete']);
					Route::post('delete'   ,['as'=>'demarchesPostDeleteSCMDownload'   ,'uses'=>'DemarcheController@scmDownloadPostDelete'  ,'before' => 'csrf']);
				});
			});
			
			// Upload du SCM
			Route::group(['prefix' => 'upload'], function() {
				Route::get (''         ,['as'=>'demarchesScmUploadGetFile'      ,'uses'=>'DemarcheController@scmUploadGetFile']);
				Route::post(''         ,['as'=>'demarchesScmUploadPostFile'     ,'uses'=>'DemarcheController@scmUploadPostFile'    ,'before' => 'csrf']);
				Route::post('process'  ,['as'=>'demarchesScmUploadPostProcess'  ,'uses'=>'DemarcheController@scmUploadPostProcess' ,'before' => 'csrf']);
			});
		});
		
		// Composants d'une démarche
		Route::group(['prefix' => 'components'], function() {
			Route::get ('/'     ,['as'=>'demarchesGetComponents',   'uses'=>'DemarcheController@getComponents']);
			Route::post('gains' ,['as'=>'demarchesPostGains'        ,'uses'=>'DemarcheController@postGains'     ,'before' => 'csrf']);
			
			// Eforms liés à une démarche
			Route::group(['prefix' => 'eforms'], function() {
				Route::get ('data'     ,['as'=>'demarchesEformsGetData'      ,'uses'=>'DemarcheController@eformsGetData']);
				Route::get ('create'   ,['as'=>'demarchesEformsGetCreate'    ,'uses'=>'DemarcheController@eformsGetCreate']);
				Route::post('create'   ,['as'=>'demarchesEformsPostCreate'   ,'uses'=>'DemarcheController@eformsPostCreate' ,'before' => 'csrf']);
					
				// Détails d'un eform lié à une démarche
				Route::group(['prefix' => '{demarche_eform}'], function() {
					Route::get ('delete'   ,['as'=>'demarchesEformsGetDelete'    ,'uses'=>'DemarcheController@eformsGetDelete']);
					Route::post('delete'   ,['as'=>'demarchesEformsPostDelete'   ,'uses'=>'DemarcheController@eformsPostDelete' ,'before' => 'csrf']);
				});
			});
			
			// Pièces liées à une démarche
			Route::group(['prefix' => 'demarches_pieces'], function() {
				Route::get ('warning'  ,['as'=>'demarchesPiecesGetWarning'   ,'uses'=>'DemarcheController@piecesGetWarning']);
				Route::get ('data'     ,['as'=>'demarchesPiecesGetData'      ,'uses'=>'DemarcheController@piecesGetData']);
				Route::get ('create'   ,['as'=>'demarchesPiecesGetCreate'    ,'uses'=>'DemarcheController@piecesGetCreate']);
				Route::post('create'   ,['as'=>'demarchesPiecesPostCreate'   ,'uses'=>'DemarcheController@piecesPostCreate'   ,'before'=> 'csrf']);
				
				// Détails d'une pièce liée à une démarche
				Route::group(['prefix' => '{demarche_piece}'], function() {
					Route::get ('edit'     ,['as'=>'demarchesPiecesGetEdit'      ,'uses'=>'DemarcheController@piecesGetEdit']);
					Route::get ('delete'   ,['as'=>'demarchesPiecesGetDelete'    ,'uses'=>'DemarcheController@piecesGetDelete']);
					Route::post('delete'   ,['as'=>'demarchesPiecesPostDelete'   ,'uses'=>'DemarcheController@piecesPostDelete' ,'before' => 'csrf']);
					Route::get ('history'  ,['as'=>'demarchesPiecesGetHistory'   ,'uses'=>'DemarcheController@piecesGetHistory']);
				});
				
				// Détails d'une pièce liée à une démarche avec considération des éléments soft-deletés
				Route::group(['prefix' => '{demarche_piece_wt}'], function() {
					Route::post('edit'      ,['as'=>'demarchesPiecesPostEdit'      ,'uses'=>'DemarcheController@piecesPostEdit'    ,'before'=> 'csrf']);
					
				});
			});
			
			// Historique d'une pièce liée à une démarche
			Route::group(['prefix' => 'pieces/{piece}/history'], function() {
					
				Route::get ('data'   ,['as'=>'demarchesPiecesGetHistoryData'   ,'uses'=>'DemarcheController@piecesGetHistoryData']);
					
				// Destruction de la révision d'une pièce liée à une démarche
				Route::group(['prefix' => '{demarche_piece_revision_wt}'], function() {
					Route::get ('destroy'   ,['as'=>'demarchesPiecesGetDestroy'    ,'uses'=>'DemarcheController@piecesGetDestroy']);
					Route::post('destroy'   ,['as'=>'demarchesPiecesPostDestroy'   ,'uses'=>'DemarcheController@piecesPostDestroy' ,'before' => 'csrf']);
				});
			});
			
			// Tâches liées à une démarche
			Route::group(['prefix' => 'demarches_tasks'], function() {
				Route::get ('data'     ,['as'=>'demarchesTasksGetData'      ,'uses'=>'DemarcheController@tasksGetData']);
				Route::get ('create'   ,['as'=>'demarchesTasksGetCreate'    ,'uses'=>'DemarcheController@tasksGetCreate']);
				Route::post('create'   ,['as'=>'demarchesTasksPostCreate'   ,'uses'=>'DemarcheController@tasksPostCreate' ,'before'=> 'csrf']);
				
				// Détails d'une tâche liée à une démarche
				Route::group(['prefix' => '{demarche_task}'], function() {
					Route::get ('edit'     ,['as'=>'demarchesTasksGetEdit'      ,'uses'=>'DemarcheController@tasksGetEdit']);
					Route::get ('delete'   ,['as'=>'demarchesTasksGetDelete'    ,'uses'=>'DemarcheController@tasksGetDelete']);
					Route::post('delete'   ,['as'=>'demarchesTasksPostDelete'   ,'uses'=>'DemarcheController@tasksPostDelete' ,'before' => 'csrf']);
					Route::get ('history'  ,['as'=>'demarchesTasksGetHistory'   ,'uses'=>'DemarcheController@tasksGetHistory']);
				});
				
				// Détails d'une tâche liée à une démarche avec considération des éléments soft-deletés
				Route::group(['prefix' => '{demarche_task_wt}'], function() {
					Route::post('edit'      ,['as'=>'demarchesTasksPostEdit'      ,'uses'=>'DemarcheController@tasksPostEdit'    ,'before'=> 'csrf']);
				});
			});
			
			// Historique d'une tâche liée à une démarche
			Route::group(['prefix' => 'tasks/{task}/history'], function() {
				Route::get ('data'   ,['as'=>'demarchesTasksGetHistoryData'   ,'uses'=>'DemarcheController@tasksGetHistoryData']);
					
				// Destruction de la révision d'une tâche liée à une démarche
				Route::group(['prefix' => '{demarche_task_revision_wt}'], function() {
					Route::get ('destroy'   ,['as'=>'demarchesTasksGetDestroy'    ,'uses'=>'DemarcheController@tasksGetDestroy']);
					Route::post('destroy'   ,['as'=>'demarchesTasksPostDestroy'   ,'uses'=>'DemarcheController@tasksPostDestroy' ,'before' => 'csrf']);
				});
			});
		});
		
		// Actions liées à une démarche
		Route::group(['prefix' => 'actions'], function() {
			Route::get (''               ,['as'=>'demarchesActionsGetIndex'          ,'uses'=>'DemarcheController@actionsGetIndex']);
			Route::get ('data'           ,['as'=>'demarchesActionsGetData'           ,'uses'=>'DemarcheController@actionsGetData']);
			Route::get ('create'         ,['as'=>'demarchesActionsGetCreate'         ,'uses'=>'DemarcheController@actionsGetCreate']);
			Route::post('create'         ,['as'=>'demarchesActionsPostCreate'        ,'uses'=>'DemarcheController@actionsPostCreate'        ,'before' => 'csrf']);
			Route::post('/triggerupdate' ,['as'=>'demarchesActionsPostTriggerUpdate' ,'uses'=>'DemarcheController@actionsPostTriggerUpdate' ,'before' => 'csrf']);

			// Détails d'une action liée à une démarche
			Route::group(['prefix' => '{ewbs_action}'], function() {
				Route::get ('edit'     ,['as'=>'demarchesActionsGetEdit'      ,'uses'=>'DemarcheController@actionsGetEdit']);
				Route::post('edit'     ,['as'=>'demarchesActionsPostEdit'     ,'uses'=>'DemarcheController@actionsPostEdit'   ,'before' => 'csrf']);
				Route::get ('delete'   ,['as'=>'demarchesActionsGetDelete'    ,'uses'=>'DemarcheController@actionsGetDelete']);
				Route::post('delete'   ,['as'=>'demarchesActionsPostDelete'   ,'uses'=>'DemarcheController@actionsPostDelete' ,'before' => 'csrf']);
				Route::get ('history'  ,['as'=>'demarchesActionsGetHistory'   ,'uses'=>'DemarcheController@actionsGetHistory']);
			});
		});
		
		// Idées liées à une démarche
		Route::group(['prefix' => 'ideas'], function() {
			Route::get ('triggerupdate'   ,['as'=>'demarchesIdeasGetTriggerUpdate'   ,'uses'=>'DemarcheController@ideasGetTriggerUpdate']);
			Route::post('triggerupdate'   ,['as'=>'demarchesIdeasPostTriggerUpdate'  ,'uses'=>'DemarcheController@ideasPostTriggerUpdate' ,'before' => 'csrf']);
			Route::get ('link'            ,['as'=>'demarchesIdeasGetLink'            ,'uses'=>'DemarcheController@ideasGetLink']);
		});
	});
});