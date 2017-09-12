<?php
use Illuminate\Database\Eloquent\Builder;

/**
 * Eforms
 * 
 * @property string         $form_id         Maximum 64 caractères, unique
 * @property int            $nostra_form_id  @see NostraForm
 * @property string         $title           Maximum 2048 caractères, unique
 * @property string         $description     
 * @property string         $language        Maximum 4 caractères
 * @property string         $url             Maximum 2048 caractères
 * @property int            $smart           null, 0 ou 1
 * @property string         $priority        Maximum 128 caractères
 * @property int            $esign           null, 0 ou 1
 * @property string         $format          Maximum 128 caractères
 * @property int            $simplified      null, 0 ou 1
 * @author jdavreux
 */
class Eform extends RevisableModel {
	
	use TraitFilterable;
	
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
	 * @see ManageableModel::permissionManage()
	 */
	public function permissionManage() {
		return 'formslibrary_manage';
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see ManageableModel::formRules()
	 */
	public function formRules() {
		$uniqueCond=$this->id?','.$this->id:'';
		return [
			'title' => ($this->nostra_form_id ? '':"required|min:3|unique:eforms,title{$uniqueCond}"),
			'form_id' => ($this->nostra_form_id ? '':"unique:eforms,form_id{$uniqueCond}"),
		];
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see ManageableModel::extraFormValidate()
	 */
	public function extraFormValidate(\Illuminate\Validation\Validator $validator) {
		$passes=true;
		
		$form_id=ltrim(Input::get ( 'form_id' ), '0');
		if(!$this->nostra_form_id && $form_id) {
			$doubloon=NostraForm::where('form_id', '=', $form_id)->limit(1)->get(['id']);
			if($doubloon->count()>0) {
				$validator->errors()->add('form_id', 'Un formulaire NOSTRA a déjà cet ID slot');
				$passes=false;
			}
		}
		
		$title=Input::get ( 'title' );
		if(!$this->nostra_form_id && $title) {
			$doubloon=NostraForm::where('title', '=', $title)->limit(1)->get(['id']);
			if($doubloon->count()>0) {
				$validator->errors()->add('title', 'Un formulaire NOSTRA porte déjà ce nom');
				$passes=false;
			}
		}
		
		return $passes;
	}
	
	public function routeGetDataUndocumented() {
		return route($this->getModelLabel() . 'UndocumentedGetData');
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see ManageableModel::name()
	 */
	public function name() {
		return $this->nostra_form_id ? $this->nostraForm->title : $this->title;
	}
	
	/**
	 * Dernière révision de l'eform
	 * 
	 * Pour les colonnes Nostra, les valeurs rendues sont celles du NostraForm si un lien existe, et à défaut celles de l'Eform
	 * @param boolean $trashed
	 * @return array|static[]
	 */
	public function getLastRevisionEform($trashed=false) {
		return self::getLastRevisionEforms($trashed, $this);
	}
	
	/**
	 * Dernière révision des eforms
	 * 
	 * Pour les colonnes Nostra, les valeurs rendues sont celles du NostraForm si un lien existe, et à défaut celles de l'Eform
	 * @param boolean $trashed
	 * @param Eform $eform
	 * @return array|static[]
	 */
	public static function getLastRevisionEforms($trashed=false, Eform $eform=null, $withCountAnnexes=false) {
		$qb = DB::table ( 'v_lastrevisioneforms' )
		->join('eforms', 'eforms.id', '=', 'v_lastrevisioneforms.eform_id')
		->leftjoin('nostra_forms', 'nostra_forms.id', '=', 'eforms.nostra_form_id')
		->leftjoin('users', 'users.id', '=', 'v_lastrevisioneforms.user_id')
		->whereRaw('v_lastrevisioneforms.deleted_at '.($trashed?'IS NOT NULL':'IS NULL')); //Note : la méthode 'whereNull' génère une erreur de syntaxe SQL (le 'AND' n'est pas positionné, bug laravel ?) => je le fais en RAW...
		
		$columns=[
			'eforms.id AS eform_id',
			'nostra_forms.nostra_id',
			DB::raw ('COALESCE("nostra_forms".form_id, "eforms".form_id) AS form_id'),
			DB::raw ('COALESCE("nostra_forms".title, "eforms".title) AS title'),
			'eforms.description',
			'v_lastrevisioneforms.current_state_id',
			'v_lastrevisioneforms.next_state_id',
			'v_lastrevisioneforms.comment',
			'v_lastrevisioneforms.deleted_at',
			'v_lastrevisioneforms.id AS revision_id', 
			'users.username', 
			'v_lastrevisioneforms.created_at'
		];

		if ($withCountAnnexes) {
			// TODO Remplacer par le nombre d'annexes comptées via les données Nostra
			$columns[] = DB::raw ('\'unknown\' AS countannexes');
		}

		if(!$eform) return $qb->get ($columns);
		
		$qb->where('eforms.id', "=", $eform->id)->limit(1);
		$columns[]=DB::raw ('COALESCE("nostra_forms".language, "eforms".language) AS language');
		$columns[]=DB::raw ('COALESCE("nostra_forms".url, "eforms".url) AS url');
		$columns[]=DB::raw ('COALESCE("nostra_forms".smart, "eforms".smart) AS smart');
		$columns[]=DB::raw ('COALESCE("nostra_forms".priority, "eforms".priority) AS priority');
		$columns[]=DB::raw ('COALESCE("nostra_forms".esign, "eforms".esign) AS esign');
		$columns[]=DB::raw ('COALESCE("nostra_forms".format, "eforms".format) AS format');
		$columns[]=DB::raw ('COALESCE("nostra_forms".simplified, "eforms".simplified) AS simplified');
		$res=$qb->get ($columns);
		if($res) return $res[0];
		return null;
	}
	
	/**
	 * Spécifier un commentaire qui sera sauvé dans la future révision liée au formulaire
	 * @param string $value
	 */
	public function setComment($value) {
		$this->addRevisionAttributes(['comment'=>$value]);
	}
	
	/**
	 * Spécifier un id d'état courant qui sera sauvé dans la future révision liée au formulaire
	 * @param int $value
	 */
	public function setCurrentStateId($value) {
		$this->addRevisionAttributes(['current_state_id'=>$value]);
	}
	
	/**
	 * Spécifier un id d'état suivant qui sera sauvé dans la future révision liée au formulaire
	 * @param int $value
	 */
	public function setNextStateId($value) {
		$this->addRevisionAttributes(['next_state_id'=>$value]);
	}
	
	/**
	 * Filtre les données sur base du filtre utilisateurs par administrations
	 *
	 * Remarque : la liaison vers demarcheEforms est inconditionnelle, car même sans filtre ce scope doit considérer uniquement les eforms liés à des démarches
	 * @param Builder $query
	 * @param array $ids
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeAdministrationsIds(Builder $query, array $ids) {
		return
		$query->whereHas( 'demarcheEforms', function ($query) use ($ids) {
			if (!empty($ids)) {
				$query->whereHas( 'demarche', function ($query) use ($ids) {
					$query->wherehas ( 'administrations', function ($query) use($ids) {
						$query->whereIn ( 'administrations.id', $ids );
					});
				});
			}
		});
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
	 * Remarque : la liaison vers demarcheEforms est inconditionnelle, car même sans filtre ce scope doit considérer uniquement les eforms liés à des démarches
	 * 
	 * @param Builder $query
	 * @param array $ids
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeNostraPublicsIds(Builder $query, array $ids) {
		return $query->whereHas( 'demarcheEforms', function ($query) use ($ids) {
			if (!empty($ids)) {
				$query->whereHas( 'demarche', function ($query) use ($ids) {
					$query->whereHas( 'nostraDemarche', function ($query) use ($ids) {
						$query->whereHas( 'nostraPublics', function ($query) use ($ids) {
							$query->whereIn ( 'nostra_publics.id', $ids );
						});
					});
				});
			}
		});
	}
	
	/**
	 * Filtre les données sur base du filtre utilisateur par tags
	 * 
	 * Attention, il faut retourner les démarches directement taggées, mais également les démarches liées à un ou plusieurs projets (Idea) taggés :-)
	 * Remarque : la liaison vers demarcheEforms est inconditionnelle, car même sans filtre ce scope doit considérer uniquement les eforms liés à des démarches
	 * 
	 * @param Builder $query
	 * @param array $ids
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeTaxonomyTagsIds(Builder $query, array $ids) {
		// Je le dis tout de suite ... ca génère une dizaine de requetes ... sans doute les whereHas qui sont en lazy loading dans l'orm, mais on peut pas jouer avec with() car on est au niveau querybuilder, pas eloquent
		
		return $query->whereHas( 'demarcheEforms', function ($query) use ($ids) {
			if (!empty($ids)) {
				$query->whereHas( 'demarche', function ($query) use ($ids) {
					//Sélection des démarches taggées directement
					$query->where( function ($query) use ($ids) {
						$query->wherehas ( 'tags', function ($query) use($ids) {
							$query->whereIn('taxonomytags.id', $ids);
						});
					})
					//et celle liée a des ideas taggées (mais le lien demarche_idea n'existe pas ... il se fait au travers de nostra_demarche ... raaaaaah !
					->orWhere( function ($query) use ($ids) {
						$query
						->whereHas('nostraDemarche', function ($query) use ($ids) {
							$query->whereHas('ideas', function ($query) use ($ids) {
								$query->whereHas('tags', function ($query) use ($ids) {
									$query->whereIn('taxonomy_tag_id', $ids);
								});
							});
						});
					});
				});
			}
		});
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see RevisableModel::revisions()
	 */
	public function revisions() {
		return $this->hasMany ( 'EformRevision' );
	}
	
	/**
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function nostraForm() {
		return $this->belongsTo ( 'NostraForm' );
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
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function demarcheEforms() {
		return $this->hasMany ( 'DemarcheEform' );
	}
}
