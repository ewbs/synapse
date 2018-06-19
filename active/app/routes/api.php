<?php

Route::group(['prefix' => 'api/v1/damus'], function() {
	
	Route::get('', 'ApiV1DamusController@getIndex');
	Route::get('/publics', 'ApiV1DamusController@getPublics');
	Route::get('/thematiquesabc/{damus_public_id}', 'ApiV1DamusController@getThematiquesABC');
	Route::get('/thematiquesadm', 'ApiV1DamusController@getThematiquesADM');
	Route::get('/evenements/{damus_public_id}', 'ApiV1DamusController@getEvenements');
	Route::get('/evenements/{damus_public_id}/{damus_thematiqueabc_id}', 'ApiV1DamusController@getEvenements');
	Route::get('/demarches', 'ApiV1DamusController@getDemarches');
	Route::post('/demarchelinks/', ['as' => 'apiGetDemarcheLinks', 'uses' => 'ApiV1DamusController@postDemarcheLinks']); //on passe le param "ids" en POST --> ids des démarches, sous forme d'un array
	
});