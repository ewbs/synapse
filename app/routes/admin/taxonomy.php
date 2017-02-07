<?php
Route::group(['prefix' => 'taxonomy'], function() {

	Route::get('synonyms',							['as' => 'taxonomyGetSynonyms',				'uses' => 'TaxonomySynonymsController@getSynonyms']);
	Route::post('synonyms', 						['as' => 'taxonomyPostSynonyms',			'uses' => 'TaxonomySynonymsController@postSynonyms',		'before' => 'csrf']); //appelé en ajax pour modifier de la synonimie

	Route::group(['prefix' => 'tags'], function() {
		Route::get('', 								['as' => 'taxonomytagsGetIndex', 			'uses' => 'TaxonomyTagsController@getList']);
		Route::get('data', 							['as' => 'taxonomytagsGetData', 			'uses' => 'TaxonomyTagsController@getData']);
		Route::get('{taxonomytag}/view',			['as' => 'taxonomytagsGetView',				'uses' => 'TaxonomyTagsController@getView']);
		Route::get('create', 						['as' => 'taxonomytagsGetCreate', 			'uses' => 'TaxonomyTagsController@getCreate']);
		Route::post('create', 						['as' => 'taxonomytagsPostCreate', 			'uses' => 'TaxonomyTagsController@postCreate',			'before' => 'csrf']);
		Route::get('{taxonomytag}/edit', 			['as' => 'taxonomytagsGetEdit', 			'uses' => 'TaxonomyTagsController@getEdit']);
		Route::post('{taxonomytag}/edit',			['as' => 'taxonomytagsPostEdit', 			'uses' => 'TaxonomyTagsController@postEdit',			'before' => 'csrf']);
		Route::get('{taxonomytag}/delete',			['as' => 'taxonomytagsGetDelete', 			'uses' => 'TaxonomyTagsController@getDelete']);
		Route::post('{taxonomytag}/delete',			['as' => 'taxonomytagsPostDelete',			'uses' => 'TaxonomyTagsController@postDelete',			'before' => 'csrf']);
		Route::get('trash', 						['as' => 'taxonomytagsGetTrash', 			'uses' => 'TaxonomyTagsController@getTrash']);
		Route::get('datatrash',                     ['as' => 'taxonomytagsGetDataTrash',		'uses' => 'TaxonomyTagsController@getDataTrash']);
		Route::get('{taxonomytag_wt}/restore',		['as' => 'taxonomytagsGetRestore',			'uses' => 'TaxonomyTagsController@getRestore']);
		Route::post('{taxonomytag_wt}/restore',		['as' => 'taxonomytagsPostRestore',			'uses' => 'TaxonomyTagsController@postRestore',			'before' => 'csrf']);
	});

	Route::group(['prefix' => 'categories'], function() {
		Route::get('', 								['as' => 'taxonomycategoriesGetIndex', 		'uses' => 'TaxonomyCategoriesController@getList']);
		Route::get('data', 							['as' => 'taxonomycategoriesGetData', 		'uses' => 'TaxonomyCategoriesController@getData']);
		Route::get('{taxonomycategory}/view',		['as' => 'taxonomycategoriesGetView',		'uses' => 'TaxonomyCategoriesController@getView']);
		Route::get('create', 						['as' => 'taxonomycategoriesGetCreate', 	'uses' => 'TaxonomyCategoriesController@getCreate']);
		Route::post('create', 						['as' => 'taxonomycategoriesPostCreate', 	'uses' => 'TaxonomyCategoriesController@postCreate',	'before' => 'csrf']);
		Route::get('{taxonomycategory}/edit', 		['as' => 'taxonomycategoriesGetEdit', 		'uses' => 'TaxonomyCategoriesController@getEdit']);
		Route::post('{taxonomycategory}/edit',		['as' => 'taxonomycategoriesPostEdit', 		'uses' => 'TaxonomyCategoriesController@postEdit',		'before' => 'csrf']);
		Route::get('{taxonomycategory}/delete',		['as' => 'taxonomycategoriesGetDelete', 	'uses' => 'TaxonomyCategoriesController@getDelete']);
		Route::post('{taxonomycategory}/delete',	['as' => 'taxonomycategoriesPostDelete',	'uses' => 'TaxonomyCategoriesController@postDelete',	'before' => 'csrf']);
		Route::get('trash', 						['as' => 'taxonomycategoriesGetTrash', 		'uses' => 'TaxonomyCategoriesController@getTrash']);
		Route::get('datatrash',                     ['as' => 'taxonomycategoriesGetDataTrash',	'uses' => 'TaxonomyCategoriesController@getDataTrash']);
		Route::get('{taxonomycategory_wt}/restore',	['as' => 'taxonomycategoriesGetRestore',	'uses' => 'TaxonomyCategoriesController@getRestore']);
		Route::post('{taxonomycategory_wt}/restore',['as' => 'taxonomycategoriesPostRestore',	'uses' => 'TaxonomyCategoriesController@postRestore',	'before' => 'csrf']);
	});

});