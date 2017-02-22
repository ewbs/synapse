<?php
/**
 * Catalogue des pièces
 *
 * @author jdavreux
 */
class Piece extends Component {
	
	protected $table = 'demarchesPieces';
	
	/**
	 * {@inheritDoc}
	 * @see ManageableModel::getModelLabel()
	 */
	public function getModelLabel() {
		return 'pieces';
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see Component::demarcheComponents()
	 */
	public function demarcheComponents() {
		return $this->demarchePieces();
	}
	
	/**
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function demarchePieces() {
		return $this->hasMany ( 'DemarchePiece' );
	}
}
