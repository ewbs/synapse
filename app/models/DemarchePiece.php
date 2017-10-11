<?php

use Illuminate\Database\Eloquent\Builder;

/**
 * Liaisons entre les pièces et les démarches
 * 
 * @property int            $piece_id                       Obligatoire, @see Piece
 * @author jdavreux
 * 
 * Note : On gère l'historique des modifications ! (à chaque modif, on crée un nouvel élément en fait.
 * C'est pour ca que les FK ne sont pas des indexes : on va avoir des doublons, forcément.
 */
class DemarchePiece extends DemarcheComponent {
	
	protected $table = 'demarche_demarchePiece';
	
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
	 * 
	 * {@inheritDoc}
	 * @see DemarcheComponent::componentType()
	 */
	public function componentType() {
		return 'piece';
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see DemarcheComponent::formRulesChoose()
	 */
	public function formRulesChoose() {
		/*
		 * Note :
		 * En général la vérification via les formRules se basent uniquement sur les paramètres reçus de la requête.
		 * Pour cette contrainte d'unicité multpile il est nécessaire que demarche_id et piece_id aient été initialisés dans l'instance courante.
		 */
		$namerules[]='required';
		if($this->demarche_id && $this->piece_id) {
			$exceptCond=($this->id)?($this->id.',id'):'null,id';
			$namerules[]="unique:demarche_demarchePiece,name,{$exceptCond},demarche_id,{$this->demarche_id},piece_id,{$this->piece_id}";
		}
		return [
			'componentId' => 'required',
			'name'=>$namerules
		];
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see ManageableModel::formRulesMessages()
	 */
	public function formRulesMessages() {
		return array_merge(parent::formRulesMessages(), [
			'name.unique' => 'Cette pièce est déjà liée à la démarche courante avec ce nom. Peut-être est-ce cette autre pièce que vous souhaitiez éditer ?<br/><i>nb : Si cette pièce n\'est pas présente parmi la liste, elle a alors été supprimée de la démarche => il est possible de la recréer via la fonction "Ajouter une pièce"</i>',
		]);
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see DemarcheComponent::scopeJoinLastRevision()
	 */
	public function scopeJoinLastRevision(Builder $query, $trashed=false) {
		$query
		->join('v_lastrevisionpiecesfromdemarche', 'v_lastrevisionpiecesfromdemarche.demarche_demarchePiece_id', '=', 'demarche_demarchePiece.id')
		->whereRaw('v_lastrevisionpiecesfromdemarche.deleted_at '.($trashed?'is not null':'is null'));
		return $query;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see DemarcheComponent::scopeMostAsked()
	 */
	public function scopeMostUsed(Builder $query, $limit=0) {
		$query
		->select([
			'demarche_demarchePiece.name AS displayname',
			DB::raw('SUM(v_lastrevisionpiecesfromdemarche.volume * v_lastrevisionpiecesfromdemarche.frequency) AS count_items')
		])
		->joinLastRevision()
		->having(DB::raw('SUM(v_lastrevisionpiecesfromdemarche.volume * v_lastrevisionpiecesfromdemarche.frequency)'), '>', 0)
		->groupBy('demarche_demarchePiece.id')
		->orderBy('count_items', "DESC");
		if($limit>0) {
			$query->limit($limit);
		}
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see DemarcheComponent::scopePotentiallyMostGainful()
	 */
	public function scopePotentiallyMostGainful(Builder $query, $limit=0) {
		$query
		->select([
			'demarche_demarchePiece.name AS displayname',
			DB::raw('SUM(v_lastrevisionpiecesfromdemarche.gain_potential_administration + v_lastrevisionpiecesfromdemarche.gain_potential_citizen) AS gpagpc')
		])
		->joinLastRevision()
		->having(DB::raw('SUM(v_lastrevisionpiecesfromdemarche.gain_potential_administration + v_lastrevisionpiecesfromdemarche.gain_potential_citizen)'), '>', 0)
		->groupBy('demarche_demarchePiece.id')
		->orderBy('gpagpc', "DESC");
		if($limit>0) {
			$query->limit($limit);
		}
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see DemarcheComponent::component()
	 */
	public function component() {
		return $this->piece();
	}
	
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
	 * Relation vers la pièce liée
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function piece() {
		return $this->belongsTo ( 'Piece', 'piece_id' );
	}
	
	/**
	 * Relation vers les révisions
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function revisions() {
		return $this->hasMany('DemarchePieceRevision', 'demarche_demarchePiece_id');
	}
}
