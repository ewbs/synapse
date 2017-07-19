<?php
use Zizaco\Confide\ConfideUserInterface;
use Zizaco\Entrust\HasRole;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * Utilisateurs
 *
 * @property string         $username           Obligatoire, maximum 255 caractères
 * @property string         $email              Obligatoire, maximum 255 caractères
 * @property string         $password           Obligatoire, maximum 255 caractères
 * @property string         $confirmation_code  Obligatoire, maximum 255 caractères
 * @property string         $remember_token     Obligatoire, maximum 255 caractères
 * @property boolean        $confirmed          Obligatoire, false par défaut
 * @author jdavreux
 */
class User extends TrashableModel implements ConfideUserInterface {
	use ArdentConfideUser, HasRole;
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see ManageableModel::permissionManage()
	 */
	public function permissionManage() {
		return 'manage_users';
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see ManageableModel::name()
	 */
	public function name() {
		return $this->username;
	}
	
	/**
	 * Vérifie si l'utilisateur est soumis à des restrictions par administrations
	 * 
	 * @return type
	 */
	public function hasRestrictionsByAdministrations() {
		return ($this->administrations->count () > 0);
	}
	
	/**
	 * Vérifie si l'utilisateur peut avoir accès à au moins une des administrations passées en paramètres (id d'aministration)
	 * La fonction retournera vrai si au moins une des administrations est acceptée
	 * 
	 * @param type $administrationsIds
	 *        	: tableau d'ids d'administrations ou id d'administration seul
	 */
	public function hasRightsForAtLeastOneAdministration($administrationsIds) {
		// si on a passé un id seul, on le transforme en tableau
		if (! is_array ( $administrationsIds )) {
			$administrationsIds [0] = $administrationsIds;
		}
		
		foreach ( $this->administrations as $adm ) {
			if (in_array ( $adm->id, $administrationsIds )) {
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * 
	 */
	public function getRestrictedAdministrationsIds() {
		$return = array ();
		if ($this->hasRestrictionsByAdministrations ()) {
			foreach ( $this->administrations as $adm ) {
				array_push ( $return, $adm->id );
			}
		}
		return ($return);
	}
	
	/**
	 * Get user by username
	 * 
	 * @param $username
	 * @return mixed
	 */
	public function getUserByUsername($username) {
		return $this->where ( 'username', '=', $username )->first ();
	}
	
	/**
	 * Find the user and check whether they are confirmed
	 *
	 * @param array $identity an array with identities to check (eg. ['username' => 'test'])
	 * @return boolean
	 */
	public function isConfirmed($identity) {
		$user = Confide::getUserByEmailOrUsername ( $identity );
		return ($user && $user->confirmed);
	}
	
	/**
	 * Get the date the user was created.
	 *
	 * @return string
	 */
	public function joined() {
		return String::date ( Carbon::createFromFormat ( 'Y-n-j G:i:s', $this->created_at ) );
	}
	
	/**
	 * Save roles inputted from multiselect
	 * 
	 * @param $inputRoles
	 */
	public function saveRoles($inputRoles) {
		if (! empty ( $inputRoles )) {
			$this->roles ()->sync ( $inputRoles );
		} else {
			$this->roles ()->detach ();
		}
	}
	
	/**
	 * Returns user's current role ids only.
	 * 
	 * @return array|bool
	 */
	public function currentRoleIds() {
		$roles = $this->roles;
		$roleIds = false;
		if (! empty ( $roles )) {
			$roleIds = array ();
			foreach ( $roles as $role ) {
				$roleIds [] = $role->id;
			}
		}
		return $roleIds;
	}
	
	/**
	 * Returns user's current role names only.
	 * 
	 * @return array|bool
	 */
	public function currentRoleNames() {
		$roles = $this->roles;
		$roleNames = false;
		if (! empty ( $roles )) {
			$roleNames = array ();
			foreach ( $roles as $role ) {
				$roleNames [] = $role->name;
			}
		}
		return $roleNames;
	}
	
	/**
	 * Retourne les administrations liées (restrictions de contenu)
	 * 
	 * @return array|bool
	 */
	public function currentAdministrationIds() {
		$roles = $this->administrations;
		$roleIds = false;
		if (! empty ( $roles )) {
			$roleIds = array ();
			foreach ( $roles as $role ) {
				$roleIds [] = $role->id;
			}
		}
		return $roleIds;
	}
	
	/**
	 * Redirect after auth.
	 * If ifValid is set to true it will redirect a logged in user.
	 * 
	 * @param
	 *        	$redirect
	 * @param bool $ifValid        	
	 * @return mixed
	 */
	public static function checkAuthAndRedirect($redirect, $ifValid = false) {
		// Get the user information
		$user = Auth::user ();
		$redirectTo = false;
		
		if (empty ( $user->id ) && ! $ifValid) // Not logged in redirect, set session.
{
			Session::put ( 'loginRedirect', $redirect );
			$redirectTo = Redirect::to ( 'user/login' )->with ( 'notice', Lang::get ( 'user/user.login_first' ) );
		} elseif (! empty ( $user->id ) && $ifValid) // Valid user, we want to redirect.
{
			$redirectTo = Redirect::to ( $redirect );
		}
		
		return array (
				$user,
				$redirectTo 
		);
	}
	public function currentUser() {
		return Confide::user ();
	}
	
	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail() {
		return $this->email;
	}
	
	/**
	 * Surcharge de la méthode plaçant une info dans la session, afin de la restreindre au scope user
	 * 
	 * @param string|array $key
	 * @param mixed|null $value
	 * @return void 
	 */
	public function sessionSet($key, $value) {
		Session::put('user_'.$key, $value);
	}
	
	/**
	 * Surcharge de la méthode récupérant une info de la session, afin de la restreindre au scope user
	 * 
	 * @param string $name The attribute name
	 * @param mixed $default The default value if not found.
	 * @return mixed
	 */
	public function sessionGet($name, $default=null) {
		return Session::get('user_'.$name, $default);
	}
	
	/**
	 * Surcharge de la méthode supprimant une info de la session, afin de la restreindre au scope user
	 * 
	 * @param string $key
	 * @return void 
	 */
	public function sessionDestroy($key) {
		Session::forget('user_'.$key);
	}
	
	/**
	 * 
	 * @param Builder $query
	 * @return unknown
	 */
	public function scopeEwbsOrSelf(Builder $query) {
		return $query
		->leftjoin('ewbs_members', 'ewbs_members.user_id', '=', 'users.id')
		->whereNotNull('ewbs_members.id')
		->orWhere('users.id', '=', Auth::user()->id)
		->order();
	}
	
	/**
	 * 
	 * @param Builder $query
	 * @return unknown
	 */
	public function scopeOrder(Builder $query) {
		return $query->orderBy('username');
	}
	
	/**
	 * 
	 * Relation entre le user et les administrations dont il fait partie.
	 * Attention, utiliser pour gérer des ACL entre utilisateurs et Administration (restriction de contenu)
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function administrations() {
		return ($this->belongsToMany ( 'Administration' ));
	}
	
	/**
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function demarches() {
		return $this->hasMany ( 'Demarche' );
	}
	
	/**
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function ewbsActionRevisions() {
		return $this->hasMany('EwbsActionRevision');
	}
	
	/**
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function EWBSMember() {
		return $this->hasOne ( 'EWBSMember' );
	}
	
	/**
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function filtersAdministration() {
		return $this->hasMany('UserFilterAdministration');
	}
	
	/**
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function filtersExpertise() {
		return $this->hasMany('UserFilterExpertise');
	}
	
	/**
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function filtersPublic() {
		return $this->hasMany('UserFilterPublic');
	}
	
	/**
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function filtersTag() {
		return $this->hasMany('UserFilterTag');
	}
	
	/**
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function ideas() {
		return $this->hasMany ( 'Idea' );
	}
}