<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the Closure to execute when that URI is requested.
  |
 */

/** ------------------------------------------
 *  Route model binding & constraint patterns
 *  ------------------------------------------
 */
$bindings=[
	['administration', 'Administration'],
	['annexe', 'Annexe'],
	['annexe_eform', 'AnnexeEform'],
	//['damus_thematique', 'NostraThematique'],
	['damus_thematiqueabc', 'NostraThematiqueabc'],
	['damus_thematiqueadm', 'NostraThematiqueadm'],
	['damus_evenement', 'NostraEvenement'],
	['damus_demarche', 'NostraDemarche'],
	['damus_form', 'NostraForm'],
	['damus_public', 'NostraPublic'],
	['demarche', 'Demarche'],
	['demarche_eform', 'DemarcheEform'],
	['demarche_piece', 'DemarchePiece'],
	['demarche_piece_revision', 'DemarchePieceRevision'],
	['demarche_task', 'DemarcheTask'],
	['demarche_task_revision', 'DemarcheTaskRevision'],
	['demarche_scm', 'DemarcheSCM'],
	['eform', 'Eform'],
	['ewbs_action', 'EwbsAction'],
	['ewbs_action_revision', 'EwbsActionRevision'],
	['ewbs_subaction', 'EwbsAction'],
	['ewbs_member', 'EWBSMember'],
	['failedjob', 'Barryvdh\Queue\Models\FailedJob'],
	['idea', 'Idea'],
	['ideaComment', 'IdeaComment'],
	['job', 'Barryvdh\Queue\Models\Job'],
	['mandate', 'Mandate'],
	['minister', 'Minister'],
	['piece', 'Piece'],
	['role', 'Role'],
	//['rate', 'PieceRate'],
	['task', 'Task'],
	//['type', 'PieceType'],
	['user', 'User'],
	['taxonomycategory', 'TaxonomyCategory'],
	['taxonomytag', 'TaxonomyTag'],
	['ewbsservice', 'EwbsService'],
];
foreach($bindings as $binding) {
	Route::model($binding[0], $binding[1]);
	Route::pattern($binding[0], '[0-9]+');
	
	// Parmi les bindings ci-dessus, ceux descendant de TrashableModel doivent aussi binder une variable par convention préfixée de _wt, afin d'inclure les soft-deletés dans la requête
	if(is_subclass_of($binding[1], 'TrashableModel')) {
		Route::bind("{$binding[0]}_wt", function($value) use($binding) {return $binding[1]::withTrashed()->find($value);});
		Route::pattern("{$binding[0]}_wt", '[0-9]+');
	}
}

/** ------------------------------------------
 *  Other route constraint patterns
 *  ------------------------------------------
 */
Route::pattern('actionId', '[0-9]+');
Route::pattern('damus_demarcheId', '[0-9]+');
Route::pattern('damus_demarche_nostra_id', '[0-9]+');
Route::pattern('damus_evenementId', '[0-9]+');
Route::pattern('damus_thematiqueabcId', '[0-9]+');
Route::pattern('demarcheId', '[0-9]+');
Route::pattern('pieceId', '[0-9]+');
//Route::pattern('rateId', '[0-9]+');
Route::pattern('taskId', '[0-9]+');
Route::pattern('token', '[0-9a-z]+');
//Route::pattern('typeId', '[0-9]+');


/** ------------------------------------------
 *  Frontend Routes
 *  ------------------------------------------
 */

// Pages publiques
require ( __DIR__ . '/routes/public.php' );

// Gestion des utilisateurs en front (login, profil etc)
require ( __DIR__ . '/routes/users.php' );

// Damus
require ( __DIR__ . '/routes/damus.php' );

// Monitoring
require ( __DIR__ . '/routes/monitor.php' );

/** ------------------------------------------
 *  Admin Routes /admin/*
 *  ------------------------------------------
 */
Route::group(['prefix' => 'admin', 'before' => 'auth'], function() {
	
	# Admin Dashboard
	Route::get('', function () { return Redirect::secure('/admin/dashboard'); });

	# Dashboard
	require ( __DIR__ . '/routes/admin/dashboard.php');

	# Utilisateurs /admin/users/*
	require( __DIR__ . '/routes/admin/users.php');
	
	# Personnel eWBS /admin/ewbsmembers/*
	require ( __DIR__ . '/routes/admin/ewbsmembers.php' );
	
	#Administrations /admin/administrations/*
	require ( __DIR__ . '/routes/admin/administrations.php' );
	
	# Rôles /admin/roles/*
	require ( __DIR__ . '/routes/admin/roles.php' );
	
	# Idées (projets) de simplification /admin/ideas/*
	require ( __DIR__ . '/routes/admin/ideas.php' );
	
	# Référentiel des démarches /admin/demarches/*
	require ( __DIR__ . '/routes/admin/demarches.php' );
	
	# Actions EWBS /admin/ewbsactions/*
	require ( __DIR__ . '/routes/admin/ewbsactions.php' );
	
	# Formulaires /admin/eforms/*
	require ( __DIR__ . '/routes/admin/eforms.php' );
	
	# Pièces /admin/pieces/*
	require ( __DIR__ . '/routes/admin/pieces.php' );

	# Tasks /admin/tasks/*
	require ( __DIR__ . '/routes/admin/tasks.php' );

	# Module Damus
	require ( __DIR__ . '/routes/admin/damus.php' );

	# Module de taxonomie
	require ( __DIR__ . '/routes/admin/taxonomy.php' );

	# Catalogue de services
	require ( __DIR__ . '/routes/admin/ewbsservices.php' );
	
	#Administrations /admin/jobs/*
	require ( __DIR__ . '/routes/admin/jobs.php' );
	
	#Administrations /admin/jobs/*
	require ( __DIR__ . '/routes/admin/ministers.php' );

	# Types /admin/piecestypes/*
	/*Route::group(['prefix' => 'piecestypes'], function() {
		Route::get(''                    ,['as'=>'piecestypesGetIndex'      ,'uses'=>'PieceTypeController@getIndex']);
		Route::get('data'                ,['as'=>'piecestypesGetData'       ,'uses'=>'PieceTypeController@getData']);
		Route::get('create'              ,['as'=>'piecestypesGetCreate'     ,'uses'=>'PieceTypeController@getCreate']);
		Route::post('create'             ,['as'=>'piecestypesPostCreate'    ,'uses'=>'PieceTypeController@postCreate'    ,'before' => 'csrf']);
		Route::get('{type}/edit'         ,['as'=>'piecestypesGetEdit'       ,'uses'=>'PieceTypeController@getEdit']);
		Route::post('{type}/edit'        ,['as'=>'piecestypesPostEdit'      ,'uses'=>'PieceTypeController@postEdit'      ,'before' => 'csrf']);
		Route::get('{type}/delete'       ,['as'=>'piecestypesGetDelete'     ,'uses'=>'PieceTypeController@getDelete']);
		Route::post('{type}/delete'      ,['as'=>'piecestypesPostDelete'    ,'uses'=>'PieceTypeController@postDelete'    ,'before' => 'csrf']);
		Route::get('trash'               ,['as'=>'piecestypesGetTrash'      ,'uses'=>'PieceTypeController@getTrash']);
		Route::get('datatrash'           ,['as'=>'piecestypesGetDataTrash'  ,'uses'=>'PieceTypeController@getDataTrash']);
		Route::get('{type_wt}/restore'   ,['as'=>'piecestypesGetRestore'    ,'uses'=>'PieceTypeController@getRestore']);
		Route::post('{type_wt}/restore'  ,['as'=>'piecestypesPostRestore'   ,'uses'=>'PieceTypeController@postRestore'   ,'before' => 'csrf']);
	});*/

	# Rates /admin/piecesrates/*
	/*Route::group(['prefix' => 'piecesrates'], function() {
		Route::get(''                    ,['as'=>'piecesratesGetIndex'      ,'uses'=>'PieceRateController@getIndex']);
		Route::get('data'                ,['as'=>'piecesratesGetData'       ,'uses'=>'PieceRateController@getData']);
		Route::get('create'              ,['as'=>'piecesratesGetCreate'     ,'uses'=>'PieceRateController@getCreate']);
		Route::post('create'             ,['as'=>'piecesratesPostCreate'    ,'uses'=>'PieceRateController@postCreate'    ,'before' => 'csrf']);
		Route::get('{rate}/edit'         ,['as'=>'piecesratesGetEdit'       ,'uses'=>'PieceRateController@getEdit']);
		Route::post('{rate}/edit'        ,['as'=>'piecesratesPostEdit'      ,'uses'=>'PieceRateController@postEdit'      ,'before' => 'csrf']);
		Route::get('{rate}/delete'       ,['as'=>'piecesratesGetDelete'     ,'uses'=>'PieceRateController@getDelete']);
		Route::post('{rate}/delete'      ,['as'=>'piecesratesPostDelete'    ,'uses'=>'PieceRateController@postDelete'    ,'before' => 'csrf']);
		Route::get('trash'               ,['as'=>'piecesratesGetTrash'      ,'uses'=>'PieceRateController@getTrash']);
		Route::get('datatrash'           ,['as'=>'piecesratesGetDataTrash'  ,'uses'=>'PieceRateController@getDataTrash']);
		Route::get('{rate_wt}/restore'   ,['as'=>'piecesratesGetRestore'    ,'uses'=>'PieceRateController@getRestore']);
		Route::post('{rate_wt}/restore'  ,['as'=>'piecesratesPostRestore'   ,'uses'=>'PieceRateController@postRestore'   ,'before' => 'csrf']);
	});*/
});


/** ------------------------------------------
 *  Routes de l'API
 *  ------------------------------------------
 */

require ( __DIR__ . '/routes/api.php' );
