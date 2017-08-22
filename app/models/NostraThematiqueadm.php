<?php
use Illuminate\Database\Eloquent\Builder;

/**
 * Thématiques administration NOSTRA
 *
 * @property int            $id              (PK)
 * @property string         $nostra_id       Obligatoire, maximum 64 caractères
 * @property int            $parent_id       Obligatoire
 * @property string         $title           Obligatoire, maximum 2048 caractères
 * @property \Carbon\Carbon $nostra_state    Obligatoire
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 *
 * @author jdavreux
 */
class NostraThematiqueadm extends Eloquent {
	
	use SoftDeletingTrait;
	protected $table = 'nostra_thematiquesadm';
	protected $fillable = array (
			'nostra_id',
			'title' 
	);
	
	public function children() {
		return ($this->hasMany ( 'NostraThematiqueadm', 'parent_id' ));
	}
	
	/**
	 *
	 * @param Builder $query
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeRoot(Builder $query) {
		// TODO Remplacer l'un par l'autre pour le release 4.4 (phase transitoire où on pouvait encore avoir 0 ou null lorsque pas de parent)
		//$query->whereNull('parent_id');
		$query->where(function ($query) {
			$query->whereNull('parent_id')->orWhere('parent_id', '=', 0);
		});
	}
	
	public function traverse() {
		self::_traverse ( $this->children, $array, $this );
		return ($array);
	}
	public function _traverse($collection, &$array, $object) {
		$new_array = array ();
		foreach ( $collection as $element ) {
			self::_traverse ( $element->children, $new_array, $element );
		}
		$array [] = $object;
		if (count ( $new_array ) > 0) {
			$array [] = $new_array;
		}
	}
	public function nostraPublics() {
		return $this->belongsToMany ( 'NostraPublic' );
	}
	public function getNostraPublicsIds() {
		$arrayIds = array ();
		foreach ( $this->nostraPublics as $p ) {
			array_push ( $arrayIds, $p->id );
		}
		return ($arrayIds);
	}
	public function getNostraPublicsNames() {
		$arrayNames = array ();
		foreach ( $this->nostraPublics as $p ) {
			array_push ( $arrayNames, $p->nostra_titre );
		}
		return ($arrayNames);
	}
	public function nostraEvenements() {
		return $this->belongsToMany ( 'NostraEvenement' );
	}
	public function nostraDemarches() {
		return $this->belongsToMany ( 'NostraDemarche' );
	}
	public function getNostraDemarchesIds() {
		$arrayIds = array ();
		foreach ( $this->nostraDemarches as $p ) {
			array_push ( $arrayIds, $p->id );
		}
		return ($arrayIds);
	}
	public function ideas() {
		return $this->belongsToMany ( 'Idea' );
	}
	public function linkedDamusEvenementsIds() {
		$array = array ();
		foreach ( $this->nostraEvenements as $t ) {
			if ($t->source == 'synapse') {
				array_push ( $array, $t->id );
			}
		}
		return $array;
	}
	
	/**
	 * Règles pour la validation
	 */
	public function getValidatorRules() {
		return ($this->validatorRules);
	}
}
