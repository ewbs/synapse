<?php

// TODO Remettre de l'ordre dans ces routes, et retirer le dispatch au controleur (car vu que tous les appels de route sont nommés il ne devrait plus servir à rien)

// User reset routes
Route::get('users/reset_password/{token}', ['as'=>'userGetReset', 'uses' => 'UserController@getReset']);
Route::post('users/reset_password', ['as'=>'userPostReset', 'uses' => 'UserController@postReset'       ,'before' => 'csrf']);

Route::get('users/confirm/{token}', ['as'=>'userGetConfirm', 'uses' => 'UserController@getConfirm']);

// User Account Routes
Route::get('user', ['as'=>'userGetIndex', 'uses' => 'UserController@getIndex']);
Route::post('user/{user}/edit', ['as'=>'userPostEdit', 'uses' => 'UserController@postEdit']);
Route::get('user/login', ['as'=>'userGetLogin', 'uses' => 'UserController@getLogin']);
Route::post('user/login', ['as'=>'userPostLogin', 'uses' => 'UserController@postLogin']);
Route::get('user/logout', ['as'=>'userGetLogout', 'uses' => 'UserController@getLogout']);

// Gestion des filtres
Route::get('user/mes-filtres', ['as'=>'userGetFilters', 'uses'=>'UserController@getFilters', 'before' => 'auth']); //cette route est sécurisée : doit etre loggé
Route::post('user/mes-filtres', ['as'=>'userPostFilters', 'uses'=>'UserController@postFilters', 'before' => 'auth']); //cette route est sécurisée : doit etre loggé

// User RESTful Routes (Login, Logout, Register, etc)
Route::get('users/forgot_password', ['as'=>'userGetForgotPassword', 'uses' => 'UserController@getForgotPassword']);
Route::post('users/forgot_password', ['as'=>'userPostForgotPassword', 'uses' => 'UserController@postForgotPassword']);

Route::controller('user', 'UserController');
