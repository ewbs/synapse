<?php

/**
 * Liaisons entre les composants (pièces, tâches) et les démarches
 * 
 * @property int            $demarche_id                    Obligatoire, @see Demarche
 * @property string         $name
 * @author mgrenson
 */
abstract class DemarcheComponent extends RevisableModel {

	use TraitFilterable;
	
	/**
	 *
	 * {@inheritDoc}
	 * @see ManageableModel::permissionManage()
	 */
	public function permissionManage() {
		return 'pieces_tasks_manage';
	}
	
	/**
	 * Colonne correspondant à l'ID du composant
	 * 
	 * @return string
	 */
	public function componentColumn() {
		return $this->componentType().'_id';
	}
	
	public function componentId() {
		return $this->getAttribute($this->componentColumn());
	}
	
	/**
	 * Type de composant
	 * 
	 * @return string
	 */
	public abstract function componentType();
	
	/**
	 *
	 * {@inheritDoc}
	 * @see ManageableModel::formRules()
	 */
	public function formRules() {
		return array_merge([
			'cost_administration_currency' => 'required',
			'cost_citizen_currency' => 'required',
			'volume' => 'required',
			'frequency' => 'required',
			'gain_real_administration' => 'required',
			'gain_real_citizen' => 'required',
			],
			$this->formRulesChoose()
		);
	}
	
	/**
	 * Contraintes de validation spécifiques à la phase de sélection d'un composant
	 * (afin de ne pouvoir checker que celles-là au niveau du controller)
	 * 
	 * @return array
	 */
	public function formRulesChoose(){
		return [];
	}
	
	/**
	 * Retourne la liste des gains d'une démarche impactés par un changement au niveau d'un composant d'une démarche
	 *
	 * @param string $action manage|delete|destroy
	 * @return array tableau avec pour chaque gain les valeurs old et new, ex. ['gain_potential_administration'=>['old=>'20,00€', 'new'=>'30,00€']]
	*/
	public function gainsToAdjust($action = 'manage') {
		$gains = [];
		$demarche = $this->demarche; /* @var Demarche $demarche */
		$demarcheRevision = $demarche->getLastRevision ();
		
		if(!$demarcheRevision)
			return $gains;
		
		$demarche_component_revision=$this->getLastRevision(true); /* @var DemarcheComponentRevision $demarche_component_revision */
		
		if($action=='destroy' && !($demarche_component_revision->affectsCalculatedGains () && $demarche->isLastRevisionAdjustingGains ()))
			return $gains;
				
		// Aller puiser les gains dans la dernière révison, sauf si on vient de la soft-deleter (car là on voudra juste déduire complètement les gains de cette version supprimée)
		$demarche_component_previous_revision = ($action == 'delete') ? null : $demarche_component_revision->getPreviousRevision ();

		foreach([
			'gain_potential_administration',
			'gain_real_administration',
			'gain_potential_citizen',
			'gain_real_citizen'
		] as $gainName) {
			$oldGain=$demarcheRevision->$gainName;
			if(!$oldGain) continue;
			
			// Si dans le composant de la démarche on a une version précédente non supprimée dont le gain est positif on le considère, sinon cela équivaut à 0
			$previousItemGain=(($demarche_component_previous_revision && $demarche_component_previous_revision->$gainName && ! $demarche_component_previous_revision->trashed ()) ? $demarche_component_previous_revision->$gainName : 0);
				
			if ($action == 'delete' || $action == 'destroy') {
				if ($action == 'destroy' && $demarche_component_revision->trashed ()) {
					$demarche_component_revision->$gainName = 0; // si la version que l'on détruit est en fait une soft-deletée, on ne doit pas considérer son montant pour calculer la différence à appliquer
				}
				$diff = $previousItemGain - $demarche_component_revision->$gainName;
			}
			else {// En cas de création donc
				$diff = $demarche_component_revision->$gainName - $previousItemGain;
			}
			if ($diff != 0) { // Si différence positive ou négative, on l'ajoute aux gains concernés par l'ajustement
				$gains[$gainName]['old']=NumberHelper::moneyFormat ( $oldGain );
				$gains[$gainName]['new']=NumberHelper::moneyFormat ( $oldGain + $diff );
				$gains[$gainName]['amount']=$oldGain + $diff;
			}
		}
		return $gains;
	}
	
	/**
	 * Relation vers les actions liées
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function actions() {
		return $this->hasMany ( 'EwbsAction' );
	}
	
	/**
	 * Relation vers le composant lié
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public abstract function component();
	
	/**
	 * Relation vers la démarche liée
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function demarche() {
		return $this->belongsTo ( 'Demarche' );
	}



	/**
	 * SCOPES
	 */

	public function scopeNostraPublicsIds($query, $publicsIds) {
		if (is_array ( $publicsIds ) && count ( $publicsIds )) {
			return $query->with(['demarche' => function($query) {
				$query->where(function ($query) use ($publicsIds) {
					$query->whereHas('nostraDemarche', function ($query) use ($publicsIds) {
						$query->whereHas('nostraPublics', function ($query) use ($publicsIds) {
							$query->whereIn('nostra_publics.id', $publicsIds);
						});
					});
				});
			}]);
		}
		return $query;
	}
	public function scopeAdministrationsIds($query, $administrationsIds) {
		if (is_array ( $administrationsIds ) && count ( $administrationsIds )) {
			return
					$query->with(['demarche' => function ($query) {
						$query->wherehas('administrations', function ($query) use ($administrationsIds) {
							$query->whereIn('administrations.id', $administrationsIds);
						});
					} ]);
		}
		return $query;
	}
	public function scopeTaxonomyTagsIds($query, $tagsIds) {
		if (is_array ( $tagsIds ) && count ( $tagsIds )) {
			return $query->with(['demarche' => function ($query) {
				$query->wherehas('tags', function ($query) use ($tagsIds) {
					$query->whereIn('taxonomytags.id', $tagsIds);
				});
			}]);
		}
		return $query;
	}

}
