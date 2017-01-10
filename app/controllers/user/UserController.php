
<?php
class UserController extends BaseController {
	
	protected function routeGetIndex() { return route('userGetIndex'); }
	
	/**
	 *
	 * @var UserRepository
	 */
	protected $userRepo;
	/**
	 * Inject the models.
	 * 
	 * @param User $user        	
	 * @param UserRepository $userRepo        	
	 */
	public function __construct(User $user, UserRepository $userRepo) {
		parent::__construct ();
		$this->user = $user;
		$this->userRepo = $userRepo;
	}
	/**
	 * Users settings page
	 *
	 * @return View
	 */
	public function getIndex() {
		list ( $user, $redirect ) = $this->user->checkAuthAndRedirect ( 'user' );
		if ($redirect) {
			return $redirect;
		}
		// Show the page
		return View::make ( 'site/user/index', compact ( 'user' ) );
	}
	/**
	 * Stores new user
	 */
	public function postIndex() {
		$user = $this->userRepo->signup ( Input::all () );
		if ($user->id) {
			if (Config::get ( 'confide::signup_email' )) {
				Mail::queueOn ( Config::get ( 'confide::email_queue' ), Config::get ( 'confide::email_account_confirmation' ), compact ( 'user' ), function ($message) use($user) {
					$message->to ( $user->email, $user->username )->subject ( Lang::get ( 'confide::confide.email.account_confirmation.subject' ) );
				} );
			}
			return Redirect::secure ( 'user/login' )->with ( 'success', Lang::get ( 'user/user.user_account_created' ) );
		} else {
			$error = $user->errors ()->all ( ':message' );
			return Redirect::secure ( 'user/create' )->withInput ( Input::except ( 'password' ) )->with ( 'error', $error );
		}
	}
	/**
	 * Edits a user
	 * 
	 * @var User
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function postEdit(User $user) {
		$oldUser = clone $user;
		$user->username = Input::get ( 'username' );
		$user->email = Input::get ( 'email' );
		$password = Input::get ( 'password' );
		$passwordConfirmation = Input::get ( 'password_confirmation' );
		if (! empty ( $password )) {
			if ($password != $passwordConfirmation) {
				// Redirect to the new user page
				$error = Lang::get ( 'admin/users/messages.password_does_not_match' );
				return Redirect::secure ( 'user' )->with ( 'error', $error );
			} else {
				$user->password = $password;
				$user->password_confirmation = $passwordConfirmation;
			}
		}
		if ($this->userRepo->save ( $user )) {
			return Redirect::secure ( 'user' )->with ( 'success', Lang::get ( 'user/user.user_account_updated' ) );
		} else {
			$error = $user->errors ()->all ( ':message' );
			return Redirect::secure ( 'user' )->withInput ( Input::except ( 'password', 'password_confirmation' ) )->with ( 'error', $error );
		}
	}
	/**
	 * Displays the form for user creation
	 */
	public function getCreate() {
		return View::make ( 'site/user/create' );
	}
	/**
	 * Displays the login form
	 */
	public function getLogin() {
		$user = Auth::user ();
		if (! empty ( $user->id )) {
			return Redirect::secure ( '/' );
		}
		return View::make ( 'site/user/login' );
	}
	/**
	 * Attempt to do login
	 */
	public function postLogin() {
		$repo = App::make ( 'UserRepository' );
		$input = Input::all ();
		if ($this->userRepo->login ( $input )) {
			return Redirect::intended ( '/admin', 302, array (), true );
		} else {
			if ($this->userRepo->isThrottled ( $input )) {
				$err_msg = Lang::get ( 'confide::confide.alerts.too_many_attempts' );
			} elseif ($this->userRepo->existsButNotConfirmed ( $input )) {
				$err_msg = Lang::get ( 'confide::confide.alerts.not_confirmed' );
			} else {
				$err_msg = Lang::get ( 'confide::confide.alerts.wrong_credentials' );
			}
			return Redirect::secure ( 'user/login' )->withInput ( Input::except ( 'password' ) )->with ( 'error', $err_msg );
		}
	}
	/**
	 * Attempt to confirm account with code
	 *
	 * @param string $code        	
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function getConfirm($code) {
		if (Confide::confirm ( $code )) {
			return Redirect::secure ( 'user/login' )->with ( 'notice', Lang::get ( 'confide::confide.alerts.confirmation' ) );
		} else {
			return Redirect::secure ( 'user/login' )->with ( 'error', Lang::get ( 'confide::confide.alerts.wrong_confirmation' ) );
		}
	}
	/**
	 * Displays the forgot password form
	 */
	public function getForgot() {
		return View::make ( 'site/user/forgot' );
	}
	/**
	 * Attempt to reset password with given email
	 */
	public function postForgotPassword() {
		if (Confide::forgotPassword ( Input::get ( 'email' ) )) {
			$notice_msg = Lang::get ( 'confide::confide.alerts.password_forgot' );
			return Redirect::secure ( 'user/forgot' )->with ( 'notice', $notice_msg );
		} else {
			$error_msg = Lang::get ( 'confide::confide.alerts.wrong_password_forgot' );
			return Redirect::secure ( 'user/login' )->withInput ()->with ( 'error', $error_msg );
		}
	}
	/**
	 * Shows the change password form with the given token
	 */
	public function getReset($token) {
		return View::make ( 'site/user/reset' )->with ( 'token', $token );
	}
	/**
	 * Attempt change password of the user
	 */
	public function postReset() {
		
		$input = array (
				'token' => Input::get ( 'token' ),
				'password' => Input::get ( 'password' ),
				'password_confirmation' => Input::get ( 'password_confirmation' ) 
		);
		// By passing an array with the token, password and confirmation
		if ($this->userRepo->resetPassword ( $input )) {
			$notice_msg = Lang::get ( 'confide::confide.alerts.password_reset' );
			return Redirect::secure ( 'user/login' )->with ( 'notice', $notice_msg );
		} else {
			$error_msg = Lang::get ( 'confide::confide.alerts.wrong_password_reset' );
			return Redirect::secure ( 'user/reset/'.$input ['token'] )->withInput ()->with ( 'error', $error_msg );
		}
	}
	/**
	 * Log the user out of the application.
	 */
	public function getLogout() {
		Confide::logout ();
		return Redirect::secure ( '/' );
	}
	/**
	 * Get user's profile
	 * 
	 * @param
	 *        	$username
	 * @return mixed
	 */
	public function getProfile($username) {
		$userModel = new User ();
		$user = $userModel->getUserByUsername ( $username );
		// Check if the user exists
		if (is_null ( $user )) {
			return App::abort ( 404 );
		}
		return View::make ( 'site/user/profile', compact ( 'user' ) );
	}
	public function getSettings() {
		list ( $user, $redirect ) = User::checkAuthAndRedirect ( 'user/settings' );
		if ($redirect) {
			return $redirect;
		}
		return View::make ( 'site/user/profile', compact ( 'user' ) );
	}


	/**
	 * Construction de la vue permettant ) un utilisateur de gérer ses filtres (filtrages d'éléments comme
	 * les démarches, les idées selon des administrations, des taxonomies, ...
	 *
	 * @return \Illuminate\View\View
	 */
	public function getFilters() {

		$regions = Region::all();
		$publics = NostraPublic::orderBy('title')->get();
		$taxonomyCategories = TaxonomyCategory::orderBy('name')->get();


		$selectedAdministrationsIds = Auth::user()->filtersAdministration->lists('administration_id'); //lists() permet de ne sélectionner que certains attributs
		$selectedTagsIds = Auth::user()->filtersTag->lists('taxonomy_tag_id');
		$selectedPublicsIds = Auth::user()->filtersPublic->lists('nostra_public_id');

		return View::make('site.user.filters', compact('regions', 'publics', 'taxonomyCategories', 'selectedAdministrationsIds', 'selectedTagsIds', 'selectedPublicsIds'));
	}

	/**
	 * Sauvegarde des filtres
	 */
	public function postFilters() {

		// on  ne travaille pas avec une relation n-m entre user et administration mais avec un modèle à part entière
		// donc il ne faut pas se contenter de créer des relations, mais il faut bien instancier des nouveaux modèles

		UserFilterAdministration::where('user_id', '=', Auth::user()->id)->delete(); // on supprime toute relation existante

		// on crée les nouvelles
		if (Input::has('administrations')) {
			$data = [];
			foreach (Input::get('administrations') as $administrationId) {
				array_push($data, ['user_id' => Auth::user()->id, 'administration_id' => $administrationId, 'created_at' => new DateTime (), 'updated_at' => new DateTime ()]);
			}
			UserFilterAdministration::insert($data);
		}


		// on travaille de la meme manière avec les tags

		UserFilterTag::where('user_id', '=', Auth::user()->id)->delete();
		if (Input::has('tags')) {
			$data = [];
			foreach (Input::get('tags') as $tagId) {
				array_push($data, ['user_id' => Auth::user()->id, 'taxonomy_tag_id' => $tagId, 'created_at' => new DateTime (), 'updated_at' => new DateTime ()]);
			}
			UserFilterTag::insert($data);
		}


		// on travaille de la meme manière avec les publics

		UserFilterPublic::where('user_id', '=', Auth::user()->id)->delete();
		if (Input::has('publics')) {
			$data = [];
			foreach (Input::get('publics') as $publicId) {
				array_push($data, ['user_id' => Auth::user()->id, 'nostra_public_id' => $publicId, 'created_at' => new DateTime (), 'updated_at' => new DateTime ()]);
			}
			UserFilterPublic::insert($data);
		}


		// et on détruit les anciens filtres qu'on avait mis en session pour économiser les requetes (voir TraitFilterable pour le détail)
		Auth::user()->sessionDestroy('filteredAdministrationIds');
		Auth::user()->sessionDestroy('filteredTagsIds');
		Auth::user()->sessionDestroy('filteredPublicsIds');


		return Redirect::route('adminDashboardGetIndex')->with ( 'success', Lang::get ( 'user/user.filters.success' ) );

	}


	/**
	 * Process a dumb redirect.
	 * 
	 * @param
	 *        	$url1
	 * @param
	 *        	$url2
	 * @param
	 *        	$url3
	 * @return string
	 */
	public function processRedirect($url1, $url2, $url3) {
		$redirect = '';
		if (! empty ( $url1 )) {
			$redirect = $url1;
			$redirect .= (empty ( $url2 ) ? '' : '/' . $url2);
			$redirect .= (empty ( $url3 ) ? '' : '/' . $url3);
		}
		return $redirect;
	}
}
