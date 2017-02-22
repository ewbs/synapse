<?php
/**
 * Table pivot entre les annexes et les eforms
 *
 * @property int            $eform_id         Obligatoire, @see Eform
 * @property int            $annexe_id        Obligatoire, @see Annexe
 * @property string         $comment
 * @property string         $current_state_id @see DemarchePieceState
 * @property string         $next_state_id    @see DemarchePieceState
 * @author mgrenson
 */
class AnnexeEform extends RevisionModel {
	
	protected $table = 'annexe_eform';
	
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
		return [];
	}
	
	/**
	 * Règles de validation au niveau du modèle
	 * @var array
	 */
	public static $rules=[
		'annexe_id' => 'required',
		'eform_id' => 'required',
	];
	
	/**
	 * Retourne l'historique des versions d'une annexe liée à un formulaire
	 *
	 * @return mixed|static
	 */
	public function getHistory() {
		return self
		::withTrashed ()
		->leftjoin('users', 'users.id', '=', 'annexe_eform.user_id')
		->where ( 'annexe_eform.annexe_id', '=', $this->annexe_id )
		->where ( 'annexe_eform.eform_id', '=', $this->eform_id )
		->orderBy ( 'annexe_eform.created_at', 'DESC' )
		->get (['annexe_eform.id as revision_id', 'users.username', 'annexe_eform.current_state_id', 'annexe_eform.next_state_id', 'annexe_eform.comment', 'annexe_eform.created_at', 'annexe_eform.deleted_at']);
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
	 * Relation vers l'annexe
	 *
	 * @see Annexe
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function annexe() {
		return $this->belongsTo ( 'Annexe' );
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
	 * Ce modèle n'a en fait pas de RevisableModel lié. A mon avis ça nous fera des histoires ça...
	 * 
	 * {@inheritDoc}
	 * @see RevisionModel::revisable()
	 */
	public function revisable() {
		throw new RuntimeException("Le modèle AnnexeEform n'a pas de RevisableModel lié");
	}
}
