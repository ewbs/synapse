<?php
class EwbsMembersController extends TrashableModelController {
	
/**
	 * Initialisation
	 *
	 * @param EWBSMember $model
	 */
	public function __construct(EWBSMember $model) {
		parent::__construct ($model);
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getList()
	 */
	protected function getList($onlyTrashed=false) {
		return View::make ('admin/ewbsmembers/list', array('trash'=>$onlyTrashed));
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getDataJson()
	 */
	protected function getDataJson($onlyTrashed=false) {
		$select = array('ewbs_members.id', 'users.confirmed', 'users.username', 'ewbs_members.lastname', 'ewbs_members.firstname', 'ewbs_members.jobtitle');
		$builder = $this->getModel()->join ( 'users', 'ewbs_members.user_id', '=', 'users.id' );
		if($onlyTrashed) {
			array_unshift($select, 'ewbs_members.deleted_at');
			$builder->onlyTrashed();
		}
		$items = $builder->select ($select);
		
		return Datatables::of ( $items )
		->edit_column ( 'username', function ($item) {
			return $item->username. (($item->confirmed < 1)?' <span class="label label-danger">Désactivé</span>':'');
		})
		->remove_column ( 'id' )
		->remove_column ( 'confirmed' )
		->add_column ( 'actions', function ($item) use ($onlyTrashed) {
			if($onlyTrashed) return
				'<a title="' . Lang::get ( 'button.restore' ) . '" href="' . route( 'ewbsmembersGetRestore',$item->id ) . '" class="btn btn-xs btn-default">' . Lang::get ( 'button.restore' ) . '</a>';
			else return 
				'<a title="' . Lang::get ( 'button.edit'    ) . '" href="' . route( 'ewbsmembersGetEdit'   ,$item->id ) . '" class="btn btn-xs btn-default"><span class="fa fa-pencil"></span></a>'.
				'<a title="' . Lang::get ( 'button.delete'  ) . '" href="' . route( 'ewbsmembersGetDelete' ,$item->id ) . '" class="btn btn-xs btn-danger"><span class="fa fa-trash-o"></span></a>';
		})
		->make ();
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getManage()
	 */
	protected function getManage(ManageableModel $eWBSMember=null){
		if($eWBSMember==null) {
			// on doit sélectionner les utilisateurs non "utilisés", cad ceux qui ne sont pas encore liés à un membre eWBS
			//FIXME : la requête devrait considérer également les membres soft-deletés !
			$arrayOfUsers = User::has ( 'EWBSMember', '=', '0' )->get ();
		}
		return View::make ( 'admin/ewbsmembers/manage', compact ( 'eWBSMember', 'arrayOfUsers' ) );
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::save()
	 */
	protected function save(ManageableModel $eWBSMember) {
		$eWBSMember->lastname = Input::get ( 'lastname' );
		$eWBSMember->firstname = Input::get ( 'firstname' );
		$eWBSMember->jobtitle = Input::get ( 'jobtitle' );
		if(!$eWBSMember->id)
			$eWBSMember->user_id = Input::get ( 'user' );
		return $eWBSMember->save ();
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getLinks()
	 */
	protected function getLinks(ManageableModel $eWBSMember) {
		//TODO : Vérifier les liens
		return [];
	}
}
