<?php
class AdminDashboardController extends BaseController {
	
	protected function routeGetIndex() { return route('adminGetIndex'); }
	
	/**
	 *
	 * {@inheritDoc}
	 * @see BaseController::getSection()
	 */
	protected function getSection(){
		return 'dashboard';
	}
	
	/**
	 * Admin dashboard
	 * Tout est filtré en fonction de l'utilisateur
	 */
	public function getIndex() {

		//TODO: O na un couplage fort avec les modèles appelés ici ... il faudrait transformer tous les appels en scope pour éviter les soucis à la maintenante évolutive --jda décembre 2016

		// on récupère les noms de filtres
		$txtUserFiltersAdministration = $this->getFilterString();

		// Liste des démarches filtrées (pour gagner quelques requêtes plus bas)
		$filteredDemarchesIds = Demarche::filtered()->lists('id');


		// -----------------------------------------
		// PROJETS
		// -----------------------------------------
		$countFilteredProjects = Idea::filtered()->count();
		$countPrioritaryProjects = Idea::filtered()->where('prioritary', '>', 0)->count();
		$countGenericProjects = Idea::filtered()->where('transversal', '>', 0)->count();
		$countInProgressProjects = Idea::filtered()->state("ENREALISATION")->count();
		$countDoneProjects = Idea::filtered()->state("REALISEE")->count();
		$countCanceledProjects = Idea::filtered()->state("ABANDONNEE")->count();
		$countValidatedProjects = Idea::filtered()->state("VALIDEE")->count();


		// -----------------------------------------
		// ACTIONS
		// -----------------------------------------
		// Préparer un tableau par pôle ayant au moins une expertise liée à une action en cours
		$poles=Pole::ordered()->get();
		$aPoles=array();
		$totalActions=0;
		foreach($poles as $pole) {
			$aPoles[$pole->id]=[
				'expertises'=>array()
			];
			foreach(Expertise::filtered()->ordered()->forPole($pole)->each()->countActions()->get() as $expertise) {
				if($expertise->actions>0) {
					array_push($aPoles[$pole->id]['expertises'], $expertise);
					$totalActions+=$expertise->actions;
				}
			}
			if(empty($aPoles[$pole->id]['expertises'])) {
				unset($aPoles[$pole->id]);
			}
		}
		
		// -----------------------------------------
		// DEMARCHES
		// -----------------------------------------
		$countFilteredDemarches = NostraDemarche::filtered()->count();
		$countDocumentedDemarches = Demarche::filtered()->count();
		$countWithGainsDemarches = Demarche::filtered()->withGains()->count();
		$countPiecesDemarches = DemarchePiece::filtered()->count();
		$countTasksDemarches = DemarcheTask::filtered()->count();


		// -----------------------------------------
		// CHARGES ADMINISTRATIVES
		// -----------------------------------------
		$potentialAmountAdministration = round( DB::table ( 'v_demarchegains' )->whereIn('demarche_id', $filteredDemarchesIds)->sum('gain_potential_administration') );
		$potentialAmountCitizen = round( DB::table ( 'v_demarchegains' )->whereIn('demarche_id', $filteredDemarchesIds)->sum('gain_potential_citizen') );


		// -----------------------------------------
		// FORMULAIRES
		// -----------------------------------------
		// Remarque : on utilise pas le trait Filterable sur les formulaires ici.
		// comme les formulaires sont liés à des démarches, et qu'on connait les démarches ...
		// Attention tout de même ... il faut prendre les valeurs de la table eForms , MAIS si on a un lien avec un NostraForm, ce sont ces valeurs qui comptent !

		$countFilteredForms = Eform::whereHas('demarcheEforms', function ($q) use ($filteredDemarchesIds) { // on ne compte pas les nostraforms, mais bien les eforms liés aux démarches
									$q->whereIn('demarche_eform.demarche_id', $filteredDemarchesIds);
								})->count();

		$countFilteredSimplifiedForms = Eform
										::whereHas('demarcheEforms', function ($q) use ($filteredDemarchesIds) { // on ne compte pas les nostraforms, mais bien les eforms liés aux démarches
											$q->whereIn('demarche_eform.demarche_id', $filteredDemarchesIds);
										})
										->leftJoin('nostra_forms', 'eforms.nostra_form_id', '=', 'nostra_forms.id')
										->where(DB::raw('CASE WHEN eforms.nostra_form_id > 0 THEN nostra_forms.simplified ELSE eforms.simplified END'), '>', 0)
										->count();

		$countFilteredElectronicForms = Eform
										::whereHas('demarcheEforms', function ($q) use ($filteredDemarchesIds) { // on ne compte pas les nostraforms, mais bien les eforms liés aux démarches
											$q->whereIn('demarche_eform.demarche_id', $filteredDemarchesIds);
										})
										->leftJoin('nostra_forms', 'eforms.nostra_form_id', '=', 'nostra_forms.id')
										->where(DB::raw('CASE WHEN eforms.nostra_form_id > 0 THEN nostra_forms.format ELSE eforms.format END'), '=', 'PEL')
										->count();

		$countFilteredEIDForms = Eform
			::whereHas('demarcheEforms', function ($q) use ($filteredDemarchesIds) { // on ne compte pas les nostraforms, mais bien les eforms liés aux démarches
				$q->whereIn('demarche_eform.demarche_id', $filteredDemarchesIds);
			})
			->leftJoin('nostra_forms', 'eforms.nostra_form_id', '=', 'nostra_forms.id')
			->where(DB::raw('CASE WHEN eforms.nostra_form_id > 0 THEN nostra_forms.esign ELSE eforms.esign END'), '>', 0)
			->count();


		return View::make ( 'admin/dashboard', compact( 'txtUserFiltersAdministration',
														'countFilteredProjects', 'countPrioritaryProjects', 'countGenericProjects', 'countInProgressProjects', 'countDoneProjects', 'countCanceledProjects', 'countValidatedProjects',
														'aPoles','totalActions',
														'countFilteredDemarches', 'countDocumentedDemarches', 'countWithGainsDemarches', 'countPiecesDemarches', 'countTasksDemarches',
														'potentialAmountAdministration', 'potentialAmountCitizen',
														'countFilteredForms', 'countFilteredSimplifiedForms', 'countFilteredElectronicForms', 'countFilteredEIDForms'
												) );

	}


	/**
	 * Obtenir l'écran de liste des projets filtrés
	 * @return \Illuminate\View\View
	 */
	public function getMyIdeas() {

		$model = new Idea();
		$loggedUser = Auth::user();

		// on récupère les noms de filtres
		$txtUserFiltersAdministration = $this->getFilterString();

		// on sauve la route en cours pour gérer les retour à la liste
		$this->setReturnTo();

		return View::make( 'admin/ideas/dashboard-list', compact('model', 'loggedUser', 'txtUserFiltersAdministration') );

	}


	/**
	 * Obtenir l'écran de liste des démarches filtrées
	 * @return \Illuminate\View\View
	 */
	public function getMyDemarches() {

		$model = new Demarche();
		$loggedUser = Auth::user();

		// on récupère les noms de filtres
		$txtUserFiltersAdministration = $this->getFilterString();

		// on sauve la route en cours pour gérer les retour à la liste
		$this->setReturnTo();

		return View::make( 'admin/demarches/dashboard-list', compact('model', 'loggedUser', 'txtUserFiltersAdministration') );


	}


	/**
	 * Obtenir l'écran de liste des actions filtrées par utilisateur
	 * @return string
	 */
	public function getMyActions () {
		$model = new EwbsAction();
		$loggedUser = Auth::user();

		// on récupère les noms de filtres
		$txtUserFiltersAdministration = $this->getFilterString();

		// on sauve la route en cours pour gérer les retour à la liste
		$this->setReturnTo();

		return View::make( 'admin/ewbsactions/dashboard-list', compact('model', 'loggedUser', 'txtUserFiltersAdministration') );

	}


	public function getMyCharges () {

		$model = new Demarche();
		$loggedUser = Auth::user();

		// on récupère les noms de filtres
		$txtUserFiltersAdministration = $this->getFilterString();

		// on sauve la route en cours pour gérer les retour à la liste
		$this->setReturnTo();

		// pour récupérer les TOP, on procédera en deux temps.
		// 1. récupérer la liste des démarches filtrées de l'utilisateur.
		// 2. récupérer les top
		// TODO: cette portion de code est elle à sa place ici ?

		// 1. la liste des ids de démarche
		$aFilteredDemarcheIds = Demarche::filtered()->lists('id');

		// 2.
		// Top des pièces les plus demandées
		$aTopExecutedPieces = Piece
								::join('v_lastrevisionpiecesfromdemarche', 'v_lastrevisionpiecesfromdemarche.piece_id', '=', 'demarchesPieces.id')
								->whereNull('demarchesPieces.deleted_at')
								->whereNull('v_lastrevisionpiecesfromdemarche.deleted_at')
								->whereIn('v_lastrevisionpiecesfromdemarche.demarche_id', $aFilteredDemarcheIds)
								->having(DB::raw('SUM(v_lastrevisionpiecesfromdemarche.volume * v_lastrevisionpiecesfromdemarche.frequency)'), '>', 0)
								->groupBy('demarchesPieces.id')
								->orderBy('count_items', "DESC")
								->limit(3)
								->get([
									'demarchesPieces.name AS displayname',
									DB::raw('SUM(v_lastrevisionpiecesfromdemarche.volume * v_lastrevisionpiecesfromdemarche.frequency) AS count_items')
								])->toArray();
		// Top des tâches les plus exéctuée
		$aTopExecutedTasks = Task
								::join('v_lastrevisiontasksfromdemarche', 'v_lastrevisiontasksfromdemarche.task_id', '=', 'demarchesTasks.id')
								->whereNull('demarchesTasks.deleted_at')
								->whereNull('v_lastrevisiontasksfromdemarche.deleted_at')
								->whereIn('v_lastrevisiontasksfromdemarche.demarche_id', $aFilteredDemarcheIds)
								->having(DB::raw('SUM(v_lastrevisiontasksfromdemarche.volume * v_lastrevisiontasksfromdemarche.frequency)'), '>', 0)
								->groupBy('demarchesTasks.id')
								->orderBy('count_items', "DESC")
								->limit(3)
								->get([
									'demarchesTasks.name AS displayname',
									DB::raw('SUM(v_lastrevisiontasksfromdemarche.volume * v_lastrevisiontasksfromdemarche.frequency) AS count_items')
								])->toArray();
		// Top des pièces qui dégageraient le plus de pognon
		$aTopValuablePieces = Piece
								::join('v_lastrevisionpiecesfromdemarche', 'v_lastrevisionpiecesfromdemarche.piece_id', '=', 'demarchesPieces.id')
								->whereNull('demarchesPieces.deleted_at')
								->whereNull('v_lastrevisionpiecesfromdemarche.deleted_at')
								->whereIn('v_lastrevisionpiecesfromdemarche.demarche_id', $aFilteredDemarcheIds)
								->groupBy('demarchesPieces.id')
								->orderBy('gpagpc', "DESC")
								->limit(3)
								->get([
									'demarchesPieces.name AS displayname',
									DB::raw('SUM(v_lastrevisionpiecesfromdemarche.gain_potential_administration + v_lastrevisionpiecesfromdemarche.gain_potential_citizen) AS gpagpc')
								])->toArray();
		// Top des taches qui dégageraient le plus de pognon
		$aTopValuableTasks = Task
								::join('v_lastrevisiontasksfromdemarche', 'v_lastrevisiontasksfromdemarche.task_id', '=', 'demarchesTasks.id')
								->whereNull('demarchesTasks.deleted_at')
								->whereNull('v_lastrevisiontasksfromdemarche.deleted_at')
								->whereIn('v_lastrevisiontasksfromdemarche.demarche_id', $aFilteredDemarcheIds)
								->groupBy('demarchesTasks.id')
								->orderBy('gpagpc', "DESC")
								->limit(3)
								->get([
									'demarchesTasks.name AS displayname',
									DB::raw('SUM(v_lastrevisiontasksfromdemarche.gain_potential_administration + v_lastrevisiontasksfromdemarche.gain_potential_citizen) AS gpagpc')
								])->toArray();



		return View::make( 'admin/demarches/dashboard-listcharges', compact('model', 'loggedUser', 'txtUserFiltersAdministration', 'aTopExecutedPieces', 'aTopExecutedTasks', 'aTopValuablePieces', 'aTopValuableTasks') );

	}


	/**
	 * Retourne sous forme de string les filtres de l'utilisateur
	 * @return string
	 */
	private function getFilterString () {
		// administrations
		$string = implode(', ',  Administration::whereHas('filters', function($query) {
			$query->where('user_id', '=', Auth::user()->id);
		})->lists('name'));
		
		// #desactivatedtags
		// tags
		/*$string .= ' | '; //TODO: faire qqe chose de plus propre, comme aller taper une icone devant les termes, tirée du fichier de langue
		$string .= implode(', ',  TaxonomyTag::whereHas('filters', function($query) {
			$query->where('user_id', '=', Auth::user()->id);
		})->lists('name'));*/
		
		// publics
		$string .= ' | '; //TODO: faire qqe chose de plus propre, comme aller taper une icone devant les termes, tirée du fichier de langue
		$string .= implode(', ',  NostraPublic::whereHas('filters', function($query) {
			$query->where('user_id', '=', Auth::user()->id);
		})->lists('title'));
		
		$string .= ' | ';
		$string .= implode(', ',  Expertise::whereHas('filters', function($query) {
			$query->where('user_id', '=', Auth::user()->id);
		})->lists('name'));
		
		return $string;
	}


	/**
	 * DEPRECATED
	 * @return array
	 */
	private function getIdeasNostraPublicDistribution() {
		$arrayNostraPublicsCounts = NostraPublic::
			join('idea_nostra_public', 'nostra_publics.id', '=', 'idea_nostra_public.nostra_public_id')
			->join('ideas', 'idea_nostra_public.idea_id', '=', 'ideas.id')
			->whereNull('ideas.deleted_at')
			->selectRaw('nostra_publics.title, COUNT(*) AS compte')
			->groupBy('nostra_publics.id')
			->orderBy('compte', 'DESC')
			->get()
			->toArray();

		//$arrayNostraPublicsCounts = array ();
		//$countIdeas = Idea::count ();
		//$nostraCountIdeas = 0;
		//array_push($arrayNostraPublicsCounts, [ 'Title' => 'Autres', 'compte' => count ( $nostraCountIdeas );

		return $arrayNostraPublicsCounts;
	}


	/**
	 * DEPRECATED
	 * @return mixed
	 */
	private function getIdeasAdministrationsDistribution() { //TODO : isnull !!!!
		$arrayAdministrationsCount = Administration::with('ideas')
			->join('administration_idea', 'administrations.id', '=', 'administration_idea.administration_id')
			->selectRaw('administrations.name, COUNT(*) AS compte')
			->groupBy('administrations.id')
			->orderBy('compte', 'desc')
			->get()
			->toArray();
		return($arrayAdministrationsCount);
	}

}