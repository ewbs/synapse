<?php
use Illuminate\Database\Eloquent\Builder;
use Zizaco\Entrust\HasRole;

/**
 * Actions
 *
 * @property string         $name
 * @property string         $token
 * @property boolean        $sub
 * @property int            $demarche_id        @see Demarche
 * @property int            $demarche_piece_id  @see Piece
 * @property int            $demarche_task_id   @see Task
 * @property int            $idea_id            @see Idea
 * @property int            $eform_id           @see EForm
 * @property int            $parent_id          @see EwbsAction
 * @author jdavreux
 */
class EwbsAction extends RevisableModel {

	use TraitFilterable;

	protected $table = 'ewbsActions';
	
	public function __construct() {
		parent::__construct();
		$this->addRevisionAttributes(['state'=>EwbsActionRevision::$STATE_TODO]);
	}
	
	/**
	 * {@inheritDoc}
	 * @see ManageableModel::getModelLabel()
	 */
	public function getModelLabel() {
		return 'ewbsactions';
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see ManageableModel::hasView()
	 */
	public function hasView() {
		return true;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see ManageableModel::canDelete()
	 */
	public function canDelete(\User $loggedUser=null) {
		return false;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see ManageableModel::formRules()
	 */
	public function formRules() {
		return [
			'name' => 'required|min:3',
			'description' => 'required|min:5',
		];
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see ManageableModel::extraFormValidate()
	 */
	public function extraFormValidate(\Illuminate\Validation\Validator $validator) {
		$user=Auth::user();
		if($user && $user->hasRole('admin') && !Input::get('sub') && $this->eachSub()->count()>0) {
			$validator->errors()->add('sub', 'Cette action a des sous-actions liées, il n\'est pas possible d\'en interdire la présence');
			return false;
		}
		return true;
	}
	
	/**
	 * Route listant les instances d'une action liée à une démarche.
	 * Si une démarche est bien liée à l'action retour vers le détail de la démarche (qui liste les actions), sinon retour vers la liste des démarches.
	 * 
	 * {@inheritDoc}
	 * @see ManageableModel::routeGetIndex()
	 */
	public function routeGetIndex() {
		/*if($this->demarche_id)
			return route('demarchesGetView', $this->demarche_id);
		else*/ //TODO: vérifier qu'il n'y a pas d'impact ... j'ai fait la modif pour revenir à la liste quand on édite une action depuis le log d'action. ---jda 2016-07-06
			return route('ewbsactionsGetIndex');
	}
	
	/**
	 * Query scope listant les différents noms des actions
	 *
	 * @param Builder $query
	 * @return Builder
	 */
	public function scopeDistinctNames(Builder $query) {
		return $query->distinct()->main()->addSelect(['name'])->orderBy('name');
	}
	
	/**
	 * Query scope listant les actions principales (=qui n'ont pas de parent) avec leur dernière révision, les composants liés (pièce ou tâche), les éléments éventuellement liés (démarche, eform, idée), et le dernier user l'ayant sauvegardé
	 *
	 * @param Builder $query
	 * @return Builder
	 */
	public function scopeDistinctResponsibles(Builder $query) {
		return $query->joinResponsibles()->distinct()->addSelect(['resp.id','resp.username']);
	}
	/**
	 * Query scope liant à la requête les responsables des actions
	 * 
	 * @param Builder $query
	 * @param string $trashed
	 * @return unknown
	 */
	public function scopeJoinResponsibles(Builder $query, $trashed=false) {
		return $query->main()->joinLastRevision($trashed, false)->join('users as resp', 'resp.id', '=', 'v_lastrevisionewbsaction.responsible_id');
	}
	
	/**
	 * Query scope ciblant les actions principales (=qui n'ont pas de parent)
	 *
	 * @param Builder $query
	 * @return Builder
	 */
	public function scopeMain(Builder $query) {
		return $query->whereNull('ewbsActions.parent_id');
	}
	
	/**
	 * Query scope listant les actions principales (=qui n'ont pas de parent) avec leur dernière révision, les composants liés (pièce ou tâche), les éléments éventuellement liés (démarche, eform, idée), et le dernier user l'ayant sauvegardé
	 *
	 * @param Builder $query
	 * @param string $trashed Prendre les soft-deletés, false par défaut
	 * @return Builder
	 */
	public function scopeEach(Builder $query, $trashed=false) {
		return $query
		->addSelect(['ewbsActions.id AS action_id', 'ewbsActions.name'])
		->main()
		->joinComponents()->joinLastRevision($trashed)->joinLinkedElement();
	}
	
	/**
	 * Query scope listant les actions principales (=qui n'ont pas de parent) avec leur dernière révision, les composants liés (pièce ou tâche), les éléments éventuellement liés (démarche, eform, idée), et le dernier user l'ayant sauvegardé
	 *
	 * @param Builder $query
	 * @param string $trashed Prendre les soft-deletés, false par défaut
	 * @return Builder
	 */
	public function scopeEachSub(Builder $query) {
		return $query
		->addSelect(['ewbsActions.id AS action_id', 'ewbsActions.name'])
		->where('ewbsActions.parent_id', '=', $this->id)
		->joinLastRevision();
	}
	
	/**
	 * Restreindre aux actions liées à une ou plusieurs administrations via un projet de simplif' ou une démarche
	 *
	 * @param Builder $query
	 * @param array $values Identifiants d'administration
	 * @return unknown|\Illuminate\Database\Eloquent\Builder
	 */
	public function scopeForAdministrations(Builder $query, array $values) {
		if($values) {
			return $query->where(
				function(Builder $query) use($values) {
					$query
					->whereExists(
						function($query) use($values) {
							$query->select(DB::raw(1))
							->from('administration_idea')
							->whereRaw('administration_idea.idea_id = "ewbsActions".idea_id')
							->whereIn('administration_idea.administration_id', $values);
						}
					)
					->OrWhereExists(
						function($query) use($values) {
							$query->select(DB::raw(1))
							->from('administration_demarche')
							->whereRaw('administration_demarche.demarche_id = "ewbsActions".demarche_id')
							->whereIn('administration_demarche.administration_id', $values);
						}
					);
				}
			);
		}
		else return $query;
	}
	
	/**
	 * Restreindre aux actions liées à une démarche
	 *
	 * @param Builder $query
	 * @param Demarche $demarche
	 * @return Builder
	 */
	public function scopeForDemarche(Builder $query, Demarche $demarche) {
		return $query->where('ewbsActions.demarche_id', '=', $demarche->id);
	}
	
	/**
	 * Restreindre aux actions correspondant à un ou plusieurs noms
	 * 
	 * @param Builder $query
	 * @param array $values Noms d'actions
	 * @return unknown|\Illuminate\Database\Eloquent\Builder
	 */
	public function scopeForNames(Builder $query, array $values) {
		if($values) return $query->whereIn('ewbsActions.name', $values);
		else return $query;
	}
	
	/**
	 * Restreindre aux actions liées à un ou plusieurs responsables
	 *
	 * @param Builder $query
	 * @param array $values Identifiants de responsables
	 * @return unknown|\Illuminate\Database\Eloquent\Builder
	 */
	public function scopeForResponsibles(Builder $query, array $values) {
		if($values) return $query->whereIn('v_lastrevisionewbsaction.responsible_id', $values);
		else return $query;
	}
	
	/**
	 * Restreindre aux actions actions liées à des démarches (sans prendre celles liées à des pièces et des taches)
	 * @param Builder $query
	 * @param Demarche $demarche
	 * @return $this
	 */
	public function scopeOnlyLinkedToDemarches(Builder $query) {
		return $query
			->where('ewbsActions.demarche_id', '>', 0)
			->whereNull('ewbsActions.demarche_piece_id')
			->whereNull('ewbsActions.demarche_task_id');
	}

	/**
	 * Restreindre aux actions liées à un eform
	 *
	 * @param Builder $query
	 * @param Eform $eform
	 * @return Builder
	 */
	public function scopeForEform(Builder $query, Eform $eform) {
		return $query->where ('ewbsActions.eform_id', '=', $eform->id);
	}

	/**
	 * Restreindre aux actions liées à un formulaire (formulaire seul ou via une démarche, sans distinction)
	 * 
	 * @param Builder $query
	 * @return $this
	 */
	public function scopeForEforms(Builder $query) {
		return $query->where ('ewbsActions.eform_id', '>', 0);
	}

	/**
	 * Restreindre aux actions liées à une idée
	 *
	 * @param Builder $query
	 * @param Idea $idea
	 * @return Builder
	 */
	public function scopeForIdea(Builder $query, Idea $idea) {
		return $query->where ('ewbsActions.idea_id', '=', $idea->id);
	}
	
	/**
	 * Restreindre aux actions liées à une pièce ou tâche
	 * 
	 * @param Builder $query
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeForComponents(Builder $query) {
		return $query->where( function ($query) {
			$query->whereNotNull('ewbsActions.demarche_piece_id')->orWhereNotNull('ewbsActions.demarche_task_id');
		});
	}
	
	/**
	 * Query scope joignant la dernière révision d'une action
	 *
	 * @param Builder $query
	 * @return Builder
	 */
	public function scopeJoinComponents(Builder $query) {
		return $query
		->addSelect([
			'ewbsActions.demarche_piece_id',
			'demarche_demarchePiece.name AS demarche_piece_name',
			'ewbsActions.demarche_task_id',
			'demarche_demarcheTask.name AS demarche_task_name'
		])
		->leftjoin('demarche_demarchePiece', 'demarche_demarchePiece.id', '=', 'ewbsActions.demarche_piece_id')
		->leftjoin('demarche_demarcheTask', 'demarche_demarcheTask.id', '=', 'ewbsActions.demarche_task_id');
	}
	
	/**
	 * Query scope joignant les éléments éventuellement liés (démarche, eform, idée)
	 *
	 * @param Builder $query
	 * @return Builder
	 */
	public function scopeJoinLinkedElement(Builder $query) {
		return $query
		
		->addSelect(['ewbsActions.demarche_id', 'nostra_demarches.title as demarche_name'])
		->leftjoin('demarches', 'demarches.id', '=', 'ewbsActions.demarche_id')
		->leftjoin('nostra_demarches', 'nostra_demarches.id', '=', 'demarches.nostra_demarche_id')
		
		->addSelect(['ewbsActions.eform_id', DB::raw('CASE WHEN eforms.nostra_form_id IS NOT NULL THEN nostra_forms.title ELSE eforms.title END as eform_name')])
		->leftjoin('eforms', 'eforms.id', '=', 'ewbsActions.eform_id')
		->leftjoin('nostra_forms', 'eforms.nostra_form_id', '=', 'nostra_forms.id')
		
		->addSelect(['ewbsActions.idea_id', 'ideas.name as idea_name'])
		->leftjoin('ideas', 'ideas.id', '=', 'ewbsActions.idea_id');
	}


	public function scopeJoinTaxonomy(Builder $query) {
		return $query
			->addSelect([DB::raw('(	SELECT 
										ARRAY_TO_STRING(ARRAY_AGG(DISTINCT taxonomytags.name), \', \', \'\') 
									FROM 
										taxonomytags,
										ewbs_action_taxonomy_tag
									WHERE 
										taxonomytags.deleted_at IS NULL 
										AND taxonomytags.id = ewbs_action_taxonomy_tag.taxonomy_tag_id
										AND ewbs_action_taxonomy_tag.ewbs_action_id = "ewbsActions".id 
								   ) AS tags' )]);
	}
	
	/**
	 * Query scope joignant la dernière révision
	 * 
	 * @param Builder $query
	 * @return Builder
	 */
	public function scopeJoinLastRevision(Builder $query, $trashed=false, $addColumn=true) {
		$query
		->join('v_lastrevisionewbsaction', 'ewbsActions.id', '=', 'v_lastrevisionewbsaction.ewbs_action_id')
		->leftjoin('users', 'users.id', '=', 'v_lastrevisionewbsaction.user_id')
		->leftjoin('users as responsibles', 'responsibles.id', '=', 'v_lastrevisionewbsaction.responsible_id')
		->whereRaw('v_lastrevisionewbsaction.deleted_at '.($trashed?'is not null':'is null'));
		
		if($addColumn) {
			$query->addSelect([
				'v_lastrevisionewbsaction.id AS revision_id',
				'v_lastrevisionewbsaction.description',
				'v_lastrevisionewbsaction.state',
				'v_lastrevisionewbsaction.priority',
				'responsibles.username as responsible',
				'v_lastrevisionewbsaction.created_at',
				'v_lastrevisionewbsaction.deleted_at',
				'users.username'
			]);
		}
		return $query;
	}


	/**
	 * Ne prendre que les actions pour l'équipe nostra.
	 * ATTENTION !! Cette méthode est pourrie. On se limite à se dire que "si il y a un token dans la db, c'est que c'est pour nostra"
	 * C'est vrai à l'heure actuelle ... mais à revoir si d'autres utilisateurs sont amenés à traiter des actions en dehors de synapse
	 * @param Builder $query
	 * @return $this
	 */
	public function scopeForNostraTeam (Builder $query) {
		return $query->where('token', '>', 0);
	}


	/**
	 * Scope par administration pour le trait Filterable
	 * une action peut être reliée à une administration :
	 *  - par une Idea
	 * 	- par une Demarche
	 *  - par une piece, une tache ou un eform dans une Demarche (et on a cette info lorsqu'on a une pice ou une tache)
	 * @param $query
	 * @param $administrationsIds
	 */
	public function scopeAdministrationsIds($query, $administrationsIds) {
		if (is_array ( $administrationsIds ) && count ( $administrationsIds )) {
			return $query->where(function ($query) use ($administrationsIds) {
				$query
					->whereHas('idea', function ($query) use ($administrationsIds) {
						$query->whereHas('administrations', function ($query) use ($administrationsIds) {
							$query->whereIn('administration_id', $administrationsIds);
						});
					})
					->orWhereHas('demarche', function ($query) use ($administrationsIds) {
						$query->whereHas('administrations', function ($query) use ($administrationsIds) {
							$query->whereIn('administration_id', $administrationsIds);
						});
					});
			});
		}
		return $query;
	}

	/**
	 * On prend les actions liées directement aux tags
	 * Mais on doit aussi prendre :
	 *  - les actions liées à des demarches qui ont ces tags
	 *  - les actions liées à des ideas qui ont ces tags
	 * @param $query
	 * @param $tagsIds
	 */
	public function scopetaxonomyTagsIds($query, $tagsIds) {
		if (is_array ( $tagsIds ) && count ( $tagsIds )) {
			return $query->where(function ($query) use ($tagsIds){
				$query
				->whereHas('tags', function ($query) use ($tagsIds) {
					$query->whereIn('taxonomy_tag_id', $tagsIds);
				})
				->orWhereHas('demarche', function ($query) use ($tagsIds) {
					$query->whereHas('tags', function ($query) use ($tagsIds) {
						$query->whereIn('taxonomy_tag_id', $tagsIds);
					});
				})
				->orWhereHas('demarche', function ($query) use ($tagsIds) {
					$query->whereHas('tags', function ($query) use ($tagsIds) {
						$query->whereIn('taxonomy_tag_id', $tagsIds);
					});
				});
			});
		}
		return $query;
	}

	/**
	 * On prend les actions
	 * 	- liées à des idées liées aux publics
	 * 	- liées à des démarche sliées aux publics
	 * @param $query
	 * @param $publicsIds
	 */
	public function scopenostraPublicsIds($query, $publicsIds) {
		if (is_array($publicsIds) && count($publicsIds)) {
			return $query->where(function ($query) use ($publicsIds) {
				$query
				->whereHas('demarche', function($query) use($publicsIds) {
					$query->whereHas('nostraDemarche', function ($query) use ($publicsIds) {
						$query->whereHas('nostraPublics', function ($query) use ($publicsIds) {
							$query->whereIn('nostra_public_id', $publicsIds);
						});
					});
				})
				->orWhereHas('idea', function ($query) use($publicsIds) {
					$query->whereHas('nostraPublics', function ($query) use ($publicsIds) {
						$query->whereIn('nostra_public_id', $publicsIds);
					});
				});
			});
		}
		return $query;
	}




	/**
	 * Query scope joignant les noms des éventuelles sous-actions
	 *
	 * @param Builder $query
	 * @return Builder
	 */
	public function scopeJoinSubActions(Builder $query) {
		return $query
		->addSelect([
			DB::raw('(SELECT ARRAY_TO_STRING(ARRAY_AGG(DISTINCT subactions.name), \', \', \'\') FROM "ewbsActions" subactions WHERE subactions.parent_id="ewbsActions".id AND subactions.deleted_at IS NULL) AS subactions'),
		]);
	}
	/**
	 * A partir d'un id de EwbsActionRevision, retourne l'historique de l'action dans cette démarche
	 *
	 * @return mixed|static
	 */
	public function getRevisions() {
		return EwbsActionRevision
		::withTrashed ()
		->leftjoin('users', 'users.id', '=', 'ewbsActionsRevisions.user_id')
		->where ( 'ewbsActionsRevisions.ewbs_action_id', '=', $this->id )
		->orderBy ( 'ewbsActionsRevisions.created_at', 'DESC' )
		->get (['ewbsActionsRevisions.id as revision_id', 'users.username', 'ewbsActionsRevisions.state', 'ewbsActionsRevisions.description', 'ewbsActionsRevisions.created_at', 'ewbsActionsRevisions.deleted_at']);
	}
	
	/**
	 * Retourne un état global selon un nombre d'actions à différents états.
	 * 
	 * La particularité est que l'état est globalement en progrès si des actions sont dans 2 des 3 états todo/standby/done.
	 * @param Object, ayant comme propriétés minimum les entiers suivants $count_state_todo, $count_state_progress, $count_state_done, $count_state_standby, $count_state_givenup
	 * @return string|NULL
	 */
	public static function globalState($obj) {
		if($obj->count_state_progress || ($obj->count_state_todo && $obj->count_state_done)|| ($obj->count_state_todo && $obj->count_state_standby)|| ($obj->count_state_standby && $obj->count_state_done)) return EwbsActionRevision::$STATE_PROGRESS;
		if($obj->count_state_todo) return EwbsActionRevision::$STATE_TODO;
		if($obj->count_state_done) return EwbsActionRevision::$STATE_DONE;
		if($obj->count_state_standby) return EwbsActionRevision::$STATE_STANDBY;
		if($obj->count_state_givenup) return EwbsActionRevision::$STATE_GIVENUP;
		return null;
	}
	
	/**
	 * Spécifier une description qui sera sauvée dans la future révision liée à l'action
	 * 
	 * @param string $value
	 */
	public function setDescription($value) {
		$this->addRevisionAttributes(['description'=>$value]);
	}
	
	/**
	 * Spécifier un état qui sera sauvé dans la future révision liée à l'action
	 * 
	 * @param string $value
	 */
	public function setState($value) {
		$this->addRevisionAttributes(['state'=>$value]);
	}
	
	/**
	 * Retourne l'historique d'une action sous forme d'une collection
	 * 
	 * @return mixed
	 */
	public function getHistory() {
		return EwbsActionRevision
			::withTrashed ()
			->leftjoin('users', 'users.id', '=', 'ewbsActionsRevisions.user_id')
			->leftjoin('users as responsibles', 'responsibles.id', '=', 'ewbsActionsRevisions.responsible_id')
			->where ( 'ewbsActionsRevisions.ewbs_action_id', '=', $this->id )
			->orderBy ( 'ewbsActionsRevisions.created_at', 'DESC' )
			->get (['ewbsActionsRevisions.id as revision_id', 'users.username', 'users.email AS usermail', 'responsibles.username as responsible_username', 'ewbsActionsRevisions.state', 'ewbsActionsRevisions.priority', 'ewbsActionsRevisions.description', 'ewbsActionsRevisions.created_at', 'ewbsActionsRevisions.deleted_at']);
	}
	
	/**
	 * Compter les actions en cours
	 * 
	 * @return int
	 */
	public static function getCountTodo() {
		return DB::table('v_lastrevisionewbsaction')
			->where('state', '=',  EwbsActionRevision::$STATE_TODO)
			->whereNull('deleted_at')
			->count();
	}
	
	/**
	 * Compter les actions terminées
	 * 
	 * @return int
	 */
	public static function getCountDone() {
		return DB::table('v_lastrevisionewbsaction')
			->where('state', '=',  EwbsActionRevision::$STATE_DONE)
			->whereNull('deleted_at')
			->count();
	}
	
	/**
	 * Relation vers la démarche
	 *
	 * @see Demarche
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function demarche() {
		return $this->belongsTo ( 'Demarche' );
	}
	
	/**
	 * Relation vers la pièce liée à la démarche
	 *
	 * @see DemarchePiece
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function demarchePiece() {
		return $this->belongsTo ( 'DemarchePiece' );
	}
	
	/**
	 * Relation vers la tâche liée à la démarche
	 *
	 * @see DemarcheTask
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function demarcheTask() {
		return $this->belongsTo ( 'DemarcheTask' );
	}
	
	/**
	 * Relation vers l'eform
	 *
	 * @see Eform
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function eform() {
		return $this->belongsTo ( 'Eform' );
	}
	
	/**
	 * Relation vers l'idée
	 *
	 * @see Idea
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function idea() {
		return $this->belongsTo ( 'Idea' );
	}
	
	/**
	 * Relation vers l'action parente
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function parent() {
		return $this->belongsTo( 'EwbsAction', 'parent_id' );
	}
	
	/**
	 * {@inheritDoc}
	 * @see RevisableModel::revisions()
	 */
	public function revisions() {
		return $this->hasMany ( 'EwbsActionRevision' );
	}
	
	/**
	 *
	 * Relation vers les sous-actions
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function subactions() {
		return $this->hasMany( 'EwbsAction', 'parent_id' );
	}
	
	/**
	 * Relation vers les tags
	 *
	 * @see TaxonomyTag
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function tags() {
		return $this->belongsToMany('TaxonomyTag');
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
		 * Traitements suite à la création d'une action ou sous-action
		 */
		self::restored(function(EwbsAction $modelInstance) {
			if($modelInstance->parent_id) {
				$parent=$modelInstance->parent; /* @var EwbsAction $parent */
				// Restaurer l'action parente si elle est supprimée alors que l'action courante vient donc d'être restaurée
				if($parent->deleted_at) {
					$parent->setDescription(Lang::get('admin/ewbsactions/messages.listener.parent.restored',['subaction'=>$action->name()]));
					$parent->restore();
				}
			}
			
			if(!$modelInstance->sub) return;
			/* 
			 * TODO : Restaurer toutes les sous-actions ?
			 * Attention, dans ce cas il faudra prendre attention au recalcul de l'état de l'action parente,
			 * qui doit se faire uniquement lorsque toutes les actions auraient été restaurées.
			 * Ne va pas être simple, car le restore du parent déclenche déjà la réactualisation de l'état (cf. 
			 */
		});
		
		/**
		 * Traitements suite à la suppression d'une action ou sous-action
		 */
		self::deleted(function(EwbsAction $modelInstance) {
			// Si l'action a des sous-actions, suppression en cascade de toutes les sous-actions
			if(!$modelInstance->sub) return;
			$subactions=$modelInstance->subactions()->getResults();
			foreach($subactions as $subaction) { /* @var EwbsAction $subaction */
				$subaction->delete();
			}
		});
		
		
	}
}
