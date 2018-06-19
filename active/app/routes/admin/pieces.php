<?php
Route::group(['prefix' => 'pieces'], function() {
	Route::get(''                      ,['as'=>'piecesGetIndex'      ,'uses'=>'PieceController@getIndex']);
	Route::get('data'                  ,['as'=>'piecesGetData'       ,'uses'=>'PieceController@getData']);
	Route::get('create'                ,['as'=>'piecesGetCreate'     ,'uses'=>'PieceController@getCreate']);
	Route::post('create'               ,['as'=>'piecesPostCreate'    ,'uses'=>'PieceController@postCreate'    ,'before' => 'csrf']);
	Route::get('{piece}/edit'          ,['as'=>'piecesGetEdit'       ,'uses'=>'PieceController@getEdit']);
	Route::post('{piece}/edit'         ,['as'=>'piecesPostEdit'      ,'uses'=>'PieceController@postEdit'      ,'before' => 'csrf']);
	Route::get('{piece}/delete'        ,['as'=>'piecesGetDelete'     ,'uses'=>'PieceController@getDelete']);
	Route::post('{piece}/delete'       ,['as'=>'piecesPostDelete'    ,'uses'=>'PieceController@postDelete'    ,'before' => 'csrf']);
	Route::get('trash'                 ,['as'=>'piecesGetTrash'      ,'uses'=>'PieceController@getTrash']);
	Route::get('datatrash'             ,['as'=>'piecesGetDataTrash'  ,'uses'=>'PieceController@getDataTrash']);
	Route::get('{piece_wt}/restore'    ,['as'=>'piecesGetRestore'    ,'uses'=>'PieceController@getRestore']);
	Route::post('{piece_wt}/restore'   ,['as'=>'piecesPostRestore'   ,'uses'=>'PieceController@postRestore'   ,'before' => 'csrf']);
});