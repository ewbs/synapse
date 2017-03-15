<?php
/**
 * Ministres
 *
 * @property string         $firstname       Maximum 255 caractères
 * @property string         $lastname        Maximum 255 caractères
 *
 * @author jdavreux
 */
class Minister extends TrashableModel {
	
	protected $table = 'ministers';
	
	/**
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function ideas() {
		return $this->belongsToMany ( 'Idea' );
	}
	
	/**
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function governements() {
		return $this->belongsToMany ( 'Governement' );
	}
}
