<?php
use Illuminate\Database\Eloquent\Builder;

/**
 * Projets de simplif'
 *
 * @property int            $user_id                            Obligatoire, @see User
 * @property int            $ewbs_member_id                     Obligatoire, @see EWBSMember
 * @property string         $name                               Maximum 1024 caractères
 * @property string         $description
 * @property string         $ext_contact                        Maximum 256 caractères
 * @property string         $abc_notrelated
 * @property string         $freeencoding_nostra_publics
 * @property string         $freeencoding_nostra_thematiquesabc
 * @property string         $freeencoding_nostra_thematiquesadm
 * @property string         $freeencoding_nostra_evenements
 * @property string         $freeencoding_nostra_demarches
 * @property string         $doc_source_title                   Maximum 2048 caractères
 * @property string         $doc_source_page                    Maximum 256 caractères
 * @property string         $doc_source_link                    Maximum 1024 caractères
 * @property int            $prioritary
 * @property int            $transversal
 * @author jdavreux
 */
class Idea extends TrashableModel {

	use TraitFilterable;
	
	//FIXME : faire qqch de la variable $currentState ?
	protected $currentState = null;
	
	/**
	 * {@inheritDoc}
	 * @see ManageableModel::formRules()
	 */
	public function formRules() {
		return [
			'name' => 'required|min:3',
			'description' => 'required|min:5',
			'nostra_publics' => 'required_without:nostra_demarches'
		];
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
	 * @see ManageableModel::checkManageRestrictions()
	 */
	public function checkManageRestrictions(\User $loggedUser) {
		// S'il y a des restrictions d'accès, on vérifie que l'idée est dans une administration à laquelle on a accèss
		if ($loggedUser->hasRestrictionsByAdministrations () && !$loggedUser->hasRightsForAtLeastOneAdministration ( $this->getAdministrationsIds () ))
			return false;
		else
			return true;
	}
	

	public function getNostraPublics()	{
		// Si on a un lien avec une ou plusieurs démarches, on prend les publics liés aux démarches
		if (count($this->nostraDemarches) > 0) {
			$publics = new Illuminate\Database\Eloquent\Collection;
			foreach ($this->nostraDemarches as $demarche) {
				$publics = $publics->merge($demarche->nostraPublics);
			}
			return ($publics);
		}
		// Si on a pas de démarche liée, on prend les publics liés via la table de jointure
		return $this->nostraPublics;
	}

	public function getNostraThematiquesabc() {
		$return = new Illuminate\Database\Eloquent\Collection;
		foreach ($this->nostraDemarches as $demarche) {
			$return = $return->merge($demarche->nostraThematiquesabc);
		}
		return ($return);
	}

	public function getNostraThematiquesadm() {
		$return = new Illuminate\Database\Eloquent\Collection;
		foreach ($this->nostraDemarches as $demarche) {
			$return = $return->merge($demarche->nostraThematiquesadm);
		}
		return ($return);
	}

	public function getNostraEvenements() {
		$return = new Illuminate\Database\Eloquent\Collection;
		foreach ($this->nostraDemarches as $demarche) {
			$return = $return->merge($demarche->nostraEvenements);
		}
		return ($return);
	}

	public function getNostraDemarches() {
		return $this->nostraDemarches;
	}
	
	public function getDemarches() {
		return Demarche::getFromIdea($this);
	}
	
	/**
	 * Récupère l'état actuel de l'idée
	 * 
	 * @return IdeaStateModification
	 */
	public function getLastStateModification() {
		try {
			return $this->stateModifications ()->firstOrFail ();
		} catch ( Illuminate\Database\Eloquent\ModelNotFoundException $ex ) {
			// pas moyen d'en trouver une ... ca ne peut arriver qu'après l'upgrade de 1.0 vers 1.1
			// on la crée
			$state = new IdeaStateModification ();
			$state->idea_state_id = 1; // @TODO : voir si y'a pas moyen de faire plus propre :-s
			$state->user_id = 1; // @TODO : on met "julian" par défaut (je répète, ce code ne peut être exécuté qu'après ce putain d'upgrade vers 1.1
			$state->comment = '';
			$this->stateModifications ()->save ( $state );
			return $this->stateModifications ()->first ();
		}
	}

	public function getAdministrationsIds() {
		return ($this->administrations()->lists('id'));
	}

	public function getAdministrationsNames() {
		$arrayAdministrationNames = array ();
		foreach ( $this->administrations as $administration ) {
			array_push ( $arrayAdministrationNames, $administration->name );
		}
		return ($arrayAdministrationNames);
	}
	public function getMinistersIds() {
		$arrayMinistersIds = array ();
		foreach ( $this->ministers as $minister ) {
			array_push ( $arrayMinistersIds, $minister->id );
		}
		return ($arrayMinistersIds);
	}

	public function getNostraPublicsIds() {
		return $this->getNostraPublics()->lists('id');
	}

	public function getNostraPublicsNames() { //cette fonction ne semble plus utilisée ...
		return $this->getNostraPublics()->filter(function ($elem) { return $elem->isRoot(); })->lists('title');
	}

	public function getNostraThematiquesabcIds() {
		return $this->getNostraThematiquesabc()->lists('id');
	}

	public function getNostraThematiquesadmIds() {
		return $this->getNostraThematiquesadm()->lists('id');
	}

	public function getNostraEvenementsIds() {
		return $this->getNostraEvenements()->lists('id');
	}

	public function getNostraDemarchesIds() {
		return $this->nostraDemarches->lists('id');
	}

	/**
	 * DEPRECATED
	 * @return mixed
	 */
	public static function countPrioritary() {
		return Idea::where ( 'prioritary', '>', 0 )->count ();
	}

	/**
	 * DEPRECATED
	 * @return mixed
	 */
	public static function countTransversal() {
		return Idea::where ( 'transversal', '>', 0 )->count ();
	}
	
	/**
	 * Retourne les idées liées à une démarche
	 * @param Demarche $demarche
	 * @param  array  $columns
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public static function getFromDemarche(Demarche $demarche, $columns = array('*')) {
		if($demarche->nostraDemarche) {
			$nostraDemarcheId = $demarche->nostraDemarche->id;
			return Idea::whereHas('nostraDemarches', function(Illuminate\Database\Eloquent\Builder $q) use ($nostraDemarcheId) {
				$q->where('nostra_demarche_id', '=', $nostraDemarcheId);
			})->get($columns);
		} else {
			return [];
		}

	}
	
	/**
	 * Récupère les états disponibles de l'idée en fonction des droits de l'utilisateur courant
	 *
	 * @param string $fromstate Nom de l'état de départ
	 * @return \Illuminate\Database\Eloquent\Collection[]|static[][]
	 */
	public function getAvailableStates($fromstate) {
		$user=Auth::user();
		if ($user->hasRole ( 'admins' ))
			return IdeaStateModification::getAvailableStates ( $fromstate, 'admin' );
		if ($user->can ( 'ideas_manage' ))
			return IdeaStateModification::getAvailableStates ( $fromstate, 'ideas_manage' );
		if ($user->id == $this->user_id)
			return IdeaStateModification::getAvailableStates ( $fromstate, 'owner' );
		if ($user->id == $this->ewbs_member_id)
			return IdeaStateModification::getAvailableStates ( $fromstate, 'ewbs' );
	}
	
	/**
	 * Query Scope : uniquement les Ideas prioritaires
	 * @param Builder $query
	 * @param string $state
	 * @return Builder
	 */
	public function scopeOnlyPrioritary(Builder $query, $state) {
		if ($state) {
			$query->where ( 'prioritary', '>', 0 );
		}
		return $query;
	}

	/**
	 * Query scope : uniquement les Ideas avec flag transversal
	 * @param Builder $query
	 * @param string $state
	 * @return Builder
	 */
	public function scopeWithTransversal(Builder $query, $state) {
		if (!$state) {
			$query->where ( 'transversal', '<', 1 );
		}
		return $query;
	}
	
	/**
	 * 
	 * @param Builder $query
	 * @param string $state
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeState(Builder $query, $state) {
		return $query->whereHas('stateModifications', function($query) use ($state) {
			$query
			->whereRaw('"ideaStateModifications".created_at = (SELECT MAX(created_at) FROM "ideaStateModifications" WHERE idea_id = "ideas".id)')
			->whereHas('ideaState', function($query) use ($state) {
				$query->where('name', '=', $state);
			});
		});
	}
	
	/**
	 * Filtre les données sur base du filtre utilisateurs par administrations
	 * 
	 * @param Builder $query
	 * @param array $ids
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeAdministrationsIds(Builder $query, array $ids) {
		if (!empty($ids)) {
			$query->wherehas ( 'administrations', function ($query) use($ids) {
				$query->whereIn ( 'administrations.id', $ids );
			});
		}
		return $query;
	}
	
	/**
	 * Filtre les données sur base du filtre utilisateur par expertises
	 * 
	 * @param Builder $query
	 * @param array $ids
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeExpertisesIds(Builder $query, array $ids) {
		if (!empty($ids)) {
			$query->whereHas('actions', function ($query) use ($ids) {
				$query->whereIn('ewbsActions.name', function($query) use ($ids) {
					$query->select('name')
					->from(with(new Expertise())->getTable())
					->whereIn('id', $ids);
				});
			})
			->orWhereHas('nostraDemarches', function ($query) use ($ids) {
				$query->whereHas('demarche', function ($query) use ($ids) {
					$query->whereHas('actions', function ($query) use ($ids) {
						$query->whereIn('ewbsActions.name', function($query) use ($ids) {
							$query->select('name')
							->from(with(new Expertise())->getTable())
							->whereIn('id', $ids);
						});
					});
				});
			});
		}
		return $query;
	}
	
	/**
	 * Filtre les données sur base du filtre utilisateur par publics-cibles
	 * 
	 * Particularité de ce scope :
	 * on doit prendre les idées selon un publics cible.
	 * Mais si une idée est reliée à une ou plusieurs démarches, l'information de public ne se trouve pas dans la jointure entre publics et idées, mais
	 * entre ideas <-> nostra_demarches <-> nostra_publics.
	 *
	 * @param Builder $query
	 * @param array $ids
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeNostraPublicsIds(Builder $query, array $ids) {
		if (!empty($ids)) {
			$query->where( function ($query) use ($ids) {
				$query->whereHas( 'nostraDemarches', function ($query) use ($ids) {
					$query->whereHas( 'nostraPublics', function ($query) use ($ids) {
						$query->whereIn ( 'nostra_publics.id', $ids );
					});
				})->orWhereHas ( 'nostraPublics', function ($query) use($ids) {
					$query->whereIn ( 'nostra_publics.id', $ids );
				});
			});
		}
		return $query;
	}
	
	/**
	 * Filtre les données sur base du filtre utilisateur par tags
	 * 
	 * @param Builder $query
	 * @param array $ids
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeTaxonomyTagsIds(Builder $query, array $ids) {
		if (!empty($ids)) {
			$query->wherehas ( 'tags', function ($query) use($ids) {
				$query->whereIn ( 'taxonomytags.id', $ids );
			});
		}
		return $query;
	}
	
	/**
	 * Filtre les données sur base de ministres
	 * 
	 * @param Builder $query
	 * @param array $ids
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeMinistersIds(Builder $query, array $ids) {
		if (!empty($ids)) {
			return $query->wherehas ( 'ministers', function ($query) use($ids) {
				$query->whereIn ( 'ministers.id', $ids );
			});
		}
		return $query;
	}
	
	/**
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user() {
		return $this->belongsTo ( 'User' )->withTrashed();
	}
	
	/**
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function ewbsMember() {
		return $this->belongsTo ( 'EWBSMember' )->withTrashed();
	}
	
	/**
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function administrations() {
		return $this->belongsToMany ( 'Administration' );
	}
	
	/**
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function ministers() {
		return $this->belongsToMany ( 'Minister' )->orderBy('lastname');
	}
	/**
	 * Attention! La relation ne doit pas être utilisée telle qu'elle.
	 * En effet, on obtient la liste des publics cibles soit par la relation, soit par le lien avec
	 * des démarches. Donc, pour obtenir la liste des publics, utilisez plutôt la méthode getNostraPublics();
	 * Le seul endroit où il est tolér de l'utiliser et pour faire un sync() entrée idées et publics
	 * (et donc quand on attache l'idée à des publics, sans lier à des démarches).
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function nostraPublics() {
		return $this->belongsToMany ( 'NostraPublic' );
	}
	
	/**
	 * Cette relation par contre est en public car on peut l'appeler directement depuis un controlleur (ou une vue)
	 * au contraire de nostraPublics() qui ne PEUT PAS être appelée endirect.
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function nostraDemarches() {
		return $this->belongsToMany ( 'NostraDemarche' );
	}
	
	/**
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function comments() {
		return $this->hasMany ( 'IdeaComment' )->orderBy ( 'created_at', 'DESC' );
	}
	
	/**
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function stateModifications() {
		return $this->hasMany ( 'IdeaStateModification' )->orderBy ( 'created_at', 'DESC' );
	}
	
	/**
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function actions() {
		return $this->hasMany ( 'EwbsAction' );
	}
	
	/**
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function tags() {
		return $this->belongsToMany('TaxonomyTag');
	}
}
