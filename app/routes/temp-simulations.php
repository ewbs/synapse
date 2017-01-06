<?php
Route::get('/', function () {
	return View::make('temp-simulations.dashboard');
});
Route::get('/projets', function () {
	return View::make('temp-simulations.projets');
});
Route::get('/demarches', function () {
	return View::make('temp-simulations.demarches');
});
Route::get('/mes-filtres', function () {
	return View::make('temp-simulations.filtres');
});
Route::get('/new-action', function () {
	return View::make('temp-simulations.newaction');
});