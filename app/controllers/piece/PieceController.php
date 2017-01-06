<?php

class PieceController extends TrashableModelController {
	
	/**
	 * Initialisation
	 *
	 * @param Piece $model
	 */
	public function __construct(Piece $model) {
		parent::__construct ($model);
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getList()
	 */
	protected function getList($onlyTrashed=false) {
		return View::make ( 'admin/pieces/list', array('trash'=>$onlyTrashed));
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getDataJson()
	 */
	protected function getDataJson($onlyTrashed=false) {
		//$select = array('demarchesPieces.id', 'demarchesPieces.name', 'demarchesPieces.description', 'demarchesPiecesAndTasksTypes.name AS name2', 'demarchesPieces.cost_administration_currency', 'demarchesPieces.cost_citizen_currency');
		//$builder = Piece::leftjoin( 'demarchesPiecesAndTasksTypes', 'demarchesPieces.type_id', '=', 'demarchesPiecesAndTasksTypes.id' );
		$select = array('demarchesPieces.id', 'demarchesPieces.name', 'demarchesPieces.description', 'demarchesPieces.cost_administration_currency', 'demarchesPieces.cost_citizen_currency');
		if($onlyTrashed) {
			array_unshift($select, 'demarchesPieces.deleted_at');
			$items = Piece::onlyTrashed()->select($select);
		}
		else {
			$items = Piece::select ($select);
		}
		
		return Datatables::of ( $items )
		->remove_column ( 'id' )
		->remove_column ( 'description' )
		->edit_column ( 'name', function ($item) {
			return ($item->name . '<br/><em>' . $item->description . '</em>');
		})
		->edit_column ( 'cost_administration_currency', function ($item) {
			return NumberHelper::moneyFormat($item->cost_administration_currency);
		})
		->edit_column ( 'cost_citizen_currency', function ($item) {
			return NumberHelper::moneyFormat($item->cost_citizen_currency);
		})
		->add_column ( 'actions', function ($item) use ($onlyTrashed) {
			if($onlyTrashed) return
				'<a title="' . Lang::get ( 'button.restore' ) . '" href="' . URL::secure ( 'admin/pieces/' . $item->id . '/restore' ) . '" class="btn btn-xs btn-default">' . Lang::get ( 'button.restore' ) . '</a>';
			else if($this->getLoggedUser()->can('pieces_tasks_manage')) return
				'<a title="' . Lang::get ( 'button.edit'    ) . '" href="' . URL::secure ( 'admin/pieces/' . $item->id . '/edit'    ) . '" class="btn btn-xs btn-default"><span class="fa fa-pencil"></span></a>'.
				'<a title="' . Lang::get ( 'button.delete'  ) . '" href="' . URL::secure ( 'admin/pieces/' . $item->id . '/delete'  ) . '" class="btn btn-xs btn-danger"><span class="fa fa-trash-o"></span></a>';
		})
		->make ();
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getManage()
	 */
	protected function getManage(ManageableModel $piece=null){
		$types = PieceType::all ();
		return View::make('admin/pieces/manage', compact ('piece', 'types'));
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::save()
	 */
	protected function save(ManageableModel $piece){
		// on vérifie que le type de pièce existe bien
		//$type = PieceType::findOrFail ( Input::get ( 'type' ) );
		
		//Assigner les valeurs et sauver
		$piece->name = Input::get ( 'name' );
		$piece->cost_administration_currency = NumberHelper::stringTofloat(Input::get ( 'cost_administration_currency' ) );
		$piece->cost_citizen_currency = NumberHelper::stringTofloat(Input::get ( 'cost_citizen_currency' ) );
		//$piece->type_id = $type->id;
		$piece->description = Input::get ( 'description' );
		return $piece->save ();
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getLinks()
	 */
	protected function getLinks(ManageableModel $piece) {
		/* @var Piece $piece */
		$demarches=$piece->demarcheComponents()->getQuery()
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
