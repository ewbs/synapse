<?php

/**
 * Cette classe symbolise un fichier XLS de SCM Light uploadé et lié à une démarche
 * Ces données sont les suivantes :
 * 
 * - id (PK)
 * - demarche_id (FK)                               id de la démarche
 * - user_id (FK)									id de l'utilisateur qui a uploadé ceci
 * - filename (TEXT)									nom du fichier XLS(x)(m)
 * - processed (INTEGER, Nullable)			        le fichier a-t'il été traité ? On peut ainsi différer le traitement d'un fichier avec ce flag
 * - created_at          
 * - updated_at
 * 
 */
class DemarcheSCM extends Eloquent {
	
	public static $path='/uploads/scm/';
	
	protected $table = 'demarche_scms';
	public function demarche() {
		return $this->belongsTo ( 'Demarche' );
	}
	public function user() {
		return $this->belongsTo ( 'User' );
	}
	
	public function getFilePath() {
		return storage_path().static::$path.$this->filename;
	}
	
	/**
	 * Interception du boot du modèle afin de définir des événements
	 */
	public static function boot() {
		parent::boot();
		
		/**
		 * Interception de la suppression de l'instance du modèle afin de supprimer automatiquement le fichier XLS stocké
		 */
		DemarcheSCM::deleted(function($item) {
			File::delete(storage_path().static::$path.$item->filename);
		});
	}
}
