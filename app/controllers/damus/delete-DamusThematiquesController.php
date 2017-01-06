<?php
class DamusThematiquesController extends BaseController {
	protected $thematique;
	
	/**
	 * Inject the models.
	 * 
	 * @param NostraThematique $thematique        	
	 */
	public function __construct(NostraThematique $thematique) {
		parent::__construct ();
		
		$this->thematique = $thematique;
	}
	
	/**
	 * Retourne les démarches
	 *
	 * @return View
	 */
	public function getIndex() {
		
		// utilisateur connecté
		$user = $this->getLoggedUser();
		
		// Montre la page
		return View::make ( 'admin/damus/thematiques/index', compact ( 'user' ) );
	}
	
	/**
	 * Retourne les démarches supprimées
	 *
	 * @return View
	 */
	public function getTrash() {
		
		// utilisateur connecté
		$user = $this->getLoggedUser();
		
		// Montre la page
		return View::make ( 'admin/damus/thematiques/trash', compact ( 'user' ) );
	}
	
	/**
	 * Donne une liste de NostraThematiques formattée pour les DataTables
	 *
	 * @return Datatables JSON
	 */
	public function getData() {
		$thematiques = NostraThematique::where ( 'source', '=', 'synapse' )->select ( array (
				'id',
				'nostra_titre',
				'created_at' 
		) );
		
		return Datatables::of ( $thematiques )->add_column ( 'publics', function ($thematique) {
			return implode ( ', ', $thematique->getNostraPublicsNames () );
		}, 2 )->add_column ( 'actions', function ($thematique) {
			$return = "";
			if ($this->getLoggedUser()->hasRole ( 'admin' ) || $this->getLoggedUser()->hasRole ( 'damus_gerer' )) {
				$return .= '<a title="' . Lang::get ( 'button.edit' ) . '" href="' . URL::secure ( 'admin/damus/thematiques/' . $thematique->id . '/edit' ) . '" class="btn btn-xs btn-default"><span class="fa fa-pencil"></span></a>';
				$return .= '<a title="' . Lang::get ( 'button.delete' ) . '" href="' . URL::secure ( 'admin/damus/thematiques/' . $thematique->id . '/delete' ) . '" class="btn btn-xs btn-danger"><span class="fa fa-trash-o"></span></a>';
			}
			return ($return);
		} )->remove_column ( 'id' )->make ();
	}
	
	/**
	 * Donne une liste de NostraThematiques supprimées formattée pour les DataTables
	 *
	 * @return Datatables JSON
	 */
	public function getDatatrash() {
		$thematiques = NostraThematique::onlyTrashed ()->where ( 'source', '=', 'synapse' )->select ( array (
				'id',
				'nostra_titre',
				'deleted_at' 
		) );
		
		return Datatables::of ( $thematiques )->add_column ( 'publics', function ($thematique) {
			return implode ( ', ', $thematique->getNostraPublicsNames () );
		}, 2 )->add_column ( 'actions', function ($thematique) {
			$return = "";
			if ($this->getLoggedUser()->hasRole ( 'admin' ) || $this->getLoggedUser()->hasRole ( 'damus_gerer' )) {
				$return .= '<a title="' . Lang::get ( 'button.restore' ) . '" href="' . URL::secure ( 'admin/damus/thematiques/' . $thematique->id . '/restore' ) . '" class="btn btn-xs btn-default">' . Lang::get ( 'button.restore' ) . '</a>';
			}
			return ($return);
		} )->remove_column ( 'id' )->make ();
	}
	
	/**
	 * Affiche le formulaire de création d'idée
	 *
	 * @return Response
	 */
	public function getCreate() {
		$arrayNostraPublics = NostraPublic::all ();
		
		// Mode
		$mode = 'create';
		
		// affiche le formulaire
		return View::make ( 'admin/damus/thematiques/create_edit', compact ( 'mode', 'arrayNostraPublics' ) );
	}
	public function postCreate() {
		$thematique = new NostraThematique ();
		
		// Valider le formulaire
		if (! Input::has ( 'nostra_publics' )) {
			return Redirect::secure ( 'admin/damus/thematiques/create' )->withInput ()->with ( 'error', Lang::get ( 'admin/damus/messages.create.nopublics' ) );
		}
		$validator = Validator::make ( Input::all (), $thematique->getValidatorRules () );
		if ($validator->fails ()) {
			return Redirect::secure ( 'admin/damus/thematiques/create' )->withInput ()->withErrors ( $validator )->with ( 'error', Lang::get ( 'admin/damus/messages.create.error' ) );
		}
		// fin validation
		
		try {
			$thematique->nostra_titre = Input::get ( 'nostra_titre' );
			$thematique->source = 'synapse';
			$thematique->save ();
			
			$thematique->nostraPublics ()->sync ( is_array ( Input::get ( 'nostra_publics' ) ) ? Input::get ( 'nostra_publics' ) : array () );
		} catch ( Exception $e ) {
			Log::error($e);
			return Redirect::secure ( 'admin/damus/thematiques/create' )->withInput ()->with ( 'error', Lang::get ( 'admin/damus/messages.create.baderror' ) . '<pre>' . $e->getMessage () . '</pre>' );
		}
		
		return Redirect::secure ( 'admin/damus/thematiques/' )->with ( 'success', Lang::get ( 'admin/damus/messages.create.success' ) );
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param
	 *        	$user
	 * @return Response
	 */
	public function getEdit($thematique) {
		if ($thematique->id) {
			
			// on vérifie que la personne PEUT editer
			if ($this->getLoggedUser()->hasRole ( 'admin' ) || $this->getLoggedUser()->hasRole ( 'damus_gerer' )) {
				// ok!
			} else {
				return Redirect::secure ( 'admin/damus/thematiques' )->with ( 'error', Lang::get ( 'admin/damus/messages.not_allowed' ) );
			}
			
			$arrayOfSelectedNostraPublics = $thematique->getNostraPublicsIds ();
			$arrayNostraPublics = NostraPublic::all ();
			
			// mode
			$mode = 'edit';
			
			return View::make ( 'admin/damus/thematiques/create_edit', compact ( 'thematique', 'mode', 'arrayOfSelectedNostraPublics', 'arrayNostraPublics' ) );
		} else {
			return Redirect::secure ( 'admin/damus/thematiques' )->with ( 'error', Lang::get ( 'admin/damus/messages.does_not_exist' ) );
		}
	}
	public function postEdit($thematique) {
		
		// on vérifie que la personne PEUT editer
		if ($this->getLoggedUser()->hasRole ( 'admin' ) || $this->getLoggedUser()->hasRole ( 'damus_gerer' )) {
			// ok!
		} else {
			return Redirect::secure ( 'admin/damus/thematiques' )->with ( 'error', Lang::get ( 'admin/damus/messages.not_allowed' ) );
		}
		
		// Valider le formulaire
		if (! Input::has ( 'nostra_publics' )) {
			return Redirect::secure ( 'admin/damus/thematiques/' . $thematique->id . '/edit' )->withInput ()->with ( 'error', Lang::get ( 'admin/damus/messages.create.nopublics' ) );
		}
		$validator = Validator::make ( Input::all (), $thematique->getValidatorRules () );
		if ($validator->fails ()) {
			return Redirect::secure ( 'admin/damus/thematiques/' . $thematique->id . '/edit' )->withInput ()->withErrors ( $validator )->with ( 'error', Lang::get ( 'admin/damus/messages.create.error' ) );
		}
		// fin validation
		
		try {
			$thematique->nostra_titre = Input::get ( 'nostra_titre' );
			$thematique->source = 'synapse';
			$thematique->save ();
			
			$thematique->nostraPublics ()->sync ( is_array ( Input::get ( 'nostra_publics' ) ) ? Input::get ( 'nostra_publics' ) : array () );
		} catch ( Exception $e ) {
			Log::error($e);
			return Redirect::secure ( 'admin/damus/thematiques/' . $thematique->id . '/edit' )->withInput ()->with ( 'error', Lang::get ( 'admin/damus/messages.edit.baderror' ) . '<pre>' . $e->getMessage () . '</pre>' );
		}
		
		return Redirect::secure ( 'admin/damus/thematiques/' )->with ( 'success', Lang::get ( 'admin/damus/messages.edit.success' ) );
	}
	public function getDelete($thematique) {
		
		// on vérifie que la personne PEUT supprimer
		if ($this->getLoggedUser()->hasRole ( 'admin' ) || $this->getLoggedUser()->hasRole ( 'damus_gerer' )) {
			// ok!
		} else {
			return Redirect::secure ( 'admin/damus/thematiques' )->with ( 'error', Lang::get ( 'admin/damus/messages.not_allowed' ) );
		}
		
		return View::make ( 'admin/damus/thematiques/delete', compact ( 'thematique' ) );
	}
	public function postDelete($thematique) {
		if ($thematique->delete ()) {
			return Redirect::secure ( 'admin/damus/thematiques' )->with ( 'success', Lang::get ( 'admin/damus/messages.delete.success' ) );
		} else {
			// There was a problem deleting the item
			return Redirect::secure ( 'admin/damus/thematiques' )->with ( 'error', Lang::get ( 'admin/damus/messages.delete.error' ) );
		}
	}
	public function getRestore($thematiqueId) {
		$thematique = NostraThematique::withTrashed ()->find ( $thematiqueId );
		
		// on vérifie que la personne PEUT supprimer
		if ($this->getLoggedUser()->hasRole ( 'admin' ) || $this->getLoggedUser()->hasRole ( 'damus_gerer' )) {
			// ok!
		} else {
			return Redirect::secure ( 'admin/damus/thematiques' )->with ( 'error', Lang::get ( 'admin/damus/messages.not_allowed' ) );
		}
		
		return View::make ( 'admin/damus/thematiques/restore', compact ( 'thematique' ) );
	}
	public function postRestore($thematiqueId) {
		$thematique = NostraThematique::withTrashed ()->find ( $thematiqueId );
		$thematique->restore ();
		
		// ca a bien été restauré ?
		$thematique = NostraThematique::find ( $thematiqueId );
		if (! empty ( $thematique )) {
			return Redirect::secure ( 'admin/damus/thematiques' )->with ( 'success', Lang::get ( 'admin/damus/messages.restore.success' ) );
		} else {
			// There was a problem deleting the item
			return Redirect::secure ( 'admin/damus/thematiques' )->with ( 'error', Lang::get ( 'admin/damus/messages.restore.error' ) );
		}
	}
}
