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
		$select = array('ministers.id', DB::raw("concat(ministers.lastname,' ', ministers.firstname) AS name"));
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
	public function getView(ManageableModel $modelInstance) {// TODO
		return $this->makeDetailView($modelInstance, 'admin/ministers/view');
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getManage()
	 */
	protected function getManage(ManageableModel $modelInstance=null){// TODO
		$regions = Region::orderBy('name')->get ();
		return $this->makeDetailView($modelInstance, 'admin/ministers/manage', compact('regions') );
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::save()
	 */
	protected function save(ManageableModel $modelInstance) {// TODO
		$modelInstance->name = Input::get ( 'name' );
		$modelInstance->region_id = Input::get ( 'region' );
		return $modelInstance->save ();
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getLinks()
	 */
	protected function getLinks(ManageableModel $modelInstance) {// TODO
		/* @var Minister $modelInstance */
		return [];
	}
}
