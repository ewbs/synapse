<?php

/*
 * OBSOLETE ET A SUPPRIMER
 */

class PieceRateController extends TrashableModelController {
	
	/**
	 * Initialisation
	 *
	 * @param PieceRate $model
	 */
	public function __construct(PieceRate $model) {
		parent::__construct ($model);
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getList()
	 */
	protected function getList($onlyTrashed=false) {
		return View::make ( 'admin/piecesrates/list', array('trash'=>$onlyTrashed));
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getDataJson()
	 */
	protected function getDataJson($onlyTrashed=false) {
		$select = array ('id','name','who','hour_rate','description' );
		if($onlyTrashed) {
			array_unshift($select, 'deleted_at');
			$items = PieceRate::onlyTrashed()->select ($select);
		}
		else
			$items = PieceRate::select ($select);
		
		return Datatables::of ( $items )
		->remove_column ( 'id' )
		->remove_column ( 'description' )
		->edit_column ( 'name', function ($item) {
			return ($item->name . '<br/><em>' . $item->description . '</em>');
		})
		->edit_column ( 'hour_rate', function ($item) {
			return NumberHelper::moneyFormat($item->hour_rate);
		})
		->add_column ( 'actions', function ($item) use ($onlyTrashed) {
			if($onlyTrashed) return
				'<a title="' . Lang::get ( 'button.restore' ) . '" href="' . URL::secure ( 'admin/piecesrates/' . $item->id . '/restore' ) . '" class="btn btn-xs btn-default">' . Lang::get ( 'button.restore' ) . '</a>';
			else if($this->getLoggedUser()->can('pieces_tasks_manage')) return
				'<a title="' . Lang::get ( 'button.edit'    ) . '" href="' . URL::secure ( 'admin/piecesrates/' . $item->id . '/edit'    ) . '" class="btn btn-xs btn-default"><span class="fa fa-pencil"></span></a>'.
				'<a title="' . Lang::get ( 'button.delete'  ) . '" href="' . URL::secure ( 'admin/piecesrates/' . $item->id . '/delete'  ) . '" class="btn btn-xs btn-danger"><span class="fa fa-trash-o"></span></a>';
		})
		->make ();
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getManage()
	 */
	protected function getManage(ManageableModel $rate=null){
		return View::make('admin/piecesrates/manage', compact ('rate'));
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::save()
	 */
	protected function save(ManageableModel $rate){
		$rate->name = Input::get ( 'name' );
		$rate->hour_rate = NumberHelper::stringTofloat(Input::get ( 'hour_rate' ) );
		$rate->who = Input::get ( 'who' );
		$rate->description = Input::get ( 'description' );
		return $rate->save ();
	}
		
	/**
	 * {@inheritDoc}
	 * @see ModelController::getLinks()
	 */
	protected function getLinks(ManageableModel $rate) {
		$tasks=Task::withTrashed()->where('rate_administration_id','=',$rate->id)->orWhere('rate_citizen_id','=',$rate->id)->get(array('id','name'))->toArray();
		$links=array();
		if(!empty($tasks))
			$links[]=[
				'route'=> 'tasksGetEdit',
				'label' => Lang::get('admin/tasks/messages.title'),
				'items' => $tasks
			];
		return $links;
	}
}
