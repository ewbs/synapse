<?php

use Illuminate\Database\Eloquent\Builder;

/**
 * Liaisons entre les pièces et les démarches
 * 
 * @property int            $demarche_id                    Obligatoire, @see Demarche
 * @property int            $eform_id                       Obligatoire, @see Eform
 * @property string         $comment
 * @author mgrenson
 * 
 * Note : On gère l'historique des modifications ! (à chaque modif, on crée un nouvel élément en fait.
 * C'est pour ca que les FK ne sont pas des indexes : on va avoir des doublons, forcément.
 */
class DemarcheEform extends RevisionModel {
	
	protected $table = 'demarche_eform';
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see RevisionModel::attributes()
	 */
	public function attributes() {return[];}
	
	/**
	 * Règles de validation au niveau du modèle
	 * @var array
	 */
	public static $rules=[
		'demarche_id' => 'required',
		'eform_id' => 'required',
	];
	
	/**
	 *
	 * {@inheritDoc}
	 * @see ManageableModel::permissionManage()
	 */
	public function permissionManage() {
		return 'formslibrary_manage';
	}
	
	/**
	 * Query scope filtrant la dernière révision d'une demarcheEform
	 *
	 * @param Builder $query
	 * @return Builder
	 */
	public function scopeLastRevision(Builder $query) {
		return $query
		->join('v_lastrevisiondemarcheeform', 'v_lastrevisiondemarcheeform.id', '=', 'demarche_eform.id')
		->addSelect(['demarche_eform.*']);
	}
	
	/**
	 * Query scope ciblant les demarcheEforms liées à une démarche
	 *
	 * @param Builder $query
	 * @return Builder
	 */
	public function scopeForDemarche(Builder $query, Demarche $demarche) {
		return $query->where( 'demarche_eform.demarche_id', '=', $demarche->id );
	}
	
	/**
	 * Query scope joignant les eforms et nostra_forms éventuels
	 *
	 * @param Builder $query
	 * @return Builder
	 */
	public function scopeJoinEforms(Builder $query) {
		return $query
		->join('eforms', 'demarche_eform.eform_id', '=', 'eforms.id')
		->leftjoin('nostra_forms', 'nostra_forms.id', '=', 'eforms.nostra_form_id')
		->addSelect(DB::raw('COALESCE(nostra_forms.title, eforms.title) AS title'), 'nostra_forms.nostra_id')
		->orderBy('title');
	}
	
	/**
	 * Relation vers la démarche
	 * @see Demarche
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function demarche() {
		return $this->belongsTo ('Demarche');
	}
	
	/**
	 * Relation vers l'Eform
	 * @see Eform
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function eform() {
		return $this->belongsTo('Eform');
	}
	
	/**
	 * Ce modèle n'a en fait pas de RevisableModel lié. A mon avis ça nous fera des histoires ça...
	 *
	 * {@inheritDoc}
	 * @see RevisionModel::revisable()
	 */
	public function revisable() {
		throw new LogicException("Le modèle DemarcheEform n'a pas de RevisableModel lié");
	}
}
