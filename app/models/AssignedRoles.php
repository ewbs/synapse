<?php
/**
 * Liaisons entre les rôles et les utilisateurs
 * 
 * @property int $id         (PK)
 * @property int $user_id    Obligatoire, @see User
 * @property int $role_id    Obligatoire, @see Role
 * 
 * @author jdavreux
 */
class AssignedRoles extends Eloquent {
	protected $guarded = array ();
	public static $rules = array ();
}