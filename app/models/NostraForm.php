<?php
/**
 * Formulaires NOSTRA
 *
 * @property int            $id              (PK)
 * @property string         $nostra_id       Obligatoire, maximum 64 caractères
 * @property string         $title           Obligatoire, maximum 2048 caractères
 * @property string         $form_id         Obligatoire, maximum 64 caractères
 * @property string         $language        Obligatoire, maximum 4 caractères
 * @property string         $url             Obligatoire, maximum 2048 caractères
 * @property int            $smart           Obligatoire
 * @property string         $priority        Obligatoire, maximum 128 caractères
 * @property int            $esign           Obligatoire
 * @property string         $format          Obligatoire, maximum 128 caractères
 * @property int            $simplified      Obligatoire
 * @property \Carbon\Carbon $nostra_state    Obligatoire
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 *
 * @author jdavreux
 */
class NostraForm extends Eloquent {
	
	use SoftDeletingTrait;
	protected $table = 'nostra_forms';
	protected $fillable = array (
			'nostra_id',
			'nostra_title' 
	);

	public function routeGetIndex() {
		return route('eformsUndocumentedGetIndex');
	}

	public function routePostCreate($id) {
		return route('eformsPostCreateFromDamus', $id);
	}

	public function routePostCreateValidation($id) {
		return route('eformsPostCreateFromDamusValidation', $id);
	}
	
	public function formatedId() {
		return '#'.$this->nostra_id;
	}
	
	/**
	 * Relation vers les NostraDemarches
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function nostraDemarches() {
		return $this->belongsToMany ( 'NostraDemarche' );
	}
	
	/**
	 * Relation vers l'Eform
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function eform() {
		return $this->hasOne ( 'Eform' );
	}
	
	/**
	 * Différentes valeur d'une colonne triées par ordre alphabétique
	 *
	 * @return \Illuminate\Database\Query\static[]
	 */
	public static function distinctColumn($column) {
		return DB::table('nostra_forms')->select($column)->distinct()->orderBy($column)->get();
	}
}
