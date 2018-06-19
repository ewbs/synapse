<?php
/**
 * Catalogue des tâches
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
