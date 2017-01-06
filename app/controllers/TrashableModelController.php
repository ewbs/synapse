<?php
abstract class TrashableModelController extends ModelController {
	
	/**
	 * Initialisation
	 *
	 * @return \TrashableModelController
	 */
	public function __construct(TrashableModel $model) {
		parent::__construct ($model);
	}
	
	
	/**
	 * ----------------------------------------------------------------------------------------------------------------------------------------------
	 * Méthodes publiques mappées avec les routes, directement prises en charge par la classe abstraite
	 * ----------------------------------------------------------------------------------------------------------------------------------------------
	 */
	
	
	/**
	 * Liste les éléments supprimés correspondant au modèle courant
	 *
	 * @return View
	 */
	public function getTrash() {
		return $this->getList(true);
	}
	
	/**
	 * Génère la liste des éléments supprimés correspondant au modèle courant, formatée pour les DataTables
	 *
	 * @return Datatables JSON
	 */
	public function getDatatrash() {
		return $this->getDataJson(true);
	}
	
	/**
	 * Propose de restaurer une administration
	 *
	 * @param ManageableModel $modelInstance
	 * @return View
	 */
	public function getRestore(ManageableModel $modelInstance) {
		return View::make ( 'admin/modelInstance/restore', compact('modelInstance') );
	}
	
	/**
	 * Restaure une instance du modèle courant
	 *
	 * @param TrashableModel $modelInstance
	 * @return Response
	 */
	public function postRestore(TrashableModel $modelInstance) {
		if(!$modelInstance->canManage())
			return Redirect::secure($modelInstance->routeGetTrash())->with ( 'error', Lang::get ( 'general.restore.noright' ) );
		if ($modelInstance->restore ())
			return Redirect::secure($this->routeGetIndex())->with ( 'success', Lang::get ( 'admin/'.$this->getModel()->getModelLabel().'/messages.restore.success' ) );
		else
			return Redirect::secure($this->routeGetIndex())->with ( 'error', Lang::get ( 'admin/'.$this->getModel()->getModelLabel().'/messages.restore.error' ) );
	}
}