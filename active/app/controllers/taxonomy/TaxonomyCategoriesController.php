<?php
class TaxonomyCategoriesController extends TrashableModelController {
	
	/**
	 * Inject the models.
	 *
	 * @param TaxonomyCategory $model
	 */
	public function __construct(TaxonomyCategory $model) {
		parent::__construct ($model);
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getList()
	 */
	protected function getList($onlyTrashed=false) {
		return View::make ('admin/taxonomy/categories/list', ['trash'=>$onlyTrashed]);
	}

	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getDataJson()
	 */
	protected function getDataJson($onlyTrashed=false) {
		$select = ['id', 'name'];
		$builder = $this->getModel()->newQuery();
		if($onlyTrashed) {
			array_unshift($select, 'deleted_at');
			$builder->onlyTrashed();
		}
		return Datatables::of ($builder->select($select))
		->edit_column( 'name', function ($item) {
			return '<a href="'.route('taxonomycategoriesGetView', $item->id).'"><strong>' . $item->name . '</strong>';
		})
		->add_column ( 'actions', function ($item) use ($onlyTrashed) {
			if ( $item->canManage() ) {
				if ($onlyTrashed)
					return
						'<a title="' . Lang::get('button.restore') . '" href="' . route('taxonomycategoriesGetRestore', $item->id) . '" class="btn btn-xs btn-default">' . Lang::get('button.restore') . '</a>';
				else
					return
						'<a title="' . Lang::get('button.edit') . '" href="' . route('taxonomycategoriesGetEdit', $item->id) . '" class="btn btn-xs btn-default"><span class="fa fa-pencil"></a>
						 <a title="' . Lang::get('button.delete') . '" href="' . route('taxonomycategoriesGetDelete', $item->id) . '" class="btn btn-xs btn-danger"><span class="fa fa-trash"></a>';
			}
		})
		->make ();
	}
	
	/**
	 * Visualisation d'une catégorie  de taxonomie
	 * 
	 * @param TaxonomyCategory $category
	 * @return View
	 */
	public function getView(TaxonomyCategory $category) {
		return $this->makeDetailView($category, 'admin/taxonomy/categories/view');
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getManage()
	 */
	protected function getManage(ManageableModel $modelInstance=null){
		return $this->makeDetailView($modelInstance, 'admin/taxonomy/categories/manage');
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::save()
	 */
	protected function save(ManageableModel $modelInstance) {
		/* @var TaxonomyCategory $modelInstance */
		$modelInstance->name = Input::get ( 'name' );
		return $modelInstance->save();
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getLinks()
	 */
	protected function getLinks(ManageableModel $modelInstance) {
		$links=[];
		
		$items=$modelInstance->tags()->withTrashed()->get(['id','name', 'deleted_at'])->toArray();
		if(!empty($items))
			$links[]=[
				'route'=> 'taxonomytagsGetView',
				'label' => Lang::get('admin/taxonomy/messages.menu'),
				'items' => $items
			];
		
		return $links;
	}
}
