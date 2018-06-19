<?php
/**
 * Filtre utilisateur par tag.
 * Dans la logique de Eloquent, on aurait du faire une relation n-m entre user et tag.
 *
 * Mais pour suivre la logique des filtres par administration on  fait ici une table intermédiaire, matérialisée par ce Modèle
 *
 * Atypique : les filtres étant en DB des tables de relation, il n'y a pas d'Id.
 *
 * @property int            $user_id                            Obligatoire, @see User
 * @property int            $taxonomy_tag_id   	                Obligatoire, @see TaxonomyTag
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @author jdavreux
 */
class UserFilterTag extends UserFilter {

	public $table = 'userfiltertags';

	public function tag() {
		return $this->belongsTo('Tag');
	}


}