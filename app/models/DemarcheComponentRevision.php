<?php

/**
 * Révisions d'un composant de démarche
 * 
 * @property string         $comment
 * @property float          $cost_administration_currency   Obligatoire
 * @property float          $cost_citizen_currency          Obligatoire
 * @property int            $volume                         Obligatoire
 * @property int            $frequency                      Obligatoire
 * @property float          $gain_potential_administration  Obligatoire
 * @property float          $gain_potential_citizen         Obligatoire
 * @property float          $gain_real_administration       Obligatoire
 * @property float          $gain_real_citizen              Obligatoire
 * @property int            $current_state_id
 * @property int            $next_state_id
 * 
 * @author mgrenson
 */
abstract class DemarcheComponentRevision extends RevisionModel {
	
	/**
	 * {@inheritDoc}
	 * @see RevisionModel::attributes()
	 */
	public function attributes(){
		return [
			'comment',
			'cost_administration_currency',
			'cost_citizen_currency',
			'volume',
			'frequency',
			'gain_potential_administration',
			'gain_potential_citizen',
			'gain_real_administration',
			'gain_real_citizen',
			'current_state_id',
			'next_state_id',
			'created_at',
		];
	}
	
	/**
	 * Détermine si cette révision est la plus récente en incluant les versions soft-deleted
	 *
	 * @return boolean
	 */
	private function isLastRevision() {
		$col=$this->revisable()->getForeignKey();
		return ($this->created_at == $this->where($col, '=', $this->attributes[$col])->withTrashed()->max('created_at'));
	}
	
	/**
	 * Détermine si cette révision est la plus récente active
	 *
	 * @return boolean
	 */
	private function isLastActiveRevision() {
		$col=$this->revisable()->getForeignKey();
		return ($this->created_at == $this->where($col, '=', $this->attributes[$col])->max('created_at'));
	}
	
	/**
	 * Détermine si cette révision affecte le calcul automatique des gains
	 *
	 * @return boolean
	 */
	public function affectsCalculatedGains() {
		return $this->isLastActiveRevision() || $this->isLastRevision();
	}
	
	/**
	 * Récupère l'avant-dernière version
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function getPreviousRevision() {
		$col=$this->revisable()->getForeignKey();
		return $this
		->withTrashed()
		->where($col, '=', $this->attributes[$col])
		->where('id', '<', $this->id)
		->orderBy('created_at', 'DESC')
		->first();
	}
	
	/**
	 * Relation vers l'état courant lié
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\belongsTo
	 */
	public abstract function current_state();
	
	/**
	 * Relation vers l'état suivant lié
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\belongsTo
	 */
	public abstract function next_state();
	
	/**
	 * Relation vers le user lié
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user() {
		return $this->belongsTo ( 'User' );
	}
	
	/**
	 * @see \LaravelBook\Ardent\Ardent::boot()
	 * @return void
	 */
	public static function boot() {
		parent::boot();
	
		/**
		 * Avant la validation de l'instance du modèle, on met automatiquement à jour les champs calculés.
		 */
		self::validating(function(DemarcheComponentRevision $modelInstance) {
			$modelInstance->gain_potential_administration = $modelInstance->volume * $modelInstance->frequency * $modelInstance->cost_administration_currency;
			$modelInstance->gain_potential_citizen = $modelInstance->volume * $modelInstance->frequency * $modelInstance->cost_citizen_currency;
		});
	}
}
