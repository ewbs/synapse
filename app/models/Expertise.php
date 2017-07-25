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
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function filters() {
		return $this->hasMany('UserFilterExpertise');
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
	
	/**
	 * Ajoute les données des expertises à la sélection 
	 * 
	 * @param Builder $query
	 * @return unknown
	 */
	public function scopeEach(Builder $query) {
		return $query
		->addSelect(['expertises.*']);
	}
	
	/**
	 * Trie les expertises par la colonne order
	 *
	 * @param Builder $query
	 * @return Builder
	 */
	public function scopeOrdered(Builder $query) {
		return $query->orderBy('order', 'asc');
	}
	
	/**
	 * Restreindre aux expertises liées à un pôle
	 * 
	 * @param Builder $query
	 * @param Pole $pole
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeForPole(Builder $query, Pole $pole) {
		return $query->where('pole_id', '=', $pole->id);
	}
	
	/**
	 * Ajouter le nombre d'actions en cours à chaque expertise
	 * La propriété "actions" est alors disponible
	 * 
	 * @param Builder $query
	 * @return unknown
	 */
	public function scopeCountActions(Builder $query) {
		return $query->addSelect(DB::raw('(
			SELECT count(*)
			FROM "ewbsActions" AS a
			JOIN v_lastrevisionewbsaction AS ra ON a.id=ra.ewbs_action_id
			WHERE a.deleted_at IS NULL
			AND ra.state IN(\''.EwbsActionRevision::$STATE_TODO.'\', \''.EwbsActionRevision::$STATE_PROGRESS.'\', \''.EwbsActionRevision::$STATE_STANDBY.'\')
			AND a.name=expertises.name
		) AS actions'));
	}
	
	/**
	 * Relation vers le pôle
	 * 
	 * @see Pole
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function pole() {
		return $this->belongsTo('Pole');
	}
}
