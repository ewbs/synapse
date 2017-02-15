<?php
/**
 * Etats de composants liés à une démarche, étendu actuellement par les pièces et tâches
 * 
 * @property string         $code                Obligatoire
 * @property string         $name                Obligatoire
 * @abstract
 * @author mgrenson
 */
abstract class DemarcheComponentState extends TrashableModel {
	
	/**
	 * Règles pour la validation de niveau formulaire
	 *
	 * @return array
	 */
	public function formRules() {
		return [
			'name' => 'required|min:3',
			'code' => 'required|min:3'
		];
	}
	
	/**
	 * Affichage graphique de l'état : le code avec une tooltip dessus affichant le nom
	 * 
	 * @return string Etat présenté en html
	 */
	public function graphicState() {
		return "<span data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"{$this->name}\">{$this->code}</span>";
	}
	
	/**
	 * Place dans un tableau la liste de tous les demarcheComponentState avec comme clé de chaque élément son ID
	 * 
	 * Permet donc aisément de ne faire qu'une seule requête SQL, et d'accéder en mémoire à un élément via son ID
	 * @return array
	 */
	public static function allKeyById() {
		$all=self::all();
		$a=array();
		foreach($all as $one) {
			$a[$one->id]=$one;
		}
		return $a;
	}
}