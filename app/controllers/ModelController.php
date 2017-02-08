<?php
abstract class ModelController extends BaseController {
	
	/**
	 * Le modèle courant
	 *
	 * @var ManageableModel
	 */
	private $model;
	
	/**
	 * Initialisation
	 *
	 * @return \ModelController
	 */
	public function __construct(ManageableModel $model) {
		$this->model=$model; //Note : bien le garder avant l'appel au constructeur parent, car ce dernier appelle la méthode routeGetIndex redéfinie ici sur base de cette variable
		
		parent::__construct ();
		
		// Partager les variables à toutes les vues
		View::share('model', $model);
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see BaseController::getSection()
	 */
	protected function getSection(){
		return $this->model->getModelLabel();
	}
	
	/**
	 * ----------------------------------------------------------------------------------------------------------------------------------------------
	 * Méthodes publiques mappées avec les routes, directement prises en charge par la classe abstraite
	 * ----------------------------------------------------------------------------------------------------------------------------------------------
	 */
	
	
	/**
	 * Liste les éléments correspondant au modèle courant
	 *
	 * @return View
	 */
	public final function getIndex() {
		$this->setReturnTo();
		return $this->getList();
	}
	
	
	/**
	 * Génère la liste des éléments, formatée pour les DataTables
	 *
	 * @return Datatables JSON
	 */
	public final function getData() {
		return $this->getDataJson();
	}

	public final function getDataFiltered() {
		return $this->getDataFilteredJson();
	}
	
	/**
	 * Affiche le formulaire de création d'une instance du modèle courant
	 *
	 * @return Response
	 */
	public final function getCreate() {
		return $this->getManage();
	}
	
	/**
	 * Affiche le formulaire d'édition d'une instance du modèle courant
	 *
	 * @param ManageableModel $modelInstance
	 * @return Response
	 */
	public final function getEdit(ManageableModel $modelInstance) {
		if(!$modelInstance->canManage())
			return $this->redirectNoRight();
		return $this->getManage($modelInstance);
	}
	
	/**
	 * Crée une instance du modèle courant
	 *
	 * @return Response
	 */
	public final function postCreate() {
		return $this->postManage($this->model->newInstance(), $this->model->routePostCreate());
	}
	
	/**
	 * Met à jour une instance du modèle courant
	 *
	 * @param ManageableModel $modelInstance
	 * @return Response
	 */
	public final function postEdit(ManageableModel $modelInstance) {
		if(!$modelInstance->canManage())
			return $this->redirectNoRight();
		return $this->postManage($modelInstance, $modelInstance->routePostEdit());
	}
	
	/**
	 * Propose de supprimer une instance du modèle courant
	 *
	 * @param ManageableModel $modelInstance
	 * @return View
	 */
	public function getDelete(ManageableModel $modelInstance) {
		if(!$modelInstance->canManage())
			return $this->redirectNoRight();
		$links=$this->getLinks($modelInstance);
		return View::make ( 'admin/modelInstance/delete', compact('modelInstance', 'links') );
	}
	
	/**
	 * Supprime une instance du modèle courant
	 *
	 * @param ManageableModel $modelInstance
	 * @return Response
	 */
	public function postDelete(ManageableModel $modelInstance) {
		// Vérifier non seulement les droits, mais aussi la présence de liens si le modèle ne permet pas la suppression en cas d'éléments liés
		// en dessous de php 5.5, il est interdit de faire un empty($this->formRules()) ... donc ibligé de d'abordcréer une var -- JDA 2016-04-11
		$linksModelInstance = $this->getLinks($modelInstance);
		if(!$modelInstance->canManage() || (!$modelInstance->deletableIfLinked() && !empty($linksModelInstance)))
			return $this->redirectNoRight();
		
		if ($modelInstance->delete ())
			return Redirect::secure($modelInstance->routeGetIndex())->with ( 'success', Lang::get ( 'admin/'.$this->model->getModelLabel().'/messages.delete.success' ) );
		else
			return Redirect::secure($modelInstance->routeGetIndex())->with ( 'error', Lang::get ( 'admin/'.$this->model->getModelLabel().'/messages.delete.error' ) );
	}
	
	
	/**
	 * ----------------------------------------------------------------------------------------------------------------------------------------------
	 * Méthodes supplémentaires que les sous-classes concrètes doivent redéfinir
	 * ----------------------------------------------------------------------------------------------------------------------------------------------
	 */
	
	
	/**
	 * Liste les instances du modèle courant formatées pour les DataTables
	 *
	 * @return View
	 */
	protected abstract function getList();
	
	/**
	 * Génère la liste des instances du modèle courant formatées pour les DataTables
	 *
	 * @return Datatables JSON
	 */
	protected abstract function getDataJson();
	
	/**
	 * Affiche le formulaire de création et édition d'une instance du modèle courant formatées pour les DataTables
	 *
	 * @param ManageableModel $modelInstance
	 * @return Response
	 */
	protected abstract function getManage(ManageableModel $modelInstance=null);
	
	/**
	 * Crée ou met à jour une instance du modèle courant
	 *
	 * @param ManageableModel $modelInstance
	 * @return boolean|string true ou false selon que la sauvegarde se soit bien passée ou pas, mais avec la possibilité de passer une Url de redirection plutôt que true
	 */
	protected abstract function save(ManageableModel $modelInstance);
	
	/**
	 * Récupère les éléments liés à une instance du modèle courant
	 *
	 * @param ManageableModel $modelInstance
	 * @return array avec 3 clés : route (nom de la route de chaque item), label (label de l'instance du modèle), items (array avec au moins les clés id et name, et optionnellement la clé deleted_at qui permettra de spécifier que l'item est lui-même dans la corbeille)
	 */
	protected abstract function getLinks(ManageableModel $modelInstance);
	
	/**
	 * ----------------------------------------------------------------------------------------------------------------------------------------------
	 * Méthodes utilitaires finales et accessibles par les sous-classes
	 * ----------------------------------------------------------------------------------------------------------------------------------------------
	 */
	
	
	/**
	 * Retourne le modèle injecté par le constructeur
	 *
	 * @return ManageableModel
	 */
	protected final function getModel() {
		return $this->model;
	}
	
	/**
	 * Route correspondant à la page d'index du contrôleur courant
	 */
	protected final function routeGetIndex() {
		return $this->model->routeGetIndex();
	}
	
	/**
	 * ----------------------------------------------------------------------------------------------------------------------------------------------
	 * Méthodes utilitaires accessibles et redéfinissables par les sous-classes
	 * ----------------------------------------------------------------------------------------------------------------------------------------------
	 */
	
	/**
	 * Liste de fonctionnalités à proposer depuis le détail d'une instance du modèle courant.
	 * 
	 * Le format attendu est un array dont chaque entrée est un array ayant les clés label, url, et optionnellement permission, class et icon.
	 * A noter que si la permission n'est pas spécifiée, le bouton est alors affiché sans vérification de droits.
	 * Exemple :
	 * [
	 *	[
	 *		'label' => 'Supprimer',
	 *		'url' => 'https://url-générée-avec-methode-route()',
	 *		'permission' => 'model_manage',
	 *		'icon' => 'pencil',
	 *		'class => 'btn-danger'
	 *	]
	 * ]
	 * 
	 * @return array
	 */
	protected function features(ManageableModel $modelInstance) {
		$features=[];
		if($modelInstance->hasView()) {
			$features[]=[
				'label' => Lang::get ( 'button.view' ),
				'url' => $modelInstance->routeGetView(),
				'icon' => 'eye'
			];
		}
		if($modelInstance->canManage()) {
			$features[]=[
				'label' => Lang::get ( 'button.edit' ),
				'url' => $modelInstance->routeGetEdit(),
				'icon' => 'pencil'
			];
		}
		if($modelInstance->canDelete()) {
			$features[]=[
				'label' => Lang::get ( 'button.delete' ),
				'url' => $modelInstance->routeGetDelete(),
				'icon' => 'trash-o',
				'class' =>'btn-danger',
			];
		}
		return $features;
	}
	
	/**
	 * Retourne la vue passée en paramètre en complétant les datas par l'instance du modèle et les features
	 *
	 * @param ManageableModel $modelInstance
	 * @param string $view
	 * @param array $data
	 * @return View
	 */
	protected function makeDetailView(ManageableModel $modelInstance=null, $view, $data = []) {
		$data ['modelInstance'] = $modelInstance;
		$data ['returnTo'] = $this->getReturnTo();
		if($modelInstance)
			$data ['features'] = $this->features ( $modelInstance );
		return View::make ( $view, $data );
	}
	
	/**
	 * ----------------------------------------------------------------------------------------------------------------------------------------------
	 * Méthodes à usage interne de la classe
	 * ----------------------------------------------------------------------------------------------------------------------------------------------
	 */
	
	
	/**
	 * Crée ou met à jour une instance du modèle courant
	 *
	 * @param ManageableModel $modelInstance
	 * @param string $url
	 * @return Redirect
	 */
	private function postManage(ManageableModel $modelInstance, $url) {
		DB::beginTransaction ();
		try {
			// Validations au niveau formulaire
			$errors=new Illuminate\Support\MessageBag();
			$validator = Validator::make ( Input::all (), $modelInstance->formRules(), $modelInstance->formRulesMessages() );
			$passes=$validator->passes();
			$passes=$modelInstance->extraFormValidate($validator) && $passes;
			// Tentative de sauvegarde avec validation implicite des règles définies au niveau du modèle
			if($passes) {
				if($result=$this->save($modelInstance)) {
					DB::commit ();
					if(is_string($result)) $url=$result;
					else $url=$modelInstance->hasView()?$modelInstance->routeGetView():$modelInstance->routeGetIndex();
					return Redirect::secure($url)->with ( 'success', Lang::get ( 'admin/'.$this->model->getModelLabel().'/messages.manage.success' ) );
				}
				DB::rollBack ();
				$errors=$modelInstance->validationErrors->merge($modelInstance->errors());
			}
			else $errors=$validator->errors();
			
			if(!$errors->isEmpty()) return Redirect::secure ($url)->withInput ()->withErrors ( $errors )->with ( 'error', Lang::get ( 'general.manage.error' ) );
			
			// S'il n'y a pas d'erreurs, peut-être un contrôleur a-t-il lui-même déjà mis des erreurs spécifiques dans le flashdata de la session
			return Redirect::secure ($url)->withInput ();
		}
		catch ( Exception $e ) {
			DB::rollBack ();
			Log::error($e);
			return Redirect::secure ($url)->withInput ()->with ( 'error', Lang::get ( 'general.baderror' ) . '<pre>' . $e->getMessage () . '</pre>' );
		}
	}
}