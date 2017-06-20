<?php
/**
 * Pôles
 *
 * @property string         $name         Obligatoire
 * @property int            $order        Obligatoire
 * @author mgrenson
 */
class Pole extends TrashableModel {
	
	/**
	 * Relation vers les expertises
	 * @see Expertise
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function expertises() {
		return $this->hasMany('Expertise');
	}
}
