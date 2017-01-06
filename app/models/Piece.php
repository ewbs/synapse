<?php
/**
 * Catalogue des pièces
 *
 * @property int            $id                           (PK)
 * @property string         $name                         Obligatoire, maximum 1024 caractères
 * @property string         $description
 * @property float          $cost_administration_currency
 * @property float          $cost_citizen_currency
 * @property int            $type_id                      (FK demarchesPiecesAndTasksTypes)
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
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
