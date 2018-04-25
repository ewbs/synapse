<?php
/**
 * Composants génériques
 *
 * @property string         $name                         Obligatoire, maximum 1024 caractères
 * @property string         $description
 * @property float          $cost_administration_currency
 * @property float          $cost_citizen_currency
 * @property int            $type_id                      (FK demarchesPiecesAndTasksTypes)
 * @author mgrenson
 */
abstract class Component extends TrashableModel {
	
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
	public function formRules() {
		return [
			'name' => 'required|min:3',
			'cost_administration_currency' => [
				'required',
				'regex:'.NumberHelper::DECIMAL_REGEX
			],
			'cost_citizen_currency' => [
				'required',
				'regex:'.NumberHelper::DECIMAL_REGEX
			],
			/*'type' => 'required|integer|min:0'*/
		];
	}
	
	/**
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function annexes() {
		return $this->hasMany ( 'Annexe' );
	}

	/**
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public abstract function demarcheComponents();
	
	/**
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function type() {
		return $this->belongsTo ( 'PieceType' );
	}
}
