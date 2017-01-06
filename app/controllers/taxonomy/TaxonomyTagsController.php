<?php
class TaxonomyTagsController extends TrashableModelController {
	
	/**
	 * Inject the models.
	 *
	 * @param Annexe $model
	 */
	public function __construct(TaxonomyTag $model) {
		parent::__construct ($model);
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::features()
	 */
	protected function features(ManageableModel $modelInstance) {
		return [
			[
				'label' => Lang::get ( 'button.view' ),
				'url' => $modelInstance->routeGetView(),
				'permission' => 'taxonomy_display',
				'icon' => 'eye'
			],
			[
				'label' => Lang::get ( 'button.edit' ),
				'url' => $modelInstance->routeGetEdit(),
				'permission' => $modelInstance->permissionManage(),
				'icon' => 'pencil'
			],
			[
				'label' => Lang::get ( 'button.delete' ),
				'url' => $modelInstance->routeGetDelete(),
				'permission' => $modelInstance->permissionManage(),
				'icon' => 'trash-o',
				'class' =>'btn-danger',
			]
		];
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
	 * Visualisation d'une annexe
	 *
	 * @param Annexe $annexe
	 * @return type
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
		/* @var Annexe $modelInstance */
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


	/**
	 * Rend l'écran de gestion des synonymes
	 *
	 * @return \Illuminate\View\View
	 */
	public function getSynonyms() {

		$unclassified = TaxonomyTag::whereNull('group')->orderBy('name')->get();

		$classifiedUnsorted = TaxonomyTag::whereNotNull('group')->orderBy('group')->orderBy('name')->get();
		$classified = [];
		foreach($classifiedUnsorted as $tag) {
			if (!isset($classified[$tag->group])) { $classified[$tag->group] = []; }
			array_push($classified[$tag->group], $tag);
		}
		unset($classifiedUnsorted);

		return View::make ('admin/taxonomy/synonyms', compact('unclassified', 'classified'));

	}


	/**
	 * Cette fonction est appelée en ajax pour modifier la synonymie.
	 * Retourne du json "error" avec false ou true
	 */
	public function postSynonyms() {

		if ( ! Input::has('ids') ) { //avant toute chose, si on a pas d'ids passé, on retourne une erreur
			return Response::json(['error'=>true]);
		}

		if ( Input::has('groupId') ) { //on doit passer un groupIp
			//si le groupId existe :
			if (Input::get('groupId') > 0) {
				TaxonomyTag::whereIn('id', Input::get('ids'))->update(['group' => Input::get('groupId')]);
				return Response::json(['error' => false]);
			}

			//si on passe le group des tags non triés :
			elseif (Input::get('groupId') == "NONE") {
				TaxonomyTag::whereIn('id', Input::get('ids'))->update(['group' => NULL]);
				return Response::json(['error' => false]);
			}

			//si le groupId n'existe pas (valeur -1) :
			else {
				$newGroupId = TaxonomyTag::max('group') + 1;
				TaxonomyTag::whereIn('id', Input::get('ids'))->update(['group' => $newGroupId]);
				return Response::json(['error'=>false, 'groupId'=>$newGroupId]);
			}

		}

		return Response::json(['error'=>true]);
	}

}
