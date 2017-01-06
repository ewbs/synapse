<?php
Route::group(['prefix' => 'ideas'], function() {
	Route::get(''                                  ,['as'=>'ideasGetIndex'           ,'uses'=>'IdeaController@getIndex']);
	Route::get('data'                              ,['as'=>'ideasGetData'            ,'uses'=>'IdeaController@getData']);
	Route::get('filtered-data'                     ,['as'=>'ideasGetFilteredData'    ,'uses'=>'IdeaController@getDataFiltered']);
	Route::get('create'                            ,['as'=>'ideasGetCreate'          ,'uses'=>'IdeaController@getCreate']);
	Route::post('create'                           ,['as'=>'ideasPostCreate'         ,'uses'=>'IdeaController@postCreate'      ,'before' => 'csrf']);
	Route::get('{idea}/view'                       ,['as'=>'ideasGetView'            ,'uses'=>'IdeaController@getView']);
	Route::get('{idea}/edit'                       ,['as'=>'ideasGetEdit'            ,'uses'=>'IdeaController@getEdit']);
	Route::post('{idea}/edit'                      ,['as'=>'ideasPostEdit'           ,'uses'=>'IdeaController@postEdit'        ,'before' => 'csrf']);
	Route::get('{idea}/delete'                     ,['as'=>'ideasGetDelete'          ,'uses'=>'IdeaController@getDelete']);
	Route::post('{idea}/delete'                    ,['as'=>'ideasPostDelete'         ,'uses'=>'IdeaController@postDelete'      ,'before' => 'csrf']);
	Route::get('trash'                             ,['as'=>'ideasGetTrash'           ,'uses'=>'IdeaController@getTrash']);
	Route::get('datatrash'                         ,['as'=>'ideasGetDataTrash'       ,'uses'=>'IdeaController@getDataTrash']);
	Route::get('{idea_wt}/restore'                 ,['as'=>'ideasGetRestore'         ,'uses'=>'IdeaController@getRestore']);
	Route::post('{idea_wt}/restore'                ,['as'=>'ideasPostRestore'        ,'uses'=>'IdeaController@postRestore'     ,'before' => 'csrf']);

	Route::get('export'                            ,['as'=>'ideasGetExport'          ,'uses'=>'IdeaController@getExport']);
	Route::post('export'                           ,['as'=>'ideasPostExport'         ,'uses'=>'IdeaController@postExport'      ,'before' => 'csrf']);
	Route::get('comments/{idea}/list'              ,['as'=>'ideasGetComments'        ,'uses'=>'IdeaController@getComments']);
	Route::post('comments/{idea}/comment'          ,['as'=>'ideasPostComment'        ,'uses'=>'IdeaController@postComment'     ,'before' => 'csrf']);
	Route::post('comments/{ideaComment}/edit'      ,['as'=>'ideasPostEditComment'    ,'uses'=>'IdeaController@editComment'     ,'before' => 'csrf']);
	Route::post('comments/{ideaComment}/delete'    ,['as'=>'ideasPostDeleteComment'  ,'uses'=>'IdeaController@deleteComment'   ,'before' => 'csrf']);
});