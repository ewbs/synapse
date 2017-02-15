<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

/**
 * Classe de base à tous les modèles soft-deletable dans Synapse
 * 
 * @property \Carbon\Carbon $deleted_at   Date de suppression
 * @author mgrenson
 *
 */
abstract class TrashableModel extends ManageableModel {
	
	use SoftDeletingTrait;
	
	/**
	 * Route listant les instances du modèle courant supprimées
	 *
	 * @return string
	 */
	public final function routeGetTrash() {
		return route($this->getModelLabel() . 'GetTrash');
	}
	
	/**
	 * Route listant les instances du modèle courant supprimées au format json
	 *
	 * @return string
	 */
	public final function routeGetDataTrash() {
		return route($this->getModelLabel() . 'GetDataTrash');
	}
	
	/**
	 * Route proposant la restauration d'une instance du modèle courant
	 *
	 * @return string
	 */
	public final function routeGetRestore() {
		return route($this->getModelLabel() . 'GetRestore', $this->id);
	}
	
	/**
	 * Route restaurant une instance du modèle courant
	 *
	 * @return string
	 */
	public final function routePostRestore() {
		return route($this->getModelLabel() . 'PostRestore', $this->id);
	}
	
	// TODO : Log des commits sur les TrashableModel
	/*
	public static function boot() {
		
		self::restored(function(TrashableModel $modelInstance) {
			$modelInstance->logEvent('restored');
		});
	
		self::deleted(function(TrashableModel $modelInstance) {
			$modelInstance->logEvent($modelInstance->forceDeleting?'hard_deleted':'soft_deleted');
		});
		
		parent::boot();
	}*/
}
