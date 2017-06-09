<?php
use Bllim\Datatables\Datatables;
class MinistersController extends TrashableModelController {
	
	/**
	 * Initialisation
	 * 
	 * @param Minister $model
	 */
	public function __construct(Minister $model) {
		parent::__construct ($model);
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getList()
	 */
	protected function getList($onlyTrashed=false) {
		return View::make ('admin/ministers/list', array('trash'=>$onlyTrashed));
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getDataJson()
	 */
	protected function getDataJson($onlyTrashed=false) {
		$select = array('ministers.id', DB::raw("concat(upper(ministers.lastname),' ', ministers.firstname) AS name"));
		$builder = $this->getModel()->query();
		
		if($onlyTrashed) {
			array_unshift($select, 'ministers.deleted_at');
			$builder->onlyTrashed();
		}
		
		$dt=Datatables::of ($builder->orderBy('ministers.lastname')->select($select));
		$dt->removeColumn('id');
		$dt->editColumn('name', function ($item) {
			return '<a title="' . Lang::get ( 'button.view' ) . '" href="' . route( 'ministersGetView', $item->id ) . '">' . $item->name . '</a>';
		});
		if($onlyTrashed) {
			$dt->add_column ( 'actions', function ($item) {
				return '<a title="' . Lang::get ( 'button.restore' ) . '" href="' . route( 'ministersGetRestore', $item->id ) . '" class="btn btn-xs btn-default">' . Lang::get ( 'button.restore' ) . '</a>';
			});
		}
		
		return $dt->make ();
	}
	
	/**
	 * Visualisation d'un ministre
	 *
	 * @param ManageableModel $modelInstance
	 * @return View
	 */
	public function getView(ManageableModel $modelInstance) {
		return $this->makeDetailView($modelInstance, 'admin/ministers/view');
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getManage()
	 */
	protected function getManage(ManageableModel $modelInstance=null){
		return $this->makeDetailView($modelInstance, 'admin/ministers/manage');
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::save()
	 */
	protected function save(ManageableModel $modelInstance) {
		$modelInstance->firstname = StringHelper::getStringOrNull(Input::get('firstname', null));
		$modelInstance->lastname  = StringHelper::getStringOrNull(Input::get('lastname', null));
		return $modelInstance->save ();
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getLinks()
	 */
	protected function getLinks(ManageableModel $modelInstance) {
		/* @var Minister $modelInstance */
		$ideas=$modelInstance->ideas()->getQuery()
		->orderBy('name')
		->get(['id', 'name'])->toArray();
		
		$links=[];
		if(!empty($ideas)) {
			$links[]=[
				'route'=> 'ideasGetView',
				'label' => Lang::get('admin/ideas/messages.title'),
				'items' => $ideas
			];
		}
		return $links;
	}
	
	/**
	 * 
	 * @param ManageableModel $modelInstance
	 * @return Datatables JSON
	 */
	public function mandatesGetData(ManageableModel $modelInstance) {
		return Datatables::of ($modelInstance->mandates()->getQuery()->all())
		->add_column('actions', function ($item) use($modelInstance) {
			if($modelInstance->canManage()) return 
			'<a title="' . Lang::get('button.edit') . '" class="edit btn btn-xs btn-default servermodal" href="' . route('ministersMandatesGetEdit', [$modelInstance->id, $item->id]) . '"><span class="fa fa-pencil"></span></a>' .
			'<a title="' . Lang::get('button.delete') . '" class="delete btn btn-xs btn-danger servermodal" href="' . route('ministersMandatesGetDelete', [$modelInstance->id, $item->id]) . '"><span class="fa fa-trash-o"></span></a>';
		})
		->remove_column('id')
		->make ();
	}
	
	/**
	 * Affiche le formulaire de création d'un mandat lié à un ministre
	 * 
	 * @param ManageableModel $modelInstance
	 * @return \Illuminate\View\View
	 */
	public function mandatesGetCreate(ManageableModel $modelInstance) {
		return $this->mandatesGetManage($modelInstance, new Mandate());
	}
	
	/**
	 * Affiche le formulaire d'édition d'un mandat lié à un ministre
	 * 
	 * @param ManageableModel $modelInstance
	 * @param Mandate $mandate
	 * @return \Illuminate\View\View
	 */
	public function mandatesGetEdit(ManageableModel $modelInstance, Mandate $mandate) {
		return $this->mandatesGetManage($modelInstance, $mandate);
	}
	
	/**
	 * Affiche le formulaire de création et édition d'un mandat lié à un ministre
	 * 
	 * @param ManageableModel $modelInstance
	 * @param Mandate $mandate
	 * @param array $extra Paramètre supplémentaires qui seraient à passer à la vue
	 * @return \Illuminate\View\View
	 */
	private function mandatesGetManage(ManageableModel $modelInstance, Mandate $mandate, array $extra=[]) {
		return View::make ( 'admin/ministers/mandates/modal-manage', array_merge(compact('modelInstance', 'mandate'), $extra));
	}
	
	/**
	 * Crée un mandat lié à un ministre
	 * @param ManageableModel $modelInstance
	 * @return \Illuminate\View\View
	 */
	public function mandatesPostCreate(ManageableModel $modelInstance) {
		return $this->mandatesPostManage($modelInstance, new Mandate());
	}
	
	/**
	 * Met à jour un mandat lié à un ministre
	 * @param ManageableModel $modelInstance
	 * @param Mandate $mandate
	 * @return \Illuminate\View\View
	 */
	public function mandatesPostEdit(ManageableModel $modelInstance, Mandate $mandate) {
		return $this->mandatesPostManage($modelInstance, $mandate);
	}
	
	/**
	 * Crée ou met à jour un mandat lié à un ministre
	 * @param ManageableModel $modelInstance
	 * @param Mandate $mandate
	 * @return \Illuminate\View\View
	 */
	private function mandatesPostManage(ManageableModel $modelInstance, Mandate $mandate) {
		if(!$modelInstance->canManage()) return $this->serverModalNoRight();
		//FIXME : Il faut encore trouver le moyen de vérifier la contrainte d'unicité avant la sauvegarde (pr éviter le sql exception)
		try {
			$mandate->setMandateRange(Input::get('start'), Input::get('end'));
			$mandate->function = Input::get('function');
			$governement=Input::get('governement');
			if($governement != $mandate->governement_id) { // N'associer que si la valeur est différente de l'actuelle
				$mandate->governement()->associate(Governement::findOrFail($governement));
			}
			if(!$mandate->minister_id) {
				$mandate->minister()->associate($modelInstance);
			}
			if (!$mandate->errors()->isEmpty() || !$mandate->save ()) {
				Input::flash ();
				return $this->mandatesGetManage($modelInstance, $mandate, ['errors'=>$mandate->errors()]);
			}
		} catch ( Exception $e ) {
			Log::error ( $e );
			Input::flash ();
			return View::make('notifications', ['error'=>Lang::get('general.baderror',['exception'=>$e->getMessage()])]);
		}
		return View::make ( 'notifications', ['success'=>Lang::get('general.success')] );
	}
	
	/**
	 * Demande de suppression d'un mandat lié à un ministre
	 *
	 * @param ManageableModel $modelInstance
	 * @param Mandate $action
	 * @return \Illuminate\View\View
	 */
	public function mandatesGetDelete(ManageableModel $modelInstance, Mandate $mandate) {
		return View::make('servermodal.delete', ['url'=>route('ministersMandatesPostDelete', [$modelInstance->id, $mandate->id])]);
	}
	
	/**
	 * Suppression d'un mandat lié à un ministre
	 * 
	 * @param ManageableModel $modelInstance
	 * @param Mandate $mandate
	 * @return \Illuminate\View\View|\Illuminate\Http\Response
	 */
	public function mandatesPostDelete(ManageableModel $modelInstance, Mandate $mandate) {
		try {
			if(!$modelInstance->canManage()) return View::make('notifications', ['error'=>Lang::get('general.no_right_action')]);
			$mandate->delete ();
			return Response::make();
		} catch ( Exception $e ) {
			Log::error ( $e );
			return View::make('notifications', ['error'=>$e->getMessage () ]);
		}
	}
}
