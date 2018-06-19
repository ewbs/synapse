<?php
/**
 * Filtre utilisateur par public cible.
 * Dans la logique de Eloquent, on aurait du faire une relation n-m entre user et nostrapublics.
 *
 * Mais pour suivre la logique des filtres par administration on  fait ici une table intermédiaire, matérialisée par ce Modèle
 *
 * Atypique : les filtres étant en DB des tables de relation, il n'y a pas d'Id.
 *
 * @property int            $user_id                            Obligatoire, @see User
 * @property int            $nostra_public_id   	            Obligatoire, @see NostraPublic
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @author jdavreux
 */
class UserFilterPublic extends UserFilter {

	public $table = 'userfilterpublics';

	public function publics() {
		return $this->belongsTo('NostraPublic');
	}


}