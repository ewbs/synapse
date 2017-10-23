<?php
class AdminUsersController extends TrashableModelController {
	
	/**
	 * 
	 * @param User $user
	 */
	public function __construct(User $user) {
		parent::__construct ($user);
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see BaseController::getSection()
	 */
	protected function getSection(){
		return 'users';
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getList()
	 */
	protected function getList($onlyTrashed=false) {
		return View::make ('admin/users/list', array('trash'=>$onlyTrashed));
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getDataJson()
	 */
	protected function getDataJson($onlyTrashed=false) {
		$select = [
			'id',
			'username',
			'email',
			'confirmed',
			'created_at'
		];
		$builder = $this->getModel()->query();
		if($onlyTrashed) {
			array_unshift($select, 'deleted_at');
			$builder->onlyTrashed();
		}
		return Datatables::of ( $builder->select($select) )
		->edit_column ( 'confirmed', function ($item) {
			return Lang::get($item->confirmed ? 'general.yes' : 'general.no');
		})
		->add_column ( 'roles', function ($item) {
			$a = $item->currentRoleNames ();
			$return = ($a) ? implode ( ', ', $a ) : '';
			if ($item->hasRestrictionsByAdministrations ())
				$return .= ' <strong>Avec restrictions</strong>';
			return ($return);
		})
		->add_column ( 'actions', function ($item) use ($onlyTrashed) {
			if($onlyTrashed) return
			'<a title="' . Lang::get ( 'button.restore' ) . '" href="' . route( 'usersGetRestore', $item->id ) . '" class="btn btn-xs btn-default">' . Lang::get ( 'button.restore' ) . '</a>';
			return
			'<a title="'.Lang::get('button.edit').'" href="'.route('usersGetEdit', $item->id).'" class="iframe btn btn-xs btn-default"><span class="fa fa-pencil"></span></a>
			 <a title="'.Lang::get('button.delete').'" href="'.route('usersGetDelete', $item->id).'" class="iframe btn btn-xs btn-danger"><span class="fa fa-trash-o"></span></a>';
		})
		->remove_column ( 'id' )
		->make ();
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getManage()
	 */
	protected function getManage(ManageableModel $user=null){
		$roles = Role::orderBy('name')->get();
		$permissions = Permission::all();
		$administrations = Administration::orderBy('name')->get();
		return $this->makeDetailView ($user, 'admin/users/manage', compact ( 'roles', 'permissions', 'mode', 'administrations' ) );
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::save()
	 */
	protected function save(ManageableModel $user) {
		$create=$user->id?false:true;
		$user->username = Input::get ( 'username' );
		$user->email = Input::get ( 'email' );
		
		// Generate a random confirmation code
		if($create)
			$user->confirmation_code = md5(uniqid(mt_rand(), true));
		
		$user->confirmed=Input::get ( 'confirmed', $user->confirmed );
		
		if ($password=Input::get ( 'password' ))
			$user->password = $password;
		
		if ($password_confirmation=Input::get ( 'password_confirmation' ))
			$user->password_confirmation = $password_confirmation;
		
		$saved=$user->save();
		if($saved) {
			$user->saveRoles ( Input::get ( 'roles' ) );
			$user->administrations ()->sync ( is_array ( Input::get ( 'administrations' ) ) ? Input::get ( 'administrations' ) : array () );
			
			if ($create && Config::get ( 'confide::signup_email' )) {
				Mail::queueOn ( Config::get ( 'confide::email_queue' ), Config::get ( 'confide::email_account_confirmation' ), compact ( 'user' ), function ($message) use($user) {
					$message->to ( $user->email, $user->username )->subject ( Lang::get ( 'confide::confide.email.account_confirmation.subject' ) );
				});
			}
		}
		return $saved;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see ModelController::getDelete()
	 */
	public function getDelete(ManageableModel $modelInstance) {
		if ($modelInstance->id === Confide::user ()->id)
			return Redirect::secure ( 'admin/users' )->with ( 'error', Lang::get ( 'admin/users/messages.delete.impossible' ) );
		return parent::getDelete($modelInstance);
	}
		
	/**
	 * {@inheritDoc}
	 * @see ModelController::getLinks()
	 */
	protected function getLinks(ManageableModel $user) {
		return [];
	}
}
