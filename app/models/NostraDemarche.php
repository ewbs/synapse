<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Database\Eloquent\Builder;

/**
 * Démarches NOSTRA
 *
 * @property int            $id              (PK)
 * @property string         $nostra_id       Obligatoire, maximum 64 caractères
 * @property string         $title           Obligatoire, maximum 2048 caractères
 * @property string         $title_long      Obligatoire, maximum 2048 caractères
 * @property string         $title_short     Obligatoire, maximum 2048 caractères
 * @property string         $type            Obligatoire, maximum 255 caractères
 * @property int            $simplified      Obligatoire
 * @property int            $german_version  Obligatoire
 * @property \Carbon\Carbon $nostra_state    Obligatoire
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 *
 * @author jdavreux
 */
class NostraDemarche extends Eloquent {
	
	use SoftDeletingTrait, TraitFilterable;
	protected $table = 'nostra_demarches';
	protected $fillable = array (
			'nostra_id',
			'title' 
	);
	
	public function getNostraPublicsIds() {
		$array = array ();
		foreach ( $this->nostraPublics as $t ) {
			array_push ( $array, $t->id );
		}
		return $array;
	}
	public function getNostraPublicsNames() {
		$array = array ();
		foreach ( $this->nostraPublics as $t ) {
			array_push ( $array, $t->title );
		}
		return $array;
	}
	public function getNostraThematiquesabcIds() {
		$array = array ();
		foreach ( $this->nostraThematiquesabc as $t ) {
			array_push ( $array, $t->id );
		}
		return $array;
	}
	public function getNostraThematiquesabcNames() {
		$array = array ();
		foreach ( $this->nostraThematiquesabc as $t ) {
			array_push ( $array, $t->title );
		}
		return $array;
	}
	public function getNostraThematiquesadmIds() {
		$array = array ();
		foreach ( $this->nostraThematiquesadm as $t ) {
			array_push ( $array, $t->id );
		}
		return $array;
	}
	public function getNostraThematiquesadmNames() {
		$array = array ();
		foreach ( $this->nostraThematiquesadm as $t ) {
			array_push ( $array, $t->title );
		}
		return $array;
	}
	public function getNostraEvenementsIds() {
		$array = array ();
		foreach ( $this->nostraEvenements as $t ) {
			array_push ( $array, $t->id );
		}
		return $array;
	}
	public function getNostraEvenementsNames() {
		$array = array ();
		foreach ( $this->nostraEvenements as $t ) {
			array_push ( $array, $t->title );
		}
		return $array;
	}
	
	/**
	 * Retourne uniquement le nom des publics liés à une démarche nostra
	 */
	public function nostraPublicsName() {
		return $this->nostraPublics()->select('title');
	}
	
	/**
	 * Retourne uniquement le nom des thématiques liéss à une démarche nostra
	 */
	public function nostraThematiquesabcName() {
		return $this->nostraThematiquesabc()->select('title');
	}
	
	/**
	 * Retourne uniquement le nom des thématiques liéss à une démarche nostra
	 */
	public function nostraThematiquesadmName() {
		return $this->nostraThematiquesadm()->select('title');
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
			$query->whereHas('demarche', function ($query) use ($ids) {
				$query->wherehas('administrations', function ($query) use ($ids) {
					$query->whereIn('administrations.id', $ids);
				});
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
			$query->whereHas('demarche', function ($query) use ($ids) {
				$query->whereHas('actions', function ($query) use ($ids) {
					$query->whereIn('ewbsActions.name', function($query) use ($ids) {
						$query->select('name')
						->from(with(new Expertise())->getTable())
						->whereIn('id', $ids);
					});
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
				$query->whereHas( 'nostraPublics', function ($query) use ($ids) {
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
			$query->whereHas('demarche', function ($query) use ($ids) {
				$query->whereHas('tags', function ($query) use ($ids) {
					$query->whereIn('taxonomytags.id', $ids);
				});
			});
		}
		return $query;
	}
	
	/**
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function nostraPublics() {
		return $this->belongsToMany ( 'NostraPublic' );
	}
	
	/**
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function nostraEvenements() {
		return $this->belongsToMany ( 'NostraEvenement' );
	}
	
	/**
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function nostraThematiquesabc() {
		return $this->belongsToMany ( 'NostraThematiqueabc' );
	}
	
	/**
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function nostraThematiquesadm() {
		return $this->belongsToMany ( 'NostraThematiqueadm' );
	}
	
	/**
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function nostraForms() {
		return $this->belongsToMany ( 'NostraForm' );
	}
	
	/**
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function nostraDocuments() {
		return $this->belongsToMany ( 'NostraDocument' );
	}
	
	/**
	 * 
	 * @return unknown
	 */
	public function administrations() {
		return $this->demarche->administrations ();
	}
	
	/**
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function ideas() {
		return $this->belongsToMany ( 'Idea' );
	}
	
	/**
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function demarche() {
		return $this->hasOne ( 'Demarche' );
	}
}
