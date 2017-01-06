<?php
use Bllim\Datatables\Datatables;
class AdministrationsController extends TrashableModelController {
	
	/**
	 * Initialisation
	 * 
	 * @param Administration $model
	 */
	public function __construct(Administration $model) {
		parent::__construct ($model);
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getList()
	 */
	protected function getList($onlyTrashed=false) {
		return View::make ('admin/administrations/list', array('trash'=>$onlyTrashed));
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getDataJson()
	 */
	protected function getDataJson($onlyTrashed=false) {
		$select = array('administrations.id', 'administrations.name', 'regions.name as region');
		$builder = $this->getModel()->join ( 'regions', 'administrations.region_id', '=', 'regions.id' );
		if($onlyTrashed) {
			array_unshift($select, 'administrations.deleted_at');
			$builder->onlyTrashed();
		}
		$items = $builder->select ($select);
	
		return Datatables::of ($items)
		->remove_column ( 'id' )
		->add_column ( 'actions', function ($item) use ($onlyTrashed) {
			if($onlyTrashed) return
			'<a title="' . Lang::get ( 'button.restore' ) . '" href="' . route( 'administrationsGetRestore', $item->id ) . '" class="btn btn-xs btn-default">' . Lang::get ( 'button.restore' ) . '</a>';
			else return
			'<a title="' . Lang::get ( 'button.edit'    ) . '" href="' . route( 'administrationsGetEdit', $item->id ) . '" class="btn btn-xs btn-default"><span class="fa fa-pencil"></span></a>'.
			'<a title="' . Lang::get ( 'button.delete'  ) . '" href="' . route( 'administrationsGetDelete', $item->id ) . '" class="btn btn-xs btn-danger"><span class="fa fa-trash-o"></span></a>';
		})
		->make ();
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getManage()
	 */
	protected function getManage(ManageableModel $administration=null){
		$regions = Region::orderBy('name')->get ();
		return View::make('admin/administrations/manage', compact('administration', 'regions') );
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::save()
	 */
	protected function save(ManageableModel $administration) {
		$administration->name = Input::get ( 'name' );
		$administration->region_id = Input::get ( 'region' );
		return $administration->save ();
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getLinks()
	 */
	protected function getLinks(ManageableModel $administration) {
		/* @var Administration $administration */
		$links=array();
		$ideas=$administration->ideas()->get(array('id','name'))->toArray();
		if(!empty($ideas))
			$links[]=[
				'route'=> 'ideasGetView',
				'label' => Lang::get('admin/ideas/messages.title'),
				'items' => $ideas
			];
		
		$demarches=Demarche::
		  join('administration_demarche', 'administration_demarche.demarche_id', '=', 'demarches.id')
		->join('nostra_demarches', 'nostra_demarches.id', '=', 'demarches.nostra_demarche_id')
		->where('administration_demarche.administration_id', '=', $administration->id)
		->get(array('demarches.id AS id', 'nostra_demarches.title AS name'))
		->toArray();
		if(!empty($demarches))
			$links[]=[
				'route'=> 'demarchesGetView',
				'label' => Lang::get('admin/demarches/messages.title'),
				'items' => $demarches
			];
		
		return $links;
	}
}
