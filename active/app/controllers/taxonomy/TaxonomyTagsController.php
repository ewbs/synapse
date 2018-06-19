<?php
class TaxonomyTagsController extends TrashableModelController {
	
	/**
	 * Inject the models.
	 *
	 * @param TaxonomyTag $model
	 */
	public function __construct(TaxonomyTag $model) {
		parent::__construct ($model);
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getList()
	 */
	protected function getList($onlyTrashed=false) {
		return View::make ('admin/taxonomy/tags/list', ['trash'=>$onlyTrashed]);
	}

	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getDataJson()
	 */
	protected function getDataJson($onlyTrashed=false) {
		$select = ['taxonomytags.id AS id', 'taxonomytags.name AS name', 'taxonomycategories.name AS categorie'];
		$builder = $this->getModel()->newQuery();
		$builder->leftJoin('taxonomycategories', 'taxonomytags.taxonomy_category_id', '=', 'taxonomycategories.id');
		if($onlyTrashed) {
			array_unshift($select, 'taxonomytags.deleted_at');
			$builder->onlyTrashed();
		}
		return Datatables::of ($builder->select($select))
		->edit_column( 'name', function ($item) {
			return '<a href="'.route('taxonomytagsGetView', $item->id).'"><strong>' . $item->name . '</strong>';
		})
		->add_column ( 'actions', function ($item) use ($onlyTrashed) {
			if ( $item->canManage() ) {
				if ($onlyTrashed)
					return
						'<a title="' . Lang::get('button.restore') . '" href="' . route('taxonomytagsGetRestore', $item->id) . '" class="btn btn-xs btn-default">' . Lang::get('button.restore') . '</a>';
				else
					return
						'<a title="' . Lang::get('button.edit') . '" href="' . route('taxonomytagsGetEdit', $item->id) . '" class="btn btn-xs btn-default"><span class="fa fa-pencil"></a>
						 <a title="' . Lang::get('button.delete') . '" href="' . route('taxonomytagsGetDelete', $item->id) . '" class="btn btn-xs btn-danger"><span class="fa fa-trash"></a>';
			}
		})
		->make ();
	}
	
	/**
	 * Visualisation d'un tag
	 * 
	 * @param TaxonomyTag $tag
	 * @return View
	 */
	public function getView(TaxonomyTag $tag) {
		return $this->makeDetailView($tag, 'admin/taxonomy/tags/view');
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getManage()
	 */
	protected function getManage(ManageableModel $modelInstance=null){

		$categories = TaxonomyCategory::orderBy ( 'name' )->get ();

		return $this->makeDetailView($modelInstance, 'admin/taxonomy/tags/manage', compact('categories'));
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::save()
	 */
	protected function save(ManageableModel $modelInstance) {
		/* @var TaxonomyTag $modelInstance */
		$modelInstance->name = Input::get ( 'name' );
		$modelInstance->taxonomy_category_id = Input::get('category');
		return $modelInstance->save();
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
