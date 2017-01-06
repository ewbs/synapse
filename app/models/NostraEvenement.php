<?php
/**
 * Evénements NOSTRA
 *
 * @property int            $id              (PK)
 * @property string         $nostra_id       Obligatoire, maximum 64 caractères
 * @property string         $title           Obligatoire, maximum 2048 caractères
 * @property \Carbon\Carbon $nostra_state    Obligatoire
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 *
 * @author jdavreux
 */
class NostraEvenement extends Eloquent {
	
	use SoftDeletingTrait;
	protected $table = 'nostra_evenements';
	protected $fillable = array (
			'nostra_id',
			'nostra_titre' 
	);
	protected $validatorRules = array (
			'nostra_titre' => 'required|min:5' 
	);
	public function nostraThematiquesabc() {
		return $this->belongsToMany ( 'NostraThematiqueabc' );
	}
	public function nostraDemarches() {
		return $this->belongsToMany ( 'NostraDemarche' );
	}
	public function nostraPublics() {
		return $this->belongsToMany ( 'NostraPublic' );
	}
	public function ideas() {
		return $this->belongsToMany ( 'Idea' );
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
			array_push ( $array, $t->nostra_titre );
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
	public function getNostraDemarchesIds() {
		$arrayIds = array ();
		foreach ( $this->nostraDemarches as $p ) {
			array_push ( $arrayIds, $p->id );
		}
		return ($arrayIds);
	}
	public function linkedDamusDemarchesIds() {
		$array = array ();
		foreach ( $this->nostraDemarches as $t ) {
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
