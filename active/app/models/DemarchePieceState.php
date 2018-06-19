<?php
/**
 * Etats de démarches-pièces
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
