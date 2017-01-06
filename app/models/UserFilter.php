<?php
/**
 * Filtre utilisateur.
 * Cette classe est sensée être mère d'autres classes de filtres.
 * Elle implémente le commun entre les filtres; chaque classe déscendante implémentant ce qui lui est propre
 *
 * Atypique : les filtres étant en DB des tables de relation, il n'y a pas d'Id.
 * La table est définie dans les classes héritantes.
 *
 * @author jdavreux
 */
class UserFilter extends Eloquent {

	/**
	 * primaryKey
	 *
	 * @var integer
	 * @access protected
	 */
	protected $primaryKey = null;

	/**
	 * Indicates if the IDs are auto-incrementing.
	 *
	 * @var bool
	 */
	public $incrementing = false;


	/**
	 * Relation d'apartenance à un utilisateur.
	 * Cette relation existe d'office dans tous les filtres.
	 */
	public function user() {
		return $this->belongsTo('User');
	}

}