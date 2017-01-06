<?php
/**
 * Tarif des tâches
 *
 * @property int            $id            (PK)
 * @property string         $name          Obligatoire, maximum 1024 caractères
 * @property float          $hour_rate     Obligatoire
 * @property string         $description
 * @property string         $who           Obligatoire, maximum 255 caractères
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 *
 * @author jdavreux
 */
class PieceRate extends TrashableModel {
	
	protected $table = 'demarchesTasksRates';
	
	/**
	 * {@inheritDoc}
	 * @see ManageableModel::getModelLabel()
	 */
	public function getModelLabel() {
		return 'piecesrates';
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see ManageableModel::permissionManage()
	 */
	public function permissionManage() {
		return 'pieces_tasks_manage';
	}
	
	/**
	 * {@inheritDoc}
	 * @see ManageableModel::formRules()
	 */
	public function formRules () {
		return [
			'name' => 'required|min:3',
			'hour_rate' => [
				'required',
				'regex:'.NumberHelper::DECIMAL_REGEX
			],
			'who' => 'required|in:citizen,administration'
		];
	}
	
	/**
	 * Scope : Ne prendre que les tarifs liés à l'administration
	 * 
	 * @param type $query        	
	 */
	public function scopeAdministration($query) {
		return $query->where ( 'who', '=', 'administration' );
	}
	
	/**
	 * Scope : Ne prendre que les tarifs liés à l'usager
	 * 
	 * @param type $query        	
	 */
	public function scopeCitizen($query) {
		return $query->where ( 'who', '=', 'citizen' );
	}
	
	/**
	 * 
	 */
	public function piecesTasksAdministration() {
		return $this->hasMany('pieceTask', 'rate_administration_id');
	}
	
	/**
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function piecesTasksCitizen() {
		return $this->hasMany('pieceTask', 'rate_citizen_id');
	}
}
