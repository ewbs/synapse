<?php

/**
 * Liaisons entre les pièces et les démarches
 * 
 * @property int            $id                             (PK)
 * @property int            $demarche_demarchePiece_id      Obligatoire, @see DemarchePiece
 * @property int            $user_id                        Obligatoire, @see User
 * @property string         $comment
 * @property float          $cost_administration_currency   Obligatoire
 * @property float          $cost_citizen_currency          Obligatoire
 * @property int            $volume Obligatoire             Obligatoire
 * @property int            $frequency Obligatoire          Obligatoire
 * @property float          $gain_potential_administration  Obligatoire
 * @property float          $gain_potential_citizen         Obligatoire
 * @property float          $gain_real_administration       Obligatoire
 * @property float          $gain_real_citizen              Obligatoire
 * @property int            $current_state_id
 * @property int            $next_state_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * 
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