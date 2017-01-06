<?php
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
	public function nostraPublics() {
		return $this->belongsToMany ( 'NostraPublic' );
	}
	public function nostraEvenements() {
		return $this->belongsToMany ( 'NostraEvenement' );
	}
	public function nostraThematiquesabc() {
		return $this->belongsToMany ( 'NostraThematiqueabc' );
	}
	public function nostraThematiquesadm() {
		return $this->belongsToMany ( 'NostraThematiqueadm' );
	}
	public function nostraForms() {
		return $this->belongsToMany ( 'NostraForm' );
	}
	public function nostraDocuments() {
		return $this->belongsToMany ( 'NostraDocument' );
	}
	public function administrations() {
		return $this->demarche->administrations ();
	}
	public function ideas() {
		return $this->belongsToMany ( 'Idea' );
	}
	public function demarche() {
		return $this->hasOne ( 'Demarche' );
	}


	
	
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
	 * ********************************************************************************* **
	 * QUERY SCOPES
	 * * ********************************************************************************* *
	 */


	public function scopeNostraPublicsIds($query, $publicsIds) {
		if (is_array ( $publicsIds ) && count ( $publicsIds )) {
			return $query->where( function ($query) use ($publicsIds) {
				$query->whereHas( 'nostraPublics', function ($query) use ($publicsIds) {
					$query->whereIn ( 'nostra_publics.id', $publicsIds );
				});
			});
		}
		return $query;
	}

	public function scopeAdministrationsIds($query, $administrationsIds) {
		if (is_array ( $administrationsIds ) && count ( $administrationsIds )) {
			return $query->whereHas('demarche', function ($query) use ($administrationsIds) {
					$query->wherehas('administrations', function ($query) use ($administrationsIds) {
						$query->whereIn('administrations.id', $administrationsIds);
					});
			});
		}
		return $query;
	}

	public function scopeTaxonomyTagsIds($query, $tagsIds) {

		if (is_array ( $tagsIds ) && count ( $tagsIds )) {
			return $query->whereHas('demarche', function ($query) use ($tagsIds) {
				$query->whereHas('tags', function ($query) use ($tagsIds) {
					$query->whereIn('taxonomytags.id', $tagsIds);
				});
			});
			/*return $query->with(['demarche' => function ($query) {
				$query->wherehas('tags', function ($query) use ($tagsIds) {
					$query->whereIn('taxonomytags.id', $tagsIds);
				});
			}]);*/
		}
		return $query;
	}

}
