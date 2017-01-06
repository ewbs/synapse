<?php
/**
 * Publics NOSTRA
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
class NostraPublic extends Eloquent {
	
	use SoftDeletingTrait;
	protected $table = 'nostra_publics';
	protected $fillable = array (
			'nostra_id',
			'nostra_titre' 
	);
	public function nostraThematiquesabc() {
		return $this->belongsToMany ( 'NostraThematiqueabc' );
	}
	public function nostraRootThematiquesabc() {
		return $this->belongsToMany ( 'NostraThematiqueabc' )->where ( 'parent_id', '=', 0 );
	}
	public function nostraEvenements() {
		return $this->belongsToMany ( 'NostraEvenement' );
	}
	public function nostraDemarches() {
		return $this->belongsToMany ( 'NostraDemarche' );
	}
	public function ideas() {
		return $this->belongsToMany ( 'Idea' );
	}
	public function children() {
		return ($this->hasMany ( 'NostraPublic', 'parent_id' )->orderBy ( 'title' ));
	}
	public function ancestor() { //"parent" est un mot réservé :(
		return $this->belongsTo('NostraPublic', 'parent_id');
	}
	public function isRoot() {
		return ($this->parent_id == 0);
	}
	public function scopeRoot($query) {
		return $query->where ( 'parent_id', '=', 0 );
		// $collection = $all->filter(function($single))
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
	public function getNostraThematiquesabcIds() {
		$arrayIds = array ();
		foreach ( $this->nostraThematiquesabc as $p ) {
			array_push ( $arrayIds, $p->id );
		}
		return ($arrayIds);
	}
	public function getNostraDemarchesIds() {
		$arrayIds = array ();
		foreach ( $this->nostraDemarches as $p ) {
			array_push ( $arrayIds, $p->id );
		}
		return ($arrayIds);
	}



	public function filters() {
		return $this->hasMany('UserFilterPublic');
	}
	
	/*
	 * pour l'import
	 *
	 *
	 * public function linkedDamusThematiquesIds() {
	 * $array = array();
	 * foreach ($this->nostraThematiques as $t) {
	 * if ($t->source == 'synapse') {
	 * array_push($array, $t->id);
	 * }
	 * }
	 * return $array;
	 * }
	 *
	 * public function linkedDamusEvenementsIds() {
	 * $array = array();
	 * foreach ($this->nostraEvenements as $t) {
	 * if ($t->source == 'synapse') {
	 * array_push($array, $t->id);
	 * }
	 * }
	 * return $array;
	 * }
	 */
}
