<?php
/**
 * TaxonomyCategory
 *
 * @property string         $name       Texte
 * @author jdavreux
 */
class TaxonomyCategory extends TrashableModel  {

	protected $table = "taxonomycategories";
	
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
	public function tags() {
		return $this->hasMany ( 'TaxonomyTag' );
	}

}
