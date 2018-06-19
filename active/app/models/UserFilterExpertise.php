<?php
/**
 * Filtre utilisateur par identification d'action (expertise).
 * Dans la logique de Eloquent, on aurait du faire une relation n-m entre user et expertise.
 * Mais ceci existe déjà pour les ACL.
 *
 * On fait donc ici une table intermédiaire, matérialisée par ce Modèle
 *
 * Atypique : les filtres étant en DB des tables de relation, il n'y a pas d'Id.
 *
 * @property int            $user_id                            Obligatoire, @see User
 * @property int            $expertise_id                       Obligatoire, @see Expertise
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @author mgrenson
 */
class UserFilterExpertise extends UserFilter {
	
	public $table = 'userfilterexpertises';
	
	/**
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function expertise() {
		return $this->belongsTo('Expertise');
	}
}