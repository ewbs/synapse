<?php

Route::group(['prefix' => 'api/v1/damus'], function() {
	Route::get('/publics', 'ApiV1DamusController@getPublics');
	Route::get('/thematiquesabc/{damus_public_id}', 'ApiV1DamusController@getThematiquesABC');
	Route::get('/thematiquesadm', 'ApiV1DamusController@getThematiquesADM');
	Route::get('/evenements/{damus_public_id}', 'ApiV1DamusController@getEvenements');
	Route::get('/evenements/{damus_public_id}/{damus_thematiqueabc_id}', 'ApiV1DamusController@getEvenements');
	Route::get('/demarches', 'ApiV1DamusController@getDemarches');
	Route::get('/demarche/{damus_demarche_nostra_id}', 'ApiV1DamusController@getDemarche'); //obtenir le détail d'une démarche (fera un appel à Nostra);
	Route::post('/demarchelinks/', ['as' => 'apiGetDemarcheLinks', 'uses' => 'ApiV1DamusController@postDemarcheLinks']); //on passe le param "ids" en POST --> ids des démarches, sous forme d'un array
	Route::get('', 'ApiV1DamusController@getIndex');
});
//Route::get('api/v1/demarches', 'ApiV1DemarchesController@getDemarches');