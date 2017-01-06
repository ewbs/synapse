<?php

/**
 * Liaisons entre les pièces et les démarches
 * 
 * @property int            $id                             (PK)
 * @property int            $demarche_id                    Obligatoire, @see Demarche
 * @property int            $eform_id                       Obligatoire, @see Eform
 * @property int            $user_id                        Obligatoire, @see User
 * @property string         $comment
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * 
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
		throw new RuntimeException("Le modèle DemarcheEform n'a pas de RevisableModel lié");
	}
}
