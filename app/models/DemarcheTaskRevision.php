<?php

/**
 * Liaisons entre les tâches et les démarches
 * 
 * @property int            $demarche_demarcheTask_id       Obligatoire, @see DemarcheTask
 * @author jdavreux
 * 
 * Note :  On gère l'historique des modifications ! (à chaque modif, on crée un nouvel élément en fait.
 * C'est pour ca que les FK ne sont pas des indexes : on va avoir des doublons, forcément.
 * 
 */
class DemarcheTaskRevision extends DemarcheComponentRevision {
	
	protected $table = 'demarche_demarcheTask_revisions';
	
	/**
	 *
	 * {@inheritDoc}
	 * @see ManageableModel::getModelLabel()
	 */
	public function getModelLabel() {
		return 'demarchesTasks';
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see ManageableModel::getModelLabelSingularSnake()
	 */
	public function getModelLabelSingularSnake() {
		return 'demarche_task';
	}
	
	/**
	 * Règles de validation au niveau du modèle
	 * @var array
	 */
	public static $rules=[
		'user_id' => 'required',
		'cost_administration_currency' => 'required',
		'cost_citizen_currency' => 'required',
		'volume' => 'required',
		'frequency' => 'required',
		'gain_potential_administration' => 'required',
		'gain_potential_citizen' => 'required',
		'gain_real_administration' => 'required',
		'gain_real_citizen' => 'required',
	];
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see DemarcheComponent::current_state()
	 */
	public function current_state() {
		return $this->belongsTo ( 'DemarcheTaskState', 'current_state_id' );
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see DemarcheComponent::next_state()
	 */
	public function next_state() {
		return $this->belongsTo ( 'DemarcheTaskState', 'next_state_id' );
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see RevisionModel::revisable()
	 */
	public function revisable() {
		return $this->belongsTo ( 'DemarcheTask', 'demarche_demarcheTask_id' );
	}
}
