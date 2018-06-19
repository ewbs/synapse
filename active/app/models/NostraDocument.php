<?php
/**
 * Documents NOSTRA
 *
 * @property int            $id              (PK)
 * @property string         $nostra_id       Obligatoire, maximum 64 caractères
 * @property string         $title           Obligatoire, maximum 2048 caractères
 * @property string         $document_id     Obligatoire, maximum 64 caractères
 * @property string         $language        Obligatoire, maximum 4 caractères
 * @property string         $url             Obligatoire, maximum 2048 caractères
 * @property \Carbon\Carbon $nostra_state    Obligatoire
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 *
 * @author jdavreux
 */
class NostraDocument extends Eloquent {
	
	use SoftDeletingTrait;
	protected $table = 'nostra_documents';
	protected $fillable = array (
			'nostra_id',
			'nostra_title' 
	);
	public function nostraDemarches() {
		return $this->belongsToMany ( 'NostraDemarche' );
	}
}
