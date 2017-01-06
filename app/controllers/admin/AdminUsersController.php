<?php
class AdminUsersController extends BaseController {
	
	protected function routeGetIndex() { return route('usersGetIndex'); }
	
	/**
	 * Role Model
	 * 
	 * @var Role
	 */
	protected $role;
	
	/**
	 * Permission Model
	 * 
	 * @var Permission
	 */
	protected $permission;
	
	/**
	 * Inject the models.
	 * 
	 * @param User $user        	
	 * @param Role $role        	
	 * @param Permission $permission        	
	 */
	public function __construct(User $user, Role $role, Permission $permission) {
		parent::__construct ();
		$this->user = $user;
		$this->role = $role;
		$this->permission = $permission;
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex() {
		// Title
		$title = Lang::get ( 'admin/users/title.user_management' );
		
		// Grab all the users
		$users = $this->user;
		
		// Show the page
		return View::make ( 'admin/users/index', compact ( 'users', 'title' ) );
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getCreate() {
		// All roles
		$roles = $this->role->orderBy('name')->get ();
		
		// Get all the available permissions
		$permissions = $this->permission->all ();
		
		// Toutes les administrations (pour restreindre le contenu à certaines administrations)
		$administrations = Administration::all ();
		
		// Selected groups
		$selectedRoles = Input::old ( 'roles', array () );
		
		// Selected permissions
		$selectedPermissions = Input::old ( 'permissions', array () );
		
		// Administrations sélectionnées
		$selectedAdministrations = Input::old ( 'administrations', array () );
		
		// Title
		$title = Lang::get ( 'admin/users/title.create_a_new_user' ); // non utilisé pour le moment
		                                                           
		// Mode
		$mode = 'create';
		
		// Show the page
		return View::make ( 'admin/users/create_edit', compact ( 'roles', 'permissions', 'selectedRoles', 'selectedPermissions', 'title', 'mode', 'administrations', 'selectedAdministrations' ) );
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function postCreate() {
		$this->user->username = Input::get ( 'username' );
		$this->user->email = Input::get ( 'email' );
		$this->user->password = Input::get ( 'password' );
		
		// The password confirmation will be removed from model
		// before saving. This field will be used in Ardent's
		// auto validation.
		$this->user->password_confirmation = Input::get ( 'password_confirmation' );
		
		// Generate a random confirmation code
		$this->user->confirmation_code = md5 ( uniqid ( mt_rand (), true ) );
		
		if (Input::get ( 'confirm' )) {
			$this->user->confirmed = Input::get ( 'confirm' );
		}
		
		// Permissions are currently tied to roles. Can't do this yet.
		// $user->permissions = $user->roles()->preparePermissionsForSave(Input::get( 'permissions' ));
		
		// Save if valid. Password field will be hashed before save
		$this->user->save ();
		
		if ($this->user->id) {
			// Save roles. Handles updating.
			$this->user->saveRoles ( Input::get ( 'roles' ) );
			// sauve les restrictions liées aux administrations
			$this->user->administrations ()->sync ( is_array ( Input::get ( 'administrations' ) ) ? Input::get ( 'administrations' ) : array () );
			
			if (Config::get ( 'confide::signup_email' )) {
				$user = $this->user;
				Mail::queueOn ( Config::get ( 'confide::email_queue' ), Config::get ( 'confide::email_account_confirmation' ), compact ( 'user' ), function ($message) use($user) {
					$message->to ( $user->email, $user->username )->subject ( Lang::get ( 'confide::confide.email.account_confirmation.subject' ) );
				} );
			}
			
			// Redirect to the new user page
			return Redirect::secure ( 'admin/users/' )->with ( 'success', Lang::get ( 'admin/users/messages.create.success' ) );
		} else {
			
			// Get validation errors (see Ardent package)
			$error = $this->user->errors ()->all ();
			$administrations = Administration::all ();
			
			return Redirect::secure ( 'admin/users/create' )->withInput ( Input::except ( 'password' ) )->with ( 'administrations', $administrations )->with ( 'error', $error );
		}
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param
	 *        	$user
	 * @return Response
	 */
	public function getEdit($user) {
		if ($user->id) {
			$roles = $this->role->orderBy('name')->get ();
			$permissions = $this->permission->all ();
			// Toutes les administrations (pour restreindre le contenu à certaines administrations)
			$administrations = Administration::all ();
			
			// Title
			$title = Lang::get ( 'admin/users/title.user_update' );
			// mode
			$mode = 'edit';
			
			return View::make ( 'admin/users/create_edit', compact ( 'user', 'roles', 'permissions', 'title', 'mode', 'administrations' ) );
		} else {
			return Redirect::secure ( 'admin/users' )->with ( 'error', Lang::get ( 'admin/users/messages.does_not_exist' ) );
		}
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param User $user        	
	 * @return Response
	 */
	public function postEdit($user) {
		$oldUser = clone $user;
		$user->username = Input::get ( 'username' );
		$user->email = Input::get ( 'email' );
		$user->confirmed = Input::get ( 'confirm' );
		
		$password = Input::get ( 'password' );
		$passwordConfirmation = Input::get ( 'password_confirmation' );
		
		if (! empty ( $password )) {
			if ($password === $passwordConfirmation) {
				$user->password = $password;
				// The password confirmation will be removed from model
				// before saving. This field will be used in Ardent's
				// auto validation.
				$user->password_confirmation = $passwordConfirmation;
			} else {
				// Redirect to the new user page
				return Redirect::secure ( 'admin/users/' . $user->id . '/edit' )->with ( 'error', Lang::get ( 'admin/users/messages.password_does_not_match' ) );
			}
		}
		
		if ($user->confirmed == null) {
			$user->confirmed = $oldUser->confirmed;
		}
		
		if ($user->save ()) {
			// Save roles. Handles updating.
			$user->saveRoles ( Input::get ( 'roles' ) );
			// sauve les restrictions liées aux administrations
			$user->administrations ()->sync ( is_array ( Input::get ( 'administrations' ) ) ? Input::get ( 'administrations' ) : array () );
		} else {
			return Redirect::secure ( 'admin/users/' . $user->id . '/edit' )->withInput()->withErrors ( $user->errors() )->with ( 'error', Lang::get ( 'admin/users/messages.edit.error' ) );
		}
		
		// Get validation errors (see Ardent package)
		$error = $user->errors ()->all ();
		
		if (empty ( $error )) {
			// Redirect to the new user page
			return Redirect::secure ( 'admin/users/' )->with ( 'success', Lang::get ( 'admin/users/messages.edit.success' ) );
		} else {
			return Redirect::secure ( 'admin/users/' . $user->id . '/edit' )->with ( 'error', Lang::get ( 'admin/users/messages.edit.error' ) );
		}
	}
	
	/**
	 * Remove user page.
	 *
	 * @param
	 *        	$user
	 * @return Response
	 */
	public function getDelete($user) {
		// Title
		$title = Lang::get ( 'admin/users/title.user_delete' );
		
		// Show the page
		return View::make ( 'admin/users/delete', compact ( 'user', 'title' ) );
	}
	
	/**
	 * Remove the specified user from storage.
	 *
	 * @param
	 *        	$user
	 * @return Response
	 */
	public function postDelete($user) {
		// Check if we are not trying to delete ourselves
		if ($user->id === Confide::user ()->id) {
			// Redirect to the user management page
			return Redirect::secure ( 'admin/users' )->with ( 'error', Lang::get ( 'admin/users/messages.delete.impossible' ) );
		}
		
		AssignedRoles::where ( 'user_id', $user->id )->delete ();
		if ($user->delete ()) {
			// TODO needs to delete all of that user's content
			return Redirect::secure ( 'admin/users' )->with ( 'success', Lang::get ( 'admin/users/messages.delete.success' ) );
		} else {
			// There was a problem deleting the user
			return Redirect::secure ( 'admin/users' )->with ( 'error', Lang::get ( 'admin/users/messages.delete.error' ) );
		}
	}
	
	/**
	 * Show a list of all the users formatted for Datatables.
	 *
	 * @return Datatables JSON
	 */
	public function getData() {
		$users = User::select ( array (
				'id',
				'username',
				'email',
				'confirmed',
				'created_at' 
		) ); // password est sélectionné pour avoir une colonne à utiliser (on y balancera les rôles)
		
		return Datatables::of ( $users )
		->edit_column ( 'confirmed', '@if($confirmed)Oui @else Non @endif' )
		->add_column ( 'roles', function ($user) {
			$a = $user->currentRoleNames ();
			$return = ($a) ? implode ( ', ', $a ) : '';
			if ($user->hasRestrictionsByAdministrations ())
				$return .= ' <strong>Avec restrictions</strong>';
			return ($return);
		})
		->add_column ( 'actions', '<a title="{{{ Lang::get(\'button.edit\')   }}}" href="{{{ URL::secure(\'admin/users/\' . $id . \'/edit\' )   }}}" class="iframe btn btn-xs btn-default"><span class="fa fa-pencil"></span></a>
		                           <a title="{{{ Lang::get(\'button.delete\') }}}" href="{{{ URL::secure(\'admin/users/\' . $id . \'/delete\' ) }}}" class="iframe btn btn-xs btn-danger"><span class="fa fa-trash-o"></span></a>' )
		->remove_column ( 'id' )
		->make ();
	}
	
	/*
	 * public function getData()
	 * {
	 * $users = User::leftjoin('assigned_roles', 'assigned_roles.user_id', '=', 'users.id')
	 * ->leftjoin('roles', 'roles.id', '=', 'assigned_roles.role_id')
	 * ->select(array('users.id', 'users.username','users.email', 'roles.name as rolename', 'users.confirmed', 'users.created_at'));
	 *
	 * return Datatables::of($users)
	 * // ->edit_column('created_at','{{{ Carbon::now()->diffForHumans(Carbon::createFromFormat(\'Y-m-d H\', $test)) }}}')
	 *
	 * ->edit_column('confirmed','@if($confirmed)
	 * Oui
	 * @else
	 * Non
	 * @endif')
	 *
	 * ->add_column('actions', '<a href="{{{ URL::secure(\'admin/users/\' . $id . \'/edit\' ) }}}" class="iframe btn btn-xs btn-default">{{{ Lang::get(\'button.edit\') }}}</a>
	 * @if($username == \'admin\')
	 * @else
	 * <a href="{{{ URL::secure(\'admin/users/\' . $id . \'/delete\' ) }}}" class="iframe btn btn-xs btn-danger">{{{ Lang::get(\'button.delete\') }}}</a>
	 * @endif
	 * ')
	 *
	 * ->remove_column('id')
	 *
	 * ->make();
	 * }
	 */
}