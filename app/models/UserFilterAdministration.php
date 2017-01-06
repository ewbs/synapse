<?php
/**
 * Filtre utilisateur par administration.
 * Dans la logique de Eloquent, on aurait du faire une relation n-m entre user et administration.
 * Mais ceci existe déjà pour les ACL.
 *
 * On fait donc ici une table intermédiaire, matérialisée par ce Modèle
 *
 * Atypique : les filtres étant en DB des tables de relation, il n'y a pas d'Id.
 *
 * @property int            $user_id                            Obligatoire, @see User
 * @property int            $administration_id                  Obligatoire, @see Administration
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @author jdavreux
 */
class UserFilterAdministration extends UserFilter {

	public $table = 'userfilteradministrations';

	public function administration() {
		return $this->belongsTo('Administration');
	}


}