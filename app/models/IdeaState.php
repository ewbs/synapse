<?php
/**
 * Révisions des actions
 *
 * @property int            $id           (PK)
 * @property string         $name         Obligatoire, maximum 64 caractères
 * @property int            $order        Obligatoire, par défaut 0
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 *
 * @author jdavreux
 */
class IdeaState extends Eloquent {
	
	use SoftDeletingTrait;
	protected $table = 'ideaStates';
	public function stateModifications() {
		return $this->hasMany ( 'IdeaStateModification' );
	}
}
