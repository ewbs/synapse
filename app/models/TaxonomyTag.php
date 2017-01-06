<?php
/**
 * TaxonomyTag
 *
 * @property int            $id              (PK)
 * @property string         $name         	 Texte
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * 
 * @author jdavreux
 */
class TaxonomyTag extends TrashableModel  {

	protected $table = "taxonomytags";
	
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
		return 'taxonomy_manage';
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see ManageableModel::formRules()
	 */
	public function formRules() {
		return [
			'name' => "required|min:3",
		];
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see ManageableModel::extraFormValidate()
	 */
	/*public function extraFormValidate(\Illuminate\Validation\Validator $validator) {
		$passes=true;
		
		$form_id=ltrim(Input::get ( 'form_id' ), '0');
		if(!$this->nostra_form_id && $form_id) {
			$doubloon=NostraForm::where('form_id', '=', $form_id)->limit(1)->get(['id']);
			if($doubloon->count()>0) {
				$validator->errors()->add('form_id', 'Un formulaire NOSTRA a déjà cet ID slot');
				$passes=false;
			}
		}
		
		$title=Input::get ( 'title' );
		if(!$this->nostra_form_id && $title) {
			$doubloon=NostraForm::where('title', '=', $title)->limit(1)->get(['id']);
			if($doubloon->count()>0) {
				$validator->errors()->add('title', 'Un formulaire NOSTRA porte déjà ce nom');
				$passes=false;
			}
		}
		
		return $passes;
	}*/
	

	/**
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function category() {
		return $this->belongsTo( 'TaxonomyCategory', 'taxonomy_category_id' );
	}


	public function synonyms() {

		if ($this->group) {
			return TaxonomyTag::where('group', '=', $this->group)->where('id', '<>', $this->id)->get();
		}
		return [];

	}

	public static function getSynonyms($array=null) {

		if (count($array)) {
			return TaxonomyTag::whereIn('group', function ($query) use ($array){
				$query->select('group')
				->from(with(new TaxonomyTag)->getTable())
				->whereIn('id', $array->lists('id'))
				->get();
			});
		}

		return new \Illuminate\Database\Eloquent\Collection;

	}



	public function ewbsServices() {
		return $this->belongsToMany('EwbsServices', 'ewbsservice_taxonomytag', 'taxonomytag_id');
	}

	public function filters() {
		return $this->hasMany('UserFilterTag');
	}

}
