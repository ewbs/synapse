<?php
/**
 * EwbsService
 *
 * @property string         $name           Texte
 * @property string         $description    Texte
 * @author jdavreux
 */
class EwbsService extends TrashableModel  {

	protected $table = "ewbsservices";
	
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
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function tags() {
		return $this->belongsToMany('TaxonomyTag', 'ewbsservice_taxonomytag', 'ewbsservice_id', 'taxonomytag_id');
	}


	public function synonyms() {

		if ($this->group) {
			return TaxonomyTag::where('group', '=', $this->group)->where('id', '<>', $this->id)->get();
		}
		return [];

	}

}
