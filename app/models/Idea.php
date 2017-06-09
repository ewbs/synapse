<?php
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
	
	public function user() {
		return $this->belongsTo ( 'User' )->withTrashed();
	}
	public function ewbsMember() {
		return $this->belongsTo ( 'EWBSMember' )->withTrashed();
	}
	public function administrations() {
		return $this->belongsToMany ( 'Administration' );
	}
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



	public function comments() {
		return $this->hasMany ( 'IdeaComment' )->orderBy ( 'created_at', 'DESC' );
	}
	public function stateModifications() {
		return $this->hasMany ( 'IdeaStateModification' )->orderBy ( 'created_at', 'DESC' );
		// return $this->hasMany('IdeaStateModification');
	}
	public function actions() {
		return $this->hasMany ( 'EwbsAction' );
	}

	public function tags() {
		return $this->belongsToMany('TaxonomyTag');
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
	 * Query scopes
	 */


	/**
	 * Query Scope : uniquement les Ideas prioritaires
	 * @param $query
	 * @param $state
	 * @return mixed
	 */
	public function scopeOnlyPrioritary($query, $state) {
		if ($state) {
			return $query->where ( 'prioritary', '>', 0 );
		}
		return $query;
	}

	/**
	 * Query scope : uniquement les Ideas avec flag transversal
	 * @param $query
	 * @param $state
	 * @return mixed
	 */
	public function scopeWithTransversal($query, $state) {
		if ($state) {
			return $query;
		}
		return $query->where ( 'transversal', '<', 1 );
	}


	public function scopeState($query, $state) {
		return $query->whereHas('stateModifications', function($query) use ($state) {
			$query
				->whereRaw('"ideaStateModifications".created_at = (SELECT MAX(created_at) FROM "ideaStateModifications" WHERE idea_id = "ideas".id)')
				->whereHas('ideaState', function($query) use ($state) {
				$query->where('name', '=', $state);
			});
		});
	}

	/**
	 * Particularité de ce scope :
	 * on doit prendre les idées selon un publics cible.
	 * Mais si une idée est reliée à une ou plusieurs démarches, l'information de public ne se trouve pas dans la jointure entre publics et idées, mais
	 * entre ideas <-> nostra_demarches <-> nostra_publics.
	 *
	 * @param $query
	 * @param $publicsIds
	 * @return mixed
	 */
	public function scopeNostraPublicsIds($query, $publicsIds) {
		if (is_array ( $publicsIds ) && count ( $publicsIds )) {
			return $query->where( function ($query) use ($publicsIds) {
				$query->whereHas( 'nostraDemarches', function ($query) use ($publicsIds) {
					$query->whereHas( 'nostraPublics', function ($query) use ($publicsIds) {
						$query->whereIn ( 'nostra_publics.id', $publicsIds );
					});
				})->orWhereHas ( 'nostraPublics', function ($query) use($publicsIds) {
					$query->whereIn ( 'nostra_publics.id', $publicsIds );
				} );
			});
		}
		return $query;
	}
	public function scopeAdministrationsIds($query, $administrationsIds) {
		if (is_array ( $administrationsIds ) && count ( $administrationsIds )) {
			return $query->wherehas ( 'administrations', function ($query) use($administrationsIds) {
				$query->whereIn ( 'administrations.id', $administrationsIds );
			} );
		}
		return $query;
	}
	public function scopeMinistersIds($query, $ministersIds) {
		if (is_array ( $ministersIds ) && count ( $ministersIds )) {
			return $query->wherehas ( 'ministers', function ($query) use($ministersIds) {
				$query->whereIn ( 'ministers.id', $ministersIds );
			} );
		}
		return $query;
	}
	public function scopeTaxonomyTagsIds($query, $tagsIds) {
		if (is_array ( $tagsIds ) && count ( $tagsIds )) {
			return $query->wherehas ( 'tags', function ($query) use($tagsIds) {
				$query->whereIn ( 'taxonomytags.id', $tagsIds );
			} );
		}
		return $query;
	}



	/**
	 * Retourne les idées liées à une démarche
	 * @param Demarche $demarche
	 * @param  array  $columns
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public static function getFromDemarche(Demarche $demarche, $columns = array('*')) {
		
		$nostraDemarcheId = $demarche->nostraDemarche->id;
		return Idea::whereHas('nostraDemarches', function(Illuminate\Database\Eloquent\Builder $q) use ($nostraDemarcheId) { 
			$q->where('nostra_demarche_id', '=', $nostraDemarcheId); 
		})->get($columns);
		
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
}
