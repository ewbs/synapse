<?php

class MaintenanceController extends BaseController {
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see BaseController::routeGetIndex()
	 */
	protected function routeGetIndex() { return route('queryrunnerGetIndex'); }
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see BaseController::getIndex()
	 */
	public function getIndex() {
		return $this->queryrunnerGetIndex();
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see BaseController::getSection()
	 */
	protected function getSection(){
		return 'maintenance';
	}
	
	/**
	 * 
	 * @param array $data
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
	 */
	public function queryrunnerGetIndex(array $data=[]) {
		if(!$this->getLoggedUser()->hasRole('admin')) {
			return $this->redirectNoRight(route('getIndex'));
		}
		return View::make ( 'admin/maintenance/queryrunner', $data);
	}
	
	/**
	 * 
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
	 */
	public function queryrunnerPostIndex() {
		$user=$this->getLoggedUser();
		if(!$user->hasRole('admin')) {
			return $this->redirectNoRight(route('getIndex'));
		}
		
		$transaction=Input::get('transaction', 'rollback');
		Input::flash();
		DB::beginTransaction();
		$results=[];
		
		try {
			$cpt=0;
			foreach(explode(';',Input::get('q')) as $q) { // Parcourir chaque requête
				$q=StringHelper::getStringOrNull($q);
				if(!$q) {
					continue; // Ignorer les lignes vides
				}
				$cpt++;
				Log::info("queryrunner [{$user->username}, $transaction] : {$q}");
				switch(strtolower(explode(' ',trim($q))[0])) { // Déterminer le type de requête
					case 'select' : $results["{$cpt}. select : {$q}"]=DB::select($q); break;
					case 'update' : $results["{$cpt}. update : {$q}"]=DB::update($q); break;
					case 'insert' : $results["{$cpt}. insert : {$q}"]=DB::insert($q); break;
					case 'delete' : $results["{$cpt}. delete : {$q}"]=DB::delete($q); break;
					default       : $results["{$cpt}. statement : {$q}"]=DB::statement($q);
				};
			}
			if($transaction=='commit')
				DB::commit();
			else
				DB::rollBack();
			return $this->queryrunnerGetIndex(['results'=>$results]);
		}
		catch(Exception $e) {
			DB::rollBack();
			Log::warning($e->getMessage());
			return $this->queryrunnerGetIndex(['error'=>$e->getMessage()]);
		}
	}
	
}
