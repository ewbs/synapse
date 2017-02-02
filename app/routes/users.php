<?php

//FIXME choses bizarres à éclaircir : routes en double, procédure de reset password qui semble ne pas fonctionner (manque un partial-template appelé par une méthode de confide,...)
//FIX de manière temporaire. Il faut revoir l'ensemble des routes liées à Confide car c'est le bordel (jda - 2016-04-12)

// User reset routes
Route::get('users/reset_password/{token}', ['as'=>'userGetReset', 'uses' => 'UserController@getReset']);
Route::post('users/reset_password', ['as'=>'userPostReset', 'uses' => 'UserController@postReset']);

Route::get('users/confirm/{token}', ['as'=>'userGetConfirm', 'uses' => 'UserController@getConfirm']);
Route::get('user/reset/{token}', ['as'=>'userGetReset', 'uses' => 'UserController@getReset']);

// User Account Routes
Route::post('user/{user}/edit', ['as'=>'userGetReset', 'uses' => 'UserController@postEdit']);
Route::post('user/login', ['as'=>'userGetReset', 'uses' => 'UserController@postLogin']);

// Gestion des filtres
Route::get('user/mes-filtres', ['as'=>'UserGetFilters', 'uses'=>'UserController@getFilters', 'before' => 'auth']); //cette route est sécurisée : doit etre loggé
Route::post('user/mes-filtres', ['as'=>'UserPostFilters', 'uses'=>'UserController@postFilters', 'before' => 'auth']); //cette route est sécurisée : doit etre loggé

// User RESTful Routes (Login, Logout, Register, etc)
Route::get('user', ['as'=>'userGetIndex', 'uses' => 'UserController@getIndex']);
Route::post('users/forgot_password', ['as'=>'userPostForgotPassword', 'uses' => 'UserController@postForgotPassword']);
Route::controller('user', 'UserController');
