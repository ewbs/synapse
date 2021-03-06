<?php
class EwbsServicesController extends TrashableModelController {
	
	/**
	 * Inject the models.
	 *
	 * @param EwbsService $model
	 */
	public function __construct(EwbsService $model) {
		parent::__construct ($model);
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getList()
	 */
	protected function getList($onlyTrashed=false) {
		return View::make ('admin/ewbsservices/list', ['trash'=>$onlyTrashed]);
	}

	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getDataJson()
	 */
	protected function getDataJson($onlyTrashed=false) {
		$select = ['id AS id', 'name AS name'];
		$builder = $this->getModel()->newQuery();
		if($onlyTrashed) {
			array_unshift($select, 'deleted_at');
			$builder->onlyTrashed();
		}
		return Datatables::of ($builder->select($select))
		->edit_column( 'name', function ($item) {
			return '<a href="'.route('ewbsservicesGetView', $item->id).'"><strong>' . $item->name . '</strong>';
		})
		->add_column ( 'actions', function ($item) use ($onlyTrashed) {
			if ( $item->canManage() ) {
				if ($onlyTrashed)
					return
						'<a title="' . Lang::get('button.restore') . '" href="' . route('ewbsservicesGetRestore', $item->id) . '" class="btn btn-xs btn-default">' . Lang::get('button.restore') . '</a>';
				else
					return
						'<a title="' . Lang::get('button.edit') . '" href="' . route('ewbsservicesGetEdit', $item->id) . '" class="btn btn-xs btn-default"><span class="fa fa-pencil"></a>
						 <a title="' . Lang::get('button.delete') . '" href="' . route('ewbsservicesGetDelete', $item->id) . '" class="btn btn-xs btn-danger"><span class="fa fa-trash"></a>';
			}
		})
		->make ();
	}
	
	/**
	 * Visualisation d'un service
	 * 
	 * @param EwbsService $modelInstance
	 * @return View
	 */
	public function getView(EwbsService $modelInstance) {
		return $this->makeDetailView($modelInstance, 'admin/ewbsservices/view');
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getManage()
	 */
	protected function getManage(ManageableModel $modelInstance=null){

		$taxonomyCategories = TaxonomyCategory::orderBy ( 'name' )->get ();

		return $this->makeDetailView($modelInstance, 'admin/ewbsservices/manage', compact('taxonomyCategories') );
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::save()
	 */
	protected function save(ManageableModel $modelInstance) {
		/* @var EwbsService $modelInstance */
		$modelInstance->name = Input::get ( 'name' );
		$modelInstance->description = Input::get ( 'description' );

		$modelInstance->save();
		$tags=Input::get('tags');
		if($tags) $modelInstance->tags()->sync($tags);

		return true;
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getLinks()
	 */
	protected function getLinks(ManageableModel $modelInstance) {
		$links=[];
		
		return $links;
	}
}
