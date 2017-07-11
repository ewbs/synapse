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
	
	/**
	 * Retourne les noms des différentes expertises triées par la colonne order
	 * 
	 * @param string $including Un nom à inclure en début de liste s'il ne fait pas partie des noms d'expertises
	 */
	public static function names($including=null) {
		$aExpertises=DB::table('expertises')->orderBy('order')->lists('name');
		if($including && !in_array($including, $aExpertises)) {
			array_unshift($aExpertises, $including);
		}
		return $aExpertises;
	}
}
