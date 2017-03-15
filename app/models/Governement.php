<?php
/**
 * Gouvernements
 *
 * @property string         $name        Maximum 255 caractères
 * @property string         $shortname   Maximum 255 caractères
 *
 * @author jdavreux
 */
class Governement extends TrashableModel {
	
	protected $table = 'governements';
	
	/**
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function ministers() {
		return $this->belongsToMany ( 'Minister' );
	}
}
