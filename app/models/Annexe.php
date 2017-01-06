<?php
/**
 * Annexes
 *
 * @property int            $id              (PK)
 * @property string         $title           Obligatoire, maximum 2048 caractères, unique
 * @property int            $piece_id        @see Piece
 * @property string         $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * 
 * @author mgrenson
 */
class Annexe extends TrashableModel {
	
	/**
	 *
	 * {@inheritDoc}
	 * @see ManageableModel::hasView()
	 */
	public function hasView() {
		return true;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see ManageableModel::permissionManage()
	 */
	public function permissionManage() {
		return 'formslibrary_manage';
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see ManageableModel::formRules()
	 */
	public function formRules() {
		$uniqueCond=$this->id?','.$this->id:'';
		return [
			'title' => "required|min:3|unique:annexes,title{$uniqueCond}",
			'piece_id' => "|unique:annexes,piece_id{$uniqueCond}",
		];
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see ManageableModel::formRulesMessages()
	 */
	public function formRulesMessages() {
		return [
			'piece_id.unique' => 'Cette pièce est déjà référencée, probalement dans une annexe supprimée.<br/>Merci de voir avec votre administrateur s\'il ne serait sans doute souhaitable de restaurer l\'annexe supprimée.',
		];
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see ManageableModel::name()
	 */
	public function name() {
		return $this->title;
	}
	
	/**
	 * Relation vers la pièce
	 * @see Piece
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function piece() {
		return $this->belongsTo('Piece');
	}
	
	/**
	 * Relation vers le formulaire
	 * @see Eform
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function eforms() {
		return $this->belongsToMany ( 'Eform' );
	}
}
