<?php
/**
 * Ministres
 *
 * @property string         $firstname       Maximum 255 caractères
 * @property string         $lastname        Maximum 255 caractères
 *
 * @author jdavreux
 */
class Minister extends TrashableModel {
	
	protected $table = 'ministers';
	
	public static $rules=[
		'firstname' => 'required',
		'lastname' => 'required',
	];
	
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
	 * @see ManageableModel::name()
	 */
	public function name() {
		return Str::upper($this->lastname).' '.$this->firstname;
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
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function governements() {
		return $this->belongsToMany ( 'Governement' );
	}
	
	/**
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function mandates() {
		return $this->hasMany ( 'Mandate' );
	}
}
