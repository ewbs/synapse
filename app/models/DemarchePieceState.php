<?php
/**
 * Etats de démarches-pièces
 * 
 * @property int            $id                  (PK)
 * @property string         $code                Obligatoire
 * @property string         $name                Obligatoire
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * 
 * @author mgrenson
 */
class DemarchePieceState extends DemarcheComponentState {
	
	protected $table = 'demarchesPiecesStates';
	
	/**
	 * {@inheritDoc}
	 * @see ManageableModel::getModelLabel()
	 */
	public function getModelLabel() {
		return 'demarchespiecesstates';
	}
	
	/**
	 * Démarches-pièces ayant cet état courant
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function DemarchePieceCurrent() {
		return $this->hasMany ( 'DemarchePiece', 'current_state_id' );
	}
	
	/**
	 * Démarches-pièces ayant cet état suivant
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function DemarchePieceNext() {
		return $this->hasMany ( 'DemarchePiece', 'next_state_id' );
	}
}
