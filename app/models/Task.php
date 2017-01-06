<?php
/**
 * Catalogue des tâches
 *
 * @property int            $id                           (PK)
 * @property string         $name                         Obligatoire, maximum 1024 caractères
 * @property string         $description
 * @property float          $cost_administration_currency
 * @property float          $cost_citizen_currency
 * @property int            $type_id                      (FK demarchesPiecesAndTasksTypes)
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 *
 * @author jdavreux
 */
class Task extends Component {
	
	protected $table = 'demarchesTasks';
	
	/**
	 * {@inheritDoc}
	 * @see ManageableModel::getModelLabel()
	 */
	public function getModelLabel() {
		return 'tasks';
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see Component::demarcheComponents()
	 */
	public function demarcheComponents() {
		return $this->demarcheTasks();
	}
	
	/**
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function demarcheTasks() {
		return $this->hasMany ( 'DemarcheTask' );
	}
}
