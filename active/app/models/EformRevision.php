<?php
/**
 * Révisions des formulaires
 *
 * @property int            $eform_id         Obligatoire, @see Eform
 * @property string         $comment
 * @property string         $current_state_id @see DemarchePieceState
 * @property string         $next_state_id    @see DemarchePieceState
 * @author mgrenson
 */
class EformRevision extends RevisionModel {
	
	protected $table = 'eformsRevisions';
	
	/**
	 * {@inheritDoc}
	 * @see ManageableModel::getModelLabel()
	 */
	public function getModelLabel() {
		return 'eforms';
	}
	
	/**
	 * {@inheritDoc}
	 * @see RevisionModel::attributes()
	 */
	public function attributes(){
		return ['comment', 'current_state_id', 'next_state_id'];
	}
	
	/**
	 * Relation vers l'eform
	 * 
	 * @see Eform
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function eform() {
		return $this->belongsTo ( 'Eform' );
	}
	
	/**
	 * Relation vers l'état courant
	 * 
	 * @see DemarchePieceState
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function current_state() {
		return $this->belongsTo ( 'DemarchePieceState' );
	}
	
	/**
	 * Relation vers l'état suivant
	 * 
	 * @see DemarchePieceState
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function next_state() {
		return $this->belongsTo ( 'DemarchePieceState' );
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see RevisionModel::revisable()
	 */
	public function revisable() {
		return $this->eform();
	}
}
