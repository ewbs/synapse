<?php
use Illuminate\Database\Eloquent\Builder;

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
	
	/**
	 * Query scope triant les pôles par la colonne order
	 *
	 * @param Builder $query
	 * @return Builder
	 */
	public function scopeOrdered(Builder $query) {
		return $query->orderBy('order', 'asc');
	}
}
