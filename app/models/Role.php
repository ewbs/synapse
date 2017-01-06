<?php
use Zizaco\Entrust\EntrustRole;

/**
 * Rôles
 *
 * @property int            $id           (PK)
 * @property string         $name         Obligatoire, maximum 255 caractères
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @author jdavreux
 */
class Role extends EntrustRole {
	
	/**
	 * Provide an array of strings that map to valid roles.
	 * 
	 * @param array $roles
	 * @return stdClass
	 */
	public function validateRoles(array $roles) {
		$user = Confide::user ();
		$roleValidation = new stdClass ();
		foreach ( $roles as $role ) {
			// Make sure theres a valid user, then check role.
			$roleValidation->$role = (empty ( $user ) ? false : $user->hasRole ( $role ));
		}
		return $roleValidation;
	}
}