<?php
use Illuminate\Database\Eloquent\Builder;

/**
 * Démarches Ewbs
 * 
 * @property int            $user_id                 Obligatoire, @see User
 * @property int            $nostra_demarche_id      Obligatoire, @see NostraDemarche
 * @property int            $ewbs
 * @property int            $eform_usage
 * $property enum           $volume
 * @property string         $comment
 * 
 * Dynamic properties :
 * @property NostraDemarche $nostraDemarche
 * @property User $user
 * @property Illuminate\Database\Eloquent\Collection $administrations
 * @property Illuminate\Database\Eloquent\Collection $docLinks
 * @property Illuminate\Database\Eloquent\Collection $scms
 * @property Illuminate\Database\Eloquent\Collection $tasks
 * @property Illuminate\Database\Eloquent\Collection $revisions
 * @property Illuminate\Database\Eloquent\Collection $actions
 * 
 * @author jdavreux
 */
class Demarche extends TrashableModel {

	use TraitFilterable;

	public static $VOLUME_L100		='< 100'; //L100 = Less than 100
	public static $VOLUME_L500		='< 500';
	public static $VOLUME_L1000		='< 1.000';
	public static $VOLUME_L10000	='< 10.000';
	public static $VOLUME_M10000	='> 10.000'; //M10000 = More than 10000

	/**
	 * {@inheritDoc}
	 * @see ManageableModel::formRules()
	 */
	public function formRules() {
		return [
			/*'gain_potential' => array (
			 'required',
					'regex:/^\d*([\,\.]\d{2})?$/'
			),
			'gain_real' => array (
					'required',
					'regex:/^\d*([\,\.]\d{2})?$/'
			) */
		];
	}

	/**
	 * Retourne les volumes possibles (enum en DB)
	 * @return array
	 */
	public static function volumes() {
		return [self::$VOLUME_L100, self::$VOLUME_L500, self::$VOLUME_L1000, self::$VOLUME_L10000, self::$VOLUME_M10000];
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
		if ($loggedUser->hasRestrictionsByAdministrations () && !$loggedUser->hasRightsForAtLeastOneAdministration ( $this->getAdministrationsIds () )) // s'il y a des restrictions d'accès, on vérifie que la démarche est dans une administration à laquelle on a accès
			return false;
		else
			return true;
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see ManageableModel::name()
	 */
	public function name() {
		return $this->nostraDemarche->title;
	}

	public function toArray() {
		$array = parent::toArray ();
		$array ['completeId'] = DateHelper::year($this->created_at) . '-' . $this->id; // cette donnée ne se trouve pas en DB
		$array ['administrations'] = $this->administrations;
		return $array;
	}
	
	public function getCompleteId() {
		return (DateHelper::year($this->created_at) . '-' . $this->id); // cette donnée ne se trouve pas en DB)
	}
	
	public function docLinksId() {
		$array = array();
		foreach($this->docLinks() as $l) {
			array_push($array, $l->id);
		}
		return ($array);
	}
	
	/**
	 * Retourne les idées liées à la démarche
	 * @param  array  $columns
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function getIdeas($columns = array('*')) {
		return Idea::getFromDemarche($this, $columns);
	}
	
	/**
	 * Retourne les démarches liées à une idée
	 * @param Idea idea
	 * @return type
	 */
	public static function getFromIdea(Idea $idea) {
		
		$aDemarches = array();
		foreach ($idea->nostraDemarches as $nd) {
			$o = Demarche::loadFromNostraDemarche($nd->id);
			if (!is_object($o)) { //si on a tenté de chargé une démarche existante dans nostra mais pas encore existante dans Synapsde (pas documentée)
				
			}
			elseif ($o->id > 0) {
				array_push($aDemarches, $o);
			}
			
		}
		return $aDemarches;
		
	}
	
	public function nostraDemarche() {
		return $this->belongsTo ( 'NostraDemarche' );
	}
	
	public function administrations() {
		return $this->belongsToMany ( 'Administration' );
	}
	
	public function docLinks() {
		return $this->hasMany('DemarcheDocLink');
	}
	
	/**
	 * Vérifie si il y a des fichiers SCMs qui ont été uploadés.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function scms() {
		return $this->hasMany('DemarcheSCM');
	}
	
	public function pieces() {
		return $this->hasMany ( 'DemarchePiece' );
	}
	
	public function tasks() {
		return $this->hasMany ( 'DemarcheTask' );
	}
	
	public function revisions() {
		return $this->hasMany ( 'DemarcheRevision' );
	}
	
	public function user() {
		return $this->belongsTo ( 'User' );
	}
	
	public function actions() {
		return $this->hasMany ( 'EwbsAction' );
	}

	public function tags() {
		return $this->belongsToMany('TaxonomyTag');
	}
	
	/**
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function demarcheEforms() {
		return $this->hasMany ( 'DemarcheEform' );
	}
	
	public function getAdministrationsIds() {
		$arrayAdministrationIds = array ();
		foreach ( $this->administrations as $administration ) {
			array_push ( $arrayAdministrationIds, $administration->id );
		}
		return ($arrayAdministrationIds);
	}
	
	public function getAdministrationsNames() {
		$arrayAdministrationNames = array ();
		foreach ( $this->administrations as $administration ) {
			array_push ( $arrayAdministrationNames, $administration->name );
		}
		return ($arrayAdministrationNames);
	}
	
	/**
	 * Retourne la dernière révision de cette démarche
	 *
	 * @return type|null
	 */
	public function getLastRevision() {
		return self::getLastDemarcheRevision($this->id);
	}
	
	/**
	 * Spécifie si la dernière révision de cette démarche ajuste les gains (mentionne donc explicitement un montant pour au moins un gain)
	 * 
	 * @return boolean
	 */
	public function isLastRevisionAdjustingGains() {
		$revision=$this->getLastRevision();
		if($revision)
			foreach(['gain_potential_administration', 'gain_real_administration', 'gain_potential_citizen', 'gain_real_citizen'] as $gainName)
				if($revision->$gainName)
					return true;
		return false;
	}
	
	/**
	 * Retourne les formulaires liés à une démarche dans leur état actuel
	 * 
	 * @return Collection
	 */
	public function getLastRevisionEforms() {
		return DemarcheEform::lastRevision()->joinEforms()->forDemarche($this)->get();
	}
	
	/**
	 * Retourne les pièces liées à une démarche dans leur état actuel. Ne prend pas les soft-deletées.
	 * 
	 * @return array|static[]
	 */
	public function getLastRevisionPieces() {
		
		return DB
		::table ( 'v_lastrevisionpiecesfromdemarche' )->where('v_lastrevisionpiecesfromdemarche.demarche_id', '=', $this->id )->whereNull('v_lastrevisionpiecesfromdemarche.deleted_at')
		->leftJoin('users', 'users.id', '=', 'v_lastrevisionpiecesfromdemarche.user_id')
		->orderBy('v_lastrevisionpiecesfromdemarche.name')
		->get(['v_lastrevisionpiecesfromdemarche.*', 'users.username']);
	}
	
	/**
	 * Retourne les tâches liées à une démarche dans leur état actuel. Ne prend pas les soft-deletées.
	 *
	 * @return array|static[]
	 */
	public function getLastRevisionTasks() {
		return DB
		::table ( 'v_lastrevisiontasksfromdemarche' )->where('v_lastrevisiontasksfromdemarche.demarche_id', '=', $this->id )->whereNull('v_lastrevisiontasksfromdemarche.deleted_at')
		->leftJoin('users', 'users.id', '=', 'v_lastrevisiontasksfromdemarche.user_id')
		->orderBy('v_lastrevisiontasksfromdemarche.name')
		->get(['v_lastrevisiontasksfromdemarche.*', 'users.username']);
	}
	
	/**
	 * Retourne la somme des 4 gains calculés de cette démarche, calculs effectués sur base des gains définis sur les dernières révisions des pièces et des tâches liées à cette démarche
	 * 
	 * @return mixed|static
	 */
	public function getCalculatedGains() {
		return DB::table ( 'v_calculateddemarchegains' )->where ( 'demarche_id', '=', $this->id )->first();
	}
	
	/**
	 * Retourne la somme des 4 gains de cette démarche
	 * 
	 * Chacune des 4 valeurs étant soit :
	 * - prise sur la dernière révision de la démarche
	 * - soit calculée selon la somme des gains définis sur les dernières révisions des pièces et des tâches liées à cette démarche
	 *
	 * @return mixed|static
	 */
	public function getGains() {
		return $gains=DB::table ( 'v_demarchegains' )->where ( 'demarche_id', '=', $this->id )->first();
	}
	
	/**
	 * Calcule la somme des gains potentiels usager de toutes les démarches
	 * 
	 * @return integer

	public static function getTotalGainPotentialCitizen() {
		return DB::table ( 'v_demarchegains' )->sum('gain_potential_citizen');
	}
	
	/**
	 * Calcule la somme des gains potentiels usager de toutes les démarches
	 *
	 * @return integer

	public static function getTotalGainPotentialAdministration() {
		return DB::table ( 'v_demarchegains' )->sum('gain_potential_administration');
	}
	
	/**
	 * A t'on déjà une démarche (synapse) liée à une démarche (nostra)
	 * retourne un objet si oui
	 * retourne faux(bool) si non
	 * 
	 * @param type $nostraDemarcheId        	
	 * @return type
	 */
	public static function loadFromNostraDemarche($nostraDemarcheId) {
		return Demarche::where ( 'nostra_demarche_id', '=', $nostraDemarcheId )->first ();
	}
	
	/**
	 * Retourne les gains calculés pour toutes les démarches
	 * 
	 * @return array un tableau de résultat donc la clé est l'id de la démarche et la valeur est un objet avec les 4 gains en propriété
	 */
	public static function getAllCalculatedGains() {
		$gains=DB::table('v_calculateddemarchegains')->get();
		$results=array();
		foreach($gains as $gain)
			$results[$gain->demarche_id]=$gain;
		return $results;
	}
	
	/**
	 * Retourne la dernière révision de cette démarche
	 *
	 * @param unknown $demarche_id
	 * @return type|null
	 */
	public static function getLastDemarcheRevision($id) {
		$results = DB::table ( 'v_lastrevisionfromdemarche' )->where ( 'demarche_id', '=', $id )->get ();
		if ( count ($results) ) {
			return $results[0];
		}
		return null;
	}
	
	/**
	 * Filtre les données sur base du filtre utilisateur par administrations
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
			});
		}
		return $query;
	}
	
	/**
	 * Filtre les données sur base du filtre utilisateur par publics-cibles
	 * 
	 * @param Builder $query
	 * @param array $ids
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeNostraPublicsIds(Builder $query, array $ids) {
		if (!empty($ids)) {
			$query->where( function ($query) use ($ids) {
				$query->whereHas( 'nostraDemarche', function ($query) use ($ids) {
					$query->whereHas( 'nostraPublics', function ($query) use ($ids) {
						$query->whereIn ( 'nostra_publics.id', $ids );
					});
				});
			});
		}
		return $query;
	}
	
	/**
	 * Filtre les données sur base du filtre utilisateur par tags
	 * 
	 * Attention, il faut retourner les démarches directement taggées, mais également les démarches liées à un ou plusieurs projets (Idea) taggés :-)
	 * 
	 * @param Builder $query
	 * @param array $ids
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeTaxonomyTagsIds(Builder $query, array $ids) {
		// Je le dis tout de suite ... ca génère une dizaine de requetes ... sans doute les whereHas qui sont en lazy loading dans l'orm, mais on peut pas jouer avec with() car on est au niveau querybuilder, pas eloquent
		if (!empty($ids)) {
			// Sélection des démarches taggées directement
			$query->where( function ($query) use ($ids) {
				$query->wherehas ( 'tags', function ($query) use($ids) {
					$query->whereIn('taxonomytags.id', $ids);
				});
			} )
			// et celle liée a des ideas taggées (mais le lien demarche_idea n'existe pas ... il se fait au travers de nostra_demarche ... raaaaaah !
			->orWhere( function ($query) use ($ids) {
				$query->whereHas('nostraDemarche', function ($query) use ($ids) {
					$query->whereHas('ideas', function ($query) use ($ids) {
						$query->whereHas('tags', function ($query) use ($ids) {
							$query->whereIn('taxonomy_tag_id', $ids);
						});
					});
				});
			});
		}
		return $query;
	}
	
	/**
	 * 
	 * @param Builder $query
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeWithGains(Builder $query) {
		return $query->where(function ($query) {
			$query->whereHas('pieces', function($q){})
				  ->orWhereHas('tasks',function($q){});
		});
	}
}
