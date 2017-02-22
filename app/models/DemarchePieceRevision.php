<?php

/**
 * Liaisons entre les pièces et les démarches
 *
 * @property int            $demarche_demarchePiece_id      Obligatoire, @see DemarchePiece
 * @author jdavreux
 * 
 * Note : On gère l'historique des modifications ! (à chaque modif, on crée un nouvel élément en fait.
 * C'est pour ca que les FK ne sont pas des indexes : on va avoir des doublons, forcément.
 */
class DemarchePieceRevision extends DemarcheComponentRevision {
	
	protected $table = 'demarche_demarchePiece_revisions';
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see ManageableModel::getModelLabel()
	 */
	public function getModelLabel() {
		return 'demarchesPieces';
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see ManageableModel::getModelLabelSingularSnake()
	 */
	public function getModelLabelSingularSnake() {
		return 'demarche_piece';
	}
	
	/**
	 * Règles de validation au niveau du modèle
	 * @var array
	 */
	public static $rules=[
		'user_id' => 'required',
		'cost_administration_currency' => 'required',
		'cost_citizen_currency' => 'required',
		'volume' => 'required',
		'frequency' => 'required',
		'gain_potential_administration' => 'required',
		'gain_potential_citizen' => 'required',
		'gain_real_administration' => 'required',
		'gain_real_citizen' => 'required',
	];
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see DemarcheComponent::current_state()
	 */
	public function current_state() {
		return $this->belongsTo ( 'DemarchePieceState', 'current_state_id' );
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see DemarcheComponent::next_state()
	 */
	public function next_state() {
		return $this->belongsTo ( 'DemarchePieceState', 'next_state_id' );
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see RevisionModel::revisable()
	 */
	public function revisable() {
		return $this->belongsTo ( 'DemarchePiece', 'demarche_demarchePiece_id' );
	}
}
