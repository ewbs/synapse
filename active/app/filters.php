<?php

/*
 * |--------------------------------------------------------------------------
 * | Application & Route Filters
 * |--------------------------------------------------------------------------
 * |
 * | Below you will find the "before" and "after" events for the application
 * | which may be used to do any work before or after a request into your
 * | application. Here you may also register your custom route filters.
 * |
 */
App::before ( function ($request) {
	// Redirige tout en HTTPS
	// if( ! Request::secure()) {
	// return Redirect::secure(Request::path());
	// }
	
	// Trim all input
	$request->merge(
		array_map_recursive(
			"trim",
			array_except(
				$request->all(),
				["password", "password_confirmation"]
			)
		)
	);
} );

App::after ( function ($request, $response) {
	//
} );

/*
 * |--------------------------------------------------------------------------
 * | Authentication Filters
 * |--------------------------------------------------------------------------
 * |
 * | The following filters are used to verify that the user of the current
 * | session is logged into this application. The "basic" filter easily
 * | integrates HTTP Basic authentication for quick, simple checking.
 * |
 */

Route::filter ( 'auth', function () {
	if (Auth::guest ()) // If the user is not logged in
{
		return Redirect::guest ( 'user/login' );
	}
} );

Route::filter ( 'auth.basic', function () {
	return Auth::basic ();
} );

/*
 * |--------------------------------------------------------------------------
 * | Guest Filter
 * |--------------------------------------------------------------------------
 * |
 * | The "guest" filter is the counterpart of the authentication filters as
 * | it simply checks that the current user is not logged in. A redirect
 * | response will be issued if they are, which you may freely change.
 * |
 */

Route::filter ( 'guest', function () {
	if (Auth::check ())
		return Redirect::to ( 'user/login/' );
} );

/*
 * |--------------------------------------------------------------------------
 * | Access filters based on permissions
 * |--------------------------------------------------------------------------
 */

// Check for permissions on admin actions
Entrust::routeNeedsPermission ( 'admin/users*', 'manage_users', Redirect::to ( '/admin' ) );
Entrust::routeNeedsPermission ( 'admin/roles*', 'manage_roles', Redirect::to ( '/admin' ) );
Entrust::routeNeedsPermission ( 'admin/ewbsmembers*', 'manage_users', Redirect::to ( '/admin' ) );
Entrust::routeNeedsPermission ( 'admin/administrations*', 'administrations_manage', Redirect::to ( '/admin' ) );

// Permissions sur le module IDEAS (Note : edit/delete sont gérés au niveau du controller, car restrictions en +)
Entrust::routeNeedsPermission ( 'admin/ideas*', 'ideas_display', Redirect::to ( '/admin' ) );
Entrust::routeNeedsPermission ( 'admin/ideas/create', 'ideas_encode', Redirect::to ( '/admin' ) );

// Permissions sur le module DEMARCHES
//Note : l'édition et suppression est vérifiée au niveau du contrôleur, car règles particulières (propriétaire de la démarches) 
Entrust::routeNeedsPermission ( 'admin/demarches*', 'demarches_display', Redirect::to ( '/admin' ) );
Entrust::routeNeedsPermission ( 'admin/demarches/*/create', 'demarches_encode', Redirect::to ( '/admin' ) );

// Permissions sur le module DAMUS
// Entrust::routeNeedsPermission( 'admin/damus*', 'damus_encode', Redirect::to('/admin') );

// Permissions sur le module CATALOGUE DES PIECES PROBANTES
Entrust::routeNeedsPermission ( 'admin/pieces*', 'pieces_tasks_display', Redirect::to ( '/admin' ) );
Entrust::routeNeedsPermission ( 'admin/pieces*/create', 'pieces_tasks_manage', Redirect::to ( 'admin' ) );
Entrust::routeNeedsPermission ( 'admin/pieces*/*/edit', 'pieces_tasks_manage', Redirect::to ( '/admin' ) );
Entrust::routeNeedsPermission ( 'admin/pieces*/*/delete', 'pieces_tasks_manage', Redirect::to ( '/admin' ) );

Entrust::routeNeedsPermission ( 'admin/tasks*', 'pieces_tasks_display', Redirect::to ( '/admin' ) );
Entrust::routeNeedsPermission ( 'admin/tasks*/create', 'pieces_tasks_manage', Redirect::to ( 'admin' ) );
Entrust::routeNeedsPermission ( 'admin/tasks*/*/edit', 'pieces_tasks_manage', Redirect::to ( '/admin' ) );
Entrust::routeNeedsPermission ( 'admin/tasks*/*/delete', 'pieces_tasks_manage', Redirect::to ( '/admin' ) );

// Permissions sur les actions
Entrust::routeNeedsPermission ( 'admin/ewbsactions*', 'ewbsactions_display', Redirect::to ('/admin' ) );
Entrust::routeNeedsPermission ( 'admin/ewbsactions*/edit', 'ewbsactions_manage', Redirect::to ('/admin' ) );

// Permissions sur les formulaires
Entrust::routeNeedsPermission ( 'admin/eforms*', 'formslibrary_display', Redirect::to ('/admin' ) );
Entrust::routeNeedsPermission ( 'admin/eforms*/create', 'formslibrary_manage', Redirect::to('/admin'));
Entrust::routeNeedsPermission ( 'admin/eforms*/edit', 'formslibrary_manage', Redirect::to('/admin'));
Entrust::routeNeedsPermission ( 'admin/eforms*/delete', 'formslibrary_manage', Redirect::to('/admin'));

// Permissions sur les jobs
Entrust::routeNeedsPermission ( 'admin/jobs*', 'jobs_manage', Redirect::to ('/admin' ) );

// Permissions sur la taxonomie
Entrust::routeNeedsPermission ( 'admin/taxonomy*', 'taxonomy_display', Redirect::to('/admin'));
Entrust::routeNeedsPermission ( 'admin/taxonomy*/create', 'taxonomy_manage', Redirect::to('/admin'));
Entrust::routeNeedsPermission ( 'admin/taxonomy*/edit', 'taxonomy_manage', Redirect::to('/admin'));
Entrust::routeNeedsPermission ( 'admin/taxonomy*/delete', 'taxonomy_manage', Redirect::to('/admin'));
Entrust::routeNeedsPermission ( 'admin/taxonomy/synonyms', 'taxonomy_manage', Redirect::to('/admin'));

// Permissions sur le catalogue de services
Entrust::routeNeedsPermission ( 'admin/ewbsservices*', 'servicescatalog_display', Redirect::to('/admin'));
Entrust::routeNeedsPermission ( 'admin/ewbsservices*/create', 'servicescatalog_manage', Redirect::to('/admin'));
Entrust::routeNeedsPermission ( 'admin/ewbsservices*/edit', 'servicescatalog_manage', Redirect::to('/admin'));
Entrust::routeNeedsPermission ( 'admin/ewbsservices*/delete', 'servicescatalog_manage', Redirect::to('/admin'));

/*
 * |--------------------------------------------------------------------------
 * | CSRF Protection Filter
 * |--------------------------------------------------------------------------
 * |
 * | The CSRF filter is responsible for protecting your application against
 * | cross-site request forgery attacks. If this special token in a user
 * | session does not match the one given in this request, we'll bail.
 * |
 */

Route::filter ( 'csrf', function () {
	if (Session::getToken () !== Input::get ( 'csrf_token' ) && Session::getToken () !== Input::get ( '_token' )) {
		throw new Illuminate\Session\TokenMismatchException ();
	}
} );

// Forcer l'https, utile pour laravel4 avec debugbar activée
	URL::forceSchema("https");