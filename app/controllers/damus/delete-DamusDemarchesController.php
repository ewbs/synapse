<?php
class DamusDemarchesController extends BaseController {
	protected $demarche; // démarche au sens "référentiel des démarches"
	
	/**
	 * Inject the models.
	 * 
	 * @param NostraDemarche $demarche        	
	 */
	public function __construct(NostraDemarche $demarche) {
		parent::__construct ();
		
		$this->demarche = $demarche;
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
		return View::make ( 'admin/damus/demarches/index', compact ( 'user' ) );
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
		return View::make ( 'admin/damus/demarches/trash', compact ( 'user' ) );
	}
	
	/**
	 * Donne une liste de NostraThematiques formattée pour les DataTables
	 *
	 * @return Datatables JSON
	 */
	public function getData() {
		$demarches = NostraDemarche::where ( 'source', '=', 'synapse' )->select ( array (
				'id',
				'nostra_titre',
				'created_at' 
		) );
		
		return Datatables::of ( $demarches )->add_column ( 'thematiques', function ($demarche) {
			return implode ( ', ', $demarche->getNostraThematiquesNames () );
		}, 2 )->add_column ( 'actions', function ($demarche) {
			$return = "";
			if ($this->getLoggedUser()->hasRole ( 'admin' ) || $this->getLoggedUser()->hasRole ( 'damus_gerer' )) {
				$return .= '<a title="' . Lang::get ( 'button.edit' ) . '" href="' . URL::secure ( 'admin/damus/demarches/' . $demarche->id . '/edit' ) . '" class="btn btn-xs btn-default"><span class="fa fa-pencil"></span></a>';
				$return .= '<a title="' . Lang::get ( 'button.delete' ) . '" href="' . URL::secure ( 'admin/damus/demarches/' . $demarche->id . '/delete' ) . '" class="btn btn-xs btn-danger"><span class="fa fa-trash-o"></span></a>';
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
		$demarches = NostraDemarche::onlyTrashed ()->where ( 'source', '=', 'synapse' )->select ( array (
				'id',
				'nostra_titre',
				'deleted_at' 
		) );
		
		return Datatables::of ( $demarches )->add_column ( 'thematiques', function ($demarche) {
			return implode ( ', ', $demarche->getNostraThematiquesNames () );
		}, 2 )->add_column ( 'actions', function ($demarche) {
			$return = "";
			if ($this->getLoggedUser()->hasRole ( 'admin' ) || $this->getLoggedUser()->hasRole ( 'damus_gerer' )) {
				$return .= '<a title="' . Lang::get ( 'button.restore' ) . '" href="' . URL::secure ( 'admin/damus/demarches/' . $demarche->id . '/restore' ) . '" class="btn btn-xs btn-default">' . Lang::get ( 'button.restore' ) . '</a>';
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
		return View::make ( 'admin/damus/demarches/create_edit', compact ( 'mode', 'arrayNostraPublics' ) );
	}
	public function postCreate() {
		$demarche = new NostraDemarche ();
		
		// Valider le formulaire
		if (! Input::has ( 'nostra_publics' )) {
			return Redirect::secure ( 'admin/damus/demarches/create' )->withInput ()->with ( 'error', Lang::get ( 'admin/damus/messages.create.nopublics' ) );
		}
		if (! Input::has ( 'nostra_thematiques' )) {
			return Redirect::secure ( 'admin/damus/demarches/create' )->withInput ()->with ( 'error', Lang::get ( 'admin/damus/messages.create.nothematiques' ) );
		}
		/*
		 * if ( ! Input::has('nostra_evenements') ) {
		 * return Redirect::secure('admin/damus/demarches/create')->withInput()->with('error', Lang::get('admin/damus/messages.create.noevenements'));
		 * }
		 */
		$validator = Validator::make ( Input::all (), $demarche->getValidatorRules () );
		if ($validator->fails ()) {
			return Redirect::secure ( 'admin/damus/demarches/create' )->withInput ()->withErrors ( $validator )->with ( 'error', Lang::get ( 'admin/damus/messages.create.error' ) );
		}
		// fin validation
		
		try {
			$demarche->nostra_titre = Input::get ( 'nostra_titre' );
			$demarche->source = 'synapse';
			$demarche->save ();
			
			$demarche->nostraPublics ()->sync ( is_array ( Input::get ( 'nostra_publics' ) ) ? Input::get ( 'nostra_publics' ) : array () );
			$demarche->nostraThematiques ()->sync ( is_array ( Input::get ( 'nostra_thematiques' ) ) ? Input::get ( 'nostra_thematiques' ) : array () );
			$demarche->nostraEvenements ()->sync ( is_array ( Input::get ( 'nostra_evenements' ) ) ? Input::get ( 'nostra_evenements' ) : array () );
		} catch ( Exception $e ) {
			Log::error($e);
			return Redirect::secure ( 'admin/damus/demarches/create' )->withInput ()->with ( 'error', Lang::get ( 'admin/damus/messages.create.baderror' ) . '<pre>' . $e->getMessage () . '</pre>' );
		}
		
		return Redirect::secure ( 'admin/damus/demarches/' )->with ( 'success', Lang::get ( 'admin/damus/messages.create.success' ) );
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param
	 *        	$user
	 * @return Response
	 */
	public function getEdit($demarche) {
		if ($demarche->id) {
			
			// on vérifie que la personne PEUT editer
			if ($this->getLoggedUser()->hasRole ( 'admin' ) || $this->getLoggedUser()->hasRole ( 'damus_gerer' )) {
				// ok!
			} else {
				return Redirect::secure ( 'admin/damus/demarches' )->with ( 'error', Lang::get ( 'admin/damus/messages.not_allowed' ) );
			}
			
			$arrayOfSelectedNostraPublics = $demarche->getNostraPublicsIds ();
			$arrayOfSelectedNostraThematiques = $demarche->getNostraThematiquesIds ();
			$arrayOfSelectedNostraEvenements = $demarche->getNostraEvenementsIds ();
			$arrayNostraPublics = NostraPublic::all ();
			
			// mode
			$mode = 'edit';
			
			return View::make ( 'admin/damus/demarches/create_edit', compact ( 'demarche', 'mode', 'arrayOfSelectedNostraPublics', 'arrayOfSelectedNostraThematiques', 'arrayOfSelectedNostraEvenements', 'arrayNostraPublics' ) );
		} else {
			return Redirect::secure ( 'admin/damus/evenementds' )->with ( 'error', Lang::get ( 'admin/damus/messages.does_not_exist' ) );
		}
	}
	public function postEdit($demarche) {
		
		// on vérifie que la personne PEUT editer
		if ($this->getLoggedUser()->hasRole ( 'admin' ) || $this->getLoggedUser()->hasRole ( 'damus_gerer' )) {
			// ok!
		} else {
			return Redirect::secure ( 'admin/damus/demarches' )->with ( 'error', Lang::get ( 'admin/damus/messages.not_allowed' ) );
		}
		
		// Valider le formulaire
		if (! Input::has ( 'nostra_publics' )) {
			return Redirect::secure ( 'admin/damus/demarches/' . $demarche->id . '/edit' )->withInput ()->with ( 'error', Lang::get ( 'admin/damus/messages.create.nopublics' ) );
		}
		if (! Input::has ( 'nostra_thematiques' )) {
			return Redirect::secure ( 'admin/damus/demarches/' . $demarche->id . '/edit' )->withInput ()->with ( 'error', Lang::get ( 'admin/damus/messages.create.nothematiques' ) );
		}
		/*
		 * if ( ! Input::has('nostra_evenements') ) {
		 * return Redirect::secure('admin/damus/demarches/'.$demarche->id.'/edit')->withInput()->with('error', Lang::get('admin/damus/messages.create.noevenements'));
		 * }
		 */
		$validator = Validator::make ( Input::all (), $demarche->getValidatorRules () );
		if ($validator->fails ()) {
			return Redirect::secure ( 'admin/damus/demarches/' . $demarche->id . '/edit' )->withInput ()->withErrors ( $validator )->with ( 'error', Lang::get ( 'admin/damus/messages.create.error' ) );
		}
		// fin validation
		
		try {
			$demarche->nostra_titre = Input::get ( 'nostra_titre' );
			$demarche->source = 'synapse';
			$demarche->save ();
			
			$demarche->nostraPublics ()->sync ( is_array ( Input::get ( 'nostra_publics' ) ) ? Input::get ( 'nostra_publics' ) : array () );
			$demarche->nostraThematiques ()->sync ( is_array ( Input::get ( 'nostra_thematiques' ) ) ? Input::get ( 'nostra_thematiques' ) : array () );
			$demarche->nostraEvenements ()->sync ( is_array ( Input::get ( 'nostra_evenements' ) ) ? Input::get ( 'nostra_evenements' ) : array () );
		} catch ( Exception $e ) {
			Log::error($e);
			return Redirect::secure ( 'admin/damus/demarches/' . $demarche->id . '/edit' )->withInput ()->with ( 'error', Lang::get ( 'admin/damus/messages.edit.baderror' ) . '<pre>' . $e->getMessage () . '</pre>' );
		}
		
		return Redirect::secure ( 'admin/damus/demarches/' )->with ( 'success', Lang::get ( 'admin/damus/messages.edit.success' ) );
	}
	public function getDelete($demarche) {
		
		// on vérifie que la personne PEUT supprimer
		if ($this->getLoggedUser()->hasRole ( 'admin' ) || $this->getLoggedUser()->hasRole ( 'damus_gerer' )) {
			// si la démarche (au sens Damus) est utilisée par une Démarche (sens "référentiel des démarches") il est INTERDIT de supprimer
			if (count ( $demarche->demarches )) {
				return Redirect::secure ( 'admin/damus/demarches' )->with ( 'error', Lang::get ( 'admin/damus/messages.demarche_inuse' ) );
			}
		} else {
			return Redirect::secure ( 'admin/damus/demarches' )->with ( 'error', Lang::get ( 'admin/damus/messages.not_allowed' ) );
		}
		
		return View::make ( 'admin/damus/demarches/delete', compact ( 'demarche' ) );
	}
	public function postDelete($demarche) {
		if ($demarche->delete ()) {
			return Redirect::secure ( 'admin/damus/demarches' )->with ( 'success', Lang::get ( 'admin/damus/messages.delete.success' ) );
		} else {
			// There was a problem deleting the item
			return Redirect::secure ( 'admin/damus/demarches' )->with ( 'error', Lang::get ( 'admin/damus/messages.delete.error' ) );
		}
	}
	public function getRestore($demarcheId) {
		$demarche = NostraDemarche::withTrashed ()->find ( $demarcheId );
		
		// on vérifie que la personne PEUT supprimer
		if ($this->getLoggedUser()->hasRole ( 'admin' ) || $this->getLoggedUser()->hasRole ( 'damus_gerer' )) {
			// ok!
		} else {
			return Redirect::secure ( 'admin/damus/demarches' )->with ( 'error', Lang::get ( 'admin/damus/messages.not_allowed' ) );
		}
		
		return View::make ( 'admin/damus/demarches/restore', compact ( 'demarche' ) );
	}
	public function postRestore($demarcheId) {
		$demarche = NostraDemarche::withTrashed ()->find ( $demarcheId );
		$demarche->restore ();
		
		// ca a bien été restauré ?
		$demarche = NostraDemarche::find ( $demarcheId );
		if (! empty ( $demarche )) {
			return Redirect::secure ( 'admin/damus/demarches' )->with ( 'success', Lang::get ( 'admin/damus/messages.restore.success' ) );
		} else {
			// There was a problem deleting the item
			return Redirect::secure ( 'admin/damus/demarches' )->with ( 'error', Lang::get ( 'admin/damus/messages.restore.error' ) );
		}
	}
}
