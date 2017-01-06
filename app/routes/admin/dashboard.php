<?php
Route::group(['prefix' => 'dashboard'], function() {
	Route::get('',['as'=>'adminDashboardGetIndex','uses'=>'AdminDashboardController@getIndex']);
	Route::get('mes-projets', 	['as'=>'adminDashboardGetMyIdeas', 'uses'=>'AdminDashboardController@getMyIdeas']);
	Route::get('mes-demarches', ['as'=>'adminDashboardGetMyDemarches', 'uses'=>'AdminDashboardController@getMyDemarches']);
	Route::get('mes-actions', 	['as'=>'adminDashboardGetMyActions', 'uses'=>'AdminDashboardController@getMyActions']);
	Route::get('mes-charges', 	['as'=>'adminDashboardGetMyCharges', 'uses'=>'AdminDashboardController@getMyCharges']);
});