<?php
/**
 * Révisions des actions
 *
 * @property int            $ewbs_action_id  Obligatoire, @see EwbsAction
 * @property string         $state           Maximum 255 caractères
 * @property string         $priority
 * @property string         $description
 * @author mgrenson
 */
class EwbsActionRevision extends RevisionModel {
	
	protected $table = 'ewbsActionsRevisions';
	
	public static $STATE_TODO='todo';
	public static $STATE_PROGRESS='progress';
	public static $STATE_DONE='done';
	public static $STATE_GIVENUP='givenup';
	
	public static $PRIORITY_LOW='low';
	public static $PRIORITY_NORMAL='normal';
	public static $PRIORITY_HIGH='high';
	public static $PRIORITY_CRITICAL='critical';
	
	/**
	 * {@inheritDoc}
	 * @see ManageableModel::getModelLabel()
	 */
	public function getModelLabel() {
		return 'ewbsactions';
	}
	
	/**
	 * {@inheritDoc}
	 * @see RevisionModel::attributes()
	 */
	public function attributes(){
		return ['description', 'state', 'priority'];
	}
	
	/**
	 * Permet de récupérer la liste des différents états possibles pour une action
	 * 
	 * return array
	 */
	public static function states() {
		return [self::$STATE_TODO, self::$STATE_PROGRESS, self::$STATE_DONE, self::$STATE_GIVENUP];
	}
	
	/**
	 * Associe une classe bootstrap à l'état de l'action
	 * 
	 * @param string $state
	 * @return string
	 */
	public static function stateToClass($state) {
		switch($state){
			case self::$STATE_TODO     :return 'default';
			case self::$STATE_PROGRESS :return 'primary';
			case self::$STATE_DONE     :return 'success';
			case self::$STATE_GIVENUP  :return 'warning';
			default : throw new \UnexpectedValueException($state);
		}
	}
	
	/**
	 * Associe un numéro d'ordre à l'état de l'action
	 * 
	 * @param string $state
	 * @return string
	 */
	public static function stateToNumber($state) {
		switch($state){
			case self::$STATE_TODO     :return 1;
			case self::$STATE_PROGRESS :return 2;
			case self::$STATE_DONE     :return 3;
			case self::$STATE_GIVENUP  :return 4;
			default : throw new \UnexpectedValueException($state);
		}
	}
	
	/**
	 * Présente visuellement l'état de l'action (span avec label + span caché avec numéro de l'action pour tri dans des tableaux)
	 *
	 * @param string $state
	 * @return string la portion de code html
	 */
	public static function graphicState($state) {
		return '<span class="hidden">'.self::stateToNumber($state).'</span><span class="label label-'.self::stateToClass($state).'">'.Lang::get( "admin/ewbsactions/messages.state.{$state}").'</span>';
	}
	
	/**
	 * Permet de récupérer la liste des différentes priorités possibles pour une action
	 *
	 * return array
	 */
	public static function priorities() {
		return [self::$PRIORITY_LOW, self::$PRIORITY_NORMAL, self::$PRIORITY_HIGH, self::$PRIORITY_CRITICAL];
	}
	
	/**
	 * Associe une classe bootstrap à la priorité de l'action
	 *
	 * @param string $priority
	 * @return string
	 */
	public static function priorityToClass($priority) {
		switch($priority){
			case self::$PRIORITY_CRITICAL :return 'danger';
			case self::$PRIORITY_HIGH     :return 'warning';
			case self::$PRIORITY_NORMAL   :return 'info';
			case self::$PRIORITY_LOW      :return 'default';
			default : throw new \UnexpectedValueException($priority);
		}
	}
	
	/**
	 * Associe un numéro d'ordre à la priorité de l'action
	 *
	 * @param string $priority
	 * @return string
	 */
	public static function prioritiesToNumber($priority) {
		switch($priority){
			case self::$PRIORITY_CRITICAL :return 1;
			case self::$PRIORITY_HIGH     :return 2;
			case self::$PRIORITY_NORMAL   :return 3;
			case self::$PRIORITY_LOW      :return 4;
			default : throw new \UnexpectedValueException($priority);
		}
	}
	
	/**
	 * Présente visuellement la priorité de l'action (span avec label + span caché avec numéro de l'action pour tri dans des tableaux)
	 *
	 * @param string $priority
	 * @return string la portion de code html
	 */
	public static function graphicPriority($priority) {
		return '<span class="hidden">'.self::prioritiesToNumber($priority).'</span><span class="label label-'.self::priorityToClass($priority).'">'.Lang::get( "admin/ewbsactions/messages.priority.{$priority}").'</span>';
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see RevisionModel::revisable()
	 */
	public function revisable() {
		return $this->belongsTo ( 'EwbsAction', 'ewbs_action_id' );
	}
	
	/**
	 * {@inheritDoc}
	 * @see RevisionModel::user()
	 */
	public function user() {
		return $this->belongsTo ( 'User' );
	}
	
	
	/**
	 * Liste les événements à enregistrer pour le modèle courant
	 *
	 * @see LaravelBook\Ardent\Ardent::boot()
	 */
	public static function boot() {
		
		/**
		 * Exécuter en premier, afin que le traitement du parent soit bien terminé
		 * (et que la transaction soit commitée)
		 */
		parent::boot();
		/**
		 * Traitements suite au create/update/restore d'une action ou sous-action :
		 * Recalculer l'état de l'action selon l'état de ses sous-actions
		 */
		self::saved(function(EwbsActionRevision $modelInstance) {
			
			/* @var EwbsAction $action */
			$action=$modelInstance->revisable()->getQuery()->withTrashed()->first(); // récupérer l'action liée à la révision, même si action est supprimée
			if(!$action->parent_id) return; // si pas de parent (si ce n'est donc pas une sous-action), on n'a rien à faire
			
			/* @var EwbsAction $parent */
			$parent=$action->parent;
			if(!$parent) { // Si le parent a été supprimé (ignoré par la requête ci-dessus), on n'a rien à faire
				return;
			}
			
			// Calculer un état global selon les état de toutes les sous-actions de l'action parente.
			$substates=$parent->subactions()->getQuery()
			->join( 'v_lastrevisionewbsaction', 'v_lastrevisionewbsaction.ewbs_action_id', '=', 'ewbsActions.id' )
			->whereNull('v_lastrevisionewbsaction.deleted_at')
			->groupBy('ewbsActions.parent_id')
			->first ([
				DB::raw("COUNT(DISTINCT CASE WHEN v_lastrevisionewbsaction.state = '".EwbsActionRevision::$STATE_TODO."'     THEN v_lastrevisionewbsaction.id ELSE NULL END) AS count_state_todo"),
				DB::raw("COUNT(DISTINCT CASE WHEN v_lastrevisionewbsaction.state = '".EwbsActionRevision::$STATE_PROGRESS."' THEN v_lastrevisionewbsaction.id ELSE NULL END) AS count_state_progress"),
				DB::raw("COUNT(DISTINCT CASE WHEN v_lastrevisionewbsaction.state = '".EwbsActionRevision::$STATE_DONE."'     THEN v_lastrevisionewbsaction.id ELSE NULL END) AS count_state_done"),
				DB::raw("COUNT(DISTINCT CASE WHEN v_lastrevisionewbsaction.state = '".EwbsActionRevision::$STATE_GIVENUP."'  THEN v_lastrevisionewbsaction.id ELSE NULL END) AS count_state_givenup")
			]);
			if(empty($substates)) return;
			$state=EwbsAction::globalState($substates);
			
			// Si l'état calculé est différent de l'état du parent, mettre à jour le parent
			if($state && $parent->getLastRevision()->state !=$state) {
				$parent->setState($state);
				$parent->setDescription(Lang::get('admin/ewbsactions/messages.listener.parent.saved',['subaction'=>$action->name()]));
				return $parent->save();
			}
		});
	}
}
