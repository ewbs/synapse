<?php

class PieceTypeController extends TrashableModelController {
	
/**
	 * Initialisation
	 *
	 * @param PieceType $model
	 */
	public function __construct(PieceType $model) {
		parent::__construct ($model);
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getList()
	 */
	protected function getList($onlyTrashed=false) {
		return View::make ( 'admin/piecestypes/list', array('trash'=>$onlyTrashed));
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getDataJson()
	 */
	protected function getDataJson($onlyTrashed=false) {
		$select = array('id','name','for','description');
		if($onlyTrashed) {
			array_unshift($select, 'deleted_at');
			$items = PieceType::onlyTrashed()->select ($select);
		}
		else
			$items = PieceType::select ($select);
		
		return Datatables::of ( $items )
		->remove_column ( 'id' )
		->remove_column ( 'description' )
		->edit_column ( 'name', function ($item) {
			return ($item->name . '<br/><em>' . $item->description . '</em>');
		})
		->add_column ( 'actions', function ($item) use ($onlyTrashed) {
			if($onlyTrashed) return
				'<a title="' . Lang::get ( 'button.restore' ) . '" href="' . route('piecestypesGetRestore', $item->id) . '" class="btn btn-xs btn-default">' . Lang::get ( 'button.restore' ) . '</a>';
			else if($this->getLoggedUser()->can('pieces_tasks_manage')) return
				'<a title="' . Lang::get ( 'button.edit'    ) . '" href="' . route('piecestypesGetEdit', $item->id) . '" class="btn btn-xs btn-default"><span class="fa fa-pencil"></span></a>'.
				'<a title="' . Lang::get ( 'button.delete'  ) . '" href="' . route('piecestypesGetDelete', $item->id) . '" class="btn btn-xs btn-danger"><span class="fa fa-trash-o"></span></a>';
		})
		->make ();
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getManage()
	 */
	protected function getManage(ManageableModel $type=null){
		return View::make('admin/piecestypes/manage', compact ('type'));
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::save()
	 */
	protected function save(ManageableModel $type){
		// Traiter les cases à cocher
		if (Input::has ( 'for_pieces' ) && Input::has ( 'for_tasks' ))
			Input::merge ( array ('for' => 'all'));
		elseif (Input::has ( 'for_pieces' ))
			Input::merge ( array ('for' => 'piece'));
		elseif (Input::has ( 'for_tasks' ))
			Input::merge ( array ('for' => 'task'));
		
		$type->name = Input::get ( 'name' );
		$type->for = Input::get ( 'for' );
		$type->description = Input::get ( 'description' );
		return $type->save ();
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getLinks()
	 */
	protected function getLinks(ManageableModel $type) {
		return [];
	}
}
