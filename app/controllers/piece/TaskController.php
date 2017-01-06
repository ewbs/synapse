<?php

class TaskController extends TrashableModelController {
	
/**
	 * Initialisation
	 *
	 * @param Task $model
	 */
	public function __construct(Task $model) {
		parent::__construct ($model);
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getList()
	 */
	protected function getList($onlyTrashed=false) {
		return View::make ( 'admin/tasks/list', array('trash'=>$onlyTrashed));
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getDataJson()
	 */
	protected function getDataJson($onlyTrashed=false) {
		//$select = array('demarchesTasks.id', 'demarchesTasks.name', 'demarchesTasks.description', 'demarchesPiecesAndTasksTypes.name AS name2', 'demarchesTasks.cost_administration_currency AS cost_administration', 'demarchesTasks.cost_citizen_currency AS cost_citizen');
		//$builder=Task::leftjoin ( 'demarchesPiecesAndTasksTypes', 'demarchesTasks.type_id', '=', 'demarchesPiecesAndTasksTypes.id' );
		$select = array('demarchesTasks.id', 'demarchesTasks.name', 'demarchesTasks.description', 'demarchesTasks.cost_administration_currency AS cost_administration', 'demarchesTasks.cost_citizen_currency AS cost_citizen');
		if($onlyTrashed) {
			array_unshift($select, 'demarchesTasks.deleted_at');
			$items = Task::onlyTrashed()->select($select);
		}
		else {
			$items = Task::select ($select);
		}
		
		
		return Datatables::of ( $items )
		->remove_column ( 'id' )
		->remove_column ( 'description' )
		->edit_column ( 'name', function ($item) {
			return ($item->name . '<br/><em>' . $item->description . '</em>');
		})
		->edit_column ( 'cost_administration', function ($item) {
			return (NumberHelper::moneyFormat( $item->cost_administration ));
		})
		->edit_column ( 'cost_citizen', function ($item) {
			return (NumberHelper::moneyFormat( $item->cost_citizen ));
		})
		->add_column ( 'actions', function ($item) use ($onlyTrashed) {
			if($onlyTrashed) return
				'<a title="' . Lang::get ( 'button.restore' ) . '" href="' . URL::secure ( 'admin/tasks/' . $item->id . '/restore' ) . '" class="btn btn-xs btn-default">' . Lang::get ( 'button.restore' ) . '</a>';
			else if($this->getLoggedUser()->can('pieces_tasks_manage')) return
				'<a title="' . Lang::get ( 'button.edit'    ) . '" href="' . URL::secure ( 'admin/tasks/' . $item->id . '/edit'    ) . '" class="btn btn-xs btn-default"><span class="fa fa-pencil"></span></a>'.
				'<a title="' . Lang::get ( 'button.delete'  ) . '" href="' . URL::secure ( 'admin/tasks/' . $item->id . '/delete'  ) . '" class="btn btn-xs btn-danger"><span class="fa fa-trash-o"></span></a>';
		})
		->make ();
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getManage()
	 */
	protected function getManage(ManageableModel $task=null){
		// Types
		$types = PieceType::all ();
		
		// affiche le formulaire
		return View::make('admin/tasks/manage', compact ('task', 'types'));
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::save()
	 */
	protected function save(ManageableModel $task){
		// on vérifie que le type de pièce existe bien
		//$type = PieceType::findOrFail ( Input::get ( 'type' ) );
			
		$task->name = Input::get ( 'name' );
		$task->description = Input::get ( 'description' );
		//$task->type_id = $type->id;
		$task->cost_administration_currency = NumberHelper::stringTofloat(Input::get ( 'cost_administration_currency' ) );
		$task->cost_citizen_currency = NumberHelper::stringTofloat(Input::get ( 'cost_citizen_currency' ) );
		return $task->save ();
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getLinks()
	 */
	protected function getLinks(ManageableModel $task) {
		/* @var Task $task */
		$demarches=$task->demarcheComponents()->getQuery()
		->join('demarches', 'demarches.id', '=', 'demarche_id')
		->join('nostra_demarches', 'nostra_demarches.id', '=', 'demarches.nostra_demarche_id')
		->groupBy(['demarches.id', 'nostra_demarches.title'])
		->orderBy('nostra_demarches.title')
		->get(['demarches.id AS id', 'nostra_demarches.title AS name']);
		
		$links=[];
		if(!empty($demarches)) {
			$links[]=[
				'route'=> 'demarchesGetView',
				'label' => Lang::get('admin/demarches/messages.title'),
				'items' => $demarches
			];
		}
		return $links;
	}
}
