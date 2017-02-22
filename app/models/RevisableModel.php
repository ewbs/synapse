<?php

/**
 * Classe de base à tous les modèles ayant des révisions
 * 
 * @abstract
 * @author mgrenson
 *
 */
abstract class RevisableModel extends TrashableModel {
	
	/**
	 * Liste des attributs supplémentaires qui seront ajoutés sur une révision lors de la sauvegarde de l'instance du modèle courant
	 * 
	 * @var array
	 */
	private $revisionAttributes = array();
	
	/**
	 * Stocke des attributs supplémentaires sur l'instance du modèle courant, afin de permettre automatiquement leur ajout à une révision lors de la sauvegarde de celui-ci
	 * 
	 * @param array $revisionAttributes
	 */
	public function addRevisionAttributes(array $revisionAttributes) {
		$this->revisionAttributes = array_merge($this->revisionAttributes, $revisionAttributes);
	}
	
	/**
	 * Liste des attributs supplémentaires qui seront ajoutés sur une révision lors de la sauvegarde de l'instance du modèle courant
	 */
	public function getRevisionAttributes() {
		return $this->revisionAttributes;
	}
	
	/**
	 * Retourne la première instance de RevisionModel correspondant au RevisableModel courant
	 *
	 * Attention, la recherche d'une première révision devrait inclure les révisions soft-deletées, le but est de retrouver la 1e version supprimée ou non.
	 * (Afin de par exemple retrouver le propriétaire du RevisableModel)
	 * @return RevisionModel
	 */
	public final function getFirstRevision() {
		return $this->revisions()->withTrashed()->orderBy ( 'created_at', 'ASC' )->first();
	}
	
	/**
	 * Retourne la dernière instance de RevisionModel correspondant au RevisableModel courant
	 *
	 * @param boolean $withTrashed inclure les versions supprimées, false par défaut
	 * @return RevisionModel
	*/
	public final function getLastRevision($withTrashed=false) {
		if($withTrashed)
			return $this->revisions()->withTrashed()->orderBy ( 'created_at', 'DESC' )->first();
		else
			return $this->revisions()->orderBy ( 'created_at', 'DESC' )->first();
	}
	
	/**
	 * Route pour visualiser l'historique d'une instance du modèle courant
	 *
	 * @param array|null $extra Un tableau de paramètres supplémentaires à placer dans l'url
	 * @return string
	 */
	public final function routeGetHistory($extra) {
		return $this->route('GetHistory', $extra);
	}
	
	/**
	 * Route pour obtenir l'historique d'une instance du modèle courant au format JSON
	 *
	 * @param array|null $extra Un tableau de paramètres supplémentaires à placer dans l'url
	 * @return string
	 */
	public final function routeGetHistoryData($extra) {
		return $this->route('GetHistoryData', $extra, false);
	}
	
	/**
	 * Relation vers les révisions
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public abstract function revisions();
	
	/**
	 * Liste les événements à enregistrer pour le modèle courant
	 *
	 * @see LaravelBook\Ardent\Ardent::boot()
	 */
	public static function boot() {
		parent::boot();
		
		/**
		 * Avant la sauvegarde (create, update, restore) de l'instance du modèle, on initie une transaction (afin que saving et saved se fassent dans une même transaction)
		 */
		self::saving(function(RevisableModel $modelInstance) {
			DB::beginTransaction();
		});
		
		/**
		 * Après la sauvegarde (create, update, restore) de l'instance du modèle courant, on :
		 * - assigne les attributs relatifs à la révision (soit ajoutés dans l'array revisionAttributes, soit repris de la révision précédente
		 * - crée la révision
		 * - commit la transaction
		 */
		self::saved(function(RevisableModel $modelInstance) {
			/* @var RevisionModel $revision */
			$revision=$modelInstance->revisions()->getRelated();
			$user=Auth::user();
			if($user) $revision->user()->associate($user); // Note : Les révisions pouvant être mises à jour par un intervenant NOSTRA externe (contexte des actions), il n'y a pas tjs de user connecté (mais tout de même dans ce cas un système de token qui garantit un minimum de sécurité)
				
			$lastRevision=null;
			
			// Pour chaque attribut défini au niveau de la révision, reprendre les valeurs :
			foreach($revision->attributes() as $attr) {
				
				// soit dans les revisionAttributes initialisés au niveau du RevisableModel
				if(array_key_exists($attr, $modelInstance->getRevisionAttributes())) {
					$val=$modelInstance->getRevisionAttributes()[$attr];
					if($val!==null && $val!=='') // Ne pas tenter d'ajouter un attribut à la révision si la valeur de cet attribut est vide ou nulle (par contre il pourrait être false, d'où l'intérêt de tester de cette manière)
						$revision->$attr=$val;
				}
				
				// soit via la révision précédente 
				else {
					if(!$lastRevision) {
						// Note : ne charger la dernière révision que si on passe au moins une fois par cet endroit
						$lastRevision = $modelInstance->getLastRevision(true);
					}
					if($lastRevision && !in_array($attr,['created_at', 'updated_at', 'deleted_at', 'comment'])) {
						// Note : Vu que l'on peut citer ces attributs comme étant à reprendre depuis le RevisableModel, il faut explicitement les ignorer dans le cas où on les reprend de la révision précédente.
						$revision->$attr=$lastRevision->$attr;
					}
				}
			}
			$modelInstance->revisions()->save($revision);
			DB::commit();
		});
		
		/**
		 * Avant la suppression d'une instance du modèle courant, on initie une transaction (afin que deleting et deleted se fassent dans une même transaction)
		 */
		self::deleting(function(RevisableModel $modelInstance) {
			if(!$modelInstance->forceDeleting)
				DB::beginTransaction();
		});
		
		/**
		 * Après la suppression d'une instance du modèle courant, si on est dans le cas d'un soft-delete, on :
		 * - crée une nouvelle révision, identique à la précédente
		 * - adapte les dates et le user_id
		 * - sauvegarde la révision
		 * - commit la transaction
		 */
		self::deleted(function(RevisableModel $modelInstance) {
			if(!$modelInstance->forceDeleting) {
				/* @var RevisionModel $revision */
				$revision=$modelInstance->getLastRevision()->replicate();
				$revision->user()->associate(Auth::user());
				$revision->created_at=$modelInstance->deleted_at;
				$revision->updated_at=$modelInstance->deleted_at;
				$revision->deleted_at=$modelInstance->deleted_at;
				if($revision->save()) {
					DB::commit();
				}
				else {
					DB::rollBack();
					throw new Exception(get_class($modelInstance)." {$modelInstance->id} not deleted, because revision could not be created. validationErrors : ".print_r($revision->validationErrors->toArray(), true));
				}
			}
		});
	}
}
