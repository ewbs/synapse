<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\MessageBag;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Illuminate\Filesystem\FileNotFoundException;
class DemarcheController extends TrashableModelController {
	
	use Synapse\Controllers\Traits\TraitFilterableController;
	
	/**
	 * Inject the models.
	 *
	 * @param Demarche $demarche
	 */
	public function __construct(Demarche $model) {
		parent::__construct ($model);
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::features()
	 */
	protected function features(ManageableModel $modelInstance) {
		$features[]=[
			'label' => Lang::get ( 'button.view' ),
			'url' => $modelInstance->routeGetView(),
			'icon' => 'eye'
		];
		if($modelInstance->canManage()) {
			$features[]=[
				'label' => Lang::get ( 'admin/demarches/messages.features.edit' ),
				'url' => $modelInstance->routeGetEdit(),
				'icon' => 'pencil'
			];
			$features[]=[
				'label' => Lang::get ( 'admin/ewbsactions/messages.title' ),
				'url' => route('demarchesActionsGetIndex', $modelInstance->id),
				'icon' => 'magic'
			];
			$features[]=[
				'label' => Lang::get ( 'admin/demarches/messages.features.components' ),
				'icon' => 'clipboard',
				'sub' => [
					[
						'label' => Lang::get('admin/demarches/messages.features.components'),
						'url' => route('demarchesGetComponents', $modelInstance->id),
						'icon' => 'clipboard',
					],
					[
						'label' => Lang::get ( 'admin/demarches/messages.features.downloadSCMLight' ),
						'url' => route('demarchesGetDownload', $modelInstance->id),
						'icon' => 'download'
					],
					[
						'label' => Lang::get ( 'admin/demarches/messages.features.uploadSCMLight' ),
						'url' => route('demarchesScmUploadGetFile', $modelInstance->id),
						'icon' => 'upload'
					]
				]
			];
		}
		return $features;
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::makeDetailView()
	 */
	protected function makeDetailView(ManageableModel $demarche, $view, $data = array()) {
		$data['aIdeas'] = $demarche->getIdeas(['name', 'id', 'created_at']);
		return parent::makeDetailView($demarche, $view, $data);
	}
	
	/**
	 * *********************************************************************************************************
	 * Gestion des démarches
	 * *********************************************************************************************************
	 */
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getList()
	 */
	protected function getList($trash=false) {
		$aRegions = Region::orderBy('name')->get();
		$aPublics = NostraPublic::root()->orderBy('title')->get();
		return View::make ( 'admin/demarches/list', compact ( 'aRegions', 'aPublics', 'trash' ) );
	}
	
	/**
	 * Pour respecter la forme d'appel des autres méthode, la route appelle getDataFilteredCharges, qui appelle une autre méthode.
	 * C'est la même construction que pour getData() qui appelle getDataJson() et que pour getDataFiltered qui appelle getDataFilteredJson.
	 * Sauf que pour ces deux dernières, on les a mise dans le ModelController.
	 * Mais pour cette méthode, il n'y a pas de raison de placer cela au niveau du ModelController car c'est une méthode spécifique à CE controlleur.
	 * @return mixed
	 */
	protected function getDataFilteredCharges() {
		return $this->getDataFilteredChargesJson();
	}


	/**
	 * Récupérer un datable des gains de charge de l'utilisateur (vue "mes charges administratives" depuis le dashboard de l'utilisateur)
	 * @return mixed
	 */
	private function getDataFilteredChargesJson() {

		/*
		 * Il faut ici construire une liste des démarches pour lesquelles on a des pieces OU des taches OU un gain calculé.
		 * Il va falloir tirer les éléments suivants :
		 * 	- Id de la démarche
		 *  - Nom de la démarche (lien cliquable vers le view de cette démarche)
		 *  - Volume (information tirée directement de la démarche, pas un calcul sur les pièces)
		 *  - Nombre de pièces de cette démarche
		 *  - Nombre de tâches de cette démarche
		 *  - Gain potentiel usager cumulé de cette démarche
		 *  - Gain potentiel administration culumé de cette démarche
		 *
		 *  Pour constuire cela on va utiliser la vue v_demarchesgains pour obtenir les gains (calculés sur base des pièces/taches ou entrés manuellement dans la démarche)
		 *  ainsi que les tables de pieces et taches pour compter
		 */

		$columns = [
			DB::raw ( "CASE WHEN demarches.id IS NOT NULL THEN demarches.id ELSE NULL END AS demarche_display_id" ),													// id de la démarche
			'nostra_demarches.title AS title',																													// nom de la démarche 1/2
			'nostra_demarches.title_long AS titlelong',																											// nom de la démarche 2/2
			'demarches.volume',																																	// volume de la démarche
			DB::raw ( 'COUNT(DISTINCT CASE WHEN "demarche_demarchePiece".deleted_at IS NULL THEN "demarche_demarchePiece".id ELSE NULL END) AS count_pieces' ), // nombre de pièces
			DB::raw ( 'COUNT(DISTINCT CASE WHEN "demarche_demarcheTask".deleted_at IS NULL THEN "demarche_demarcheTask".id ELSE NULL END) AS count_tasks' ),	// nombre de taches
			DB::raw ('v_demarchegains.gain_potential_citizen AS gpa'),
			DB::raw ('v_demarchegains.gain_potential_administration AS gpc'),

			DB::raw ( "CASE WHEN demarches.id IS NOT NULL THEN demarches.created_at ELSE NULL END AS demarche_created_at" ),									// pour afficher l'id complet ?
			'nostra_demarches.id AS nostra_demarche_id', //ne pas changer de place : c'est envoyé à la vue puis caché
		];

		$builder = NostraDemarche
			::filtered()
			->join ( 'demarches', 'demarches.nostra_demarche_id', '=', 'nostra_demarches.id', 'inner' )
			->join ( 'v_demarchegains', 'demarches.id', '=', 'v_demarchegains.demarche_id' )
			->leftjoin ( 'demarche_demarchePiece', 'demarche_demarchePiece.demarche_id', '=', 'demarches.id')
			->leftjoin ( 'demarche_demarcheTask', 'demarche_demarcheTask.demarche_id', '=', 'demarches.id')
			->where ( function ($query) {
				$query
					->where('gain_potential_administration', '>', 0)
					->orWhere('gain_potential_citizen', '>', 0);
			})
			->groupBy ( ['nostra_demarches.id', 'demarches.id', 'v_demarchegains.gain_potential_administration', 'v_demarchegains.gain_potential_citizen']);


		$items = $builder->select ( $columns );

		$dt = Datatables::of ( $items )
			->edit_column('demarche_display_id', function ($item) {
				if  (! strlen($item->demarche_display_id)) {
					return ('<span class="label label-danger">Non documenté</span>');
				}
				return DateHelper::year($item->demarche_created_at) . '-' . str_pad ( $item->demarche_display_id, 4, "0", STR_PAD_LEFT );
			})
			->edit_column('title', function ($item) {
				if ( strlen($item->demarche_display_id) ) {
					return '<a href="' . route('demarchesGetView', $item->demarche_display_id) . '"><strong>'.$item->title.'</strong><br/><small>'.$item->titlelong.'</small></a>';
				}

				return '<strong>'.$item->title.'</strong><br/><small>'.$item->titlelong.'</small>';
			})
			->edit_column('gpa', function ($item) {
				return NumberHelper::moneyFormat($item->gpa);
			})
			->edit_column('gpc', function ($item) {
				return NumberHelper::moneyFormat($item->gpc);
			})
			->remove_column('titlelong')
			->remove_column('demarche_id')
			->remove_column('demarche_created_at');

		return $dt->make ();

	}



	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getDataJson()
	 */
	protected function getDataJson($trash=false) {

		// ne prendre que les démarches documentées
		$documented = Input::has('onlyDocumented');
		// ne prendre que les démarches avec des actions en cours
		$actions = Input::has('onlyWithActions');

		$pieces = Input::has('minPieces') ? Input::get('minPieces') : false;
		$tasks = Input::has('minTasks') ? Input::get('minTasks') : false;
		$forms = Input::has('minForms') ? Input::get('minForms') : false;

		$publics = Input::has('publics') ? Input::get('publics') : false;
		$administrations = Input::has('administrations') ? Input::get('administrations') : false;

		// on sauve en session user
		Auth::user()->sessionSet('catalogDemarches_onlyDocumented', $documented);
		Auth::user()->sessionSet('catalogDemarches_onlyWithActions', $actions);
		Auth::user()->sessionSet('catalogDemarches_minPieces', $pieces);
		Auth::user()->sessionSet('catalogDemarches_minTasks', $tasks);
		Auth::user()->sessionSet('catalogDemarches_minForms', $forms);
		Auth::user()->sessionSet('catalogDemarches_publics', $publics);
		Auth::user()->sessionSet('catalogDemarches_administrations', $administrations);



		$items = $this->getDataSql($trash, false, $documented, $actions, $pieces, $tasks, $forms, $publics, $administrations);

		$dt = Datatables::of ( $items )
			->edit_column('demarche_completeid', function ($item) {
				if  (! strlen($item->demarche_completeid)) {
					return ('<span class="label label-danger">Non documenté</span>');
				}
				return DateHelper::year($item->demarche_created_at) . '-' . str_pad ( $item->demarche_completeid, 4, "0", STR_PAD_LEFT );
			})
			->edit_column('title', function ($item) use($trash) {
				if(!$trash) {
					if ( strlen($item->demarche_completeid) ) {
						return '<a href="' . route('demarchesGetView', $item->demarche_id) . '"><strong>'.$item->title.'</strong><br/><small>'.$item->titlelong.'</small></a>';
					}
					elseif ( Auth::user()->can('demarches_encode') ) {
						return '<a href="' . route('demarchesGetCreate', $item->nostra_demarche_id) . '"><strong>' . $item->title . '</strong><br/><small>' . $item->titlelong . '</small></a>';
					}
				}
				return '<strong>'.$item->title.'</strong><br/><small>'.$item->titlelong.'</small>';
			})
			->edit_column('count_pieces', function ($item) {
				if ($item->count_pieces > 0 || $item->count_tasks > 0 || $item->count_eforms > 0) {
					return $item->count_pieces . ' / ' . $item->count_tasks . ' / ' .$item->count_eforms;
				}
				return ('');
			})
			->edit_column('actions', function ($item) {
				$globalState=EwbsAction::globalState($item);
				if($globalState) {
					$tooltip='<ul>';
					foreach(EwbsActionRevision::states() as $state)
						if($count=$item->getAttribute("count_state_{$state}"))
							$tooltip.="<li>".Lang::choice("admin/ewbsactions/messages.wording.{$state}", $count)."</li>";
					$tooltip.='</ul>';
					return '<a href="'.route('demarchesActionsGetIndex', $item->demarche_id).'" data-toggle="popover" data-content="'.$tooltip.'" data-html="true"><span class="label label-'.EwbsActionRevision::stateToClass($globalState).'">'.($item->count_state_todo + $item->count_state_progress + $item->count_state_done + $item->count_state_standby + $item->count_state_givenup).'</span></a>';
				}
			})
			->remove_column('titlelong')
			->remove_column('demarche_id')
			->remove_column('demarche_created_at')
			->remove_column('count_tasks')
			->remove_column('count_eforms')
			->remove_column('count_state_todo')
			->remove_column('count_state_progress')
			->remove_column('count_state_done')
			->remove_column('count_state_standby')
			->remove_column('count_state_givenup');

		return $dt->make ();

	}
	
	/**
	 * {@inheritDoc}
	 * @see Synapse\Controllers\Traits\TraitFilterableController::getDataFilteredJson()
	 */
	protected function getDataFilteredJson() {

		// ne prendre que les démarches documentées
		$documented = Input::has('onlyDocumented');
		// ne prendre que les démarches avec des actions en cours
		$actions = Input::has('onlyWithActions');

		$pieces = Input::has('minPieces') ? Input::get('minPieces') : false;
		$tasks = Input::has('minTasks') ? Input::get('minTasks') : false;
		$forms = Input::has('minForms') ? Input::get('minForms') : false;

		// on sauve en session user
		Auth::user()->sessionSet('dashboardDemarches_onlyDocumented', $documented);
		Auth::user()->sessionSet('dashboardDemarches_onlyWithActions', $actions);
		Auth::user()->sessionSet('dashboardDemarches_minPieces', $pieces);
		Auth::user()->sessionSet('dashboardDemarches_minTasks', $tasks);
		Auth::user()->sessionSet('catalogDemarches_minForms', $forms);




		$items = $this->getDataSql(false, true, $documented, $actions, $pieces, $tasks, $forms);

		$dt = Datatables::of ( $items )
			->edit_column('demarche_completeid', function ($item) {
				if  (! strlen($item->demarche_completeid)) {
					return ('<span class="label label-danger">Non documenté</span>');
				}
				return DateHelper::year($item->demarche_created_at) . '-' . str_pad ( $item->demarche_completeid, 4, "0", STR_PAD_LEFT );
			})
			->edit_column('title', function ($item) {
				if ( strlen($item->demarche_completeid) ) {
					return '<a href="' . route('demarchesGetView', $item->demarche_id) . '"><strong>'.$item->title.'</strong><br/><small>'.$item->titlelong.'</small></a>';
				}
				elseif ( Auth::user()->can('demarches_encode') ) {
					return '<a href="' . route('demarchesGetCreate', $item->nostra_demarche_id) . '"><strong>' . $item->title . '</strong><br/><small>' . $item->titlelong . '</small></a>';
				}

				return '<strong>'.$item->title.'</strong><br/><small>'.$item->titlelong.'</small>';
			})
			->edit_column('count_pieces', function ($item) {
				if ($item->count_pieces > 0 || $item->count_tasks > 0 || $item->count_eforms > 0) {
					return $item->count_pieces . ' / ' . $item->count_tasks . ' / ' .$item->count_eforms;
				}
				return ('');
			})
			->edit_column('actions', function ($item) {
				$globalState=EwbsAction::globalState($item);
				if($globalState) {
					$tooltip='<ul>';
					foreach(EwbsActionRevision::states() as $state)
						if($count=$item->getAttribute("count_state_{$state}"))
							$tooltip.="<li>".Lang::choice("admin/ewbsactions/messages.wording.{$state}", $count)."</li>";
					$tooltip.='</ul>';
					return '<a href="'.route('demarchesActionsGetIndex', $item->demarche_id).'" data-toggle="popover" data-content="'.$tooltip.'" data-html="true"><span class="label label-'.EwbsActionRevision::stateToClass($globalState).'">'.($item->count_state_todo + $item->count_state_progress + $item->count_state_done + $item->count_state_standby + $item->count_state_givenup).'</span></a>';
				}
			})
			->remove_column('titlelong')
			->remove_column('demarche_id')
			->remove_column('demarche_created_at')
			->remove_column('count_tasks')
			->remove_column('count_eforms')
			->remove_column('count_state_todo')
			->remove_column('count_state_progress')
			->remove_column('count_state_done')
			->remove_column('count_state_standby')
			->remove_column('count_state_givenup');

		return $dt->make ();

	}


	/**
	 * Cette fonction retourne un builder, pour créer
	 * - la liste des démarches
	 * - la liste des démarches filtrées dans le dashboard d'un utilisateur
	 * @param bool $trash Considérer les soft-deletés, false par défaut
	 * @param bool $withUserFilters  ne prendre que selon les démarches filtrées par les filtres de l'utilisateur (si false : on prend toutes les démarches/nostrademarches)
	 * @param bool $onlyDocumented : ne prendre que les documentées (donc les Demarches, sans les NostraDemarche non liées à une Demarche)
	 * @param bool $onlyWithActions : ne prendre que les élément avec des actions EN COURS (ou DEMARREES)
	 * @param bool $minPieces : ne prende que les demarches avec un nombre minimum de x pièces
	 * @param bool $minTasks : ne prendre que les demarches avec un nombre minimum de x taches
	 * @param bool $publics : ne prendre que les demarches liées à un ou plusieurs publics
	 * @param bool $administrations : ne prendre que les demarches liées à une ou plusieurs administrations
	 * @param string $multipleSeparator : separateur litéraire pour les arrays transformés en strings
	 * @return Eloquent\Builder;
	 */
	private function getDataSql($trash=false, $withUserFilters = false, $onlyDocumented=false, $onlyWithActions=false, $minPieces=false, $minTasks=false, $minForms=false, $publics=false, $administrations=false, $multipleSeparator = ', ')
	{

		$columns = [
			DB::raw("CASE WHEN demarches.id IS NOT NULL THEN demarches.id ELSE NULL END AS demarche_completeid"),
			'demarches.id AS demarche_id',
			'nostra_demarches.title AS title',
			'nostra_demarches.title_long AS titlelong',
			'demarches.volume',
			DB::raw("CASE WHEN demarches.id IS NOT NULL THEN demarches.created_at ELSE NULL END AS demarche_created_at"),
			DB::raw('COUNT(DISTINCT CASE WHEN "demarche_demarchePiece".deleted_at IS NULL THEN "demarche_demarchePiece".id ELSE NULL END) AS count_pieces'),
			DB::raw('COUNT(DISTINCT CASE WHEN "demarche_demarcheTask".deleted_at IS NULL THEN "demarche_demarcheTask".id ELSE NULL END) AS count_tasks'),
			DB::raw('COUNT(DISTINCT CASE WHEN "demarche_eform".deleted_at IS NULL THEN "demarche_eform".id ELSE NULL END) AS count_eforms'),
			DB::raw("ARRAY_TO_STRING(ARRAY_AGG(DISTINCT nostra_publics.title), '{$multipleSeparator}', '') AS publics"),
			DB::raw("ARRAY_TO_STRING(ARRAY_AGG(DISTINCT administrations.name), '{$multipleSeparator}', '') AS administrations"),
			DB::raw('1 AS actions'), //pour afficher le compte des actions
			'nostra_demarches.id AS nostra_demarche_id', //ne pas changer de place : c'est envoyé à la vue puis caché
			DB::raw("COUNT(DISTINCT CASE WHEN v_lastrevisionewbsaction.deleted_at iS NULL AND v_lastrevisionewbsaction.state = '" . EwbsActionRevision::$STATE_TODO . "'     THEN v_lastrevisionewbsaction.id ELSE NULL END) AS count_state_todo"),
			DB::raw("COUNT(DISTINCT CASE WHEN v_lastrevisionewbsaction.deleted_at iS NULL AND v_lastrevisionewbsaction.state = '" . EwbsActionRevision::$STATE_PROGRESS . "' THEN v_lastrevisionewbsaction.id ELSE NULL END) AS count_state_progress"),
			DB::raw("COUNT(DISTINCT CASE WHEN v_lastrevisionewbsaction.deleted_at iS NULL AND v_lastrevisionewbsaction.state = '" . EwbsActionRevision::$STATE_DONE . "'     THEN v_lastrevisionewbsaction.id ELSE NULL END) AS count_state_done"),
			DB::raw("COUNT(DISTINCT CASE WHEN v_lastrevisionewbsaction.deleted_at iS NULL AND v_lastrevisionewbsaction.state = '" . EwbsActionRevision::$STATE_STANDBY . "'  THEN v_lastrevisionewbsaction.id ELSE NULL END) AS count_state_standby"),
			DB::raw("COUNT(DISTINCT CASE WHEN v_lastrevisionewbsaction.deleted_at iS NULL AND v_lastrevisionewbsaction.state = '" . EwbsActionRevision::$STATE_GIVENUP . "'  THEN v_lastrevisionewbsaction.id ELSE NULL END) AS count_state_givenup")
		];

		if ($withUserFilters) { // si on est dans le dashboard : on ne prend que les démarches de l'utilisateur (selon ses filtres)
			$builder = NostraDemarche::filtered();
		} else { // sinon on prend tout par défaut
			$builder = NostraDemarche::query(); //pas utiliser getQuery car l'objet retourné n'est pas le meme !!!!!!!!
		}
		
		if($trash) $builder->onlyTrashed();

		$builder->join('demarches', 'demarches.nostra_demarche_id', '=', 'nostra_demarches.id', (($onlyDocumented || $onlyWithActions) ? 'inner' : 'left'))
			->join('ewbsActions', 'ewbsActions.demarche_id', '=', 'demarches.id', ($onlyWithActions ? 'inner' : 'left'))
			->join('v_lastrevisionewbsaction', 'v_lastrevisionewbsaction.ewbs_action_id', '=', 'ewbsActions.id', ($onlyWithActions ? 'inner' : 'left'))
			->leftjoin('nostra_demarche_nostra_public', 'nostra_demarches.id', '=', 'nostra_demarche_nostra_public.nostra_demarche_id')
			->leftjoin('nostra_publics', 'nostra_publics.id', '=', 'nostra_demarche_nostra_public.nostra_public_id')
			->leftjoin ( 'administration_demarche', 'demarches.id', '=', 'administration_demarche.demarche_id' )
			->leftjoin ( 'administrations', 'administrations.id', '=', 'administration_demarche.administration_id' )
			->groupBy(['nostra_demarches.id', 'demarches.id']);

		// Si on demande un nombre minimum de pièces, on fera un inner join, avec en where du join le nombre de pieces
		if ($minPieces) {
			$builder
				->join('demarche_demarchePiece', function ($join) use ($minPieces) {
					$join->on('demarche_demarchePiece.demarche_id', '=', 'demarches.id')->whereNull('demarche_demarchePiece.deleted_at');
				})->having(DB::raw('COUNT(DISTINCT CASE WHEN "demarche_demarchePiece".deleted_at IS NULL THEN "demarche_demarchePiece".id ELSE NULL END)'), '>=', $minPieces);
		} // si on a pas besoin, on se contente d'un leftjoin
		else {
			$builder->leftjoin('demarche_demarchePiece', 'demarche_demarchePiece.demarche_id', '=', 'demarches.id');
		}

		// Si on demande un nombre minimum de taches, on fera un inner join, avec en where du join le nombre de taches
		if ($minTasks) {
			$builder
				->join('demarche_demarcheTask', function ($join) use ($minTasks) {
					$join->on('demarche_demarcheTask.demarche_id', '=', 'demarches.id')->whereNull('demarche_demarcheTask.deleted_at');
				})->having(DB::raw('COUNT(DISTINCT CASE WHEN "demarche_demarcheTask".deleted_at IS NULL THEN "demarche_demarcheTask".id ELSE NULL END)'), '>=', $minTasks);
		} // si on a pas besoin, on se contente d'un leftjoin
		else {
			$builder->leftjoin('demarche_demarcheTask', 'demarche_demarcheTask.demarche_id', '=', 'demarches.id');
		}

		// Si on demande un nombre minimum de formulaires, on fera un inner join, avec en where du join le nombre de formulaires
		if ($minForms) {
			$builder
				->join('demarche_eform', function ($join) use ($minForms) {
					$join->on('demarche_eform.demarche_id', '=', 'demarches.id')->whereNull('demarche_eform.deleted_at');
				})->having(DB::raw('COUNT(DISTINCT CASE WHEN "demarche_eform".deleted_at IS NULL THEN "demarche_eform".id ELSE NULL END)'), '>=', $minForms);
		} // si on a pas besoin, on se contente d'un leftjoin
		else {
			$builder->leftjoin('demarche_eform', 'demarche_eform.demarche_id', '=', 'demarches.id');
		}

		// faut il filtrer selon des publics ?
		if ($publics) {
			$aPublicsIds = explode(',', $publics);
			$builder->whereIn('nostra_demarche_nostra_public.nostra_public_id', $aPublicsIds);
		}

		if ($administrations) {
			$aAdministrationsIds = explode(',', $administrations);
			$builder->whereIn('administrations.id', $aAdministrationsIds);
		}


		return $builder->select($columns); ///////////////////////

	}
	
	/**
	 * Affiche le formulaire de création d'une démarche
	 * 
	 * @param string $nostraDemarcheID
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function getCreateDemarcheNostra($nostraDemarcheID) {
		try {
			$nostraDemarche = NostraDemarche::findOrFail ( $nostraDemarcheID );
		} catch ( ModelNotFoundException $ex ) {
			Log::error ( $ex );
			return Redirect::route ( $this->getModel ()->routeGetIndex () )->with ( 'error', Lang::get ( 'admin/demarches/messages.create.baderror' ) . '<pre>' . $ex->getMessage () . '</pre>' );
		}
		
		// Lors de la création de démarche, il existe d'office une Démarche Damus (tirée de Nostra).
		// Mais pas forcément une démarche Synapse.
		// On va donc la créer directement, et rediriger l'utilisateur vers la visualisation,
		// ainsi on pourra utiliser toutes les fonctionnalités offerte par la vue détails,
		// qui ne sont possible que si la Démarche Synapse existe.
		
		// On vérifie si ca n'a pas déjà été créé
		/* @var Demarche $demarche */
		$demarche = Demarche::loadFromNostraDemarche ( $nostraDemarcheID );
		if ($demarche) {
			return Redirect::secure ( $demarche->routeGetView () );
		} else {
			
			$demarche = new Demarche ();
			
			$demarche->nostra_demarche_id = $nostraDemarche->id;
			$demarche->user_id = $this->getLoggedUser ()->id;
			$demarche->ewbs = 0;
			$demarche->eform_usage = 0;
			$demarche->save ();
			
			return Redirect::secure ( $demarche->routeGetView () );
		}
	}
	
	/**
	 * Visualisation d'une démarche
	 *
	 * @param Demarche $demarche
	 * @return type
	 */
	public function getView(Demarche $demarche) {
		// Préparer un tableau par pôle ayant au moins une expertise liée à une action en cours
		$poles=Pole::ordered()->get();
		$aPoles=array();
		foreach($poles as $pole) {
			$aPoles[$pole->id]=[
				'expertises'=>array()
			];
			foreach(Expertise::ordered()->forPole($pole)->each()->countActionsForDemarche($demarche)->get() as $expertise) {
				if($expertise->actions>0) {
					array_push($aPoles[$pole->id]['expertises'], $expertise);
				}
			}
			if(empty($aPoles[$pole->id]['expertises'])) {
				unset($aPoles[$pole->id]);
			}
		}
		
		return $this->makeDetailView ( $demarche, 'admin/demarches/view', [
			'gains' => $demarche->getGains (),
			'aPoles' => $aPoles
		] );
	}
	
	/**
	 * Edition des infos générales d'une démarche
	 *
	 * @param Demarche $demarche
	 *
	 */
	/**
	 * {@inheritDoc}
	 * @see ModelController::getManage()
	 */
	protected function getManage(ManageableModel $demarche=null){
		try {
			$nostraDemarche = NostraDemarche::findOrFail ( $demarche->nostra_demarche_id );
		}
		catch ( ModelNotFoundException $ex ) {
			Log::error ( $e );
			return Redirect::secure ( $this->getModel ()->routeGetIndex () )->with ( 'error', Lang::get ( 'admin/demarches/messages.create.baderror' ) . '<pre>' . $ex->getMessage () . '</pre>' );
		}

		$returnTo = $this->getReturnTo();
		
		$calculatedGains = $demarche->getCalculatedGains ();
		$lastRevision = $demarche->getLastRevision ();
		$aRegions = Region::all ();
		$aSelectedAdministrations = $demarche->getAdministrationsIds ();
		$aVolumes = Demarche::volumes();
		$aTaxonomy = TaxonomyCategory::orderBy('name')->get();
		$aSelectedTags = $demarche->tags->lists('id');
		return $this->makeDetailView ( $demarche, 'admin/demarches/manage', compact ( 'aRegions', 'aSelectedAdministrations', 'nostraDemarche', 'calculatedGains', 'lastRevision', 'aVolumes', 'returnTo', 'aTaxonomy', 'aSelectedTags' ) );
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::save()
	 */
	protected function save(ManageableModel $demarche) {
			
		// Valider le formulaire
		if (! Input::has ( 'nostra_demarche' )) {
			Session::flash('error', Lang::get ( 'admin/demarches/messages.manage.nodemarche-error' ));
			return false;
		}
		
		// Valider les liens urls de documentation
		$aLinkId = Input::get ( "docLinkId", [ ] );
		$aLinkTitle = Input::get ( "docLinkTitle", [ ] );
		$aLinkURL = Input::get ( "docLinkURL", [ ] );
		$aLinkDescription = Input::get ( "docLinkDescription", [ ] );
		foreach ( $aLinkURL as $l ) { // on parcourt ... seule l'url doit être remplie
			if (strlen ( $l ) < 1) {
				Session::flash('error', Lang::get ( 'admin/demarches/messages.manage.linkerror' ));
				return false;
			}
		}
		
		// Valider les champs spécifiques à la révion
		$dr = new DemarcheRevision ();
		$validatorRevision = Validator::make ( Input::all (), $dr->formRules () );
		if ($validatorRevision->fails ()) {
			Session::flash('error', Lang::get ( 'general.manage.error'));
			Session::flash('errors', $validatorDemarche->errors()->merge($validatorRevision->errors()));
			return false;
		}
		
		// Sauvegarde de la démarche
		$demarche->nostra_demarche_id = Input::get ( 'nostra_demarche' );
		$demarche->user_id = $this->getLoggedUser ()->id;
		$demarche->ewbs = Input::has ( 'ewbs' ) ? 1 : 0;
		$demarche->eform_usage = Input::get ( 'eform_usage' );
		$demarche->comment = Input::get ( 'comment' );
		$demarche->volume = strlen(Input::get('volume')) ? Input::get('volume') : null;
		if(!$demarche->save ()) return false;
		$demarche->administrations ()->sync ( is_array ( Input::get ( 'administrations' ) ) ? Input::get ( 'administrations' ) : array () );
		$demarche->tags()->sync( is_array( Input::get('tags') ) ? Input::get('tags') : []);
		// Création de la révision de la démarche
		$this->createDemarcheRevisionFromInput ( $demarche );
		
		// Parcours ds ids de liens envoyés
		$newDocLinkIds = array ();
		$iteratorLinks = 0;
		foreach ( $aLinkId as $l ) {
			if ($aLinkId [$iteratorLinks] == "-1") {
				// nouveau lien
				$nL = new DemarcheDocLink ();
				$nL->demarche_id = $demarche->id; // obligatoire sinon on sait pas sauver
				$nL->name = $aLinkTitle [$iteratorLinks];
				$nL->url = $aLinkURL [$iteratorLinks];
				$nL->description = $aLinkDescription [$iteratorLinks];
				$nL->save ();
				array_push ( $newDocLinkIds, $nL->id );
			} else {
				// lien existant
				$nL = DemarcheDocLink::find ( $aLinkId [$iteratorLinks] );
				if ($nL->id > 0) {
					$nL->name = $aLinkTitle [$iteratorLinks];
					$nL->url = $aLinkURL [$iteratorLinks];
					$nL->description = $aLinkDescription [$iteratorLinks];
					$nL->save ();
					array_push ( $newDocLinkIds, $nL->id );
				}
			}
			$iteratorLinks ++;
		}
		
		// Suppression des liens qui ont disparu
		foreach ( $demarche->docLinks as $l ) {
			if (! in_array ( $l->id, $newDocLinkIds )) {
				$l->delete ();
			}
		}
		
		return true;
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getLinks()
	 */
	protected function getLinks(ManageableModel $demarche) {
		return [];
	}
	
	/**
	 * Génération de la liste des démarches en excel
	 * 
	 * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
	 */
	public function postExport() {
		try {
			$multipleSeparator = PHP_EOL;
			$demarches_ids = Input::has('demarches_ids') ? explode(',', Input::get('demarches_ids')) : [0];
			
			$columns = [
				'nostra_demarches.nostra_id',
				'nostra_demarches.title',
				'nostra_demarches.title_long',
				'nostra_demarches.type',
				'nostra_demarches.simplified', // xls
				'nostra_demarches.german_version', // xls
				'demarches.ewbs AS demarche_ewbs',
				'demarches.id AS demarche_id',
				'demarches.user_id AS demarche_user_id',
				'demarches.eform_usage AS demarche_eform_usage', // xls
				'demarches.comment AS demarche_comment', // xls
				'demarches.volume',
				DB::raw ( "ARRAY_TO_STRING(ARRAY_AGG(DISTINCT administrations.name), '{$multipleSeparator}', '') AS administrations" ),
				DB::raw ( "ARRAY_TO_STRING(ARRAY_AGG(DISTINCT nostra_thematiquesabc.title), '{$multipleSeparator}', '') AS thematiquesabc" ),
				DB::raw ( "ARRAY_TO_STRING(ARRAY_AGG(DISTINCT nostra_thematiquesadm.title), '{$multipleSeparator}', '') AS thematiquesadm" ),
				DB::raw ( "ARRAY_TO_STRING(ARRAY_AGG(DISTINCT nostra_publics.title), '{$multipleSeparator}', '') AS publics" ),
				DB::raw ( "CASE WHEN demarches.id IS NOT NULL THEN CONCAT(DATE_PART('year', demarches.created_at), '-', demarches.id) ELSE NULL END AS demarche_completeid" ),
				DB::raw ( "COUNT(DISTINCT CASE WHEN v_lastrevisionewbsaction.deleted_at iS NULL AND v_lastrevisionewbsaction.state = '".EwbsActionRevision::$STATE_TODO."'     THEN v_lastrevisionewbsaction.id ELSE NULL END) AS count_state_todo" ),
				DB::raw ( "COUNT(DISTINCT CASE WHEN v_lastrevisionewbsaction.deleted_at iS NULL AND v_lastrevisionewbsaction.state = '".EwbsActionRevision::$STATE_PROGRESS."' THEN v_lastrevisionewbsaction.id ELSE NULL END) AS count_state_progress" ),
				DB::raw ( "COUNT(DISTINCT CASE WHEN v_lastrevisionewbsaction.deleted_at iS NULL AND v_lastrevisionewbsaction.state = '".EwbsActionRevision::$STATE_DONE."'     THEN v_lastrevisionewbsaction.id ELSE NULL END) AS count_state_done" ),
				DB::raw ( "COUNT(DISTINCT CASE WHEN v_lastrevisionewbsaction.deleted_at iS NULL AND v_lastrevisionewbsaction.state = '".EwbsActionRevision::$STATE_STANDBY."'  THEN v_lastrevisionewbsaction.id ELSE NULL END) AS count_state_standby" ),
				DB::raw ( "COUNT(DISTINCT CASE WHEN v_lastrevisionewbsaction.deleted_at iS NULL AND v_lastrevisionewbsaction.state = '".EwbsActionRevision::$STATE_GIVENUP."'  THEN v_lastrevisionewbsaction.id ELSE NULL END) AS count_state_givenup" ),
				DB::raw ( "ARRAY_TO_STRING(ARRAY_AGG(DISTINCT eforms.title), '{$multipleSeparator}', '') AS eforms"),
				DB::raw ( "ARRAY_TO_STRING(ARRAY_AGG(DISTINCT nostra_forms.title), '{$multipleSeparator}', '') AS nostra_forms"),
				DB::raw ( "ARRAY_TO_STRING(ARRAY_AGG(DISTINCT nostra_documents.title), '{$multipleSeparator}', '') AS nostra_documents"),
			];
			
			$aNostraDemarches =
			NostraDemarche
				::leftjoin('demarches', 'demarches.nostra_demarche_id', '=', 'nostra_demarches.id')
				
				->leftjoin('ewbsActions', 'ewbsActions.demarche_id', '=', 'demarches.id')
				->leftjoin('v_lastrevisionewbsaction', 'v_lastrevisionewbsaction.ewbs_action_id', '=', 'ewbsActions.id')
				
				->leftjoin('nostra_demarche_nostra_public', 'nostra_demarches.id', '=', 'nostra_demarche_nostra_public.nostra_demarche_id')
				->leftjoin('nostra_publics', 'nostra_publics.id', '=', 'nostra_demarche_nostra_public.nostra_public_id')
				
				->leftjoin('nostra_demarche_nostra_thematiqueabc', 'nostra_demarches.id', '=', 'nostra_demarche_nostra_thematiqueabc.nostra_demarche_id' )
				->leftjoin('nostra_thematiquesabc', 'nostra_thematiquesabc.id', '=', 'nostra_demarche_nostra_thematiqueabc.nostra_thematiqueabc_id' )
				
				->leftjoin('nostra_demarche_nostra_thematiqueadm', 'nostra_demarches.id', '=', 'nostra_demarche_nostra_thematiqueadm.nostra_demarche_id' )
				->leftjoin('nostra_thematiquesadm', 'nostra_thematiquesadm.id', '=', 'nostra_demarche_nostra_thematiqueadm.nostra_thematiqueadm_id' )
				
				->leftjoin('administration_demarche', 'demarches.id', '=', 'administration_demarche.demarche_id' )
				->leftjoin('administrations', 'administrations.id', '=', 'administration_demarche.administration_id' )
				
				->leftjoin('v_lastrevisiondemarcheeform', 'v_lastrevisiondemarcheeform.demarche_id', '=', 'demarches.id')
				->leftjoin('eforms', 'eforms.id', '=', 'v_lastrevisiondemarcheeform.eform_id')
				->whereNull('v_lastrevisiondemarcheeform.deleted_at')
				
				->leftjoin('nostra_demarche_nostra_form', 'nostra_demarche_nostra_form.nostra_demarche_id', '=', 'demarches.nostra_demarche_id')
				->leftjoin('nostra_forms', 'nostra_forms.id', '=', 'nostra_demarche_nostra_form.nostra_form_id')
				->whereNull('nostra_forms.deleted_at')
				
				->leftjoin('nostra_demarche_nostra_document', 'nostra_demarche_nostra_document.nostra_demarche_id', '=', 'demarches.nostra_demarche_id')
				->leftjoin('nostra_documents', 'nostra_documents.id', '=', 'nostra_demarche_nostra_document.nostra_document_id')
				->whereNull('nostra_documents.deleted_at')
				
				->whereIn('nostra_demarches.id', $demarches_ids)
				->groupBy(['nostra_demarches.id', 'demarches.id'])
				->get($columns);
			
			$objPHPExcel = xlsexport_getNewHandler ();
			$line = 1; // ligne dans Excel
			$objPHPExcel->setActiveSheetIndex ( 0 );
			$worksheet = $objPHPExcel->getActiveSheet ();

			// STYLES GLOBAUX
			foreach ( range ( 'A', 'W' ) as $columnID ) {
				$worksheet->getColumnDimension ( $columnID )->setAutoSize ( true );
			}
			$objPHPExcel->getDefaultStyle ()->getFont ()->setName ( 'Arial' );
			$objPHPExcel->getDefaultStyle ()->getFont ()->setSize ( 9 );

			// couleur des éléments a mettre en évidence
			$styles = [
				'white_on_blue' => [
					'font' => ['color' => ['rgb' => 'FFFFFF']],
					'fill' => [
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => ['rgb' => '4E9DFF']
					]
				],
				'default' => ['font' => ['color' => ['rgb' => '333333']]],
				'primary' => ['font' => ['color' => ['rgb' => '337ab7']]],
				'success' => ['font' => ['color' => ['rgb' => '5cb85c']]],
				'warning' => ['font' => ['color' => ['rgb' => 'f0ad4e']]]
			];

			// TITRES DANS EXCEL
			$worksheet->getCell ( 'A1' )->setValue ( 'Documenté' );
			$worksheet->getCell ( 'B1' )->setValue ( 'ID Nostra' );
			$worksheet->getCell ( 'C1' )->setValue ( 'ID Synapse' );
			$worksheet->getCell ( 'D1' )->setValue ( 'Nom' );
			$worksheet->getCell ( 'E1' )->setValue ( 'Public(s)' );
			$worksheet->getCell ( 'F1' )->setValue ( 'Thématique(s) usager' );
			$worksheet->getCell ( 'G1' )->setValue ( 'Thématique(s) administration' );
			
			$worksheet->getCell ( 'H1' )->setValue ( 'Simplifié' );
			$worksheet->getCell ( 'I1' )->setValue ( 'Version allemande' );
			$worksheet->getCell ( 'J1' )->setValue ( 'Type' );
			$worksheet->getCell ( 'K1' )->setValue ( 'Administrations impliquées' );
			$worksheet->getCell ( 'L1' )->setValue ( 'Périmètre eWBS' );
			$worksheet->getCell ( 'M1' )->setValue ( 'Usage e-Form' );
			$worksheet->getCell ( 'N1' )->setValue ( 'Gain potentiel usager' );
			$worksheet->getCell ( 'O1' )->setValue ( 'Gain potentiel administration' );
			$worksheet->getCell ( 'P1' )->setValue ( 'Gain effectif usager' );
			$worksheet->getCell ( 'Q1' )->setValue ( 'Gain effectif administration' );
			$worksheet->getCell ( 'R1' )->setValue ( 'Actions' );
			$worksheet->getCell ( 'S1' )->setValue ( 'Commentaire' );
			$worksheet->getCell ( 'T1' )->setValue ( 'Taxonomie');
			$worksheet->getCell ( 'U1' )->setValue ( 'Volume');
			$worksheet->getCell ( 'V1' )->setValue ( 'Formulaires');
			$worksheet->getCell ( 'W1' )->setValue ( 'Formulaires NOSTRA');
			$worksheet->getCell ( 'X1' )->setValue ( 'Documents NOSTRA');
			
			$worksheet->getStyle ( 'A1:X1' )->getFont ()->setBold ( true );
			
			// CONTENU
			$calculatedGains = Demarche::getAllCalculatedGains ();
			foreach ( $aNostraDemarches as $nostraDemarche ) {
				/*@var NostraDemarche $nostraDemarche */
				$line ++; // on commencera donc en ligne 2 : ceci est le compteur de position générale
				if (isset ( $nostraDemarche->demarche_id )) {
					$lastRevision = Demarche::getLastDemarcheRevision ( $nostraDemarche->demarche_id ); // TODO Voir si pas moyen de récupérer cela au niveau de la requête principale, pour éviter de faire cette requête pour chaque nostraDemarche
					foreach ( [
								  'gain_potential_administration',
								  'gain_real_administration',
								  'gain_potential_citizen',
								  'gain_real_citizen'
							  ] as $gainName ) {
						if ($lastRevision && $lastRevision->$gainName) {
							$nostraDemarche->$gainName = $lastRevision->$gainName;
						}
						else if (array_has ( $calculatedGains, $nostraDemarche->demarche_id )) {
							$nostraDemarche->$gainName = $calculatedGains [$nostraDemarche->demarche_id]->$gainName;
						}
					}
					
					$worksheet->getStyle ( "A$line" )->applyFromArray ( $styles ['white_on_blue'] );
					$worksheet->getCell ( "A$line" )->setValue ( "oui" );
					$worksheet->getCell ( "B$line" )->setValue ( $nostraDemarche->nostra_id );
					$worksheet->getCell ( "C$line" )->setValue ( $nostraDemarche->demarche_completeid );
					$worksheet->getCell ( "K$line" )->setValue ( $nostraDemarche->administrations )->getStyle ()->getAlignment ()->setWrapText ( true );
					if ($nostraDemarche->demarche_ewbs) {
						$worksheet->getStyle ( "L$line" )->applyFromArray ( $styles ['white_on_blue'] );
						$worksheet->getCell ( "L$line" )->setValue ( "oui" );
					}
					else {
						$worksheet->getCell ( "L$line" )->setValue ( "non" );
					}
					$worksheet->getCell ( "M$line" )->setValue ( $nostraDemarche->demarche_eform_usage . '%' );
					$worksheet->getCell ( "N$line" )->setValue ( $nostraDemarche->gain_potential_citizen );
					$worksheet->getCell ( "O$line" )->setValue ( $nostraDemarche->gain_potential_administration );
					$worksheet->getCell ( "P$line" )->setValue ( $nostraDemarche->gain_real_citizen );
					$worksheet->getCell ( "Q$line" )->setValue ( $nostraDemarche->gain_real_administration );
					
					if ($globalActionsState = EwbsAction::globalState ( $nostraDemarche )) {
						$value = Lang::get ( "admin/ewbsactions/messages.state.{$globalActionsState}" ) . ' :';
						foreach(EwbsActionRevision::states() as $state)
							if ($count=$nostraDemarche->getAttribute("count_state_{$state}"))
								$value .= PHP_EOL . Lang::choice ( "admin/ewbsactions/messages.wording.{$state}", $count);
						$cell = $worksheet->getCell ( "R$line" )->setValue ( $value );
						$cell->getHyperlink ()->setUrl ( route ( 'demarchesActionsGetIndex', $nostraDemarche->demarche_id ) );
						$cell->getStyle ()->applyFromArray ( $styles [EwbsActionRevision::stateToClass ( $globalActionsState )] )->getAlignment ()->setWrapText ( true );
					}
					$worksheet->getCell ( "S$line" )->setValue ( $nostraDemarche->demarche_comment )->getStyle ()->getAlignment ()->setWrapText ( true );
					
					/*
					 * Taxonomie
					 */
					//FIXME: beau gros kludge par manque de temps : à remplacer par une jointure propre dans la requete principale
					if ($nostraDemarche->demarche_id) {
						$worksheet->getCell ( "T$line" )->setValue ( implode($multipleSeparator, Demarche::find($nostraDemarche->demarche_id)->tags()->lists('name')) )->getStyle ()->getAlignment ()->setWrapText ( true );
					}
					
					$worksheet->getCell ( "U$line" )->setValue ( $nostraDemarche->volume )->getStyle ()->getAlignment ()->setWrapText ( true );
					$worksheet->getCell ( "V$line" )->setValue ( $nostraDemarche->eforms )->getStyle ()->getAlignment ()->setWrapText ( true );
					$worksheet->getCell ( "W$line" )->setValue ( $nostraDemarche->nostra_forms )->getStyle ()->getAlignment ()->setWrapText ( true );
					$worksheet->getCell ( "X$line" )->setValue ( $nostraDemarche->nostra_documents )->getStyle ()->getAlignment ()->setWrapText ( true );
				}
				else {
					$worksheet->getCell ( "A$line" )->setValue ( "non" );
				}
				
				$worksheet->getCell ( "D$line" )->setValue ( $nostraDemarche->title );
				$worksheet->getCell ( "E$line" )->setValue ( $nostraDemarche->publics )->getStyle ()->getAlignment ()->setWrapText ( true );
				$worksheet->getCell ( "F$line" )->setValue ( $nostraDemarche->thematiquesabc )->getStyle ()->getAlignment ()->setWrapText ( true );
				$worksheet->getCell ( "G$line" )->setValue ( $nostraDemarche->thematiquesadm )->getStyle ()->getAlignment ()->setWrapText ( true );
				$worksheet->getCell ( "H$line" )->setValue ( $nostraDemarche->simplified ? "oui" : "non" );
				$worksheet->getCell ( "I$line" )->setValue ( $nostraDemarche->german_version ? "oui" : "non" );
				$worksheet->getCell ( "J$line" )->setValue ( $nostraDemarche->type );
				
				// hauteur de ligne en auto (car pas mal de texte dans certaines cellules)
				$worksheet->getRowDimension ( $line )->setRowHeight ( - 1 );
			}
			$worksheet->getStyle ( "N2:Q{$line}" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00 );
			
			$fileName = 'synapse-export-demarches-' . uniqid () . '.xlsx';
			$file = public_path () . '/temp/' . $fileName;
			$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' );
			$objWriter->save ( $file );
			
			$response = Response::download ( $file, $fileName, ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
			ob_end_clean ();
			return $response;
		}
		catch ( Exception $e ) {
			Log::error ( $e );
			return Redirect::secure ( $this->getModel ()->routeGetIndex () )->with ( 'error', Lang::get ( 'admin/demarches/messages.export.baderror' ) . '<pre>' . $e->getMessage () . '</pre>' );
		}
	}
	
	/**
	 * *********************************************************************************************************
	 * Traitement des SCM
	 * *********************************************************************************************************
	 */
	
	/**
	 * Télécharger le SCM Light au format XLS (si possible)
	 * 
	 * @param Demarche $demarche
	 * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
	 */
	public function scmDownloadGetIndex(Demarche $demarche) {
		if(!$demarche->canManage()) return $this->redirectNoRight ();
		
		/*
		 * La première chose à faire, c'est de vérifier s'il existe des fichiers XLS uploadé.
		 * S'il en existe, on les proposera au téléchargement, sinon on fera un download direct du SCMLight
		 * Par contre, si on passe un paramètre spécial en GET, on bypasse cette vérif
		 */
		if (! Input::has ( 'bypassXLSCheck' )) {
			$scmFiles = $demarche->scms ()->orderBy ( 'created_at', 'DESC' )->get ();
			if (count ( $scmFiles ) > 0 || Input::has ( 'list' ))
				return $this->makeDetailView ( $demarche, 'admin/demarches/scm/download', compact ( 'scmFiles' ) );
		}
		
		try {
			// pour télécharger un fichier SCM, il faut au moins une pièce ou une tâche ... sinon ca sera vide
			$aDemarcheComponent=[
				'piece'=>$demarche->getLastRevisionPieces(),
				'task' =>$demarche->getLastRevisionTasks()
			];
			if (! count ( $aDemarcheComponent['piece'] ) && ! count ( $aDemarcheComponent['task'] )) {
				return Redirect::secure ( $demarche->routeGetView() )->with ( 'error', Lang::get ( 'admin/demarches/messages.scm.empty' ) );
			}
			
			// tout semble ok, on crée l'xlsx
			$totalGainPotentialCitizen = 0;
			$totalGainPotentialAdministration = 0;
			$totalGainRealCitizen = 0;
			$totalGainRealAdministration = 0;
			
			$objPHPExcel = xlsexport_getNewHandler ();
			$line = 1; // ligne dans Excel
			
			$objPHPExcel->setActiveSheetIndex ( 0 );
			$worksheet = $objPHPExcel->getActiveSheet ();
			$worksheet->getProtection ()->setSheet ( true );
			
			$columns = scmexport_getDemarcheComponentColumns ();
			$lastColumn = scmexport_getLastDemarcheComponentColumnPosition ();
			/*
			 * Autosize sur les colonnes
			 */
			foreach ( range ( 'A', $lastColumn ) as $columnID ) {
				$worksheet->getColumnDimension ( $columnID )->setAutoSize ( true );
			}
			
			/*
			 * TITRES DANS EXCEL
			 */
			$worksheet->mergeCells ( "A{$line}:{$lastColumn}{$line}" );
			$worksheet->getCell ( "A{$line}" )->setValue ( $demarche->nostraDemarche->title );
			$worksheet->getStyle ( "A{$line}" )->applyFromArray ( xlsexport_getStyles ( 'big_title' ) );
			$line += 2;
			$worksheet->getCell ( "A{$line}" )->setValue ( Lang::get ( 'admin/demarches/scmfiles.titles.activity' ) );
			$worksheet->getCell ( "B{$line}" )->setValue ( Lang::get ( 'admin/demarches/scmfiles.titles.id' ) );
			$worksheet->getCell ( "C{$line}" )->setValue ( Lang::get ( 'admin/demarches/scmfiles.titles.name' ) );
			foreach ( $columns as $colname => $colproperties )
				$worksheet->getCell ( $colproperties ['pos'] . $line )->setValue ( Lang::get ( "admin/demarches/scmfiles.titles.{$colname}" ) );
			$worksheet->getStyle ( "A{$line}:{$lastColumn}{$line}" )->getFont ()->setBold ( true );
			$worksheet->getStyle ( "A{$line}:{$lastColumn}{$line}" )->applyFromArray ( xlsexport_getStyles ( 'white_on_blue' ) );
			
			/*
			 * CONTENU
			 */
			$startingLine = ++ $line; // on commencera à la ligne suivante.
			
			foreach($aDemarcheComponent as $type=>$a) {
				foreach ( $a as $element ) {
					$uctype=ucfirst($type);
					$idcol="demarche_demarche{$uctype}_id";
					$worksheet->getCell ( "A{$line}" )->setValue ( Lang::get ( "admin/demarches/scmfiles.types.{$type}" ) );
					$worksheet->getCell ( "B{$line}" )->setValue ( $element->$idcol );
					$worksheet->getCell ( "C{$line}" )->setValue ( $element->name );
					foreach ( $columns as $colname => $colproperties ) {
						if ($colname == 'gain_potential_citizen') {
							$value = "=D{$line}*E{$line}*F{$line}";
						}
						elseif ($colname == 'gain_potential_administration') {
							$value = "=D{$line}*E{$line}*G{$line}";
						}
						else {
							$value = $element->$colname;
						}
						$worksheet->getCell ( $colproperties ['pos'] . $line )->setValue ( $value );
					}
				
					// hauteur de ligne en auto (car pas mal de texte dans certaines cellules)
					$worksheet->getRowDimension ( $line )->setRowHeight ( - 1 );
				
					// totaux
					$totalGainPotentialAdministration += $element->gain_potential_administration;
					$totalGainPotentialCitizen += $element->gain_potential_citizen;
					$totalGainRealAdministration += $element->gain_real_administration;
					$totalGainRealCitizen += $element->gain_real_citizen;
				
					$line ++;
				}
			}
			
			$adjustmentsStartingLine = $line;
			// Ajouter une ligne "ajustement des totaux par l'analyste" si au moins un des 4 montants est différent de la somme calculée au niveau des pièces et tâches
			$lastRevision = $demarche->getLastRevision ();
			if ($lastRevision) {
				if ($lastRevision->gain_potential_citizen || $lastRevision->gain_potential_administration || $lastRevision->gain_real_citizen || $lastRevision->gain_real_administration) {
					$line ++;
					$worksheet->getCell ( "C{$line}" )->setValue ( Lang::get ( 'admin/demarches/scmfiles.adjustments' ) );
					if ($lastRevision->gain_potential_citizen && $lastRevision->gain_potential_citizen != $totalGainPotentialCitizen)
						$worksheet->getCell ( $columns ['gain_potential_citizen'] ['pos'] . $line )->setValue ( $lastRevision->gain_potential_citizen - $totalGainPotentialCitizen );
					if ($lastRevision->gain_potential_administration && $lastRevision->gain_potential_administration != $totalGainPotentialAdministration)
						$worksheet->getCell ( $columns ['gain_potential_administration'] ['pos'] . $line )->setValue ( $lastRevision->gain_potential_administration - $totalGainPotentialAdministration );
					if ($lastRevision->gain_real_citizen && $lastRevision->gain_real_citizen != $totalGainRealCitizen)
						$worksheet->getCell ( $columns ['gain_real_citizen'] ['pos'] . $line )->setValue ( $lastRevision->gain_real_citizen - $totalGainRealCitizen );
					if ($lastRevision->gain_real_administration && $lastRevision->gain_real_administration != $totalGainRealAdministration)
						$worksheet->getCell ( $columns ['gain_real_administration'] ['pos'] . $line )->setValue ( $lastRevision->gain_real_administration - $totalGainRealAdministration );
				}
			}
			$line += 3;
			$endingLine = $line - 1;
			
			/*
			 * Formattage des colonnes contenant des devises
			 */
			$worksheet->getStyle ( "D{$startingLine}:E{$line}" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER );
			$worksheet->getStyle ( "F{$startingLine}:K{$line}" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00 );
			/*
			 * TOTAUX
			 */
			$worksheet->getCell ( "A{$line}" )->setValue ( Lang::get ( 'admin/demarches/scmfiles.eof' ) );
			$worksheet->getCell ( "C{$line}" )->setValue ( Lang::get ( 'admin/demarches/scmfiles.totals' ) );
			$worksheet->getCell ( "H{$line}" )->setValue ( "=SUM(H$startingLine:H$endingLine)" );
			$worksheet->getCell ( "I{$line}" )->setValue ( "=SUM(I$startingLine:I$endingLine)" );
			$worksheet->getCell ( "J{$line}" )->setValue ( "=SUM(J$startingLine:J$endingLine)" );
			$worksheet->getCell ( "K{$line}" )->setValue ( "=SUM(K$startingLine:K$endingLine)" );
			$worksheet->getStyle ( "A{$line}:C{$line}" )->getFont ()->setBold ( true );
			$worksheet->getStyle ( "A{$line}:C{$line}" )->applyFromArray ( xlsexport_getStyles ( 'white_on_blue' ) );
			$worksheet->getStyle ( "H{$line}:K{$line}" )->applyFromArray ( xlsexport_getStyles ( 'white_on_blue' ) );
			$worksheet->getStyle ( "H{$line}:K{$line}" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00 );
			
			/*
			 * Déprotection de certaines plages
			 */
			$worksheet->getStyle ( "C{$startingLine}:G{$endingLine}" )->getProtection ()->setLocked ( PHPExcel_Style_Protection::PROTECTION_UNPROTECTED );
			$worksheet->getStyle ( "J{$startingLine}:{$lastColumn}{$endingLine}" )->getProtection ()->setLocked ( PHPExcel_Style_Protection::PROTECTION_UNPROTECTED );
			$worksheet->getStyle ( "H{$adjustmentsStartingLine}:I{$endingLine}" )->getProtection ()->setLocked ( PHPExcel_Style_Protection::PROTECTION_UNPROTECTED );
			
			/*
			 * Sauvegarde et download
			 */
			$fileName = 'synapse-scm-' . uniqid () . '.xlsx';
			$file = public_path () . '/temp/' . $fileName;
			$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' );
			$objWriter->save ( $file );
			
			$response = Response::download ( $file, $fileName, array (
					"Content-Type" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" 
			) );
			ob_end_clean ();
			return $response;
		} catch ( Exception $e ) {
			Log::error ( $e );
			return Redirect::secure ( $this->getModel ()->routeGetIndex () )->with ( 'error', Lang::get ( 'admin/demarches/messages.scm.export-error' ) . '<pre>' . $e->getMessage () . '</pre>' );
		}
	}
	
	/**
	 * Propose de supprimer le SCM Light
	 *
	 * @param Demarche $demarche
	 * @param DemarcheSCM $scmFile
	 * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
	 */
	public function scmDownloadGetDelete(Demarche $demarche, DemarcheSCM $scmFile) {
		if (! $demarche->canManage() || $demarche->id != $scmFile->demarche_id)
			return $this->redirectNoRight ();
		return View::make ( 'admin/demarches/scm/download_delete', compact ( 'scmFile' ) );
	}
	
	/**
	 * Supprime le SCM Light
	 *
	 * @param Demarche $demarche
	 * @param DemarcheSCM $scmFile
	 * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
	 */
	public function scmDownloadPostDelete(Demarche $demarche, DemarcheSCM $scmFile) {
		if (! $demarche->canManage() || $demarche->id != $scmFile->demarche_id)
			return $this->redirectNoRight ();
		if ($scmFile->delete ())
			return Redirect::route ( 'demarchesGetDownload', [ 
					'demarche' => $demarche->id,
					'list' => '1' 
			] )->with ( 'success', Lang::get ( 'admin/demarches/messages.delete.success' ) );
		else
			return Redirect::route ( 'demarchesGetDownload', [ 
					'demarche' => $demarche->id,
					'list' => '1' 
			] )->with ( 'error', Lang::get ( 'admin/demarches/messages.delete.error' ) );
	}
	
	/**
	 * Télécharger un fichier SCM uploadé par un utilisateur.
	 * Le fichier étant stocké dans le storage de Synapse, on ne pas l'appeler directement.
	 *
	 * @param Demarche $demarche
	 * @param DemarcheSCM $scmFile
	 * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
	 */
	public function scmDownloadGetFromXLS(Demarche $demarche, DemarcheSCM $scmFile) {
		if (! $demarche->canManage() || $demarche->id != $scmFile->demarche_id)
			return $this->redirectNoRight ();
		ob_clean ();
		return Response::download ( $scmFile->getFilePath (), $scmFile->filename, array (
				"Content-Type" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" 
		) );
	}
	
	/**
	 * Ecran d'upload de SCM Light
	 *
	 * @param Demarche $demarche
	 * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
	 */
	public function scmUploadGetFile(Demarche $demarche) {
		if(!$demarche->canManage()) return $this->redirectNoRight ();
			// LOGIQUE:
			// L'upload de fichier XLSX permet de mettre à jour les chiffres des pièces.
			// Cela ne permet pas (encore) de créer des pièces à la volée dans le catalogue ou autre. Juste de la mise à jour
			// Donc via cet écran on peut recevoir un fichier XLSX donc le format est bien défini (logiquement, un fichier téléchargé depuis Synapse)
			// Une fois le fichier vérifié, on affichera les différentes modifications détectées et on demandera de valider oui/non les màj
		return $this->makeDetailView ( $demarche, 'admin/demarches/scm/upload' );
	}
	
	/**
	 * Cette fonction est appelée via un ajax (DropZone).
	 * Elle va recevoir un fichier en Input:: qu'on va traiter si correct.
	 * 
	 * @param Demarche $demarche
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function scmUploadPostFile(Demarche $demarche) {
		
		// La vérif de token se fait en amont (filters)
		try {
			// Peut on manipuler ce fichier?
			if(!$demarche->canManage()) {
				return Response::json ( array (
						'error' => false,
						'return' => 'NOTALLOWED' 
				), 200 );
			}
			
			$file = Input::file ( 'file' );
			if ( !$file->isValid ()) {
				throw new \UnexpectedValueException ( $file->getErrorMessage() );
			}
			
			// On déplace le fichier dans les uploads, et on le renomme.
			// Le nom du fichier sera de la forme "SCM-<DEMARCHEID>-YYYYMMDDHHMMSS-<RANDOMSTRING(15)>.<EXTENSION>
			$fileName = "SCM-" . $demarche->id . "-" . Carbon::now ()->format ( "YmdHis" ) . "-" . str_random ( 15 ) . "." . $file->getClientOriginalExtension ();
			$destinationPath = storage_path () . '/uploads/scm';
			// Si le dossier de destination n'existe pas, on le crée
			if (! File::exists ( $destinationPath )) {
				File::makeDirectory ( $destinationPath, 0775 );
			}
			// Déplacement effectif du fichier (Exception levée en cas d'échec)
			$file->move ( $destinationPath, $fileName );
			// Enregistrement en DB
			$scm = new DemarcheSCM ();
			$scm->filename = $fileName;
			$scm->demarche_id = $demarche->id;
			$scm->user_id = $this->getLoggedUser ()->id;
			$scm->processed = 0;
			$scm->save ();
			
			/* LE TRAITEMENT DU FICHIER SE FERA PLUS TARD, DANS LA METHODE postSCMProcess() (appelée en POST par du jQuery) */
			
			return Response::json ( array (
					'error' => false,
					'return' => $fileName 
			), 200 );
		} catch ( Exception $ex ) {
			return Response::json ( array (
					'error' => true,
					'return' => $ex->getMessage () 
			), 400 );
		}
	}
	
	/**
	 * Traitement d'un fichier XLS de SCMLight uploadé par un utilisateur.
	 *
	 * NB : La vérif de token se fait en amont (filters)
	 * 
	 * @param Demarche $demarche
	 * @return \Illuminate\Http\JsonResponse
	 * @throws Exception
	 */
	public function scmUploadPostProcess(Demarche $demarche) {
		$scm = null;
		try {
			// A-t-on un fileName ?
			if (! Input::has ( 'fileName' ))
				throw new MissingMandatoryParametersException( "Aucun nom de fichier envoyé" );
				
				// La démarche et la démarche SCM existent-ils ?
			$scm = DemarcheSCM::where ( 'filename', '=', Input::get ( 'fileName' ) )->firstOrFail ();
			
			// Peut on manipuler ce fichier ?
			if(!$demarche->canManage() || $demarche->id!=$scm->demarche_id)
				throw new AccessDeniedException( "NOTALLOWED" );
				
				// Le fichier existe-t-il ?
			$completeFileName = $scm->getFilePath ();
			if (! File::exists ( $completeFileName ))
				throw new FileNotFoundException( "Fichier non trouvé : {$completeFileName}" );
				
				/*
			 * Structure des résultats retournés à l'utilisateur :
			 * processedLines : line
			 * numéro de ligne dans le fichier excel
			 * action
			 * - "processed" (traité)
			 * - "unprocessed" (non traité)
			 * - "error" (en erreur)
			 * type
			 * "piece" (pièce connue)
			 * "task" (tache connue)
			 * "totals" (totaux)
			 * "unknown" (non connu mais pris en compte)
			 * result (si "processed")
			 * dépend du type :
			 * - totals : array avec le nouveau gain effectif et le nouveau gain potentiel de la démarche
			 */
			$return ['processedLines'] = array ();
			
			/*
			 * Début du traitement !
			 */
			
			// Identifier le type de fichier (xls(x/m))
			$inputFileType = PHPExcel_IOFactory::identify ( $completeFileName );
			
			// Créer un reader du bon type
			$objReader = PHPExcel_IOFactory::createReader ( $inputFileType );
			
			// Charger
			$objPHPExcel = $objReader->load ( $completeFileName );
			$objWorksheet = $objPHPExcel->getActiveSheet ();
			$highestRow = $objWorksheet->getHighestRow ();
			$eofFound = false;
			$anyChangeMade = false; // utilisé pour savoir si au moins une ligne a été traitée dans le fichier. Si on a rien traité, on ne conservera pas le fichier
			
			/*
			 * BOUCLE GENERALE
			 * Fonctionnement :
			 * - On boucle sur les lignes jusqu'à rencontrer le marqueur #EOF# en colonne A
			 * - Pour chaque ligne,
			 * - on détecte si on a une "activité" en colonne A (pièce ou tâche)
			 * - On regarde alors si on a un ID
			 * - Si pas d'id, on ignore la ligne
			 * - Si Id valable, on acte une modification de pièce ou tâche
			 * - A la fin du fichier (#EOF#), on détermine si on doit créer une révision de démarche en fonction des totaux
			 * - Une fois terminé, on retourne les résultats à l'utilisateur
			 */
			
			for($row = 4; $row <= $highestRow; ++ $row) {
				// Sortie : Si on a atteint la valeur #EOF# dans la colonne A (D'ailleurs ... si on ne trouve pas ce marqueur avant la dernière ligne on lève une exception)
				if ($objWorksheet->getCell ( "A{$row}" )->getValue () == Lang::get ( 'admin/demarches/scmfiles.eof' )) {
					$eofFound = true;
					break;
				}
				
				$columns = scmexport_getDemarcheComponentColumns ();
				
				$demarcheComponentId = $objWorksheet->getCell ( "B{$row}" )->getValue ();
				if ($demarcheComponentId) {
					$type = null;
					/* @var DemarcheComponent $demarcheComponent */
					$demarcheComponent = null;
					
					// Quelle est le demarcheComponent
					$componentType=$objWorksheet->getCell ( "A{$row}" )->getValue ();
					switch (strtolower($componentType)) {
						case 'tache' :
						case 'tâche' :
						case 'tche' :
							$type = 'task';
							$demarcheComponent = DemarcheTask::find ( $demarcheComponentId );
							break;
						case 'piece' :
						case 'pièce' :
						case 'pice' :
							$type = 'piece';
							$demarcheComponent = DemarchePiece::find ( $demarcheComponentId );
							break;
						default: throw new \UnexpectedValueException("Type de composant inattendu : $componentType");
					}
					
					// on a trouvé un demarcheComponent valable à traiter
					if ($demarcheComponent) {
						$onError = false;
						foreach ( $columns as $colname => $colproperties ) {
							$cell = $objWorksheet->getCell ( "{$colproperties['pos']}$row" );
							if (! empty ( $colproperties ['calculated'] ))
								$val = $cell->getCalculatedValue ();
							else
								$val = $cell->getValue ();
							if ($colproperties ['type'] == 'int')
								$val = intval ( $val );
							else if ($colproperties ['type'] == 'dec')
								$val = number_format($val, 2, NumberHelper::INTERNAL_DECIMAL_SEP, ''); // Explicitement reformater en arrondissant à 2 décimales, au cas où des valeurs auraient été encodées ds l'excel avec + de 2 décimales (sinon ça fausse les calculs!)
							
							if ($colproperties ['type'] != 'char' && ! is_numeric ( $val )) {
								$onError = true;
								array_push ( $return ['processedLines'], [ 
										'line' => $row,
										'action' => 'error',
										'type' => $type,
										'result' => Lang::get('admin/demarches/scmfiles.process.data_error', ['column'=>$cell->getColumn(), 'value'=>$val])
								] );
							}
							$columns [$colname] ['val'] = $val;
						}
						
						// Si on a pas d'erreur, on continue
						if (! $onError) {
							$revision=$demarcheComponent->getLastRevision(true);
							$onChange = false;
							foreach ( $columns as $colname => $colproperties ) {
								$revisionval=$revision->$colname;
								if($colproperties ['type'] == 'dec') $revisionval=number_format($revisionval, 2, NumberHelper::INTERNAL_DECIMAL_SEP, '');
								if ($colproperties ['val'] != $revisionval) {
									$onChange = true;
									$demarcheComponent->addRevisionAttributes([$colname=>$colproperties ['val']]);
									array_push ( $return ['processedLines'], [
										'line' => $row,
										'action' => 'processed',
										'type' => $type,
										'result' => preg_replace ( [ 
											"/{{CHANGENAME}}/",
											"/{{NAME}}/",
											"/{{OLDVALUE}}/",
											"/{{NEWVALUE}}/" 
										],[ 
											Lang::get ( "admin/demarches/scmfiles.titles.{$colname}" ),
											$demarcheComponent->name,
											$revision->$colname,
											$colproperties ['val']
										], Lang::get ( "admin/demarches/scmfiles.process.change_{$type}" ) ) 
									]);
								}
							}
							
							if ($onChange) { // si une donnée à changé, on sauve cette nouvelle révision
								$anyChangeMade=$demarcheComponent->save ();
								if(!$anyChangeMade)
									Log::error("SCM Process : la sauvegarde de la {$type} {$demarcheComponent->name} n'a pas été effectué ! ".var_dump($demarcheComponent->errors()->toArray()));
							} else { // aucun changement, on notifie l'utilisateur
								array_push ( $return ['processedLines'], [ 
									'line' => $row,
									'action' => 'unprocessed',
									'type' => $type,
									'result' => str_replace ( "{{NAME}}", $demarcheComponent->name, Lang::get ( "admin/demarches/scmfiles.process.nochange_{$type}" ) ) 
								] );
							}
						}
					} else { // on n'a pas trouvé de demarcheComponent. On ne fait rien, mais on notifie l'utilisateur
						array_push ( $return ['processedLines'], [ 
							'line' => $row,
							'action' => 'unprocessed',
							'type' => $type,
							'result' => Lang::get ( "admin/demarches/scmfiles.process.{$type}_not_found" ) 
						] );
					}
				}
			} /* fin de la boucle générale */
			
			if (! $eofFound)
				throw new \ExceUnexpectedValueException( Lang::get ( 'admin/demarches/scmfiles.process.eof_not_found' ) );
				
				/*
			 * Gestion des totaux et de la création éventuelle d'une révision d'une démarche
			 */
			else {
				$revision = new DemarcheRevision ();
				$cellgains=[];
				foreach([
					'gain_potential_administration',
					'gain_real_administration',
					'gain_potential_citizen',
					'gain_real_citizen' 
				] as $gainName) {
					$cellgains[$gainName] = number_format($objWorksheet->getCell ( $columns [$gainName] ['pos'] . $row )->getCalculatedValue(), 2, NumberHelper::INTERNAL_DECIMAL_SEP, '');
				}
				$demarchegains=$demarche->getGains();
				foreach($cellgains as $gainName => $cellgain) {
					if (! $demarchegains->$gainName || $cellgain != number_format($demarchegains->$gainName, 2, NumberHelper::INTERNAL_DECIMAL_SEP, '')) {
						array_push ( $return ['processedLines'], [ 
							'lineNumber' => $row,
							'action' => 'processed',
							'type' => 'totals',
							'result' => preg_replace ( [ 
								"/{{FIELD}}/",
								"/{{CALCULATEDVALUE}}/",
								"/{{ADJUSTEDVALUE}}/" 
							],[
								Lang::get ( "admin/demarches/scmfiles.titles.$gainName" ),
								$demarchegains->$gainName,
								$cellgain
							],
							Lang::get ( 'admin/demarches/scmfiles.process.total_adjusted' ) ) 
						]);
						$revision->setAttribute ( $gainName, $cellgain );
					}
				}
				// Si des attributs ont été créés, on sauve ces valeurs actualisées dans une révision
				if ($revision->getAttributes ()) {
					$anyChangeMade = true;
					$revision->demarche_id = $demarche->id;
					$revision->user_id = $this->getLoggedUser ()->id;
					$revision->save ();
				}
			}
			
			// Si aucun changement n'a été fait, on supprime le fichier (aucun intérêt de le garder)
			if (! $anyChangeMade)
				$scm->delete ();
			return Response::json ( array (
					'error' => false,
					'return' => $return 
			), 200 );
		} catch ( Exception $ex ) {
			Log::error ( $ex );
			// on efface le fichier s'il existe (puisque ça c'est mal passé)
			if ($scm && $scm->id > 0)
				$scm->delete ();
			return Response::json ( array (
					'error' => true,
					'return' => $ex->getMessage () 
			), 200 ); // on renvoie qd meme un 200. on traitera l'erreur avec du jQuery (mais il n'y a pas eu d'erreur serveur en soi ...
		}
	}
	
	/**
	 * *********************************************************************************************************
	 * Gestion des composants
	 * *********************************************************************************************************
	 */
	
	/**
	 * Liste des composants d'une démarche
	 *
	 * @param Demarche $demarche
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
	 */
	public function getComponents(Demarche $demarche) {
		if(!$demarche->canManage()) return $this->redirectNoRight ();
		try {
			$nostraDemarche = NostraDemarche::findOrFail ( $demarche->nostra_demarche_id );
		}
		catch ( ModelNotFoundException $ex ) {
			Log::error ( $e );
			return Redirect::secure ( $this->getModel ()->routeGetIndex () )->with ( 'error', Lang::get ( 'admin/demarches/messages.create.baderror' ) . '<pre>' . $ex->getMessage () . '</pre>' );
		}
		return $this->makeDetailView ( $demarche, 'admin/demarches/components/list');
	}
	
	/**
	 * Récupérer la liste des pièces liées à une annexe, elle-même liée à un formulaire, lui-même lié à la démarche courante.
	 * Si certaines de ces pièces et tâches ne sont pas encore directement liées à la démarche courante, permettre de les ajouter.
	 * 
	 * @param Demarche $demarche
	 * @return \Illuminate\View\View
	 */
	public function piecesGetWarning(Demarche $demarche) {
		$aNotLinkedPieces=
		DB::table('demarchesPieces')
		->join('annexes', 'demarchesPieces.id', '=', 'annexes.piece_id')
		->join('v_lastrevisionannexes', 'v_lastrevisionannexes.annexe_id', '=', 'annexes.id')
		->join('v_lastrevisiondemarcheeform', 'v_lastrevisionannexes.eform_id', '=', 'v_lastrevisiondemarcheeform.eform_id')
		->where('v_lastrevisiondemarcheeform.demarche_id', '=', $demarche->id)
		->whereNull('demarchesPieces.deleted_at')
		->whereNull('v_lastrevisionannexes.deleted_at')
		->whereNull('v_lastrevisiondemarcheeform.deleted_at')
		->whereNotNull('annexes.piece_id')
		->whereNotIn('annexes.piece_id', function(\Illuminate\Database\Query\Builder $query) use($demarche) {
			$query
			->select('piece_id')
			->from('v_lastrevisionpiecesfromdemarche')
			->where('demarche_id', '=', $demarche->id)
			->whereNull('deleted_at');
		})
		->distinct()
		->orderBy('demarchesPieces.name')
		->get(['demarchesPieces.id', 'demarchesPieces.name']);
		
		if(empty($aNotLinkedPieces)) return Response::make();
		return View::make('admin/demarches/components/partial-warning', compact ('aNotLinkedPieces'));
	}
	
	/**
	 * *********************************************************************************************************
	 * Gestion des formulaires
	 * *********************************************************************************************************
	 */
	
	/**
	 * Liste des formulaires liés à une démarche au format json pour le datatable
	 *
	 * @param Demarche $demarche
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function eformsGetData(Demarche $demarche) {
		$manage=Input::get('manage');
		$minimal=Input::get('minimal');
		$rows=[];
		/*
		 * Note : Si un eform était soft-deleté cela donnerait une erreur car $item est un non object lorsqu'il a une date de suppression .
		 * Mais actuellement on interdit le soft-delete d'une pièce si elle est liée à une demarche-pièce => ce cas ne se produira pas tant que ce check sera souhaité.
		 */
		$states = DemarchePieceState::allKeyById ();
		foreach ( $demarche->getLastRevisionEforms () as $demarcheEform ) {
			/* @var DemarcheEform $demarcheEform */
			/* @var Eform $eform */
			$eform=$demarcheEform->eform;
			$annexes='';
			foreach($eform->getAnnexes() as $annexe_revision) {
				// Etats courant et suivant
				$state = '';
				if ($annexe_revision->current_state_id || $annexe_revision->next_state_id) {
					$state .= '<div class="state">';
					$state .= ($annexe_revision->current_state_id ? $states [$annexe_revision->current_state_id]->graphicState () : '?');
					$state .= ' <i class="fa fa-long-arrow-right"></i> ';
					$state .= ($annexe_revision->next_state_id ? $states [$annexe_revision->next_state_id]->graphicState () : '?');
					$state .= '</div>';
				}
				
				// Pièce ou tâche liée à l'annexe
				$related='';
				if($annexe_revision->piece_id) $related="<div><a href=\"#pieces\" title=\"".Lang::get ( 'admin/demarches/messages.piece.piece' )."\"><i class=\"fa fa-clipboard\"></i>{$annexe_revision->piece_name}</a></div>";
				
				// Picto éditer si pas de related
				$edit='';
				if(!$related && $eform->canManage()) $edit='<a title="'.Lang::get ( 'button.edit' ).'"class="btn btn-default btn-xs servermodal" href="'.route('eformsAnnexesGetEdit',[$eform->id, $annexe_revision->revision_id]).'"><span class="fa fa-pencil"></span></a>';
				
				$annexes.="<li><strong>{$annexe_revision->annexe_title}</strong>{$edit}{$related}{$state}</li>";
			}
			if($annexes)$annexes="<ul>{$annexes}</ul>";
			if ($minimal) {
				$rows[] = [
					$eform->name(),
					$annexes,
				];
			}
			else {
				$rows[] = [
					$eform->name(),
					$annexes,
					$demarcheEform->nostra_id?'#'.$demarcheEform->nostra_id:'',
					DateHelper::sortabledatetime($demarcheEform->created_at) . '<br/>' . $demarcheEform->user->username,
					(($manage && $demarcheEform->canManage()) ? '<a href="' . route('demarchesEformsGetDelete', [$demarche->id, $demarcheEform->id]) . '" title="' . Lang::get('button.delete') . '" class="delete btn btn-xs btn-danger servermodal"><span class="fa fa-trash-o"></span></a>' : '')
				];
			}
		}
		return Response::json (['aaData' => $rows], 200);
	}
	
	/**
	 * Affiche le formulaire de création d'un eform lié à une démarche
	 *
	 * @param Demarche $demarche
	 * @return \Illuminate\View\View
	 */
	public function eformsGetCreate(Demarche $demarche) {
		return $this->eformsGetManage($demarche);
	}
	
	/**
	 * Affiche le formulaire de création et édition d'un eform lié à une démarche
	 *
	 * @param Demarche $demarche
	 * @param DemarcheEform $demarche_eform
	 * @param MessageBag $errors
	 * @return \Illuminate\View\View
	 */
	private function eformsGetManage(Demarche $demarche, DemarcheEform $demarche_eform=null, MessageBag $errors=null) {
		$states = DemarchePieceState::allKeyById ();
		if(!$errors) $errors=new MessageBag();
		if($demarche_eform) {
			$aEforms[]=$demarche_eform->eform()->getResults();
			$aSuggestedEforms = null;
		}
		else {
			//on regarde les nostra_forms liés à cette démarche pour proposer ceux-ci en "suggérés".
			$aSuggestedEforms = $demarche->nostraDemarche->nostraForms()->getBaseQuery()
			->join('eforms', 'eforms.nostra_form_id', '=', 'nostra_forms.id')
			->join('v_lastrevisioneforms', 'eforms.id', '=', 'v_lastrevisioneforms.eform_id')
			->whereNull('v_lastrevisioneforms.deleted_at')
			->whereRaw("eforms.id NOT IN(SELECT eform_id FROM v_lastrevisiondemarcheeform WHERE demarche_id={$demarche->id} AND deleted_at IS NULL)")
			->orderby('title')
			->select(['eforms.id', DB::raw('COALESCE(nostra_forms.title, eforms.title) AS title'), 'nostra_forms.nostra_id as nostra_id', 'v_lastrevisioneforms.current_state_id', 'v_lastrevisioneforms.next_state_id'])->get();
			$aEforms=[];
			
			// et ici on prend les autres formulaires non liés à un formulaire nostra
			$aEforms=Eform
			::leftjoin('v_lastrevisioneforms', 'eforms.id', '=', 'v_lastrevisioneforms.eform_id')
			->whereNull('v_lastrevisioneforms.deleted_at')
			->whereRaw("eforms.id NOT IN(SELECT eform_id FROM v_lastrevisiondemarcheeform WHERE demarche_id={$demarche->id} AND deleted_at IS NULL)")
			->whereNull('eforms.nostra_form_id')
			->orderby('title')
			->select(['eforms.id', 'eforms.title', 'v_lastrevisioneforms.current_state_id', 'v_lastrevisioneforms.next_state_id'])->get();
		}
		$states=DemarchePieceState::allKeyById();
		return View::make ( 'admin/demarches/components/eforms/modal-manage', compact ( 'demarche', 'demarche_eform', 'aEforms', 'aSuggestedEforms', 'errors', 'states' ) );
	}
	
	/**
	 * Création d'un eform lié à une démarche
	 *
	 * @param Demarche $demarche
	 * @return \Illuminate\View\View
	 */
	public function eformsPostCreate(Demarche $demarche) {
		return $this->eformsPostManage($demarche);
	}
	
	/**
	 * Création ou édition d'un eform lié à une démarche
	 *
	 * @param Demarche $demarche
	 * @param DemarcheEform $demarche_eform
	 * @return \Illuminate\View\View
	 */
	private function eformsPostManage(Demarche $demarche, DemarcheEform $demarche_eform=null) {
		if(!$demarche->canManage()) return $this->serverModalNoRight();
		try {
			$revision=new DemarcheEform();
			$revision->demarche()->associate($demarche);
				
			$eform_id=Input::get('eform_id');
			if($eform_id) $revision->eform()->associate(Eform::find($eform_id));
			
			$revision->comment=Input::get('comment');
			$revision->user()->associate($this->getLoggedUser());
			if($revision->save()) {
				return $this->actionsRenderTriggerUpdate($demarche, 'eform', $eform_id);
			}
			else {
				return $this->eformsGetManage($demarche, $demarche_eform, $revision->errors());
			}
		}
		catch ( Exception $e ) {
			Log::error ( $e );
			return View::make('notifications', ['error'=>Lang::get('general.baderror',['exception'=>$e->getMessage()])]);
		}
	}
	
	/**
	 * Demande de suppression d'un eform lié à une démarche
	 *
	 * @param Demarche $demarche
	 * @param DemarcheEform $demarche_eform
	 * @return \Illuminate\View\View
	 */
	public function eformsGetDelete(Demarche $demarche, DemarcheEform $demarche_eform) {
		return View::make ( 'servermodal.delete', ['url'=>route('demarchesEformsPostDelete', [$demarche->id, $demarche_eform->id])]);
	}
	
	/**
	 * Suppression d'un eform lié à une démarche
	 *
	 * @param Demarche $demarche
	 * @param DemarcheEform $demarche_eform
	 * @return \Illuminate\View\View
	 */
	public function eformsPostDelete(Demarche $demarche, DemarcheEform $demarche_eform) {
		if(!$demarche->canManage()) return $this->serverModalNoRight();
		try {
			if($demarche_eform->delete()) return $this->actionsRenderTriggerUpdate($demarche, 'eform', $demarche_eform->eform_id);
			return View::make('notifications', ['error'=>Lang::get('general.delete.error')]);
		}
		catch ( Exception $e ) {
			Log::error ( $e );
			return View::make('notifications', ['error'=>Lang::get('general.baderror',['exception'=>$e->getMessage()])]);
		}
	}
	
	/**
	 * *********************************************************************************************************
	 * Gestion des composants (pièces et tâches)
	 * *********************************************************************************************************
	 */
	
	/**
	 * Liste des pièces liées à une démarche au format json pour le datatable
	 *
	 * @param Demarche $demarche
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function piecesGetData(Demarche $demarche) {
		return $this->componentsGetData($demarche, 'piece', DemarchePieceState::allKeyById(), $demarche->getLastRevisionPieces());
	}
	
	/**
	 * Liste des tâches liées à une démarche au format json pour le datatable
	 *
	 * @param Demarche $demarche
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function tasksGetData(Demarche $demarche) {
		return $this->componentsGetData($demarche, 'task', DemarcheTaskState::allKeyById(), $demarche->getLastRevisionTasks());
	}
	
	/**
	 * 
	 * @param Demarche $demarche
	 * @param string $type
	 * @param DemarcheComponentState $states
	 * @param DemarcheComponent[] $demarche_components
	 * @return \Illuminate\Http\JsonResponse
	 */
	private function componentsGetData(Demarche $demarche, $type, $states, $demarche_components) {
		/*
		 * Note : Si un composant était soft-deleté cela donnerait une erreur car $item est un non object lorsqu'elle a une date de suppression .
		 * Mais actuellement on interdit le soft-delete d'un composant s'il est lié à une demarche => ce cas ne se produira pas tant que ce check sera souhaité.
		 */
		$ptype=str_plural($type);
		$uctype=ucfirst($type);
		$manage=Input::get('manage');
		$minimal=Input::get('minimal');
		$rows=[];
		
		/* @var DemarcheComponent $model */
		$model=$demarche->$ptype()->getRelated();
		foreach ( $demarche_components as $item ) {
			$col="demarche_demarche{$uctype}_id";
			$model->id=$item->$col; // Mettre "artificiellement" l'ID sur ce modèle afin de pouvoir invoquer les méthodes de génération de route, qui ont besoin de cet ID
			$state = '';
			if ($item->current_state_id || $item->next_state_id) {
				$state .= '<div class="state">';
				$state .= ($item->current_state_id ? $states [$item->current_state_id]->graphicState () : '?');
				$state .= ' <i class="fa fa-long-arrow-right"></i> ';
				$state .= ($item->next_state_id ? $states [$item->next_state_id]->graphicState () : '?');
				$state .= '</div>';
			}
			if ($minimal) {
				$rows[] = [
					"<strong>{$item->name}</strong> " . (strlen($item->comment) ? "<i class=\"fa fa-comment\" data-placement=\"right\" data-toggle=\"tooltip\" title=\"" . str_replace('"', '', $item->comment) . "\"></i>{$state}" : ''),
					NumberHelper::numberFormat($item->frequency) . ' x ' . NumberHelper::numberFormat($item->volume),
					NumberHelper::moneyFormat($item->gain_potential_administration),
					NumberHelper::moneyFormat($item->gain_potential_citizen),
				];
			}
			else {
				$rows[] = [
					"<strong>{$item->name}</strong> " . (strlen($item->comment) ? "<i class=\"fa fa-comment\" data-placement=\"right\" data-toggle=\"tooltip\" title=\"" . str_replace('"', '', $item->comment) . "\"></i>{$state}" : ''),
					NumberHelper::moneyFormat($item->cost_administration_currency),
					NumberHelper::moneyFormat($item->cost_citizen_currency),
					NumberHelper::numberFormat($item->volume),
					NumberHelper::numberFormat($item->frequency),
					NumberHelper::moneyFormat($item->gain_potential_administration),
					NumberHelper::moneyFormat($item->gain_real_administration),
					NumberHelper::moneyFormat($item->gain_potential_citizen),
					NumberHelper::moneyFormat($item->gain_real_citizen),
					DateHelper::sortabledatetime($item->created_at) . '<br/>' . $item->username,
					(
						'<a href="' . $model->routeGetHistory(['demarche' => $demarche->id, 'manage' => $manage]) . '" title="' . Lang::get('button.historical') . '" class="btn btn-xs btn-default servermodal"><span class="fa fa-clock-o"></span></a>' .
						(
						(!$manage) ? '' :
							'<a href="' . $model->routeGetEdit(['demarche' => $demarche->id]) . '" title="' . Lang::get('button.edit') . '" class="btn btn-xs btn-default servermodal"><span class="fa fa-pencil"></span></a>' .
							'<a href="' . $model->routeGetDelete(['demarche' => $demarche->id]) . '" title="' . Lang::get('button.delete') . '" class="btn btn-xs btn-danger servermodal"><span class="fa fa-trash-o"></span></a>'
						)
					)
				];
			}
		}
		return Response::json(['aaData' => $rows], 200);
	}
	
	/**
	 * Affiche le formulaire de création d'une pièce liée à une démarche
	 *
	 * @param Demarche $demarche
	 * @return \Illuminate\View\View
	 */
	public function piecesGetCreate(Demarche $demarche) {
		return $this->componentsGetManage($demarche, new DemarchePiece());
	}
	
	/**
	 * Affiche le formulaire de création d'une tâche liée à une démarche
	 *
	 * @param Demarche $demarche
	 * @return \Illuminate\View\View
	 */
	public function tasksGetCreate(Demarche $demarche) {
		return $this->componentsGetManage($demarche, new DemarcheTask());
	}
	
	/**
	 * Affiche le formulaire d'édition d'une pièce liée à une démarche
	 *
	 * @param Demarche $demarche_component
	 * @param DemarchePiece $demarche_piece
	 * @return \Illuminate\View\View
	 */
	public function piecesGetEdit(Demarche $demarche, DemarchePiece $demarche_component) {
		return $this->componentsGetManage($demarche, $demarche_component);
	}
	
	/**
	 * Affiche le formulaire d'édition d'une tâche liée à une démarche
	 *
	 * @param Demarche $demarche
	 * @param DemarchePiece $demarche_component
	 * @return \Illuminate\View\View
	 */
	public function tasksGetEdit(Demarche $demarche, DemarcheTask $demarche_component) {
		return $this->componentsGetManage($demarche, $demarche_component);
	}
	
	/**
	 * Affiche le formulaire de création et édition d'une pièce liée à une démarche
	 *
	 * @param Demarche $demarche
	 * @param DemarcheComponent $demarche_component
	 * @param MessageBag|null $errors
	 * @return \Illuminate\View\View
	 */
	private function componentsGetManage(Demarche $demarche, DemarcheComponent $demarche_component, $errors=null) {
		$relatedComponent=$demarche_component->component()->getRelated();
		$states=$demarche_component->current_state()->getRelated()->allKeyById(); // Un peu tordu d'accéder à une méthode statique via un contexte d'instance, mais bon ça passe...
		if(!$errors) $errors=new MessageBag();
		$action='edit';
		$restoring=false;
		$aLinks=$relatedComponent->query()->orderBy('name', 'ASC')->get (['id','name','description']);
		$component_description='';
		// On est en création
		if(!$demarche_component->id) {
			$componentId=Input::get('componentId');
			$name=StringHelper::getStringOrNull(Input::get('name'));
			$component = $componentId ? $relatedComponent->findOrFail(Input::get('componentId')) : null;/* @var Component $component */
			if(!$component || !$name) {
				$action='choose';
			}
			else {
				$component_description=$component->description;
				$deleted_demarche_component=$component->demarcheComponents()->getQuery()->onlyTrashed()->where('demarche_id', '=', $demarche->id)->where('name', '=', StringHelper::getStringOrNull(Input::get('name')))->first();
				if($deleted_demarche_component) {
					// Si on a trouvé un demarche_component existant mais soft-deleté, on convertit la création en édition
					$demarche_component=$deleted_demarche_component;
					$revision=$demarche_component->getLastRevision(true);
					$restoring=true;
				}
				// Si vraiment il n'y avait pas de demarche_component soft-deleté, on va tout de même esayer de pré-remplir les coûts avec les valeurs venant du composant lié, et mettre à 0 les gains effectifs
				else {
					$action='create';
					$revision=$demarche_component->revisions()->getRelated()->newInstance();
					$revision->cost_administration_currency=$component->cost_administration_currency;
					$revision->cost_citizen_currency=$component->cost_citizen_currency;
					$revision->gain_real_administration=0;
					$revision->gain_real_citizen=0;
				}
			}
		}
		// On est en édition
		else {
			$component_description=$demarche_component->component()->getResults()->description;
			$revision=$demarche_component->getLastRevision(true);
		}

		$returnTo = $this->getReturnTo();

		return View::make ( 'admin/demarches/components/modal-manage', compact ( 'demarche', 'demarche_component', 'revision', 'component_description', 'aLinks', 'errors', 'states', 'action', 'restoring', 'returnTo') )->withErrors($errors);
	}
	
	/**
	 * Crée une pièce liée à une démarche
	 *
	 * @param Demarche $demarche
	 * @return \Illuminate\View\View
	 */
	public function piecesPostCreate(Demarche $demarche) {
		return $this->componentsPostManage($demarche, new DemarchePiece());
	}
	
	/**
	 * Crée une tâche liée à une démarche
	 *
	 * @param Demarche $demarche
	 * @return \Illuminate\View\View
	 */
	public function tasksPostCreate(Demarche $demarche) {
		return $this->componentsPostManage($demarche, new DemarcheTask());
	}
	
	/**
	 * Met à jour une pièce liée à une démarche
	 *
	 * @param Demarche $demarche
	 * @param DemarchePiece $demarche_component
	 * @return \Illuminate\View\View
	 */
	public function piecesPostEdit(Demarche $demarche, DemarchePiece $demarche_component) {
		return $this->componentsPostManage($demarche, $demarche_component);
	}
	
	/**
	 * Met à jour une tâche liée à une démarche
	 *
	 * @param Demarche $demarche
	 * @param DemarchePiece $demarche_component
	 * @return \Illuminate\View\View
	 */
	public function tasksPostEdit(Demarche $demarche, DemarcheTask $demarche_component) {
		return $this->componentsPostManage($demarche, $demarche_component);
	}
	
	/**
	 * Crée ou met à jour un composant lié à une démarche
	 * 
	 * @param Demarche $demarche
	 * @param DemarcheComponent $demarche_component
	 * @return \Illuminate\View\View
	 */
	private function componentsPostManage(Demarche $demarche, DemarcheComponent $demarche_component) {
		if(!$demarche->canManage()) return $this->serverModalNoRight();
		$errors=new MessageBag();
		$action=Input::get('action');
		$componentId=Input::get('componentId');
		$name=StringHelper::getStringOrNull(Input::get('name'));
		$component = $componentId ? $demarche_component->component()->getRelated()->findOrFail(Input::get('componentId')) : null;
		
		/*
		 * Si on est en création, associer la démarche et le composant au composant de démarche
		 * nb : Important de l'initialiser avant la validation, car le Validator utilise ces 2 valeurs pour la contrainte d'unicité multiple
		 */
		if(!$demarche_component->id) {
			$demarche_component->demarche()->associate($demarche);
			// Le faire "salement" car dans ce cas $componentId pourrait être vide...
			$demarche_component->setAttribute($demarche_component->componentColumn(), $componentId);
		}
		else {
			$demarche_component->deleted_at=null; // Au cas où on a restauré un demarche_component qui était soft-deleté
		}
		
		$demarche_component->name=$name;
		
		DB::beginTransaction();
		
		// Si on est à l'étape du choix d'un composant, vérifier explicitement que le lien et le name sont bien remplis
		if($action=='choose') {
			Input::flash();
			// Vérifier les contraintes de validation spécifiques à la phase de sélection de pièce ou tâche
			$validator=Validator::make(['componentId'=>$componentId,'name'=>$name], $demarche_component->formRulesChoose(), $demarche_component->formRulesMessages());
			if ($validator->fails ()) {
				return $this->componentsGetManage($demarche, $demarche_component, $validator->errors());
			}
			// Pas d'erreur, on passe à l'étape 2
			return $this->componentsGetManage($demarche, $demarche_component);
		}
		
		$revisionAttributes=[];
		
		// Forcer la date de création de la future révision
		$created_at = Input::get ( 'created_at' );
		if ($created_at) {
			$created_at = DateTime::createFromFormat ( 'd/m/Y H:i', $created_at );
			$created_at = $created_at->format ( 'Y-m-d H:i:s' );
			// Note : Ne pas permettre d'enregistrer 2 composants avec la même date de création, démarche et id de composant
			// TODO Ce serait sympa d'arriver à mettre ce test dans les validations du modèle afin que ce soit checké automatiquement à la sauvegarde
			if ($demarche_component->revisions()->getQuery()->withTrashed ()->where('created_at', '=', $created_at)->count()>0) {
				$errors->add('created_at', Lang::get ( 'admin/demarches/messages.'.$demarche_component->componentType().'.created_exists' ));
				Input::flash();
				return $this->componentsGetManage($demarche, $demarche_component, $errors);
			}
			$revisionAttributes['created_at']=$created_at;
		}
		
		// Ajouter les différents inputs dans les attributs utilisés lors de la création de la révision
		foreach(['current_state_id', 'next_state_id', 'comment'] as $param)
			$revisionAttributes[$param]=StringHelper::getStringOrNull(Input::get($param));
		foreach([
			'volume',
			'frequency',
			'cost_administration_currency',
			'cost_citizen_currency',
			'gain_real_administration',
			'gain_real_citizen',
		]
		as $param) $revisionAttributes[$param]=Input::get($param);
		$demarche_component->addRevisionAttributes($revisionAttributes);
		
		// Vérifier les contraintes de validation;
		$validator = Validator::make(Input::all(), $demarche_component->formRules(), $demarche_component->formRulesMessages());
		if ($validator->fails ()) {
			Input::flash();
			return $this->componentsGetManage($demarche, $demarche_component, $validator->errors());
		}
		
		// Sauvegarder le composant de démarche
		if($demarche_component->save()) {
			DB::commit();
			$componentType=$demarche_component->componentType();
			$componentId=$demarche_component->id; // Note : Bien que ça s'appelle component, c'est bien un id de demarcheComponent qu'il faut passer !
			if($componentGains=$demarche_component->gainsToAdjust()) {
				return View::make ( 'admin/demarches/modal-adjust-gains', compact ( 'demarche', 'componentGains', 'componentType', 'componentId') );
			}
			return $this->actionsRenderTriggerUpdate($demarche, $componentType, $componentId);
		}
		
		Input::flash();
		return $this->componentsGetManage($demarche, $demarche_component, $demarche_component->validationErrors);
	}
	
	/**
	 * Demande de suppression d'une pièce liée à une démarche
	 *
	 * @param Demarche $demarche
	 * @param DemarchePiece $demarche_component
	 * @return \Illuminate\View\View
	 */
	public function piecesGetDelete(Demarche $demarche, DemarchePiece $demarche_component) {
		return View::make ( 'servermodal.delete', ['url'=>$demarche_component->routePostDelete(['demarche'=>$demarche->id])]);
	}
	
	/**
	 * Demande de suppression d'une tâche liée à une démarche
	 *
	 * @param Demarche $demarche
	 * @param DemarcheTask $demarche_component
	 * @return \Illuminate\View\View
	 */
	public function tasksGetDelete(Demarche $demarche, DemarcheTask $demarche_component) {
		return View::make ( 'servermodal.delete', ['url'=>$demarche_component->routePostDelete(['demarche'=>$demarche->id])]);
	}
	
	/**
	 * Suppression d'une pièce liée à une démarche
	 *
	 * @param Demarche $demarche
	 * @param DemarchePiece $demarche_component
	 * @return \Illuminate\View\View
	 */
	public function piecesPostDelete(Demarche $demarche, DemarchePiece $demarche_component) {
		return $this->componentsPostDelete($demarche, $demarche_component);
	}
	
	/**
	 * Suppression d'une pièce tâche à une démarche
	 *
	 * @param Demarche $demarche
	 * @param DemarcheTask $demarche_component
	 * @return \Illuminate\View\View
	 */
	public function tasksPostDelete(Demarche $demarche, DemarcheTask $demarche_component) {
		return $this->componentsPostDelete($demarche, $demarche_component);
	}
	
	/**
	 * Suppression d'un composant lié à une démarche
	 *
	 * @param Demarche $demarche
	 * @param DemarcheComponent $demarche_component
	 * @return \Illuminate\View\View
	 */
	private function componentsPostDelete(Demarche $demarche, DemarcheComponent $demarche_component) {
		if(!$demarche->canManage()) return $this->serverModalNoRight();
		try {
			if($demarche_component->delete()) {
				if($componentGains=$demarche_component->gainsToAdjust('delete')) {
					$componentType=$demarche_component->componentType();
					$componentId=$demarche_component->id;
					return View::make ( 'admin/demarches/modal-adjust-gains', compact ( 'demarche', 'componentGains', 'componentType', 'componentId') );
				}
				return $this->actionsRenderTriggerUpdate($demarche, $demarche_component->componentType(), $demarche_component->id);
			}
			return View::make('notifications', ['error'=>Lang::get('general.delete.error')]);
		}
		catch ( Exception $e ) {
			Log::error ( $e );
			return View::make('notifications', ['error'=>Lang::get('general.baderror',['exception'=>$e->getMessage()])]);
		}
	}
	
	/**
	 * Modale affichant l'historique des versions d'une pièce liée à une démarche
	 *
	 * @param Demarche $demarche
	 * @param DemarchePiece $demarche_component
	 * @return \Illuminate\View\View
	 */
	public function piecesGetHistory(Demarche $demarche, DemarchePiece $demarche_component) {
		$manage=Input::get('manage');
		return View::make ( 'admin/demarches/components/modal-history', compact('demarche', 'demarche_component', 'manage'));
	}
	
	/**
	 * Modale affichant l'historique des versions d'une tâche liée à une démarche
	 *
	 * @param Demarche $demarche
	 * @param DemarcheTask $demarche_component
	 * @return \Illuminate\View\View
	 */
	public function tasksGetHistory(Demarche $demarche, DemarcheTask $demarche_component) {
		$manage=Input::get('manage');
		return View::make ( 'admin/demarches/components/modal-history', compact('demarche', 'demarche_component', 'manage'));
	}
	
	/**
	 * Historique des versions d'une pièce liée à une démarche au format json pour le datatable
	 *
	 * @param Demarche $demarche
	 * @param Piece $component
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function piecesGetHistoryData(Demarche $demarche, Piece $component) {
		return $this->componentsGetHistoryData($demarche, $component, DemarchePieceState::allKeyById ());
	}
	
	/**
	 * Historique des versions d'une tâche liée à une démarche au format json pour le datatable
	 *
	 * @param Demarche $demarche
	 * @param Task $component
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function tasksGetHistoryData(Demarche $demarche, Task $component) {
		return $this->componentsGetHistoryData($demarche, $component, DemarcheTaskState::allKeyById ());
	}
	
	/**
	 * Historique des versions d'un composant lié à une démarche, au format json pour le datatable
	 *
	 * @param Demarche $demarche
	 * @param Component $component
	 * @param DemarcheComponentState $states
	 * @return \Illuminate\Http\JsonResponse
	 */
	private function componentsGetHistoryData(Demarche $demarche, Component $component, $states) {
		$manage=Input::get('manage');
		$name=Input::get('name');
		$rows=[];
		
		$demarche_component=$component->demarcheComponents()->getQuery()->where('demarche_id', '=', $demarche->id)->where('name', '=', $name)->first();/* @var DemarcheComponent $demarche_component */
		if($demarche_component) {
			$relrev=$demarche_component->revisions();
			$tbl=$relrev->getRelated()->getTable();
			foreach($relrev->getQuery()->leftJoin('users', 'users.id', '=', 'user_id')->withTrashed()->orderBy("$tbl.created_at", 'DESC')->get(["$tbl.*", 'users.username']) as $item ) {/* @var DemarcheComponentRevision $item */
				$state = '';
				if($item->current_state_id || $item->next_state_id) {
					$state .= '<div class="state">';
					$state .= ($item->current_state_id ? $states [$item->current_state_id]->graphicState () : '?');
					$state .= ' <i class="fa fa-long-arrow-right"></i> ';
					$state .= ($item->next_state_id ? $states [$item->next_state_id]->graphicState () : '?');
					$state .= '</div>';
				}
				$row=[
					($item->deleted_at ? '<span class="label label-danger">Supprimé</span>' : '') . $item->comment . $state,
					NumberHelper::moneyFormat ( $item->cost_administration_currency ),
					NumberHelper::moneyFormat ( $item->cost_citizen_currency ),
					NumberHelper::numberFormat ( $item->volume ),
					NumberHelper::numberFormat ( $item->frequency ),
					NumberHelper::moneyFormat ( $item->gain_potential_administration ),
					NumberHelper::moneyFormat ( $item->gain_real_administration ),
					NumberHelper::moneyFormat ( $item->gain_potential_citizen ),
					NumberHelper::moneyFormat ( $item->gain_real_citizen ),
					$item->deleted_at ? DateHelper::sortabledatetime ( $item->deleted_at ) : DateHelper::sortabledatetime ( $item->created_at ) . '<br/>' . $item->username
				];
				if ($manage) $row[]='<a href="' . $item->routeGetDestroy(['demarche'=>$demarche->id, 'component'=>$component->id]) . '" title="' . Lang::get ( 'button.destroy' ) . '" class="destroy btn btn-xs btn-danger servermodal"><span class="fa fa-times"></span></a>';
				$rows[]=$row;
			}
		}
		return Response::json (['aaData' => $rows], 200 );
	}
	
	/**
	 * Demande de suppression d'une pièce liée à une démarche
	 *
	 * @param Demarche $demarche
	 * @param Piece $component
	 * @param DemarchePieceRevision $revision
	 * @return \Illuminate\View\View
	 */
	public function piecesGetDestroy(Demarche $demarche, Piece $component, DemarchePieceRevision $revision) {
		return View::make ( 'servermodal.delete', ['url'=>$revision->routePostDestroy(['demarche'=>$demarche->id, 'piece'=>$component->id])]);
	}
	
	/**
	 * Demande de suppression d'une tâche liée à une démarche
	 *
	 * @param Demarche $demarche
	 * @param Task $component
	 * @param DemarcheTaskRevision $revision
	 * @return \Illuminate\View\View
	 */
	public function tasksGetDestroy(Demarche $demarche, Task $component, DemarcheTaskRevision $revision) {
		return View::make ( 'servermodal.delete', ['url'=>$revision->routePostDestroy(['demarche'=>$demarche->id, 'task'=>$component->id])]);
	}
	
	/**
	 * Destruction d'une révision d'une pièce liée à une démarche
	 *
	 * @param Demarche $demarche
	 * @param Piece $component
	 * @param DemarchePieceRevision $revision
	 * @return \Illuminate\View\View
	 */
	public function piecesPostDestroy(Demarche $demarche, Piece $component, DemarchePieceRevision $revision) {
		return $this->componentsPostDestroy($demarche, $component, $revision);
	}
	
	/**
	 * Destruction d'une tâche liée à une démarche
	 *
	 * @param Demarche $demarche
	 * @param Task $component
	 * @param DemarcheTaskRevision $revision
	 * @return \Illuminate\View\View
	 */
	public function tasksPostDestroy(Demarche $demarche, Task $component, DemarcheTaskRevision $revision) {
		return $this->componentsPostDestroy($demarche, $component, $revision);
	}
	
	/**
	 * Destruction d'une révision d'un composant lié à une démarche
	 *
	 * @param Demarche $demarche
	 * @param Component $component
	 * @param DemarcheComponentRevision $revision
	 * @return \Illuminate\View\View
	 */
	private function componentsPostDestroy(Demarche $demarche, Component $component, DemarcheComponentRevision $revision) {
		if(!$demarche->canManage()) return $this->serverModalNoRight();
		try {
			$demarche_component=$revision->revisable()->getResults(); /*@var DemarcheComponent $demarche_component */
			$componentGains=$demarche_component->gainsToAdjust('destroy');
			$componentType=$demarche_component->componentType();
			$componentId=$demarche_component->id;
			
			$revision->forceDelete();
			// Si la révision était la dernière liée à l'action, destroy du DemarcheComponent également
			if (! $demarche_component->getLastRevision (true))
				$demarche_component->forceDelete();
			
			if($componentGains)
				return View::make ( 'admin/demarches/modal-adjust-gains', compact ( 'demarche', 'componentGains', 'componentType', 'componentId') );
			return $this->actionsRenderTriggerUpdate($demarche, $componentType, $componentId);
		}
		catch ( Exception $e ) {
			Log::error ( $e );
			return View::make('notifications', ['error'=>Lang::get('general.baderror',['exception'=>$e->getMessage()])]);
		}
	}
	
	
	/**
	 * *********************************************************************************************************
	 * Gestion des gains
	 * *********************************************************************************************************
	 */
	/**
	 * Mise à jour des gains pour une démarche
	 *
	 * @param Demarche $demarche
	 */
	public function postGains(Demarche $demarche) {
		if(!$demarche->canManage()) return $this->serverModalNoRight();
		$componentType=Input::get('componentType');
		$componentId=Input::get('componentId');
		if(Input::get('action')=='save') {
			try {
				$this->createDemarcheRevisionFromInput ( $demarche );
			}
			catch (Exception $e) {
				Log::error ($e);
				return View::make('admin/demarches/modal-adjust-gains', compact ( 'demarche', 'componentGains', 'componentType', 'componentId'))->with('error', $e->getMessage());
			}
		}
		return $this->actionsRenderTriggerUpdate($demarche, $componentType, $componentId);
	}
	
	/**
	 * Crée une révision de démarche si nécessaire
	 * 
	 * @param Demarche $demarche
	 * @return  boolean
	 */
	private function createDemarcheRevisionFromInput(Demarche $demarche) {
		$lastRevision=$demarche->getLastRevision();
		$revision=new DemarcheRevision();
		// Récupérer les gains passés en paramètres à la requête
		$gains=[];
		foreach([ 
			'gain_potential_administration',
			'gain_real_administration',
			'gain_potential_citizen',
			'gain_real_citizen' 
		] as $gainName) {
			if($lastRevision) $revision->$gainName=$lastRevision->$gainName; // Par défaut, considérer la valeur de la révision précédente (elle sera peut-être écrasée + bas)
			if (!Input::exists($gainName)) continue;
			$gain=Input::get($gainName);
			if(strlen($gain)>0)
				$gains[$gainName]=NumberHelper::stringTofloat($gain);
			elseif ($lastRevision && $lastRevision->$gainName!=null)
				$gains[$gainName]=null; // Vu que le paramètre a été passé mais avait une valeur vide, forcer une valeur vide si dans la dernière révision sauvegardée cette valeur était remplie
		}
		
		// Si on en a trouvé en paramètre, les comparer avec ceux de la démarche, et pour ceux qui sont différents les mettre à jour, sinon garder l'ancienne valeur
		if(!empty($gains)) {
			$revision->demarche()->associate($demarche);
			$revision->user()->associate($this->getLoggedUser());
			$calculatedGains=$demarche->getCalculatedGains ();
			foreach($gains as $gainName => $gain) {
				if(!$calculatedGains || !$calculatedGains->$gainName || $gain!=$calculatedGains->$gainName)
					$revision->setAttribute($gainName, $gain);
			}
			return $revision->save();
		}
		return false;
	}
	
	/**
	 * *********************************************************************************************************
	 * Gestion des actions
	 * *********************************************************************************************************
	 */
	
	/**
	 * Liste des actions liées à une démarche
	 *
	 * @param Demarche $demarche
	 *
	 */
	public function actionsGetIndex(Demarche $demarche) {
		if(!$demarche->canManage()) return $this->redirectNoRight ();
		return $this->makeDetailView ( $demarche, 'admin/demarches/actions/list', ['manage' => true]);
	}
	
	/**
	 * Liste des actions liées à une démarche au format json pour le datatable
	 *
	 * @param Demarche $demarche
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function actionsGetData(Demarche $demarche) {
		$manage=Input::get('manage');
		$minimal=Input::get('minimal');
		$rows=[];
		/*
		 * Note : Si une tâche était soft-deletée cela donnerait une erreur car $item est un non object lorsqu'elle a une date de suppression .
		 * Mais actuellement on interdit le soft-delete d'une tâche si elle est liée à une demarche-tâche => ce cas ne se produira pas tant que ce check sera souhaité.
		 */
		foreach ( EwbsAction::each()->forDemarche($demarche)->get() as $item ) {
			if ($minimal) {
				$rows[] = [
					'<strong>' . $item->name . '</strong><br/><em>' . $item->description . '</em>',
					EwbsActionRevision::graphicState($item->state),
					EwbsActionRevision::graphicPriority($item->priority),
					(
					$item->demarche_piece_name ? ('<span title="' . Lang::get('admin/demarches/messages.piece.piece') . '"><i class="fa fa-clipboard"></i>' . $item->demarche_piece_name . '</span>') :
						(
						$item->demarche_task_name ? ('<span title="' . Lang::get('admin/demarches/messages.task.task') . '"><i class="fa fa-tasks"></i>' . $item->demarche_task_name . '</span>') :
							(
							$item->eform_name ? ('<span title="' . Lang::get('admin/demarches/messages.eform.eform') . '"><i class="fa fa-wpforms"></i>' . $item->eform_name . '</span>') :
								''
							)
						)
					),
				];
			}
			else {
				$rows[] = [
					'<strong>' . $item->name . '</strong><br/><em>' . $item->description . '</em>',
					EwbsActionRevision::graphicState($item->state),
					EwbsActionRevision::graphicPriority($item->priority),
					(
					$item->demarche_piece_name ? ('<span title="' . Lang::get('admin/demarches/messages.piece.piece') . '"><i class="fa fa-clipboard"></i>' . $item->demarche_piece_name . '</span>') :
						(
						$item->demarche_task_name ? ('<span title="' . Lang::get('admin/demarches/messages.task.task') . '"><i class="fa fa-tasks"></i>' . $item->demarche_task_name . '</span>') :
							(
							$item->eform_name ? ('<span title="' . Lang::get('admin/demarches/messages.eform.eform') . '"><i class="fa fa-wpforms"></i>' . $item->eform_name . '</span>') :
								''
							)
						)
					),
					$item->responsible,
					DateHelper::sortabledatetime($item->created_at) . '<br/>' . $item->username,
					(
						'<a title="' . Lang::get('button.historical') . '" class="history btn btn-xs btn-default servermodal" href="' . route('demarchesActionsGetHistory', [$item->demarche_id, $item->action_id]) . '"><span class="fa fa-clock-o"></span></a>' .
						(
						(!$manage) ? '' : '<a title="' . Lang::get('button.edit') . '" class="edit btn btn-xs btn-default servermodal" href="' . route('demarchesActionsGetEdit', [$item->demarche_id, $item->action_id]) . '"><span class="fa fa-pencil"></span></a>' .
							'<a title="' . Lang::get('button.delete') . '" class="delete btn btn-xs btn-danger servermodal" href="' . route('demarchesActionsGetDelete', [$item->demarche_id, $item->action_id]) . '"><span class="fa fa-trash-o"></span></a>'
						)
					)
				];
			}
		}
		return Response::json (['aaData' => $rows], 200);
	}
	
	/**
	 * Affiche le formulaire de création d'une action liée à une démarche
	 *
	 * @param Demarche $demarche
	 * @return \Illuminate\View\View
	 */
	public function actionsGetCreate(Demarche $demarche) {
		return $this->actionsGetManage( $demarche );
	}
	
	/**
	 * Affiche le formulaire d'édition d'une action liée à une démarche
	 *
	 * @param Demarche $demarche
	 * @param EwbsAction $action
	 * @return \Illuminate\View\View
	 */
	public function actionsGetEdit(Demarche $demarche, EwbsAction $action) {
		return $this->actionsGetManage( $demarche, $action );
	}
	
	/**
	 * Affiche le formulaire de création et édition d'une action liée à une démarche
	 *
	 * @param Demarche $demarche
	 * @param EwbsAction $action
	 * @param array $extra Paramètre supplémentaires qui seraient à passer à la vue
	 * @return \Illuminate\View\View
	 */
	protected function actionsGetManage(Demarche $demarche, EwbsAction $action = null, array $extra=[]) {
		$edit=($action && $action->id);
		$aTaxonomy = TaxonomyCategory::orderBy('name')->get();
		$selectedTags = [];
		$aExpertises=Expertise::names($edit?$action->name():null);
		$aUsers= [];
		if ($action) {
			$selectedTags = $action->tags->lists('id');
			
			//FIXME : Il faudrait aussi ajouter les conditions nécessaires pour inclure le user supprimé qui serait en fait celui lié à l'action courante (afin que le lien ne se perde pas)
			$aUsers=User::query()->ewbsOrSelf()->get(['users.id', 'users.username']);
			
		}
		$returnTo = $this->getReturnTo();
		return View::make ( 'admin/demarches/actions/modal-manage', array_merge(compact('demarche', 'action', 'edit', 'aTaxonomy', 'selectedTags', 'aUsers', 'aExpertises', 'returnTo'), $extra));
	}
	
	/**
	 * Crée une action liée à une démarche
	 *
	 * @param Demarche $demarche
	 * @return \Illuminate\View\View
	 */
	public final function actionsPostCreate(Demarche $demarche) {
		return $this->actionsPostManage( $demarche, new EwbsAction () );
	}
	
	/**
	 * Met à jour une action liée à une démarche
	 *
	 * @param Demarche $demarche
	 * @param EwbsAction $action
	 * @return \Illuminate\View\View
	 */
	public final function actionsPostEdit(Demarche $demarche, EwbsAction $action) {
		return $this->actionsPostManage( $demarche, $action );
	}
	
	/**
	 * Crée ou à jour une action liée à une démarche
	 * 
	 * @param Demarche $demarche
	 * @param EwbsAction $action
	 * @return \Illuminate\View\View
	 */
	private function actionsPostManage(Demarche $demarche, EwbsAction $action) {
		if(!$demarche->canManage()) return $this->serverModalNoRight();
		
		$fromTriggerUpdate=Input::get('fromTriggerUpdate');
		if(Input::get ( 'action' )!='cancel') {
			try {
				$errors = new Illuminate\Support\MessageBag ();
				$validator = Validator::make ( Input::all (), $action->formRules () );
				if ($validator->fails ()) {
					$errors = $validator->errors ();
				} else {
					$lastRevision=$action->getLastRevision(true);
					$action->demarche()->associate($demarche);
					$action->name = Input::get ( 'name' );
					$piecetask = Input::get ( 'piecetask' );
					//Note : Attention, on part du principe que ce piecetask n'est plus modifié à l'édition (car sinon il faudrait en + vider les valeurs des autres liens si par ex. on prenait un formulaire au lieu d'une pièce)
					if($piecetask) {
						foreach ([
							'eform'=>'eform_id',
							'piece'=>'demarche_piece_id',
							'task' =>'demarche_task_id'
						] as $type=>$attribute ) {
							$id = str_replace ( $type, '', $piecetask );
							if ($id != $piecetask) { // Si l'ID est différent du paramètre piecetask c'est que le replace ci-dessus a retiré le type de la chaîne et qu'on agit donc sur le bon type
								$action->setAttribute ( $attribute, $id );
								break;
							}
						}
					}
					$priority=$lastRevision?$lastRevision->priority:null; // Par défaut celle de la précédente révision
					if($this->getLoggedUser()->can('ewbsaction_prioritize') && $p=Input::get('priority')) { // et SSI on a le droit et qu'elle est passée, on prend celle-ci
						$priority=$p;
					}
					
					$action->addRevisionAttributes ( [ 
						'description' => Input::get ( 'description' ),
						'state' => Input::get('state', ($lastRevision?$lastRevision->state:EwbsActionRevision::$STATE_TODO)),
						'priority'=>$priority,
						'responsible_id' => Input::get('responsible_id')
					] );

					if (! $action->save ()) {
						$errors = $action->errors();
					}
					else {
						//on sauve les tags
						$action->tags()->sync( is_array( Input::get('tags') ) ? Input::get('tags') : []);
					}
				}
				if (! $errors->isEmpty ()) {
					Input::flash();
					return $this->actionsGetManage($demarche, $action, ['errors'=>$errors]);
				}
			} catch ( Exception $e ) {
				Log::error ( $e );
				return View::make ( 'notifications', ['error'=>$e->getMessage ()] );
			}
		}
		// Nécessaire de proposer la maj des projets de simplif ?
		$aIdeas = $demarche->getIdeas ();
		if (count ( $aIdeas ) > 0) return View::make ( 'admin/demarches/ideas/modal-triggerupdate', compact ( 'demarche', 'aIdeas' ) );
		
		return View::make ( 'notifications', ['success'=>Lang::get('general.success')] );
	}
	
	/**
	 * Historique des versions d'une action liée à une démarche
	 *
	 * @param Demarche $demarche
	 * @param EwbsAction $action
	 * @return \Illuminate\View\View
	 */
	public function actionsGetHistory(Demarche $demarche, EwbsAction $action) {
		return View::make('admin/demarches/actions/modal-history', compact('action'));
	}
	
	/**
	 * Demande de suppression d'une action liée à une démarche
	 *
	 * @param Demarche $demarche
	 * @param EwbsAction $action
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function actionsGetDelete(Demarche $demarche, EwbsAction $action) {
		return View::make('servermodal.delete', ['url'=>route('demarchesActionsPostDelete', [$demarche->id, $action->id])]);
	}
	
	/**
	 * Suppression d'une action liée à une démarche
	 *
	 * @param Demarche $demarche
	 * @param EwbsAction $action
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function actionsPostDelete(Demarche $demarche, EwbsAction $action) {
		if(!$demarche->canManage()) return $this->serverModalNoRight();
		try {
			$action->delete ();
			return Response::make();
		} catch ( Exception $e ) {
			Log::error ( $e );
			return View::make('notifications', ['error'=>$e->getMessage () ]);
		}
	}
	
	/**
	 * Met à jour la ou les actions liées au composant passé en paramètre
	 * 
	 * @param Demarche $demarche
	 * @return View|\Illuminate\Http\RedirectResponse
	 */
	public final function actionsPostTriggerUpdate(Demarche $demarche) {
		if(!$demarche->canManage()) return $this->serverModalNoRight();
		$componentType=Input::get('componentType');
		$componentId=Input::get('componentId');
		try {
			$action=Input::get ( 'action' );
			if($action!='cancel') {
			
				// Regrouper depuis les paramètres de la requête les valeurs des états et commentaires par action
				$actions = array ();
				foreach ( Input::all () as $inputname => $inputvalue ) {
					foreach ( ['description', 'state'] as $field ) {
						if (strpos ( $inputname, $field ) !== 0) continue;
						$id = str_replace ( $field, '', $inputname );
						$actions [$id] [$field] = $inputvalue;
					}
				}
					
				// Mettre à jour les actions
				DB::beginTransaction();
				foreach ( $actions as $id => $values ) {
					$ewbsAction = EwbsAction::find ( $id ); /* @var $ewbsAction EwbsAction */
					$ewbsActionRevision=$ewbsAction->getLastRevision();
					
					// Si l'état ou le commentaire ont changé, création d'une nouvelle révision
					if ($values ['state'] != $ewbsActionRevision->state || $values ['description'] != $ewbsActionRevision->description) {
						$ewbsAction->addRevisionAttributes ([
							'description' => $values ['description'],
							'state' => $values ['state']
						]);
						if (! $ewbsAction->save ())
							return $this->actionsRenderTriggerUpdate($demarche, $componentType, $componentId, $ewbsAction->errors());
					}
				}
				DB::commit();
			}
			
			// Nécessaire de proposer la maj des projets de simplif ?
			$aIdeas = $demarche->getIdeas ();
			if (count ( $aIdeas ) > 0)
				return View::make ( 'admin/demarches/ideas/modal-triggerupdate', compact ( 'demarche', 'aIdeas' ) );
		} catch ( Exception $e ) {
			Log::error ( $e );
			return $this->actionsRenderTriggerUpdate($demarche, $componentType, $componentId, $e->getMessage());
		}
		
		return View::make ( 'notifications', ['success'=>Lang::get ( 'general.success' )] );
	}
	
	/**
	 * Affiche le formulaire "Assistant actions" permettant de mettre à jour la ou les actions liées à la pièce/tâche passée en paramètre
	 *
	 * Utilisé lorsqu'on vient de toucher à une pièce et tâche et que l'on veut adapter les actions concernées.
	 * A noter que lorsqu'aucune action n'est liée à la pièce/tâche, le formulaire proposé est celui de la création d'une action.
	 *
	 * @param Demarche $demarche
	 * @param string $componentType
	 * @param int $componentId
	 * @param string|null $error
	 * @return View
	 */
	private final function actionsRenderTriggerUpdate(Demarche $demarche, $componentType, $componentId, $error=null) {
		$query=$demarche->actions()->getQuery();
		if($componentType=='eform')
			$aActions=$query->where('eform_id', '=', $componentId)->get();
		elseif($componentType=='piece')
			$aActions=$query->where('demarche_piece_id', '=', $componentId)->get();
		elseif($componentType=='task')
			$aActions=$query->where('demarche_task_id', '=', $componentId)->get();
		else
			$aActions=null;
		
		// Pas d'action, on renvoit vers la création d'une action
		if(!$error && (!$aActions || count($aActions)==0)) {
			$action = new EwbsAction ();
			$action->demarche()->associate($demarche);
			if    ($componentType=='eform') $action->eform_id = $componentId;
			if    ($componentType=='piece') $action->demarche_piece_id = $componentId;
			elseif ($componentType=='task') $action->demarche_task_id  = $componentId;
			return $this->actionsGetManage($demarche, $action, ['fromTriggerUpdate'=>true]);
		}
		
		// Une ou +ieurs actions, on propose leur update
		// Avant cela, si les actions trouvées ont des sous-actions, on considère plutôt ces sous-actions.
		$aActionsAndSub=[];
		foreach($aActions as $action) {/* @var EwbsAction $action */
			$subactions=$action->subactions()->getResults();
			if($subactions->count()>0) {
				foreach($subactions as $subaction) {
					$aActionsAndSub[]=$subaction;
				}
			}
			else {
				$aActionsAndSub[]=$action;
			}
		}
		$aActions=$aActionsAndSub;
		
		$view=View::make('admin/demarches/actions/modal-triggerupdate', compact ( 'demarche', 'aActions', 'componentType', 'componentId'));
		if($error) {
			$view->with('error', $error);
			Input::flash ();
		}
		return $view;
	}
	
	
	
	/**
	 * *********************************************************************************************************
	 * Gestion des maj de projets depuis des changements effectués sur des actions
	 * *********************************************************************************************************
	 */
	
	/**
	 *
	 * @param Demarche $demarche
	 * @return View
	 */
	public function ideasGetTriggerUpdate(Demarche $demarche) {
		if(!$demarche->canManage()) return $this->serverModalNoRight();
		$aIdeas = $demarche->getIdeas ();
		return View::make ( 'admin/demarches/ideas/modal-triggerupdate', compact ( 'demarche', 'aIdeas' ) );
	}
	
	/**
	 *
	 * @param Demarche $demarche
	 * @return View
	 */
	public function ideasPostTriggerUpdate(Demarche $demarche) {
		if(!$demarche->canManage()) return $this->serverModalNoRight();
		try {
			// Regrouper depuis les paramètres de la requête les valeurs des commentaires et états par idée
			$ideas = array ();
			foreach ( Input::all () as $inputname => $inputvalue ) {
				foreach ( ['comment', 'state'] as $field ) {
					if (strpos ( $inputname, $field ) !== 0) continue;
					$id = str_replace ( $field, '', $inputname );
					$ideas [$id] [$field] = $inputvalue;
				}
			}
			
			// Mettre à jour les idées
			DB::beginTransaction();
			foreach ( $ideas as $id => $values ) {
				/* @var Idea $idea */
				$idea = Idea::find ( $id ); 
				
				// Si l'état a changé, maj de l'état avec le commentaire éventuel lié à l'état
				if ($values ['state'] != $idea->getLastStateModification ()->idea_state_id) {
					$ideaState = IdeaState::find ( $values ['state'] );
					$state = new IdeaStateModification ();
					$state->comment = $values ['comment'];
					$state->user ()->associate ( $this->getLoggedUser () );
					$state->ideaState ()->associate ( $ideaState );
					$state->idea ()->associate ( $idea );
					$state->save ();
				} 
				
				// Sinon, si un commentaire était complété, l'ajouter en tant que simple commentaire sur l'idée
				elseif ($values ['comment']) {
					$comment = new IdeaComment ();
					$comment->user_id = $this->getLoggedUser ()->id;
					$comment->comment = $values ['comment'];
					$idea->comments ()->save ( $comment );
				}
			}
			DB::commit();
		} catch ( Exception $e ) {
			Log::error ( $e );
			Input::flash ();
			$aIdeas = $demarche->getIdeas ();
			return View::make ( 'admin/demarches/ideas/modal-triggerupdate', compact ( 'demarche', 'aIdeas' ) )->with ( 'error', $e->getMessage () );
		}
		return View::make ( 'notifications', ['success'=>Lang::get ( 'general.success' )] );
	}
	
	/**
	 * Modale proposant de lier un projet à la démarche
	 *
	 * @param Demarche $demarche
	 * @return View
	 */
	public function ideasGetLink(Demarche $demarche) {
		$aIdeas=Idea
		::leftjoin('idea_nostra_demarche', function($join) use($demarche) {
			$join->on('idea_nostra_demarche.idea_id', '=', 'ideas.id');
			$join->where('idea_nostra_demarche.nostra_demarche_id', '=', $demarche->nostra_demarche_id);
		})
		->whereNull('idea_nostra_demarche.idea_id')
		->distinct()
		->get(['ideas.id', 'ideas.name', 'ideas.created_at']);
		return View::make ( 'admin/demarches/ideas/modal-link', compact ( 'demarche', 'aIdeas' ) );
	}
}
