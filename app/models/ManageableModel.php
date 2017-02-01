<?php
use LaravelBook\Ardent\Ardent;

/**
 * Classe de base à tous les modèles à gérer dans Synapse
 * 
 * On part de l'hypothèse que les modèles étendus auront bien au minimum les propriétés ci-dessous
 * 
 * @property int            $id              (PK)
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @abstract
 * @author mgrenson
 *
 */
abstract class ManageableModel extends Ardent {
	
	/**
	 * Initialisation
	 * 
	 * @throws Exception
	 */
	public function __construct() {
		parent::__construct();
		// en dessous de php 5.5, il est interdit de faire un empty($this->formRules()) ... donc obligé de d'abord créer une var -- JDA 2016-04-11
		$fRules = $this->formRules();
		if(!empty($fRules) && !empty(static::$rules))
			throw new Exception('Ne pas mélanger les règles de validation au niveau du modèle (cf. Ardent) et du formulaire (cf. formRules()), choisir l\'une ou l\'autre');
	}
	
	/**
	 * Règles pour la validation de niveau formulaire
	 * 
	 * @return array
	 */
	public function formRules() {return [];}
	
	/**
	 * Messages liés aux règles pour la validation de niveau formulaire
	 *
	 * @return array
	 */
	public function formRulesMessages() {return [];}
	
	/**
	 * Possibilité d'effectuer des vérifications sur mesure sur les input reçus de la requête (lorsque la définition de rules ne suffit pas),
	 * et d'ajouter dans ce cas des erreurs à l'objet $validator
	 * 
	 * @param \Illuminate\Validation\Validator $validator
	 * @return boolean
	 */
	public function extraFormValidate(\Illuminate\Validation\Validator $validator) {return true;}
	
	/**
	 * Retourne un intitulé représentant l'instance du modèle courant. Par défaut il s'agit de la propriété name.
	 * 
	 * Cette méthode doit être redéfinie pour un modèle n'ayant pas cette propriété.
	 * @return string
	 */
	public function name() {
		return $this->name;
	}
	
	/**
	 * Spécifie si la suppression peut être rendue possible si l'instance du modèle courant a des éléments liés
	 *
	 * Par défaut non
	 * @return boolean
	 */
	public function deletableIfLinked() {
		return false;
	}
	
	/**
	 * Le modèle a-t-il une route dédiée à la visualisation ?
	 * 
	 * Non par défaut
	 */
	public function hasView() {
		return false;
	}
	
	/**
	 * Formate l'identifiant du modèle courant
	 * @return string
	 */
	public function formatedId() {
		return self::formatId($this->id);
	}
	
	/**
	 * Formate un identifiant
	 * 
	 * @param int $id Identifiant à formater
	 * @param int $pad_length Longueur souhaitée de la châine
	 * @param string $prefix Préfixe automatiquement ajouté devant l'identiant formaté
	 * @return string
	 */
	public static function formatId($id, $pad_length=5, $prefix='#') {
		if($id) return $prefix . str_pad($id, $pad_length, "0", STR_PAD_LEFT);
		return '';
	}
	
	/**
	 * Vérifie si on dispose de droits de gestion sur l'instance du modèle courant, à savoir :
	 * 
	 * - Si on est le propriétaire (id du user courant = au champ user_id)
	 * - Si on a le rôle de gestionnnaire ET que des éventuelles restrictions supplémentaires ne brident pas ce droit de gestion
	 *  
	 * @param \User $loggedUser l'utilisateur concerné par la vérification, par défaut le user connecté
	 * @return boolean
	 */
	public final function canManage(\User $loggedUser=null) {
		if(!$loggedUser) $loggedUser=Auth::user();
		
		if($this instanceof RevisableModel) {
			$owner_id=$this->getFirstRevision()->user_id;
		}
		else
			$owner_id=$this->user_id;
		
		if ($loggedUser->id == $owner_id) // Si on est le propriétaire, on a d'office accès
			return true;
		elseif ($loggedUser->can ( $this->permissionManage() )) { // Si on a le rôle de gestionnaire
			return $this->checkManageRestrictions($loggedUser);
		}
		return false;
	}
	
	/**
	 * Donne la possibilité à des modèles de vérifier via des restrictions supplémentaires
	 * que l'on a bien le droit de gérer l'instance du modèle courant lorsque les tests de base
	 * de la méthode canManage() sont positifs
	 * 
	 * @param \User $loggedUser
	 * @return boolean
 */
	public function checkManageRestrictions(\User $loggedUser) {
		return true;
	}
	
	/**
	 * Retourne le label correspondant au modèle, correspond par défaut au nom de la table (la règle par défaut étant le nom de la classe en minuscule et au pluriel)
	 *
	 * @return string
	 */
	public function getModelLabel() {
		return $this->getTable();
	}
	
	/**
	 * Retourne le label correspondant au modèle, chaques mots étant remis en minuscule et singulier, et séparés par un '_'
	 */
	public function getModelLabelSingularSnake() {
		$chain='';
		foreach (explode('_', snake_case($this->getModelLabel())) as $word) {
			if($chain) $chain.='_';
			$chain.=str_singular($word);
		}
		return $chain;
	}
	
	/**
	 * Nom de la permission de gestion correspondant au modèle courant
	 * 
	 * @return string la permission
	 */
	public function permissionManage() {
		return $this->getModelLabel().'_manage';
	}
	
	/**
	 * Route listant les instances du modèle courant
	 *
	 * @return string
	 */
	public function routeGetIndex() {
		return route($this->getModelLabel() . 'GetIndex');
	}
	
	/**
	 * Route listant les instances du modèle courant au format json
	 *
	 * @return string
	 */
	public final function routeGetData() {
		return route($this->getModelLabel() . 'GetData');
	}

	/**
	 * Route listant les instances du modèle courant au format Json MAIS selon les filtres de l'utilisateur
	 *
	 * @return string
	 */
	public final function routeGetFilteredData() {
		return route($this->getModelLabel() . 'GetFilteredData');
	}
	
	/**
	 * Route proposant de visualiser une instance du modèle courant
	 *
	 * Par défaut la route considérée est celle d'édition, car tous les ManageableModel n'ont pas de route propre à la visualisation.
	 * Cette méthode peut alors être redéfinie pour le ManageableModel qui en définit un.
	 * @return string
	 */
	public final function routeGetView() {
		if($this->hasView())
			return route($this->getModelLabel() . 'GetView', $this->id);
		return $this->routeGetEdit();
	}
	
	/**
	 * Route proposant de créer une instance du modèle courant
	 *
	 * @param array|null $extra Un tableau de paramètres supplémentaires à placer dans l'url
	 * @return string
	 */
	public final function routeGetCreate($extra=null) {
		return $this->route('GetCreate', $extra, false);
	}
	
	/**
	 * Route proposant d'éditer une instance du modèle courant
	 *
	 * @param array|null $extra Un tableau de paramètres supplémentaires à placer dans l'url
	 * @return string
	 */
	public final function routeGetEdit($extra=null) {
		return $this->route('GetEdit', $extra);
	}
	
	/**
	 * Route proposant de créer ou mettre à jour une instance du modèle courant
	 * 
	 * @param array|null $extra Un tableau de paramètres supplémentaires à placer dans l'url
	 * @return string
	 */
	public final function routeGetManage($extra=null) {
		if($this->id)
			return $this->routeGetEdit($extra);
		return $this->routeGetCreate($extra);
	}
	
	/**
	 * Route postant la demande de création d'une instance du modèle courant
	 *
	 * @param array|null $extra Un tableau de paramètres supplémentaires à placer dans l'url
	 * @return string
	 */
	public final function routePostCreate($extra=null) {
		return $this->route('PostCreate', $extra);
	}
	
	/**
	 * Route postant la demande d'édition d'une instance du modèle courant
	 *
	 * @param array|null $extra Un tableau de paramètres supplémentaires à placer dans l'url
	 * @return string
	 */
	public final function routePostEdit($extra=null){
		return $this->route('PostEdit', $extra);
	}
	
	/**
	 * Route postant la demande de création ou d'édition d'une instance du modèle courant
	 * 
	 * @param array|null $extra Un tableau de paramètres supplémentaires à placer dans l'url
	 * @return string
	 */
	public final function routePostManage($extra=null){
		if($this->id) return $this->routePostCreate($extra);
		return $this->routePostEdit($extra);
	}
	
	/**
	 * Route proposant de supprimer une instance du modèle courant
	 *
	 * @param array|null $extra Un tableau de paramètres supplémentaires à placer dans l'url
	 * @return string
	 */
	public final function routeGetDelete($extra=null) {
		return $this->route('GetDelete', $extra);
	}
	
	/**
	 * Route postant la demande de suppression d'une instance du modèle courant
	 *
	 * @param array|null $extra Un tableau de paramètres supplémentaires à placer dans l'url
	 * @return string
	 */
	public final function routePostDelete($extra=null){
		return $this->route('PostDelete', $extra);
	}
	
	/**
	 * Construction d'une route
	 * 
	 * @param string $method
	 * @param array|null $extra Un tableau de paramètres supplémentaires à placer dans l'url
	 * @param boolean $withId, true par défaut
	 */
	protected function route($method, $extra=null, $withId=true) {
		if($withId) {
			if(!$extra) $extra=$this->id;
			else $extra[$this->getModelLabelSingularSnake()]=$this->id;
		}
		return route($this->getModelLabel().$method, $extra);
	}
	
	// TODO : Log des commits sur les ManageableModel
	/*
	public static function boot() {
		
		self::created(function(ManageableModel $modelInstance) {
			$modelInstance->logEvent('created');
		});
		
		// Dommage, il est également appelé lors d'un restore => je ne vois pas encore ce que je peux faire pr l'éviter
		self::updated(function(ManageableModel $modelInstance) {
			$modelInstance->logEvent('updated');
		});
		
		self::deleted(function(ManageableModel $modelInstance) {
			if($modelInstance instanceof TrashableModel) return;
			$modelInstance->logEvent('deleted');
		});
		
		parent::boot();
	}
	
	protected function logEvent($event) {
		$user=Auth::user();
		if($user) $username=$user->username;
		else $username='<guest>';
		
		return Log::info('Commit on ManageableModel', ['event'=>$event, 'model'=>get_class($this), 'id'=>$this->id, 'name'=>$this->name(), 'username'=>$username]);
	}*/
}
