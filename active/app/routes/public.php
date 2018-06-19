<?php
Route::get('', ['as'=>'getIndex', 'uses' => 'PublicSiteController@getIndex']);
Route::get('contact', ['as'=>'getContact', 'uses' => 'PublicSiteController@getContact']);