<?php
use Illuminate\Support\MessageBag;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

class EformController extends TrashableModelController {
	
	/**
	 * Inject the models.
	 *
	 * @param Eform $model
	 */
	public function __construct(Eform $model) {
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
				'label' => Lang::get ( 'button.edit' ),
				'url' => $modelInstance->routeGetEdit(),
				'icon' => 'pencil'
			];
		}
		/*$features[]=[
			'label' => Lang::get ( 'admin/annexes/messages.menu' ),
			'url' => route('eformsAnnexesGetIndex', $modelInstance->id),
			'icon' => 'wpforms'
		];*/
		/*$features[]=[
			'label' => Lang::get ( 'admin/eforms/messages.revisions' ),
			'url' => route('eformsRevisionsGetIndex', $modelInstance->id),
			'icon' => 'road'
		];*/
		if($modelInstance->canDelete()) {
			$features[]=[
				'label' => Lang::get ( 'button.delete' ),
				'url' => $modelInstance->routeGetDelete(),
				'icon' => 'trash-o',
				'class' =>'btn-danger',
			];
		}
		return $features;
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getList()
	 */
	protected function getList($onlyTrashed=false) {
		$data=['trash' => $onlyTrashed, 'context' => 'documented', 'countDocumented' => 0, 'countUndocumented' => 0];
		if(!$onlyTrashed) {
			$data['countDocumented']=Eform::count();
			$data['countUndocumented']=NostraForm::whereNotIn('id', function ($query) {
				$query->select(DB::raw('COALESCE(nostra_form_id,0)'))->from('eforms');
			})->count();
		}
		return View::make ('admin/forms/eforms/list',$data);
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getDataJson()
	 */
	protected function getDataJson($onlyTrashed=false) {
		$array=[];
		foreach ( Eform::getLastRevisionEforms ($onlyTrashed, null, true) as $item ) {

			/* 
			 * Note : par facilité on refait une requête SQL par résultat.
			 * Si besoin d'optimiser il faudrait essayer de tout récupérer en une seule requête => bon amusement...
			 */
			$aDemarches=Demarche
			::join('v_lastrevisiondemarcheeform', 'v_lastrevisiondemarcheeform.demarche_id', '=', 'demarches.id')
			->join('nostra_demarches', 'nostra_demarches.id', '=', 'nostra_demarche_id')
			->where('v_lastrevisiondemarcheeform.eform_id', '=', $item->eform_id)
			->whereNull('v_lastrevisiondemarcheeform.deleted_at')
			->orderBy('nostra_demarches.title')
			->get(['demarches.id', 'nostra_demarches.title']);
			$demarches='';
			if(!empty($aDemarches)) {
				$demarches='<ul>';
				foreach($aDemarches as $demarche)
					$demarches.='<li><a href="'.route('demarchesGetView', $demarche->id).'">'.$demarche->title.'</a></li>';
				$demarches.='</ul>';
			}
				
			$entry=[];
			if($onlyTrashed) $entry[]=DateHelper::sortabledatetime ( $item->deleted_at );
			$entry[]=strlen($item->nostra_id) ? ManageableModel::formatId($item->nostra_id) : '<span class="label label-danger">'.Lang::get('admin/eforms/messages.not_linked').'</span>';
			$entry[]=ManageableModel::formatId($item->form_id);
			if ($onlyTrashed) {
				$entry[] = '<strong>' . $item->title . '</strong><br/><em>' . $item->description . '</em>';
			}
			else {
				$entry[] = '<a title="' . Lang::get ( 'button.view' ) . '" href="' . route ( 'eformsGetView', $item->eform_id ) . '"><strong>' . $item->title . '</strong><br/><em>' . $item->description . '</em></a>';
			}
			//$entry[]='<a '. ($item->countannexes > 0 ? ' class="label label-info" ':''). ' href="'.route('eformsAnnexesGetIndex', $item->eform_id).'">'.$item->countannexes.'</a>';
			$entry[]=$demarches;
			$entry[]=$item->deleted_at ? DateHelper::sortabledatetime ( $item->deleted_at ) : DateHelper::sortabledatetime ( $item->created_at ) . '<br/>' . $item->username;
			if($onlyTrashed) {
				$entry[] = '<a title="' . Lang::get('button.restore') . '" href="' . route('eformsGetRestore', $item->eform_id) . '" class="btn btn-xs btn-default">' . Lang::get('button.restore') . '</a>';
			}
			$array[]=$entry;
		}
		return Response::json (['aaData' => $array], 200 );
	}
	
	/**
	 * Visualisation d'un eform
	 *
	 * @param Eform $eform
	 * @return \Illuminate\View\View 
	 */
	public function getView(Eform $eform) {
		return $this->makeDetailView($eform, 'admin/forms/eforms/view');
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getManage()
	 */
	protected function getManage(ManageableModel $modelInstance=null){
		return $this->makeDetailView($modelInstance, 'admin/forms/eforms/manage');
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::save()
	 */
	protected function save(ManageableModel $modelInstance) {
		/* @var Eform $modelInstance */
		$modelInstance->description = Input::get ( 'description' );
		$modelInstance->disponible_en_ligne = Input::get ( 'disponible_en_ligne' ) ?: null;
		$modelInstance->deposable_en_ligne = Input::get ( 'deposable_en_ligne' ) ?: null;
		$modelInstance->dematerialisation = Input::get ( 'dematerialisation' ) ?: null;
		$modelInstance->dematerialisation_date = Input::get ( 'dematerialisation_date' ) ?: null;
		$modelInstance->dematerialisation_canal = Input::get ( 'dematerialisation_canal' ) ?: null;
		$modelInstance->dematerialisation_canal_autres = Input::get ( 'dematerialisation_canal_autres' ) ?: null;
		$modelInstance->intervention_ewbs = Input::get ( 'intervention_ewbs' ) ?: null;
		$modelInstance->references_contrat_administration = Input::get ( 'references_contrat_administration' );
		$modelInstance->remarques = Input::get ( 'remarques' );
		if($modelInstance->dematerialisation != 'oui') {
			// ce champ doit etre vide dans ce cas la
			$modelInstance->dematerialisation_date = null;
		}
		if($modelInstance->dematerialisation != 'deja_effectue') {
			// ce champ doit etre vide dans ce cas la
			$modelInstance->dematerialisation_canal = '';
		}
		if($modelInstance->dematerialisation_canal != 'autres' || $modelInstance->dematerialisation != 'deja_effectue'){
			// ce champ doit etre vide dans ce cas la
			$modelInstance->dematerialisation_canal_autres = '';
		}


		// Champs non révisés, mis à jour uniquement si pas de lien avec un nostra_form
		if(!$modelInstance->nostra_form_id) {
			$modelInstance->title = Input::get ( 'title' );
			$modelInstance->language = Input::get ( 'language' );
			$modelInstance->form_id = StringHelper::getStringOrNull(ltrim(Input::get ( 'form_id' ), '0'));
			$modelInstance->priority = Input::get ( 'priority' );
			$modelInstance->format = Input::get ( 'format' );
			$modelInstance->url = Input::get ( 'url' );
			$modelInstance->smart = Input::get ( 'smart' );
			$modelInstance->esign = Input::get ( 'esign' );
			$modelInstance->simplified = Input::get ( 'simplified' );
		}
		
		// Champs propres aux révisions
		$modelInstance->setComment(Input::get ( 'comment' ));
		$modelInstance->setCurrentStateId(Input::get ( 'current_state' ));
		$modelInstance->setNextStateId(Input::get ( 'next_state' ));
		if($modelInstance->save()) {


			if(Input::has ( 'nostraRequest' ))return route('damusGetRequestEform', $modelInstance->id);

			if(Input::get('fromDemarche')){
				// lier le formulaire a une démarche

				$demarche = Demarche::find(Input::get ( 'fromDemarche' ));
				$route = route('demarchesGetView', Input::get('fromDemarche'));
			}
			if(Input::get('fromDemarchePT')){
				// lier le formulaire a une démarche

				$demarche = Demarche::find(Input::get ( 'fromDemarchePT' ));
				$route = route('demarchesGetComponents', Input::get('fromDemarchePT'));
			}
			if(Input::get('fromDemarche') || Input::get('fromDemarchePT')) {
				$revision=new DemarcheEform();
				$revision->demarche()->associate($demarche);

				$eform_id=$modelInstance->id;
				if($eform_id) $revision->eform()->associate(Eform::find($eform_id));
				$revision->comment = '';
				$revision->user()->associate($this->getLoggedUser());
				$revision->save();

				return $route;
			}

			return true;
		}
		return false;
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getLinks()
	 */
	protected function getLinks(ManageableModel $modelInstance) {
		$links=[];
		
		$items=
		Demarche
		::join('v_lastrevisiondemarcheeform', 'v_lastrevisiondemarcheeform.demarche_id', '=', 'demarches.id')
		->join('nostra_demarches', 'nostra_demarches.id', '=', 'demarches.nostra_demarche_id')
		->where('v_lastrevisiondemarcheeform.eform_id', '=', $modelInstance->id)
		->get(['demarches.id', 'nostra_demarches.title as name'])->toArray();
		if(!empty($items)) {
			$links[]=[
				'route'=> 'demarchesGetView',
				'label' => Lang::get('admin/demarches/messages.title'),
				'items' => $items
			];
		}
		return $links;
	}
	
	/**
	 * Révisions d'un eform au format JSON
	 *
	 * @param Eform $eform
	 * @return Datatables JSON
	 */
	public function getRevisionsData(Eform $eform) {
		$states=DemarchePieceState::all();
		$revisions=EformRevision
		::orderBy('created_at', 'DESC')
		->where('eform_id', '=', $eform->id)
		->select(['created_at', 'current_state_id', 'next_state_id', 'comment']);
	
		return Datatables::of ($revisions)
		->edit_column ( 'current_state_id', function (EformRevision $item) use($states) {
			foreach($states as $state) if($state->id == $item->current_state_id) return  "<span data-toggle=\"tooltip\" title=\"{$state->name}\">{$state->code}</span>";
		})
		->edit_column ( 'next_state_id', function (EformRevision $item) use($states) {
			foreach($states as $state) if($state->id == $item->next_state_id) return  "<span data-toggle=\"tooltip\" title=\"{$state->name}\">{$state->code}</span>";
		})
		->edit_column ( 'comment', function (EformRevision $item) {
			return nl2br($item->comment);
		})
		->make ();
	}
	
	/**
	 * ******************************************************
	 * Formulaires non documentés
	 * ******************************************************
	 */
	
	/**
	 * Ecran de liste des formulaires de Nostra qui ne sont pas encore documentés
	 *
	 * @return \Illuminate\View\View 
	 */
	protected function undocumentedGetIndex() {
		$countDocumented = Eform::count();
		$countUndocumented = NostraForm::whereNotIn('id', function ($query) {
			$query->select(DB::raw('COALESCE(nostra_form_id,0)'))->from('eforms');
		})->count();
		$this->setReturnTo();
		return View::make ('admin/forms/eforms/list', array('trash'=>false, 'context'=>'undocumented', 'countDocumented' => $countDocumented, 'countUndocumented' => $countUndocumented));
	}
	
	/**
	 * Liste des formulaires de Nostra qui ne sont pas encore documentés, au format JSON
	 * 
	 * @return Datatables JSON
	 */
	protected function undocumentedGetData() {
		$forms = NostraForm::select ( array (
			'id',
			'nostra_id',
			'form_id',
			'title',
			'language',
			'created_at',
		))->whereNotIn('id', function ($query) {
			$query->select(DB::raw('COALESCE(nostra_form_id,0)'))->from('eforms');
		}); 
		
		return Datatables::of ( $forms )
			->edit_column ( 'created_at', function (NostraForm $item) {
				return DateHelper::datetime($item->created_at);
			})
			->edit_column ( 'nostra_id', function (NostraForm $item) {
				return ManageableModel::formatId($item->nostra_id);
			})
			->edit_column ( 'form_id', function (NostraForm $item) {
				return ManageableModel::formatId($item->form_id);
			})
			/*->add_column ( 'actions', function (NostraForm $item) {
				return ('<a title="' . Lang::get ( 'button.view' ) . '" href="' . route ( 'eformsUndocumentedGetView', $item->id ) . '" class="btn btn-xs btn-default"><span class="fa fa-eye"></a>');
			})*/
			->edit_column ( 'title', function (NostraForm $item) {
				return '<a title="' . Lang::get ( 'button.view' ) . '" href="' . route ( 'eformsUndocumentedGetView', $item->id ) . '"><strong>'.$item->title.'</strong></a>';
			})
			->remove_column('id')
			->make ();
	}

	/**
	 * Affiche la vue qui permet de valider l'intégrations de tous les formulaires dans synapse (ceux qui ne le sont pas encore)
	 * @return \Illuminate\View\View
	 */
	public function undocumentedGetIntegrer() {
		return View::make ( 'admin/forms/eforms/undocumented-integrer');
	}

	/**
	 * Boucle sur les formulaires Nostra qui ne sont pas dans Synapse
	 * Ceux-ci sont ensuite intégrés dans Synapse.
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws Exception
	 */
	public function undocumentedPostIntegrer() {
		$nostraForms = NostraForm::whereNotIn('id', function ($query) {
			$query->select(DB::raw('COALESCE(nostra_form_id,0)'))->from('eforms');
		})->get();
		$i=0;
		foreach($nostraForms as $nostraForm) {
			$eform = new Eform();
			$eform->nostra_form_id = $nostraForm->id;
			$eform->save();
			$i++;
		}
		return Redirect::route('eformsGetIndex')->with ( 'success', $i.' formulaires ont été intégrés !' );

	}
	
	/**
	 * Visualisation d'un formulaire nostra non documenté
	 * @param NostraForm $nostraForm
	 * @return \Illuminate\View\View 
	 */
	protected function undocumentedGetView(NostraForm $nostraForm) {
		return View::make('admin/forms/eforms/view-undocumented', ['nostraForm' => $nostraForm]);
	}

	/**
	 * Création d'un eform sur base d'un formulaire Nostra non documenté
	 * @param NostraForm $nostraForm
	 * @return \Illuminate\View\View
	 */
	public function undocumentedGetCreate(NostraForm $nostraForm) {
		/*
		 * Donc ici on prend un formulaire nostra pour en faire un eform.
		 * On va regarder si on a pas un formulaire qui y ressemble via 3 checks =
		 * 1. le numéro de slot est il là (form_id) ?
		 * 2. le nom existe t'il tel quel ?
		 * 3. le nom ressemble t'il ? similarité? (nécessite un module complémentaire de pgsql)
		 */

		$possibleExistingForms = new \Illuminate\Database\Eloquent\Collection();

		// 1. Le numéro de slot
		if (strlen($nostraForm->form_id)) {
			$possibleExistingForms = $possibleExistingForms->merge( EForm::where('form_id', '=', $nostraForm->form_id)->get() );
		}

		// 2. Le nom est égal ... (match EXACT en lower :( )
		$possibleExistingForms = $possibleExistingForms->merge( EForm::where(DB::raw('LOWER(title)'), '=', DB::raw('LOWER(:title)'))->setBindings(['title' => $nostraForm->title])->get() ); //setbindings pour échapper correctement le titre dans la query

		// 3. Le nom ressemble
		//DB::select('SELECT set_limit(0.8)'); //fixe une limite pour les résultats retournés par "similarity" de PGSQL
		/*$ids = DB::select('	SELECT similarity(f.title, n.title) AS sim, f.id
							FROM eforms f
							JOIN nostra_forms n ON f.title <> n.title AND f.title % n.title
							ORDER BY sim DESC');
		dd($ids);*/

		//ensuite on prend le restant des eforms
		if (count($possibleExistingForms)) {
			$eForms = Eform::whereNotIn('id', $possibleExistingForms->lists('id'))->whereNull('nostra_form_id')->get();
		}
		else {
			$eForms = Eform::whereNull('nostra_form_id')->get();
		}

		return View::make('admin/forms/eforms/create-from-undocumented', ['nostraForm' => $nostraForm, 'possibleExistingForms' => $possibleExistingForms, 'eForms' => $eForms]);
	}


	/**
	 * Dans cette méthode on recoit en POST le formulaire nostra à intégrer, et une instructio qui détermine si on crée un nouveau eform ou si on utilise un existant
	 * On va afficher ici une écran de validation qui dit ce que vont devenir les données
	 * On passera ensuite à la création proprement dite de l'eform en repostant tout à la méthode undocumentPostCreateValidation
	 * @param NostraForm $nostraForm
	 */
	public function undocumentedPostCreate(NostraForm $nostraForm) {
		$eform = null;

		if ( ! Input::has('eform') ) {
			throw new MissingMandatoryParametersException(Lang::get('admin/eforms/messages.exceptions.no_eform_id'));
		}

		if (Input::get('eform') == '-1') { /* on ne fait rien, on passera null à la view */}
		else {
			$eform = Eform::findOrFail(Input::get('eform'));
		}

		return View::make('admin/forms/eforms/create-from-undocumented-validation', ['nostraForm' => $nostraForm, 'eform' => $eform]);
	}


	public function undocumentedPostCreateValidation(NostraForm $nostraForm) {

		$eform = null;

		if ( ! Input::has('eform') ) {
			throw new MissingMandatoryParametersException(Lang::get('admin/eforms/messages.exceptions.no_eform_id'));
		}

		/* on crée un nouveau formulaire */
		if (Input::get('eform') == '-1') {
			$eform = new Eform();
		}
		/* on merge avc un formulaire existant */
		else {
			$eform = Eform::findOrFail(Input::get('eform'));
		}

		$eform->description = Input::get ( 'description' );
		$eform->nostra_form_id = $nostraForm->id;

		// Champs propres aux révisions
		$eform->setComment(Input::get ( 'comment' ));
		$eform->setCurrentStateId(Input::get ( 'current_state' ));
		$eform->setNextStateId(Input::get ( 'next_state' ));

		DB::beginTransaction ();
		try {
			// rien à valider
			if ($eform->save()) {
				DB::commit ();
				return Redirect::route('eformsUndocumentedGetIndex')->with ( 'success', Lang::get ( 'admin/eforms/messages.manage.success' ) );
			}
			DB::rollBack ();
			return Redirect::route('eformsUndocumentedGetIndex')->with ( 'error', Lang::get ( 'general.baderror' ) );
		}
		catch ( Exception $e ) {
			DB::rollBack ();
			Log::error($e);
			return Redirect::secure ('eformsUndocumentedGetIndex')->withInput ()->with ( 'error', Lang::get ( 'general.baderror' ) . '<pre>' . $e->getMessage () . '</pre>' );
		}

	}
	
	/**
	 * ******************************************************
	 * Annexes liée à un eform
	 * ******************************************************
	 */
	
	/**
	 * Annexes liées à un eform
	 *
	 * @param Eform $eform
	 * @return \Illuminate\View\View 
	 */
	public function annexesGetIndex(Eform $eform) {
		return $this->makeDetailView($eform, 'admin/forms/eforms/annexes/list');
	}
	
	/**
	 * Annexes liées à un eform, au format JSON
	 *
	 * @param Eform $eform
	 * @return Datatables JSON
	 */
	public function annexesGetData(Eform $eform) {
		$states=DemarchePieceState::all();
		$array=[];
		foreach ( $eform->getAnnexes() as $item ) {
			$entry=[];
			$entry[0]=$item->annexe_title;
			$entry[1]=''; foreach($states as $state) if($state->id == $item->current_state_id) $entry[1]="<span data-toggle=\"tooltip\" title=\"{$state->name}\">{$state->code}</span>";
			$entry[2]=''; foreach($states as $state) if($state->id == $item->next_state_id)    $entry[2]="<span data-toggle=\"tooltip\" title=\"{$state->name}\">{$state->code}</span>";
			$entry[3]=nl2br($item->comment);
			$entry[4]=DateHelper::sortabledatetime ( $item->created_at ) . '<br/>' . $item->username;
			$entry[5]='';
			if($eform->canManage())
				$entry[5]=
				'<a title="' . Lang::get ( 'button.historical' ) . '" href="' . route ( 'eformsAnnexesGetHistory', [$eform->id, $item->revision_id] ) . '" class="btn btn-xs btn-default servermodal"><span class="fa fa-clock-o"></a>'.
				'<a title="' . Lang::get ( 'button.edit'       ) . '" href="' . route ( 'eformsAnnexesGetEdit'   , [$eform->id, $item->revision_id] ) . '" class="btn btn-xs btn-default servermodal"><span class="fa fa-pencil"></a>'.
				'<a title="' . Lang::get ( 'button.delete'     ) . '" href="' . route ( 'eformsAnnexesGetDelete' , [$eform->id, $item->revision_id] ) . '" class="btn btn-xs btn-danger servermodal"><span class="fa fa-trash"></a>';
			$array[]=$entry;
		}
		return Response::json (['aaData' => $array], 200 );
	}
	
	/**
	 * Affiche le formulaire de création d'une annexe liée à un eform
	 *
	 * @param Eform $eform
	 * @return \Illuminate\View\View
	 */
	public function annexesGetCreate(Eform $eform) {
		return $this->annexesGetManage($eform);
	}
	
	/**
	 * Affiche le formulaire d'édition d'une annexe liée à un eform
	 *
	 * @param Eform $eform
	 * @param AnnexeEform $annexe_eform
	 * @return \Illuminate\View\View
	 */
	public function annexesGetEdit(Eform $eform, AnnexeEform $annexe_eform) {
		return $this->annexesGetManage($eform, $annexe_eform);
	}
	
	/**
	 * Affiche le formulaire de création et édition d'une annexe liée à un eform
	 * 
	 * @param Eform $eform
	 * @param AnnexeEform $annexe_eform
	 * @return \Illuminate\View\View
	 */
	private function annexesGetManage(Eform $eform, AnnexeEform $annexe_eform=null, $errors=null) {
		if(!$errors) $errors=new MessageBag();
		if($annexe_eform) {
			$aAnnexes[]=$annexe_eform->annexe()->getResults();
		}
		else {
			$aAnnexes=Annexe
			::whereRaw("annexes.id NOT IN(SELECT annexe_id FROM v_lastrevisionannexes WHERE eform_id={$eform->id} AND deleted_at IS NULL)")
			->orderby('title')->select(['id', 'title'])->get();
		}

		return View::make ( 'admin/forms/eforms/annexes/modal-manage', compact ( 'eform', 'annexe_eform', 'aAnnexes', 'errors' ) );
	}
	
	/**
	 * Création d'une annexe liée à un eform
	 * 
	 * @param Eform $eform
	 * @return \Illuminate\View\View
	 */
	public function annexesPostCreate(Eform $eform) {
		return $this->annexesPostManage($eform);
	}
	
	/**
	 * Edition d'une annexe liée à un eform
	 * 
	 * @param Eform $eform
	 * @param AnnexeEform $annexe_eform
	 * @return \Illuminate\View\View
	 */
	public function annexesPostEdit(Eform $eform, AnnexeEform $annexe_eform) {
		return $this->annexesPostManage($eform, $annexe_eform);
	}
	
	/**
	 * Création ou édition d'une annexe liée à un eform
	 * 
	 * @param Eform $eform
	 * @param AnnexeEform $annexe_eform
	 * @return \Illuminate\View\View
	 */
	private function annexesPostManage(Eform $eform, AnnexeEform $annexe_eform=null) {
		try {
			$revision=new AnnexeEform();
			$revision->eform()->associate($eform);
			
			$annexe_id=Input::get('annexe_id');
			if($annexe_id) $revision->annexe()->associate(Annexe::find($annexe_id));
			
			$current_state=Input::get('current_state');
			if($current_state) $revision->current_state()->associate(DemarchePieceState::find($current_state));
			
			$next_state=Input::get('next_state');
			if($next_state) $revision->next_state()->associate(DemarchePieceState::find($next_state));
			
			$revision->comment=Input::get('comment');
			$revision->user()->associate($this->getLoggedUser());
			if($revision->save())
				return View::make ( 'notifications', ['success'=>Lang::get('admin/eforms/messages.annexes.manage.success')] );
			else {
				return $this->annexesGetManage($eform, $annexe_eform, $revision->errors());
			}
		}
		catch ( Exception $e ) {
			Log::error ( $e );
			return View::make('notifications', ['error'=>Lang::get('general.baderror',['exception'=>$e->getMessage()])]);
		}
	}
	
	/**
	 * Demande de suppression d'une annexes liée à un eform
	 *
	 * @param Eform $eform
	 * @param AnnexeEform $annexe_eform
	 * @return \Illuminate\View\View
	 */
	public function annexesGetDelete(Eform $eform, AnnexeEform $annexe_eform) {
		return View::make ( 'servermodal.delete', ['url'=>route('eformsAnnexesPostDelete', [$eform->id, $annexe_eform->id])]);
	}
	
	/**
	 * Suppression d'une annexes liée à un eform
	 *
	 * @param Eform $eform
	 * @param AnnexeEform $annexe_eform
	 * @return \Illuminate\View\View
	 */
	public function annexesPostDelete(Eform $eform, AnnexeEform $annexe_eform) {
		try {
			if($annexe_eform->delete()) return Response::make();
			return View::make('notifications', ['error'=>Lang::get('general.delete.error')]);
		}
		catch ( Exception $e ) {
			Log::error ( $e );
			return View::make('notifications', ['error'=>Lang::get('general.baderror',['exception'=>$e->getMessage()])]);
		}
	}
	
	/**
	 * Historique des versions d'une annexe liée à un formulaire
	 *
	 * @param Eform $eform
	 * @param AnnexeEform $annexe_eform
	 * @return \Illuminate\View\View
	 */
	public function annexesGetHistory(Eform $eform, AnnexeEform $annexe_eform) {
		return View::make('admin/forms/eforms/annexes/modal-history', compact('eform', 'annexe_eform'));
	}
	
	/**
	 * Historique des versions d'une annexe liée à un formulaire au format json pour le datatable
	 *
	 * @param Eform $eform
	 * @param AnnexeEform $annexe_eform
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function annexesGetHistoryData(Eform $eform, AnnexeEform $annexe_eform) {
		$states=DemarchePieceState::all();
		$array = [];
		foreach ( $annexe_eform->getHistory() as $item ) {
			$entry=[];
			$entry[0]=''; foreach($states as $state) if($state->id == $item->current_state_id) $entry[0]="<span data-toggle=\"tooltip\" title=\"{$state->name}\">{$state->code}</span>";
			$entry[1]=''; foreach($states as $state) if($state->id == $item->next_state_id)    $entry[1]="<span data-toggle=\"tooltip\" title=\"{$state->name}\">{$state->code}</span>";
			$entry[2]=($item->deleted_at ? '<span class="label label-danger">Supprimé</span>' : '') . nl2br($item->comment);
			$entry[3]=$item->deleted_at ? DateHelper::sortabledatetime ( $item->deleted_at ) : DateHelper::sortabledatetime ( $item->created_at ) . '<br/>' . $item->username;
			$array[]=$entry;
		}
		return Response::json (['aaData' => $array], 200 );
	}
	
	/**
	 * *********************************************************************************************************
	 * Gestion des révisions liées à un eform
	 * *********************************************************************************************************
	 */
	
	/**
	 * Liste des révisions liées à un eform
	 *
	 * @param Eform $modelInstance
	 *
	 */
	public function revisionsGetIndex(Eform $modelInstance) {
		return $this->makeDetailView ( $modelInstance, 'admin/forms/eforms/revisions/list');
	}
	
	/**
	 * *********************************************************************************************************
	 * Gestion des actions liées à un eform
	 * *********************************************************************************************************
	 */
	
	/**
	 * Liste des actions liées à un eform au format json pour le datatable
	 *
	 * @param Eform $modelInstance
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function actionsGetData(Eform $modelInstance) {
		$array = [];
		foreach ( EwbsAction::each()->forEform($modelInstance)->get() as $item ) {
			array_push($array, [
					'<strong>' . $item->name . '</strong><br/><em>' . $item->description . '</em>',
					EwbsActionRevision::graphicState ( $item->state ),
					EwbsActionRevision::graphicPriority($item->priority),
					$item->responsible,
					DateHelper::sortabledatetime ( $item->created_at ) . '<br/>' . $item->username,
					(
						'<a title="' . Lang::get ( 'button.historical' ) . '" class="history btn btn-xs btn-default servermodal" href="' . route ( 'eformsActionsGetHistory', [$item->eform_id,$item->action_id]).'"><span class="fa fa-clock-o"></span></a>'.
						(
							(!$modelInstance->canManage()) ? '' :
								'<a title="' . Lang::get ( 'button.edit' ) . '" class="edit btn btn-xs btn-default servermodal" href="' . route ( 'eformsActionsGetEdit', [$item->eform_id, $item->action_id]) . '"><span class="fa fa-pencil"></span></a>'.
								'<a title="' . Lang::get ( 'button.delete' ) . '" class="delete btn btn-xs btn-danger servermodal" href="' . route ( 'eformsActionsGetDelete', [$item->eform_id, $item->action_id]) . '"><span class="fa fa-trash-o"></span></a>'
						)
					)
			]);
		}
		return Response::json (['aaData' => $array], 200 );
	}
	
	/**
	 * Affiche le formulaire de création d'une action liée à un eform
	 *
	 * @param Eform $modelInstance
	 * @return \Illuminate\View\View
	 */
	public function actionsGetCreate(Eform $modelInstance) {
		return $this->actionsGetManage ( $modelInstance );
	}
	
	/**
	 * Affiche le formulaire d'édition d'une action liée à un eform
	 *
	 * @param Eform $modelInstance
	 * @param EwbsAction $action
	 * @return \Illuminate\View\View
	 */
	public function actionsGetEdit(Eform $modelInstance, EwbsAction $action) {
		return $this->actionsGetManage ( $modelInstance, $action );
	}
	
	/**
	 * Affiche le formulaire de création et édition d'une action liée à un eform
	 *
	 * @param Eform $modelInstance
	 * @param EwbsAction $action
	 * @param array $extra Paramètre supplémentaires qui seraient à passer à la vue
	 * @return \Illuminate\View\View
	 */
	protected function actionsGetManage(Eform $modelInstance, EwbsAction $action = null, array $extra=[]) {
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
		
		return View::make ( 'admin/forms/eforms/actions/modal-manage', array_merge(compact ( 'modelInstance', 'action', 'edit', 'aTaxonomy', 'selectedTags', 'aUsers', 'aExpertises' ), $extra));
	}
	
	/**
	 * Crée une action liée à un eform
	 *
	 * @param Eform $modelInstance
	 * @return \Illuminate\View\View
	 */
	public final function actionsPostCreate(Eform $modelInstance) {
		return $this->actionsPostManage ( $modelInstance, new EwbsAction () );
	}
	
	/**
	 * Met à jour une action liée à un eform
	 *
	 * @param Eform $modelInstance
	 * @param EwbsAction $action
	 * @return \Illuminate\View\View
	 */
	public final function actionsPostEdit(Eform $modelInstance, EwbsAction $action) {
		return $this->actionsPostManage ( $modelInstance, $action );
	}
	
	/**
	 * Crée ou à jour une action liée à un eform
	 *
	 * @param Eform $modelInstance
	 * @param EwbsAction $action
	 * @return \Illuminate\View\View
	 */
	private function actionsPostManage(Eform $modelInstance, EwbsAction $action) {
		if (! $modelInstance->canManage()) return $this->redirectNoRight ();
		
		try {
			$action->eform_id = $modelInstance->id;
			$errors = new Illuminate\Support\MessageBag ();
			$validator = Validator::make ( Input::all (), $action->formRules () );
			if ($validator->fails ()) {
				$errors = $validator->errors ();
			} else {
				$action->name = Input::get ( 'name' );
				$action->addRevisionAttributes ( [
						'description' => Input::get ( 'description' ),
						'state' => Input::get ( 'state' ),
						'responsible_id' => Input::get('responsible_id')
				] );
				if($this->getLoggedUser()->can('ewbsaction_prioritize') && $p=Input::get('priority')) { // et SSI on a le droit et qu'elle est passée, on prend celle-ci
					$action->addRevisionAttributes([ 'priority'=>$p ]);
				}
				if ($action->save ()) {
					//on sauve les tags
					$action->tags()->sync( is_array( Input::get('tags') ) ? Input::get('tags') : []);
					return View::make('notifications', ['success' => Lang::get('admin/eforms/messages.actions.manage.success')]);
				}
				$errors = $action->errors ();
			}
			if (! $errors->isEmpty ()) {
				Input::flash ();
				return $this->actionsGetManage($modelInstance, $action, ['errors'=>$errors]);
			}
		} catch ( Exception $e ) {
			Log::error ( $e );
			return View::make('notifications', ['error'=>Lang::get('general.baderror',['exception'=>$e->getMessage()])]);
		}
	}
	
	/**
	 * Demande de suppression d'une action liée à un eform
	 *
	 * @param Eform $modelInstance
	 * @param EwbsAction $action
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function actionsGetDelete(Eform $modelInstance, EwbsAction $action) {
		return View::make('servermodal.delete', ['url'=>route('eformsActionsPostDelete', [$modelInstance->id, $action->id])]);
	}
	
	/**
	 * Suppression d'une action liée à un eform
	 *
	 * @param Eform $modelInstance
	 * @param EwbsAction $action
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function actionsPostDelete(Eform $modelInstance, EwbsAction $action) {
		try {
			if(!$modelInstance->canManage()) return View::make('notifications', ['error'=>Lang::get('general.no_right_action')]);
			$action->delete ();
			return Response::make();
		} catch ( Exception $e ) {
			Log::error ( $e );
			return View::make('notifications', ['error'=>$e->getMessage () ]);
		}
	}
	
	/**
	 * Historique des versions d'une action liée à un eform
	 *
	 * @param Eform $modelInstance
	 * @param EwbsAction $action
	 * @return \Illuminate\View\View
	 */
	public function actionsGetHistory(Eform $modelInstance, EwbsAction $action) {
		return View::make('admin/forms/eforms/actions/modal-history', compact('modelInstance', 'action'));
	}
	
	/**
	 * Historique des versions d'une action liée à un eform au format json pour le datatable
	 *
	 * @param Eform $modelInstance
	 * @param EwbsAction $action
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function actionsGetHistoryData(Eform $modelInstance, EwbsAction $action) {
		$array = [];
		foreach ( $action->getRevisions() as $item ) {
			array_push ( $array, [
				($item->deleted_at ? '<span class="label label-danger">Supprimé</span>' : '') . $item->description,
				EwbsActionRevision::graphicState ( $item->state ),
				$item->deleted_at ? DateHelper::sortabledatetime ( $item->deleted_at ) : DateHelper::sortabledatetime ( $item->created_at ) . '<br/>' . $item->username,
				($modelInstance->canManage())?'<a href="' . route ( 'eformsActionsGetDestroy', [$modelInstance->id, $action->id, $item->revision_id]) . '" title="' . Lang::get ( 'button.destroy' ) . '" class="destroy btn btn-xs btn-danger servermodal"><span class="fa fa-times"></span></a>' : '',
			]);
		}
		return Response::json (['aaData' => $array], 200 );
	}
	
	/**
	 * Demande de destruction d'une révision d'action liée à une eform
	 *
	 * @param Eform $modelInstance
	 * @param EwbsAction $action
	 * @param EwbsActionRevision $revision
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function actionsGetDestroy(Eform $modelInstance, EwbsAction $action, EwbsActionRevision $revision) {
		return View::make('servermodal.delete', ['url'=>route('eformsActionsPostDestroy', [$modelInstance->id, $action->id, $revision->id])]);
	}
	
	/**
	 * Destruction d'une révision d'action liée à un eform
	 *
	 * @param Eform $modelInstance
	 * @param EwbsAction $action
	 * @param EwbsActionRevision $revision
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function actionsPostDestroy(Eform $modelInstance, EwbsAction $action, EwbsActionRevision $revision) {
		try {
			if(! $modelInstance->canManage()) return View::make('notifications', ['error'=>Lang::get('general.no_right_action')]);
				
			DB::beginTransaction ();
			$revision->forceDelete ();
				
			// Si la révision était la dernière liée à l'action, destroy de l'action également
			if(!$action->getLastRevision(true)) {
				$action->forceDelete ();
				// TODO Dans ce cas le datatable sera en fait rechargé avec une action qui n'existe plus => un http404 pas visible de l'utilisateur. Si on veut mieux faire, je pense que la meilleur solution serait de rendre un redirect avec un success qui explicite la suppresion, mais alors il faut adapter le JS des modales serveur pour interpréter les codes http correspodant aux redirections.
			}
					
			DB::commit ();
			return Response::make();
		} catch ( Exception $e ) {
			Log::error ( $e );
			return View::make('notifications', ['error'=>$e->getMessage ()]);
		}
	}
}
