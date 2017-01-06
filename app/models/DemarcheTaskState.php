<?php
/**
 * Etats de démarches-pièces
 * 
 * @property int            $id                  (PK)
 * @property string         $code                Obligatoire
 * @property string         $name                Obligatoire
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
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
