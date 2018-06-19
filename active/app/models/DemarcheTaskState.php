<?php
/**
 * Etats de démarches-pièces
 * 
 * @author mgrenson
 */
class DemarcheTaskState extends DemarcheComponentState {
	
	protected $table = 'demarchesTasksStates';
	
	/**
	 * {@inheritDoc}
	 * @see ManageableModel::getModelLabel()
	 */
	public function getModelLabel() {
		return 'demarchestaskstates';
	}
	
	/**
	 * Démarches-tâches ayant cet état suivant
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function DemarcheTaskCurrent() {
		return $this->hasMany ( 'DemarcheTask', 'current_state_id' );
	}
	
	/**
	 * Démarches-tâches ayant cet état suivant
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function DemarcheTaskNext() {
		return $this->hasMany( 'DemarcheTask', 'next_state_id' );
	}
}
