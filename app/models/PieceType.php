<?php
/**
 * Types des pièces et tâches
 *
 * @property string         $for          Obligatoire, maximum 255 caractères
 * @property string         $name         Obligatoire, maximum 1024 caractères
 * @property string         $description
 * @author jdavreux
 */
class PieceType extends TrashableModel {
	
	protected $table = 'demarchesPiecesAndTasksTypes';
	
	/**
	 * {@inheritDoc}
	 * @see ManageableModel::getModelLabel()
	 */
	public function getModelLabel() {
		return 'piecestypes';
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see ManageableModel::permissionManage()
	 */
	public function permissionManage() {
		return 'pieces_tasks_manage';
	}
	
	/**
	 * Règles de validation au niveau du modèle
	 * @var array
	 */
	public static $rules=[
		'name' => 'required|min:3',
		'for' => 'required|in:piece,task,all'
	];
}
