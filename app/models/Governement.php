<?php
/**
 * Gouvernements
 *
 * @property int            $id          (PK)
 * @property string         $name        Maximum 255 caractères
 * @property string         $shortname   Maximum 255 caractères
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 *
 * @author jdavreux
 */
class Governement extends Eloquent {
	
	use SoftDeletingTrait;
	protected $table = 'governements';
	// public $arrayAdministrations = array();
	
	/*
	 * public static function getGovernementsByAlphabeticalOrder( ) {
	 *
	 * return Governement::orderBy('name')->get();
	 *
	 * }
	 */
	public function ministers() {
		return $this->belongsToMany ( 'Minister' );
	}
}
