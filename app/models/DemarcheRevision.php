<?php

/**
 * Révisions liées à une démarche
 *
 * @property int            $id                             (PK)
 * @property int            $user_id                        Obligatoire, @see User
 * @property float          $gain_potential_administration
 * @property float          $gain_potential_citizen
 * @property float          $gain_real_administration
 * @property float          $gain_real_citizen
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 *
 * @author mgrenson
 * 
 * Note : On gère l'historique des modifications (à chaque modif, on crée un nouvel élément).
 */
class DemarcheRevision extends TrashableModel {
	
	protected $table = 'demarchesRevisions';
	
	public function formRules() {
		return [
				//FIXME : pourquoi il ne veut pas de ces regex ??
			/*'gain_potential_administration' => 'regex:'.NumberHelper::DECIMAL_REGEX,
			'gain_potential_citizen' => 'regex:'.NumberHelper::DECIMAL_REGEX,
			'gain_real_administration' => 'regex:'.NumberHelper::DECIMAL_REGEX,
			'gain_real_citizen' => 'regex:'.NumberHelper::DECIMAL_REGEX,*/
		];
	}
	
	public function demarche() {
		return $this->belongsTo ( 'Demarche' );
	}
	
	public function user() {
		return $this->belongsTo ( 'User' );
	}
	
}
