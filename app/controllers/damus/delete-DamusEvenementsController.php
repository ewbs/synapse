<?php
class DamusEvenementsController extends BaseController {
	protected $evenement;
	
	/**
	 * Inject the models.
	 * 
	 * @param NostraThematique $thematique        	
	 */
	public function __construct(NostraEvenement $evenement) {
		parent::__construct ();
		
		$this->evenement = $evenement;
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
		return View::make ( 'admin/damus/evenements/index', compact ( 'user' ) );
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
		return View::make ( 'admin/damus/evenements/trash', compact ( 'user' ) );
	}
	
	/**
	 * Donne une liste de NostraThematiques formattée pour les DataTables
	 *
	 * @return Datatables JSON
	 */
	public function getData() {
		$evenements = NostraEvenement::where ( 'source', '=', 'synapse' )->select ( array (
				'id',
				'nostra_titre',
				'created_at' 
		) );
		
		return Datatables::of ( $evenements )->add_column ( 'thematiques', function ($evenement) {
			return implode ( ', ', $evenement->getNostraThematiquesNames () );
		}, 2 )->add_column ( 'actions', function ($evenement) {
			$return = "";
			if ($this->getLoggedUser()->hasRole ( 'admin' ) || $this->getLoggedUser()->hasRole ( 'damus_gerer' )) {
				$return .= '<a title="' . Lang::get ( 'button.edit' ) . '" href="' . URL::secure ( 'admin/damus/evenements/' . $evenement->id . '/edit' ) . '" class="btn btn-xs btn-default"><span class="fa fa-pencil"></span></a>';
				$return .= '<a title="' . Lang::get ( 'button.delete' ) . '" href="' . URL::secure ( 'admin/damus/evenements/' . $evenement->id . '/delete' ) . '" class="btn btn-xs btn-danger"><span class="fa fa-trash-o"></span></a>';
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
		$evenements = NostraEvenement::onlyTrashed ()->where ( 'source', '=', 'synapse' )->select ( array (
				'id',
				'nostra_titre',
				'deleted_at' 
		) );
		
		return Datatables::of ( $evenements )->add_column ( 'thematiques', function ($evenement) {
			return implode ( ', ', $evenement->getNostraThematiquesNames () );
		}, 2 )->add_column ( 'actions', function ($evenement) {
			$return = "";
			if ($this->getLoggedUser()->hasRole ( 'admin' ) || $this->getLoggedUser()->hasRole ( 'damus_gerer' )) {
				$return .= '<a title="' . Lang::get ( 'button.restore' ) . '" href="' . URL::secure ( 'admin/damus/evenements/' . $evenement->id . '/restore' ) . '" class="btn btn-xs btn-default">' . Lang::get ( 'button.restore' ) . '</a>';
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
		return View::make ( 'admin/damus/evenements/create_edit', compact ( 'mode', 'arrayNostraPublics' ) );
	}
	public function postCreate() {
		$evenement = new NostraEvenement ();
		
		// Valider le formulaire
		if (! Input::has ( 'nostra_publics' )) {
			return Redirect::secure ( 'admin/damus/evenements/create' )->withInput ()->with ( 'error', Lang::get ( 'admin/damus/messages.create.nopublics' ) );
		}
		if (! Input::has ( 'nostra_thematiques' )) {
			return Redirect::secure ( 'admin/damus/evenements/create' )->withInput ()->with ( 'error', Lang::get ( 'admin/damus/messages.create.nothematiques' ) );
		}
		$validator = Validator::make ( Input::all (), $evenement->getValidatorRules () );
		if ($validator->fails ()) {
			return Redirect::secure ( 'admin/damus/evenements/create' )->withInput ()->withErrors ( $validator )->with ( 'error', Lang::get ( 'admin/damus/messages.create.error' ) );
		}
		// fin validation
		
		try {
			$evenement->nostra_titre = Input::get ( 'nostra_titre' );
			$evenement->source = 'synapse';
			$evenement->save ();
			
			$evenement->nostraPublics ()->sync ( is_array ( Input::get ( 'nostra_publics' ) ) ? Input::get ( 'nostra_publics' ) : array () );
			$evenement->nostraThematiques ()->sync ( is_array ( Input::get ( 'nostra_thematiques' ) ) ? Input::get ( 'nostra_thematiques' ) : array () );
		} catch ( Exception $e ) {
			Log::error($e);
			return Redirect::secure ( 'admin/damus/evenements/create' )->withInput ()->with ( 'error', Lang::get ( 'admin/damus/messages.create.baderror' ) . '<pre>' . $e->getMessage () . '</pre>' );
		}
		
		return Redirect::secure ( 'admin/damus/evenements/' )->with ( 'success', Lang::get ( 'admin/damus/messages.create.success' ) );
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param
	 *        	$user
	 * @return Response
	 */
	public function getEdit($evenement) {
		if ($evenement->id) {
			
			// on vérifie que la personne PEUT editer
			if ($this->getLoggedUser()->hasRole ( 'admin' ) || $this->getLoggedUser()->hasRole ( 'damus_gerer' )) {
				// ok!
			} else {
				return Redirect::secure ( 'admin/damus/evenements' )->with ( 'error', Lang::get ( 'admin/damus/messages.not_allowed' ) );
			}
			
			$arrayOfSelectedNostraPublics = $evenement->getNostraPublicsIds ();
			$arrayOfSelectedNostraThematiques = $evenement->getNostraThematiquesIds ();
			$arrayNostraPublics = NostraPublic::all ();
			
			// mode
			$mode = 'edit';
			
			return View::make ( 'admin/damus/evenements/create_edit', compact ( 'evenement', 'mode', 'arrayOfSelectedNostraPublics', 'arrayOfSelectedNostraThematiques', 'arrayNostraPublics' ) );
		} else {
			return Redirect::secure ( 'admin/damus/evenementds' )->with ( 'error', Lang::get ( 'admin/damus/messages.does_not_exist' ) );
		}
	}
	public function postEdit($evenement) {
		
		// on vérifie que la personne PEUT editer
		if ($this->getLoggedUser()->hasRole ( 'admin' ) || $this->getLoggedUser()->hasRole ( 'damus_gerer' )) {
			// ok!
		} else {
			return Redirect::secure ( 'admin/damus/evenements' )->with ( 'error', Lang::get ( 'admin/damus/messages.not_allowed' ) );
		}
		
		// Valider le formulaire
		if (! Input::has ( 'nostra_publics' )) {
			return Redirect::secure ( 'admin/damus/evenements/' . $evenement->id . '/edit' )->withInput ()->with ( 'error', Lang::get ( 'admin/damus/messages.create.nopublics' ) );
		}
		if (! Input::has ( 'nostra_thematiques' )) {
			return Redirect::secure ( 'admin/damus/evenements/' . $evenement->id . '/edit' )->withInput ()->with ( 'error', Lang::get ( 'admin/damus/messages.create.nothematiques' ) );
		}
		$validator = Validator::make ( Input::all (), $evenement->getValidatorRules () );
		if ($validator->fails ()) {
			return Redirect::secure ( 'admin/damus/evenements/' . $evenement->id . '/edit' )->withInput ()->withErrors ( $validator )->with ( 'error', Lang::get ( 'admin/damus/messages.create.error' ) );
		}
		// fin validation
		
		try {
			$evenement->nostra_titre = Input::get ( 'nostra_titre' );
			$evenement->source = 'synapse';
			$evenement->save ();
			
			$evenement->nostraPublics ()->sync ( is_array ( Input::get ( 'nostra_publics' ) ) ? Input::get ( 'nostra_publics' ) : array () );
			$evenement->nostraThematiques ()->sync ( is_array ( Input::get ( 'nostra_thematiques' ) ) ? Input::get ( 'nostra_thematiques' ) : array () );
		} catch ( Exception $e ) {
			Log::error($e);
			return Redirect::secure ( 'admin/damus/evenements/' . $evenement->id . '/edit' )->withInput ()->with ( 'error', Lang::get ( 'admin/damus/messages.edit.baderror' ) . '<pre>' . $e->getMessage () . '</pre>' );
		}
		
		return Redirect::secure ( 'admin/damus/evenements/' )->with ( 'success', Lang::get ( 'admin/damus/messages.edit.success' ) );
	}
	public function getDelete($evenement) {
		
		// on vérifie que la personne PEUT supprimer
		if ($this->getLoggedUser()->hasRole ( 'admin' ) || $this->getLoggedUser()->hasRole ( 'damus_gerer' )) {
			// ok!
		} else {
			return Redirect::secure ( 'admin/damus/evenements' )->with ( 'error', Lang::get ( 'admin/damus/messages.not_allowed' ) );
		}
		
		return View::make ( 'admin/damus/evenements/delete', compact ( 'evenement' ) );
	}
	public function postDelete($evenement) {
		if ($evenement->delete ()) {
			return Redirect::secure ( 'admin/damus/evenements' )->with ( 'success', Lang::get ( 'admin/damus/messages.delete.success' ) );
		} else {
			// There was a problem deleting the item
			return Redirect::secure ( 'admin/damus/evenements' )->with ( 'error', Lang::get ( 'admin/damus/messages.delete.error' ) );
		}
	}
	public function getRestore($evenementId) {
		$evenement = NostraEvenement::withTrashed ()->find ( $evenementId );
		
		// on vérifie que la personne PEUT supprimer
		if ($this->getLoggedUser()->hasRole ( 'admin' ) || $this->getLoggedUser()->hasRole ( 'damus_gerer' )) {
			// ok!
		} else {
			return Redirect::secure ( 'admin/damus/evenements' )->with ( 'error', Lang::get ( 'admin/damus/messages.not_allowed' ) );
		}
		
		return View::make ( 'admin/damus/evenements/restore', compact ( 'evenement' ) );
	}
	public function postRestore($evenementId) {
		$evenement = NostraEvenement::withTrashed ()->find ( $evenementId );
		$evenement->restore ();
		
		// ca a bien été restauré ?
		$evenement = NostraEvenement::find ( $evenementId );
		if (! empty ( $evenement )) {
			return Redirect::secure ( 'admin/damus/evenements' )->with ( 'success', Lang::get ( 'admin/damus/messages.restore.success' ) );
		} else {
			// There was a problem deleting the item
			return Redirect::secure ( 'admin/damus/evenements' )->with ( 'error', Lang::get ( 'admin/damus/messages.restore.error' ) );
		}
	}
}
