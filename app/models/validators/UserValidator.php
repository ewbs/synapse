<?php namespace Synapse\models\validators;

class UserValidator extends \Zizaco\Confide\UserValidator {
	
	/**
	 * Validation rules for this Validator.
	 *
	 * @var array
	 */
	public $rules = [
		'create' => [
			'username' => 'required|min:3',
			'email'    => 'required|email',
			'password' => 'required|min:4',
		],
		'update' => [
			'username' => 'required|min:3',
			'email'    => 'required|email',
			'password' => 'required|min:4',
		]
	];
}
