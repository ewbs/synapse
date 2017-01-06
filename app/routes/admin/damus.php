<?php
Route::group(['prefix' => 'damus'], function() {
	Route::get(''                                            ,['as'=>'damusGetIndex'                 ,'uses'=>'DamusController@getIndex']);
	Route::get('detail/public/{damus_public}'                ,['as'=>'damusGetDetailPublic'          ,'uses'=>'DamusController@getDetailPublic']);
	Route::get('detail/thematiqueabc/{damus_thematiqueabc}'  ,['as'=>'damusGetDetailThematiqueabc'   ,'uses'=>'DamusController@getDetailThematiqueABC']);
	Route::get('detail/evenement/{damus_evenement}'          ,['as'=>'damusGetDetailEvenement'       ,'uses'=>'DamusController@getDetailEvenement']);
	Route::get('detail/thematiqueadm/{damus_thematiqueadm}'  ,['as'=>'damusGetDetailThematiqueadm'   ,'uses'=>'DamusController@getDetailThematiqueADM']);
	Route::get('detail/demarche/{damus_demarche}'            ,['as'=>'damusGetDetailDemarche'        ,'uses'=>'DamusController@getDetailDemarche']);
	
	// Demandes vers l'équipe Nostra
	Route::group(['prefix' => 'request'], function() {
		
		// Demandes au départ des démarches
		Route::group(['prefix' => 'demarches'], function() {
			Route::get (''           ,['as'=>'damusGetRequestCreateDemarche'  ,'uses'=>'DamusController@getRequestCreateDemarche']);
			Route::post(''           ,['as'=>'damusPostRequestCreateDemarche' ,'uses'=>'DamusController@postRequestCreateDemarche' ,'before' => 'csrf']);
			Route::get ('{demarche}' ,['as'=>'damusGetRequestDemarche'        ,'uses'=>'DamusController@getRequestDemarche']);
			Route::post('{demarche}' ,['as'=>'damusPostRequestDemarche'       ,'uses'=>'DamusController@postRequestDemarche'       ,'before' => 'csrf']);
		});
		
		// Demandes au départ des projets de simplif
		Route::group(['prefix' => 'ideas/{idea}'], function() {
			Route::get ('' ,['as'=>'damusGetRequestIdea'  ,'uses'=>'DamusController@getRequestIdea']);
			Route::post('' ,['as'=>'damusPostRequestIdea' ,'uses'=>'DamusController@postRequestIdea' ,'before' => 'csrf']);
		});
		
		// Demandes au départ des formulaires
		Route::group(['prefix' => 'eforms/{eform}'], function() {
			Route::get ('' ,['as'=>'damusGetRequestEform'  ,'uses'=>'DamusController@getRequestEform']);
			Route::post('' ,['as'=>'damusPostRequestEform' ,'uses'=>'DamusController@postRequestEform' ,'before' => 'csrf']);
		});
	});
	
	Route::group(['prefix' => 'nostra'], function() {
		Route::get ('demarches/{nostra_id}' ,['as'=>'damusNostraGetDemarche'  ,'uses'=>'DamusController@nostraGetDemarche']);
	});
});