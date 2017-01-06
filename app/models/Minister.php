<?php
/**
 * Ministres
 *
 * @property int            $id              (PK)
 * @property string         $firstname       Maximum 255 caractères
 * @property string         $lastname        Maximum 255 caractères
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 *
 * @author jdavreux
 */
class Minister extends Eloquent {
	
	use SoftDeletingTrait;
	protected $table = 'ministers';
	public function ideas() {
		return $this->belongsToMany ( 'Idea' );
	}
	public function governements() {
		return $this->belongsToMany ( 'Governement' );
	}
	
	/*
	 * public static function getAMinistersByGovernementId( $governementId ) {
	 *
	 * return Minister::where('regions_id', '=', $regionId)->get();
	 *
	 * }
	 */
	public static function getArrayOfGovernementsAndMinisters() {
		return Governement::getGovernementsByAlphabeticalOrder ();
	}
}
