<?php

/**
 * Administrations
 * 
 * @property int            $id           (PK)
 * @property int            $region_id    Obligatoire, @see Region
 * @property string         $name         Maximum 255 caractères
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * 
 * @author mgrenson
 */
class Administration extends TrashableModel {
	
	/**
	 * {@inheritDoc}
	 * @see \LaravelBook\Ardent\Ardent::validate()
	 */
	public function validate(array $rules=array(), array $customMessages=array(), array $customAttributes=array()) {
		$uniqueRuleCond=($this->id)?(','.$this->id):''; // C'est pour cela qu'on doit redéfinir la méthode, on doit accéder à l'id courant => on ne peut pas se contenter des variables static $rules et $customAttributes
		return parent::validate(
			[
				'name' => "required|unique:administrations,name{$uniqueRuleCond}",
				'region_id' => 'required',
			],
			[
				'name.unique' => 'Ce nom existe déjà, soit dans la liste, soit dans la corbeille.',
			],
			$customAttributes
		);
	}
	
	/**
	 * {@inheritDoc}
	 * @see ManageableModel::deletableIfLinked()
	 */
	public function deletableIfLinked() {
		return true;
	}
	
	/**
	 * 
	 */
	public function ideas() {
		return $this->belongsToMany ( 'Idea' );
	}
	
	/**
	 * 
	 */
	public function region() {
		return $this->belongsTo ( 'Region' );
	}
	
	/**
	 * 
	 */
	public function demarches() {
		return $this->belongsToMany ( 'Demarche' );
	}
	
	/**
	 * 
	 */
	public function users() {
		// Utiliser pour gérer des ACL entre utilisateurs et Administration (restriction de contenu)
		return ($this->belongsToMany ( 'User' ));
	}

	public function filters() {
		return $this->hasMany('UserFilterAdministration');
	}
	
	/**
	 * 
	 */
	public function getNostraDemarchesIds() {
		$arrayIds = array ();
		foreach ( $this->demarches as $d ) {
			array_push ( $arrayIds, $d->nostraDemarche->id );
		}

		
		return ($arrayIds);
	}




	
	
	
	/*
	 * public static function getAdministrationsByRegionId( $regionId ) {
	 *
	 * return Administration::where('regions_id', '=', $regionId)->get();
	 *
	 * }
	 *
	 * public static function getArrayOfRegionsAndAdministrations() {
	 *
	 * return Region::getRegionsByAlphabeticalOrder();
	 *
	 * }
	 */
}
