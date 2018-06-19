<?php
/**
 * Régions
 *
 * @property int            $id           (PK)
 * @property string         $name         Maximum 255 caractères
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 *
 * @author jdavreux
 */
class Region extends Eloquent {
	
	use SoftDeletingTrait;
	protected $table = 'regions';
	public function administrations() {
		return $this->hasMany ( 'Administration' );
	}
}
