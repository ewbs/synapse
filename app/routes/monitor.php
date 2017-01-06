<?php

Route::group(['prefix' => 'monitor'], function() {
	Route::get('health', 'MonitorController@getHealth');
	Route::get('status', 'MonitorController@getStatus');
});