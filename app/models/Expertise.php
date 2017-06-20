<?php
use Illuminate\Database\Eloquent\Builder;
/**
 * Expertises
 *
 * @property string         $name         Obligatoire
 * @property int            $order        Obligatoire
 * @property int            $pole_id      @see Pole
 * @author mgrenson
 */
class Expertise extends TrashableModel {
	
	/**
	 * Relation vers le pôle
	 * @see Pole
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function pole() {
		return $this->belongsTo('Pole');
	}
	
	/**
	 * Query scope triant les expertises par la colonne order
	 *
	 * @param Builder $query
	 * @return Builder
	 */
	public function scopeOrdered(Builder $query) {
		return $query->orderBy('order', 'asc');
	}
}
