<?php

use Illuminate\Database\Eloquent\Builder;

/**
 * Liaisons entre les tâches et les démarches
 * 
 * @property int            $task_id                        Obligatoire, @see Task
 * @author jdavreux
 * 
 * Note : On gère l'historique des modifications ! (à chaque modif, on crée un nouvel élément en fait.
 * C'est pour ca que les FK ne sont pas des indexes : on va avoir des doublons, forcément.
 */
class DemarcheTask extends DemarcheComponent {
	
	protected $table = 'demarche_demarcheTask';
	
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
	 * 
	 * {@inheritDoc}
	 * @see DemarcheComponent::componentType()
	 */
	public function componentType() {
		return 'task';
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see DemarcheComponent::formRulesChoose()
	 */
	public function formRulesChoose() {
		/*
		 * Note :
		 * En général la vérification via les formRules se basent uniquement sur les paramètres reçus de la requête.
		 * Pour cette contrainte d'unicité multpile il est nécessaire que demarche_id et task_id aient été initialisés dans l'instance courante.
		 */
		$namerules[]='required';
		if($this->demarche_id && $this->task_id) {
			$exceptCond=($this->id)?($this->id.',id'):'null,id';
			$namerules[]="unique:demarche_demarcheTask,name,{$exceptCond},demarche_id,{$this->demarche_id},task_id,{$this->task_id}";
		}
		return [
			'componentId' => 'required',
			'name'=>$namerules
		];
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see DemarcheComponent::scopeJoinLastRevision()
	 */
	public function scopeJoinLastRevision(Builder $query, $trashed=false) {
		$query
		->join('v_lastrevisiontasksfromdemarche', 'v_lastrevisiontasksfromdemarche.demarche_demarcheTask_id', '=', 'demarche_demarcheTask.id')
		->whereRaw('v_lastrevisiontasksfromdemarche.deleted_at '.($trashed?'is not null':'is null'));
		return $query;
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see DemarcheComponent::scopeMostAsked()
	 */
	public function scopeMostUsed(Builder $query, $limit=0) {
		$query
		->select([
			'demarche_demarcheTask.name AS displayname',
			DB::raw('SUM(v_lastrevisiontasksfromdemarche.volume * v_lastrevisiontasksfromdemarche.frequency) AS count_items')
		])
		->joinLastRevision()
		->having(DB::raw('SUM(v_lastrevisiontasksfromdemarche.volume * v_lastrevisiontasksfromdemarche.frequency)'), '>', 0)
		->groupBy('demarche_demarcheTask.id')
		->orderBy('count_items', "DESC");
		if($limit>0) {
			$query->limit($limit);
		}
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see DemarcheComponent::scopePotentiallyMostGainful()
	 */
	public function scopePotentiallyMostGainful(Builder $query, $limit=0) {
		$query
		->select([
			'demarche_demarcheTask.name AS displayname',
			DB::raw('SUM(v_lastrevisiontasksfromdemarche.gain_potential_administration + v_lastrevisiontasksfromdemarche.gain_potential_citizen) AS gpagpc')
		])
		->joinLastRevision()
		->having(DB::raw('SUM(v_lastrevisiontasksfromdemarche.gain_potential_administration + v_lastrevisiontasksfromdemarche.gain_potential_citizen)'), '>', 0)
		->groupBy('demarche_demarcheTask.id')
		->orderBy('gpagpc', "DESC");
		if($limit>0) {
			$query->limit($limit);
		}
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see ManageableModel::formRulesMessages()
	 */
	public function formRulesMessages() {
		return array_merge(parent::formRulesMessages(), [
			'name.unique' => 'Cette tâche est déjà liée à la démarche courante avec ce nom. Peut-être est-ce cette autre tâche que vous souhaitiez éditer ?<br/><i>nb : Si cette tâche n\'est pas présente parmi la liste, elle a alors été supprimée de la démarche => il est possible de la recréer via la fonction "Ajouter une tâche"</i>',
		]);
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see DemarcheComponent::component()
	 */
	public function component() {
		return $this->task();
	}
	
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
	 * Relation vers les révisions
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function revisions() {
		return $this->hasMany('DemarcheTaskRevision', 'demarche_demarcheTask_id');
	}
	
	/**
	 * Relation vers la tâche liée
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function task() {
		return $this->belongsTo ( 'Task', 'task_id' );
	}
}
