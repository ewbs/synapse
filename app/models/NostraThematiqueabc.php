<?php
/**
 * Thématiques usager NOSTRA
 *
 * @property int            $id              (PK)
 * @property string         $nostra_id       Obligatoire, maximum 64 caractères
 * @property int            $parent_id       Obligatoire, @TODO : Devrait être nullable et devrait être une clé étrangère vers la table elle-même
 * @property string         $title           Obligatoire, maximum 2048 caractères
 * @property \Carbon\Carbon $nostra_state    Obligatoire
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 *
 * @author jdavreux
 */
class NostraThematiqueabc extends Eloquent {
	
	use SoftDeletingTrait;
	protected $table = 'nostra_thematiquesabc';
	protected $fillable = array (
			'nostra_id',
			'title' 
	);
	protected $validatorRules = array (
			'nostra_titre' => 'required|min:5' 
	);
	
	
	public function children() {
		return ($this->hasMany ( 'NostraThematiqueabc', 'parent_id' ));
	}
	public function ancestor() { //"parent" est un mot réservé :(
		return $this->belongsTo('NostraThematiqueabc', 'parent_id');
	}
	
	public function scopeRoot($query) {
		return $query->where ( 'parent_id', '=', 0 );
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
