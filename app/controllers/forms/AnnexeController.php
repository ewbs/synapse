<?php
class AnnexeController extends TrashableModelController {
	
	/**
	 * Inject the models.
	 *
	 * @param Annexe $model
	 */
	public function __construct(Annexe $model) {
		parent::__construct ($model);
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getList()
	 */
	protected function getList($onlyTrashed=false) {
		return View::make ('admin/forms/annexes/list', ['trash'=>$onlyTrashed]);
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getDataJson()
	 */
	protected function getDataJson($onlyTrashed=false) {
		$select = ['annexes.id', 'annexes.title', 'annexes.description', 'demarchesPieces.name as piece_name'];
		$builder = $this->getModel()->newQuery();
		$builder->leftjoin('demarchesPieces', 'demarchesPieces.id', '=', 'annexes.piece_id');
		if($onlyTrashed) {
			array_unshift($select, 'annexes.deleted_at');
			$builder->onlyTrashed();
		}
		$dt = Datatables::of ($builder->select($select))
		->edit_column ( 'id', function ($item) {
			return ManageableModel::formatId($item->id);
		})
		->remove_column ( 'description' );
		if ($onlyTrashed) {
			$dt->edit_column('title', function ($item) {
				return '<strong>' . $item->title . '</strong><br/><em>' . $item->description . '</em>';
			})
			->add_column('actions', function ($item) use ($onlyTrashed) {
				return '<a title="' . Lang::get('button.restore') . '" href="' . route('annexesGetRestore', $item->id) . '" class="btn btn-xs btn-default">' . Lang::get('button.restore') . '</a>';
			});
		}
		else {
			$dt->edit_column('title', function ($item) {
				return '<a title="' . Lang::get ( 'button.view' ) . '" href="' . route ( 'annexesGetView', $item->id ) . '"><strong>' . $item->title . '</strong><br/><em>' . $item->description . '</em></a>';
			});
		}


		return $dt->make ();
	}
	
	/**
	 * Visualisation d'une annexe
	 *
	 * @param Annexe $annexe
	 * @return type
	 */
	public function getView(Annexe $annexe) {
		return $this->makeDetailView($annexe, 'admin/forms/annexes/view');
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getManage()
	 */
	protected function getManage(ManageableModel $modelInstance=null){
		/* @var Annexe $modelInstance */
		$builder=$pieces=Piece
		::whereNotIn('id', function(\Illuminate\Database\Query\Builder $query) {
			$query
			->select('piece_id')
			->distinct()
			->from('annexes')
			->whereNotNull('piece_id')
			->whereNull('deleted_at');
			/*
			 * Note : on prend volontairement les id de pièces des annexes qui sont soft-deletées.
			 * Si l'utilisateur la sélectionne, il aura une erreur à la sauvegarde (cf. unicité), mais vu que l'utilisateur n'a pas d'accès à la corbeille,
			 * s'il ne l'avait pas dans la liste il penserait que ce serait un bug
			 */
		})
		->orderBy('name');
		
		// Condition explicite sur l'éventiel piece_id déjà lié afin de l'avoir dans la liste même si la pièce a été soft-deletée
		// Note : Attention que le OR fonctionne bien car il suit la condition sur la colonne deleted_at => serait sans doute à adapter si on modifiait la requête
		if($modelInstance && $modelInstance->piece_id)
			$builder->orWhere('id', '=', $modelInstance->piece_id);
		
		$aPieces=$builder->get(['id', 'name']);
		return $this->makeDetailView($modelInstance, 'admin/forms/annexes/manage', ['aPieces'=>$aPieces]);
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::save()
	 */
	protected function save(ManageableModel $modelInstance) {
		/* @var Annexe $modelInstance */
		$modelInstance->title = Input::get ( 'title' );
		$modelInstance->description = Input::get ( 'description' );
		
		$piece_id=Input::get ( 'piece_id' );
		if($piece_id) $modelInstance->piece()->associate(Piece::find($piece_id));
		else $modelInstance->piece()->dissociate();
		
		return $modelInstance->save();
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getLinks()
	 */
	protected function getLinks(ManageableModel $modelInstance) {
		$links=[];
		
		$items=$modelInstance->eforms()->withTrashed()->get(['eforms.id','eforms.title as name', 'eforms.deleted_at'])->toArray();
		if(!empty($items))
			$links[]=[
				'route'=> 'eformsGetView',
				'label' => Lang::get('admin/eforms/messages.menu'),
				'items' => $items
			];
		
		return $links;
	}
}
