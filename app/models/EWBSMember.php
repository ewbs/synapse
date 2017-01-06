<?php
/**
 * Membres de l'eWBS
 *
 * @property int            $id              (PK)
 * @property int            $user_id         Obligatoire, @see User
 * @property int            $ewbs_action_id  Obligatoire, @see EwbsAction
 * @property string         $lastname        Maximum 255 caractères
 * @property string         $firstname       Maximum 255 caractères
 * @property string         $jobtitle        Maximum 255 caractères
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 *
 * @author jdavreux
 */
class EWBSMember extends TrashableModel {
	
	protected $table = 'ewbs_members';
	
	/**
	 * {@inheritDoc}
	 * @see \LaravelBook\Ardent\Ardent::validate()
	 */
	public function validate(array $rules=array(), array $customMessages=array(), array $customAttributes=array()) {
		$uniqueRuleCond=($this->id)?(','.$this->id):''; // C'est pour cela qu'on doit redéfinir la méthode, on doit accéder à l'id courant => on ne peut pas se contenter des variables static $rules et $customAttributes
		return parent::validate(
			[
				'lastname' => 'required|min:3',
				'firstname' => 'required|min:3',
				'user_id' => "unique:ewbs_members,user_id{$uniqueRuleCond}|required_without:id",
			],
			[
				'user_id.required_without' => 'Le champ <i>Utilisateur</i> est requis.',
				'user_id.unique' => 'Cet utilisateur est déjà lié à un membre du personnel, voir peut-être dans la corbeille ?',
			],
			$customAttributes
		);
		// FIXME : A cause du composant select2 le message sur le champ user ne parvient pas à être affiché
	}
		
	/**
	 * {@inheritDoc}
	 * @see ManageableModel::getModelLabel()
	 */
	public function getModelLabel() {
		return 'ewbsmembers';
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see ManageableModel::permissionManage()
	 */
	public function permissionManage() {
		return 'manage_users';
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see ManageableModel::name()
	 */
	public function name() {
		return $this->firstname.' '.$this->lastname;
	}
	
	/**
	 * 
	 */
	public function ideas() {
		return $this->hasMany ( 'Idea' );
	}
	
	/**
	 * 
	 */
	public function user() {
		return $this->belongsTo ( 'User' );
	}
}
