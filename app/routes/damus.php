<?php
Route::group(['prefix' => 'damus'], function() {
	Route::get('response/{ewbs_action}/{token}'      ,['as'=>'damusGetResponse'      ,'uses'=>'DamusController@getResponse']);
	Route::post('response/{ewbs_action}/{token}'     ,['as'=>'damusPostResponse'     ,'uses'=>'DamusController@postResponse'   ,'before' => 'csrf']);
});