<?php
/**
 * Liens (URL) de documentation d'une démarche
 * 
 * @property int            $id                   (PK)
 * @property int            $demarche_id          Obligatoire, @see Demarche
 * @property string         $name                 Obligatoire
 * @property string         $description          Obligatoire
 * @property string         $url                  Obligatoire
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * 
 * @author jdavreux
 */
class DemarcheDocLink extends TrashableModel {
	
	protected $table = 'demarchesDocLinks';
	
	public function formRules() {
		return [
			'url' => 'required|min:3', // pas de vérfication stricte d'URL dans ce cas (on pourrait avoir un lien file:// ou autre ... sait on jamais !)
		];
	}
	
	
	public function demarche() {
		return $this->belongsTo ( 'Demarche' );
	}
	
}
