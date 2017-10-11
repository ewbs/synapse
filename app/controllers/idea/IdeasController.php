<?php
class IdeaController extends TrashableModelController {
	
	use Synapse\Controllers\Traits\TraitFilterableController;
	
	/**
	 * Initialisation
	 *
	 * @param Idea $model
	 */
	public function __construct(Idea $model) {
		parent::__construct ($model);
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getList()
	 */
	protected function getList($onlyTrashed=false) {
		return View::make ('admin/ideas/list', array('trash'=>$onlyTrashed));
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getDataJson()
	 */
	protected function getDataJson($onlyTrashed=false, $filtered=false) {
		
		$selectCols= [
			'ideas.id',
			'ideas.name',
			'ideas.prioritary',
			'ideas.transversal',
			'ideas.description',
			'ideas.user_id',
			'ideas.ewbs_member_id',
			'ideas.created_at',
			DB::raw('(SELECT "ideaStates"."name"
					  FROM "ideaStates", "ideaStateModifications" 
					  WHERE "ideaStates"."id" = "ideaStateModifications"."idea_state_id" AND "ideaStateModifications"."idea_id" = "ideas"."id" 
					  ORDER BY "ideaStateModifications"."created_at" DESC
					  LIMIT 1) AS state'),
			DB::raw("ARRAY_TO_STRING(ARRAY_AGG(DISTINCT administrations.name), ', ', '') AS administrations" ),
			DB::raw("ARRAY_TO_STRING ( ARRAY_AGG ( DISTINCT CASE WHEN LENGTH(publicswithdemarche.title) > 0 THEN publicswithdemarche.title ELSE publicswithoutdemarche.title END ), ', ', '' ) AS publics" ),
			DB::raw("COUNT(DISTINCT demarches.id) AS count_demarches"),
			DB::raw ( "COUNT(DISTINCT CASE WHEN v_lastrevisionewbsaction.deleted_at iS NULL AND v_lastrevisionewbsaction.state = '".EwbsActionRevision::$STATE_TODO."'     THEN v_lastrevisionewbsaction.id ELSE NULL END) AS count_state_todo" ),
			DB::raw ( "COUNT(DISTINCT CASE WHEN v_lastrevisionewbsaction.deleted_at iS NULL AND v_lastrevisionewbsaction.state = '".EwbsActionRevision::$STATE_PROGRESS."' THEN v_lastrevisionewbsaction.id ELSE NULL END) AS count_state_progress" ),
			DB::raw ( "COUNT(DISTINCT CASE WHEN v_lastrevisionewbsaction.deleted_at iS NULL AND v_lastrevisionewbsaction.state = '".EwbsActionRevision::$STATE_DONE."'     THEN v_lastrevisionewbsaction.id ELSE NULL END) AS count_state_done" ),
			DB::raw ( "COUNT(DISTINCT CASE WHEN v_lastrevisionewbsaction.deleted_at iS NULL AND v_lastrevisionewbsaction.state = '".EwbsActionRevision::$STATE_STANDBY."'  THEN v_lastrevisionewbsaction.id ELSE NULL END) AS count_state_standby" ),
			DB::raw ( "COUNT(DISTINCT CASE WHEN v_lastrevisionewbsaction.deleted_at iS NULL AND v_lastrevisionewbsaction.state = '".EwbsActionRevision::$STATE_GIVENUP."'  THEN v_lastrevisionewbsaction.id ELSE NULL END) AS count_state_givenup" )
		];

		if ($filtered) {
			$builder = Idea::filtered();
		} else {
			$builder = Idea::query();
		}


		$builder = $builder
		->leftjoin( 'idea_nostra_demarche', 'idea_nostra_demarche.idea_id', '=', 'ideas.id' )
		->leftjoin( 'administration_idea', 'ideas.id', '=', 'administration_idea.idea_id' )->leftjoin( 'administrations', 'administrations.id', '=', 'administration_idea.administration_id' )
		->leftjoin( 'idea_nostra_public', 'ideas.id', '=', 'idea_nostra_public.idea_id' )->leftjoin( 'nostra_publics AS publicswithoutdemarche', 'publicswithoutdemarche.id', '=', 'idea_nostra_public.nostra_public_id' )
		->leftjoin( 'ideaStateModifications', 'ideaStateModifications.idea_id', '=', 'ideas.id')->leftjoin( 'ideaStates', 'ideaStates.id', '=', 'ideaStateModifications.idea_state_id' )
		->leftjoin( 'demarches', 'demarches.nostra_demarche_id', '=', 'idea_nostra_demarche.nostra_demarche_id' )
		->leftjoin( 'nostra_demarche_nostra_public', 'demarches.nostra_demarche_id', '=', 'nostra_demarche_nostra_public.nostra_demarche_id')->leftjoin( 'nostra_publics AS publicswithdemarche', 'publicswithdemarche.id', '=', 'nostra_demarche_nostra_public.nostra_public_id' )
		->leftjoin( 'ewbsActions', 'ewbsActions.demarche_id', '=', 'demarches.id' )
		->leftjoin( 'v_lastrevisionewbsaction', 'v_lastrevisionewbsaction.ewbs_action_id', '=', 'ewbsActions.id' )
		->whereNull('ideaStateModifications.deleted_at');
		if($onlyTrashed) {
			array_unshift($selectCols, 'ideas.deleted_at');
			$builder->onlyTrashed();
		}
		$items = $builder->groupBy('ideas.id')->select ($selectCols);


		$dt = Datatables::of ( $items )
		->edit_column ( 'id', function ($item) {
			return DateHelper::year($item->created_at) . '-' . str_pad ( $item->id, 4, "0", STR_PAD_LEFT );
		})
		->edit_column( 'prioritary', function ($item) {
			$return= '';
			if ($item->prioritary > 0) $return .= ' <span class="label label-primary">Prioritaire</span>';
			if ($item->transversal > 0) $return .= ' <span class="label label-info">Générique</span>';
			return ($return);
		})
		->edit_column ( 'name', function ($item) {
			$return = '<a href="'.route('ideasGetView', $item->id).'"><strong>' . $item->name . '</strong>';
			$description = $item->description;
			if (strlen ( $description ) > 100) {
				$description = wordwrap ( $description, 100 );
				$description = substr ( $description, 0, strpos ( $description, "\n" ) );
			}
			if($description) {
				$return .= '<br/><em>' . $description . '</em></a>';
			}
			if($item->count_demarches) {
				$globalState=EwbsAction::globalState($item);
				if($globalState) {
					$tooltip='<ul>';
					foreach(EwbsActionRevision::states() as $state)
						if($count=$item->getAttribute("count_state_{$state}"))
							$tooltip.="<li>".Lang::choice("admin/ewbsactions/messages.wording.{$state}", $count)."</li>";
					$tooltip.='</ul>';
					$title=Lang::choice( 'admin/demarches/messages.action.distributed', $item->count_demarches).' :';
					$return .= '<br/><a href="'.route('ideasGetView', $item->id).'#demarches" data-toggle="popover" title="'.$title.'" data-content="'.$tooltip.'" data-html="true"><span class="label label-'.EwbsActionRevision::stateToClass($globalState).'">'.Lang::get('admin/demarches/messages.action.longtitle').' : '.Lang::get( "admin/ewbsactions/messages.state.{$globalState}").'</span>';
				}
			}
			return ($return);
		})
		->add_column ( 'Etat', function ($item) {
			//$a = $item->getLastStateModification ();
			return $item->state ? Lang::get ( 'admin/ideas/states.label-' . $item->state ) : '';
		})
		->add_column ( 'DG(s)', function ($item) {
			return $item->administrations;
		})
		->add_column ( 'Public(s)', function ($item) {
			return $item->publics;
		})
		->remove_column ( 'transversal' )
		->remove_column ( 'description' )
		->remove_column ( 'user_id' )
		->remove_column ( 'ewbs_member_id' )
		->remove_column ( 'created_at' )
		->remove_column ( 'count_demarches' )
		->remove_column ( 'count_state_todo' )
		->remove_column ( 'count_state_progress' )
		->remove_column ( 'count_state_done' )
		->remove_column ( 'count_state_standby' )
		->remove_column ( 'count_state_givenup' )
		->remove_column ( 'administrations' )
		->remove_column ( 'publics' )
		->remove_column ( 'state' );

		if ($onlyTrashed) {
			$dt->add_column ( 'actions', function ($item) use ($onlyTrashed) {
				if (!$item->canManage()) return;
				return     '<a title="' . Lang::get ( 'button.restore' ) . '" href="' . route( 'ideasGetRestore', $item->id ) . '" class="btn btn-xs btn-default">' . Lang::get ( 'button.restore' ) . '</a>';
			});
		}
		/* ->add_column ( 'actions', function ($item) use ($onlyTrashed) {
			if($onlyTrashed){
				if (!$item->canManage()) return;
				return     '<a title="' . Lang::get ( 'button.restore' ) . '" href="' . route( 'ideasGetRestore', $item->id ) . '" class="btn btn-xs btn-default">' . Lang::get ( 'button.restore' ) . '</a>';
			}
			//$return =    '<a title="' . Lang::get ( 'button.view' )    . '" href="' . route( 'ideasGetView', $item->id )    . '" class="btn btn-xs btn-default"><span class="fa fa-eye"></a>';
			$return= '';
			if ($item->canManage()) {
				$return = '<a title="' . Lang::get('button.edit') . '" href="' . route('ideasGetEdit', $item->id) . '" class="btn btn-xs btn-default"><span class="fa fa-pencil"></a>
							<a title="' . Lang::get('button.delete') . '" href="' . route('ideasGetDelete', $item->id) . '" class="btn btn-xs btn-danger"><span class="fa fa-trash"></a>';
			}
			return $return;
		}); */
		return $dt->make ();
	}
	
	/**
	 * {@inheritDoc}
	 * @see Synapse\Controllers\Traits\TraitFilterableController::getDataFilteredJson()
	 */
	protected function getDataFilteredJson() {
		return $this->getDataJson(false, true);
	}
	
	/**
	 * Affiche la visualisation d'une idée
	 *
	 * @param Idea $idea
	 * @return Response
	 */
	public function getView(Idea $idea) {
		return $this->makeDetailView($idea, 'admin/ideas/view');
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getManage()
	 */
	protected function getManage(ManageableModel $modelInstance=null){
		// Tous les membres de eWBS
		$ewbsMembers = EWBSMember::orderBy ( 'lastname' )->orderBy ( 'firstname' )->get ();
		$aRegions = Region::all ();
		$aGovernements = Governement::all ();
		$aTaxonomy = TaxonomyCategory::orderBy('name')->get();
		$aNostraPublics = NostraPublic::root()->get();
		$aNostraDemarches = NostraDemarche::orderBy('title')->get();

		$returnTo = $this->getReturnTo();
		
		if($modelInstance){
			$aSelectedAdministrations = $modelInstance->getAdministrationsIds ();
			$aSelectedMinisters = $modelInstance->getMinistersIds ();

			$aSelectedNostraDemarches = $modelInstance->getNostraDemarchesIds ();
			$aSelectedNostraPublics = count($aSelectedNostraDemarches) ? [] : $modelInstance->getNostraPublicsIds (); //si ona une démarche, on ne prend pas les publics de la relation, mais ceux liés à la démarche
			
			$currentIdeaState = $modelInstance->getLastStateModification()->ideaState;
			$availableStates=$modelInstance->getAvailableStates($currentIdeaState->name);
			$ideaStates=IdeaState::orderBy('order')->get();
			
			$aSelectedTags = $modelInstance->tags->lists('id');

			return $this->makeDetailView($modelInstance, 'admin/ideas/manage',
				compact ('ewbsMembers', 'aRegions', 'aSelectedAdministrations', 'aGovernements', 'aSelectedMinisters', 'aNostraDemarches', 'aNostraPublics',
				         'aSelectedNostraPublics', 'aSelectedNostraDemarches', 'availableStates', 'ideaStates','currentIdeaState', 'aTaxonomy', 'aSelectedTags', 'returnTo' ) );
		}
		return View::make ( 'admin/ideas/manage', compact ( 'modelInstance', 'ewbsMembers', 'aRegions', 'aGovernements', 'aTaxonomy', 'aNostraDemarches', 'aNostraPublics', 'returnTo' ) );
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::save()
	 */
	protected function save(ManageableModel $idea) {
		$create=($idea->id) ? false : true;
		
		//Assigner les valeurs et sauver
		$idea->name = Input::get ( 'name' );
		$idea->description = Input::get ( 'description' );
		$idea->reference = Input::get('reference');
		$idea->ext_contact = Input::get ( 'ext_contact' );
		$idea->doc_source_title = Input::get ( 'doc_source_title' );
		$idea->doc_source_page = Input::get ( 'doc_source_page' );
		$idea->doc_source_link = Input::get ( 'doc_source_link' );
		$idea->transversal = Input::has ( 'transversal' ) ? 1 : 0;
		$idea->ewbs_member_id = StringHelper::getStringOrNull(Input::get ( 'ewbs_contact'));
		if($create) $idea->user_id = $this->getLoggedUser()->id;
		// pour prioritary, on regarde si l'utilisateur peut modifier la valeur
		if ($this->getLoggedUser()->can ( 'ideas_manage' ) && ! $this->getLoggedUser()->hasRestrictionsByAdministrations ()) {
			$idea->prioritary = Input::has ( 'prioritary' ) ? 1 : 0;
		}
		if(!$idea->save()) return false;
		
		$idea->administrations()->sync(Input::get('administrations', []));
		$idea->ministers()->sync(Input::get('ministers', []));
		$idea->tags()->sync(Input::get('tags', []));

		$arrayNostraDemarches = Input::get('nostra_demarches', []);
		$arrayNostraPublics = Input::get('nostra_publics', []);

		//si on a des démarches liées :
		if (count($arrayNostraDemarches)) {
			$idea->nostraPublics()->sync([]);
			$idea->nostraDemarches()->sync($arrayNostraDemarches);
		}
		//sinon ... on lie à des publics
		else {
			$idea->nostraPublics()->sync($arrayNostraPublics);
			$idea->nostraDemarches()->sync([]);
		}
		
		if(!$create) {
			// et on termine avec un éventuel changement d'état
			$state=Input::get ( 'state' );
			if ($state!=$idea->getLastStateModification ()->ideaState->id) {
				$ideaState = IdeaState::findOrFail($state);
				$ideaStateModification = new IdeaStateModification ();
				$ideaStateModification->comment = Input::get ( 'statecomment' );
				$ideaStateModification->user ()->associate ( $this->getLoggedUser() );
				$ideaStateModification->ideaState ()->associate ( $ideaState );
				$ideaStateModification->idea ()->associate ( $idea );
				$ideaStateModification->save ();
			}
		}
		else {
			// etat
			$state = new IdeaStateModification ();
			$state->idea_state_id = 1; // @TODO : voir si y'a pas moyen de faire plus propre :-s
			$state->user_id = $this->getLoggedUser()->id;
			$state->comment = '';
			$idea->stateModifications ()->save ( $state );
			// commentaire
			if ($comment=Input::get ( 'comment' )) {
				$ideaComment = new IdeaComment ();
				$ideaComment->user_id = $this->getLoggedUser()->id;
				$ideaComment->comment = $comment;
				$idea->comments ()->save ( $ideaComment );
			}
		}
		if(Input::has ( 'nostraRequest' )) return route('damusGetRequestIdea', $idea->id);
		return true;
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getLinks()
	 */
	protected function getLinks(ManageableModel $idea) {
		//TODO : Vérifier les liens
		return [];
	}
	
	/**
	 * Propose d'effectuer l'export des idées
	 * 
	 * @return View
	 */
	public function getExport() {
		$arrayOfRegions = Region::all ();
		$arrayOfGovernements = Governement::all ();
		$arrayNostraPublics = NostraPublic::root ()->get ();
		
		return View::make ( 'admin/ideas/export', compact ( 'arrayOfRegions', 'arrayOfGovernements', 'arrayNostraPublics' ) );
	}
	
	/**
	 * Effectue l'export des idées
	 * 
	 * @return Response
	 */
	public function postExport() {
		try {
			
			$onlyPrioritary = (Input::has ( 'prioritary' ) ? true : false);
			$withTransversal = (Input::has ( 'transversal' ) ? true : false);
			$nostraPublics = (Input::has ( 'nostra_publics' ) ? Input::get ( 'nostra_publics' ) : array ());
			$administrations = (Input::has ( 'administrations' ) ? Input::get ( 'administrations' ) : array ());
			$ministers = (Input::has ( 'ministers' ) ? Input::get ( 'ministers' ) : array ());
			
			$ideas = Idea::NostraPublicsIds ( $nostraPublics )->AdministrationsIds ( $administrations )->MinistersIds ( $ministers )->OnlyPrioritary ( $onlyPrioritary )->withTransversal ( $withTransversal )->get ();
			
			$objPHPExcel = new PHPExcel ();
			$line = 1; // ligne dans Excel
			$objPHPExcel->getProperties ()->setCreator ( "eWBS - Synapse" );
			$objPHPExcel->getProperties ()->setLastModifiedBy ( "eWBS - Synapse" );
			$objPHPExcel->getProperties ()->setTitle ( "Export Synapse" );
			$objPHPExcel->getProperties ()->setSubject ( "Export Synapse" );
			$objPHPExcel->getProperties ()->setDescription ( "Export Synapse" );
			$objPHPExcel->setActiveSheetIndex ( 0 );
			$worksheet = $objPHPExcel->getActiveSheet ();
			
			/*
			 * STYLES GLOBAUX
			 */
			$worksheet->getColumnDimension ( 'D' )->setAutoSize ( true );
			$worksheet->getColumnDimension ( 'E' )->setWidth ( 100 );
			foreach ( range ( 'F', 'M' ) as $columnID ) {
				$worksheet->getColumnDimension ( $columnID )->setAutoSize ( true );
			}
			$worksheet->getColumnDimension ( 'K' )->setWidth ( 100 );
			foreach ( range ( 'O', 'S' ) as $columnID ) {
				$worksheet->getColumnDimension ( $columnID )->setAutoSize ( true );
			}
			$objPHPExcel->getDefaultStyle ()->getFont ()->setName ( 'Arial' );
			$objPHPExcel->getDefaultStyle ()->getFont ()->setSize ( 9 );
			
			// couleur des éléments encodés à la main (dg, public cibles etc)
			$customColor = new PHPExcel_Style_Color ();
			$customColor->setRGB ( "0000FF" );
			
			/*
			 * TITRES DANS EXCEL
			 */
			$worksheet->getCell ( 'A1' )->setValue ( 'ID' );
			$worksheet->getCell ( 'B1' )->setValue ( 'Etat' );
			$worksheet->getCell ( 'C1' )->setValue ( 'Prioritaire' );
			$worksheet->getCell ( 'D1' )->setValue ( 'Générique' );
			$worksheet->getCell ( 'E1' )->setValue ( 'Nom de l\'idée' );
			$worksheet->getCell ( 'F1' )->setValue ( 'Description' );
			$worksheet->getCell ( 'G1' )->setValue ( 'AG/DG/OIP' );
			$worksheet->getCell ( 'H1' )->setValue ( 'Ministre(s)' );
			$worksheet->getCell ( 'I1' )->setValue ( 'Relai eWBS' );
			$worksheet->getCell ( 'J1' )->setValue ( 'Contact administration' );
			$worksheet->getCell ( 'K1' )->setValue ( 'Public(s) cible(s)' );
			$worksheet->getCell ( 'L1' )->setValue ( 'Thématique(s) usager' );
			$worksheet->getCell ( 'M1' )->setValue ( 'Evénement(s) déclencheur(s)' );
			$worksheet->getCell ( 'N1' )->setValue ( 'Thématique(s) administration' );
			$worksheet->getCell ( 'O1' )->setValue ( 'Démarche(s)' );
			$worksheet->getCell ( 'P1' )->setValue ( 'Source documentaire' );
			$worksheet->getCell ( 'Q1' )->setValue ( 'Page' );
			$worksheet->getCell ( 'R1' )->setValue ( 'Lien de la source' );
			$worksheet->getCell ( 'S1' )->setValue ( 'Encodeur' );
			$worksheet->getCell ( 'T1' )->setValue ( 'Date encodage' );
			
			$worksheet->getStyle ( 'A1:T1' )->getFont ()->setBold ( true );
			
			/*
			 * CONTENU
			 */
			foreach ( $ideas as $idea ) {
				$line ++; // on commencera donc en ligne 2
				         // identifiant
				$worksheet->getCell ( "A$line" )->setValue ( DateHelper::year($idea->created_at) . '-' . $idea->id );
				// etat
				$worksheet->getCell ( "B$line" )->setValue ( Lang::get ( 'admin/ideas/states.' . $idea->getLastStateModification ()->ideaState->name ) );
				// prioritaire
				if ($idea->prioritary) {
					$objRichText = new PHPExcel_RichText ();
					$objColoredText = $objRichText->createTextRun ( 'oui' );
					$objColoredText->getFont ()->setColor ( $customColor );
					$worksheet->getCell ( "C$line" )->setValue ( $objRichText );
				} else {
					$worksheet->getCell ( "C$line" )->setValue ( 'non' );
				}
				// transversal
				if ($idea->transversal) {
					$objRichText = new PHPExcel_RichText ();
					$objColoredText = $objRichText->createTextRun ( 'oui' );
					$objColoredText->getFont ()->setColor ( $customColor );
					$worksheet->getCell ( "D$line" )->setValue ( $objRichText );
				} else {
					$worksheet->getCell ( "D$line" )->setValue ( 'non' );
				}
				// titre
				$worksheet->getCell ( "E$line" )->setValue ( $idea->name );
				// description
				$worksheet->getCell ( "F$line" )->setValue ( $idea->description );
				$worksheet->getStyle ( "F$line" )->getAlignment ()->setWrapText ( true );
				// ag,dg,oip
				$elements = array ();
				foreach ( $idea->administrations ()->get () as $dg ) {
					array_push ( $elements, $dg->name );
				}
				$objRichText = new PHPExcel_RichText ();
				$objRichText->createText ( implode ( "\n", $elements ) );
				$worksheet->getCell ( "G$line" )->setValue ( $objRichText );
				$worksheet->getStyle ( "G$line" )->getAlignment ()->setWrapText ( true );
				// ministre
				$elements = array ();
				foreach ( $idea->ministers as $minister ) {
					array_push ( $elements, $minister->name());
				}
				$worksheet->getCell ( "H$line" )->setValue ( implode ( "\n", $elements ) );
				$worksheet->getStyle ( "H$line" )->getAlignment ()->setWrapText ( true );
				// contact ewbs
				$worksheet->getCell ( "I$line" )->setValue ( $idea->ewbsMember->firstname . ' ' . $idea->ewbsMember->lastname );
				unset ( $ewbsContact );
				// contact administration
				$worksheet->getCell ( "J$line" )->setValue ( $idea->ext_contact );
				// publics cibles
				$elements = array ();
				foreach ( $idea->nostraPublics ()->get () as $npc ) {
					array_push ( $elements, $npc->title );
				}
				$objRichText = new PHPExcel_RichText ();
				$objRichText->createText ( implode ( "\n", $elements ) );
				if (strlen ( $idea->freeencoding_nostra_publics )) {
					$objColoredText = $objRichText->createTextRun ( "\n" . $idea->freeencoding_nostra_publics );
					$objColoredText->getFont ()->setColor ( $customColor );
				}
				$worksheet->getCell ( "K$line" )->setValue ( $objRichText );
				$worksheet->getStyle ( "K$line" )->getAlignment ()->setWrapText ( true );
				// thematiques usager
				$elements = array ();
				foreach ( $idea->nostraDemarches as $demarche) {
					foreach ($demarche->nostraThematiquesabc()->get() as $npc) {
						array_push($elements, $npc->title);
					}
				}
				$objRichText = new PHPExcel_RichText ();
				$objRichText->createText ( implode ( "\n", $elements ) );
				if (strlen ( $idea->freeencoding_nostra_thematiques )) {
					$objColoredText = $objRichText->createTextRun ( "\n" . $idea->freeencoding_nostra_thematiquesabc );
					$objColoredText->getFont ()->setColor ( $customColor );
				}
				$worksheet->getCell ( "L$line" )->setValue ( $objRichText );
				$worksheet->getStyle ( "L$line" )->getAlignment ()->setWrapText ( true );
				// evenements
				$elements = array ();
				foreach ( $idea->nostraDemarches as $demarche ) {
					foreach ($demarche->nostraEvenements()->get() as $npc) {
						array_push($elements, $npc->title);
					}
				}
				$objRichText = new PHPExcel_RichText ();
				$objRichText->createText ( implode ( "\n", $elements ) );
				if (strlen ( $idea->freeencoding_nostra_evenements )) {
					$objColoredText = $objRichText->createTextRun ( "\n" . $idea->freeencoding_nostra_evenements );
					$objColoredText->getFont ()->setColor ( $customColor );
				}
				$worksheet->getCell ( "M$line" )->setValue ( $objRichText );
				$worksheet->getStyle ( "M$line" )->getAlignment ()->setWrapText ( true );
				// thematiques administration
				$elements = array ();
				foreach ( $idea->nostraDemarches as $demarche ) {
					foreach ($demarche->nostraThematiquesadm()->get() as $npc) {
						array_push($elements, $npc->title);
					}
				}
				$objRichText = new PHPExcel_RichText ();
				$objRichText->createText ( implode ( "\n", $elements ) );
				if (strlen ( $idea->freeencoding_nostra_thematiques )) {
					$objColoredText = $objRichText->createTextRun ( "\n" . $idea->freeencoding_nostra_thematiquesadm );
					$objColoredText->getFont ()->setColor ( $customColor );
				}
				$worksheet->getCell ( "N$line" )->setValue ( $objRichText );
				$worksheet->getStyle ( "N$line" )->getAlignment ()->setWrapText ( true );
				// demarches
				$elements = array ();
				foreach ( $idea->nostraDemarches ()->get () as $npc ) {
					array_push ( $elements, $npc->title );
				}
				$objRichText = new PHPExcel_RichText ();
				$objRichText->createText ( implode ( "\n", $elements ) );
				if (strlen ( $idea->freeencoding_nostra_demarches )) {
					$objColoredText = $objRichText->createTextRun ( "\n" . $idea->freeencoding_nostra_demarches );
					$objColoredText->getFont ()->setColor ( $customColor );
				}
				$worksheet->getCell ( "O$line" )->setValue ( $objRichText );
				$worksheet->getStyle ( "O$line" )->getAlignment ()->setWrapText ( true );
				// source documentaire
				$worksheet->getCell ( "P$line" )->setValue ( $idea->doc_source_title );
				$worksheet->getCell ( "Q$line" )->setValue ( $idea->doc_source_page );
				$worksheet->getCell ( "R$line" )->setValue ( $idea->doc_source_link );
				// encodeur
				$worksheet->getCell ( "S$line" )->setValue ( $idea->user->username );
				// date encodage
				$worksheet->getCell ( "T$line" )->setValue ( DateHelper::datetime($idea->created_at, true) );
				
				// hauteur de ligne en auto (car pas mal de texte dans certaines cellules)
				$worksheet->getRowDimension ( $line )->setRowHeight ( - 1 );
			}
			
			$fileName = 'synapse-export-idees-' . uniqid () . '.xlsx';
			$file = public_path () . '/temp/' . $fileName;
			$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' );
			$objWriter->save ( $file );
			
			$response = Response::download ( $file, $fileName, array (
					"Content-Type" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" 
			) );
			ob_end_clean ();
			return $response;
		} catch ( Exception $e ) {
			Log::error($e);
			return Redirect::secure($this->routeGetIndex())->with ( 'error', Lang::get ( 'general.baderror' ) . '<pre>' . $e->getMessage () . '</pre>' );
		}
	}
	
	
	/**
	 **********************************************************************************************************
	 * Gestion des commentaires
	 **********************************************************************************************************
	 */
	
	/**
	 * Retourne les commentaires des utilisateurs ainsi que les changements d'états
	 * 
	 * @param Idea $idea
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getComments(Idea $idea) {
		try {
			$array = array ();
			
			// on choppe les commentaires
			foreach ( $idea->comments as $comment ) {
				array_push ( $array, array (
					'type' => 'comment',
					'id' => $comment->id,
					'username' => $comment->user->username,
					'avatar' => Gravatarer::make( ['email' => $comment->user->email, 'size' => 50, 'secured' => true] )->url(),
					'comment' => $comment->comment,
					'date' => $comment->created_at,
					'shortdate' => $comment->created_at->format ( 'j/m/Y H:i' ),
					'timestamp' => $comment->created_at->timestamp,
					'state' => null 
				));
			}
			
			// puis on choppe les changement d'états
			foreach ( $idea->stateModifications as $mod ) {
				array_push ( $array, array (
					'type' => 'state',
					'id' => $mod->id,
					'username' => $mod->user->username,
					'avatar' => Gravatarer::make( ['email' => $mod->user->email, 'size' => 50, 'secured' => true] )->url(),
					'comment' => $mod->comment,
					'date' => $mod->created_at,
					'shortdate' => $mod->created_at->format ( 'j/m/Y H:i' ),
					'timestamp' => $mod->created_at->timestamp,
					'state' => Lang::get ( 'admin/ideas/states.' . $mod->ideaState->name ) 
				));
			}
			
			// on trie par date décroissante
			$timestamp = array ();
			foreach ( $array as $key => $row ) {
				$timestamp [$key] = $row ['timestamp'];
			}
			array_multisort ( $timestamp, SORT_DESC, $array );
			
			return Response::json ( array ('error' => false, 'comments' => $array ), 200 );
		}
		catch ( ModelNotFoundException $e ) {
			Log::error($e);
			return Response::json ( array ('error' => true, 'comments' => null ), 200 );
		}
	}
	
	/**
	 * Ajoute un commentaire
	 *
	 * @param Idea $idea
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function postComment(Idea $idea) {
		try {
			if(!$idea->canManage())
				return Response::json ( array ('error' => true, 'return' => "NOTALLOWED"), 200 );
			
			$comment = new IdeaComment ();
			$comment->user_id = $this->getLoggedUser()->id;
			$comment->comment = Input::get ( 'comment' );
			$idea->comments ()->save ( $comment );
			
			return Response::json ( array ('error' => false, 'return' => "SUCCESS" ), 200 );
		}
		catch ( Exception $e ) {
			Log::error($e);
			return Response::json ( array ('error' => true, 'return' => $e->getMessage ()), 200 );
		}
	}
	
	/**
	 * Met à jour un commentaire
	 *
	 * @param IdeaComment $ideaComment
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function editComment(IdeaComment $ideaComment) {
		try {
			//FIXME : ne doit-on pas juste être le propriétaire du commentaire pour pouvoir éditer ?
			if(!$ideaComment->idea->canManage())
				return Response::json ( array ('error' => true, 'return' => "NOTALLOWED"), 200 );
			
			$ideaComment->comment = Input::get ( 'comment' );
			if($ideaComment->save ())
				return Response::json ( array ('error' => false, 'return' => "SUCCESS"), 200 );
			else
				return Response::json ( array ('error' => true, 'return' => "NOTUPDATED"), 200 );
		}
		catch ( Exception $e ) {
			Log::error($e);
			return Response::json ( array ('error' => true, 'return' => $e->getMessage ()), 200 );
		}
	}
	
	/**
	 * Supprime un commentaire
	 *
	 * @param IdeaComment $ideaComment
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function deleteComment(IdeaComment $ideaComment) {
		try {
			if(!$ideaComment->idea->canManage())
				return Response::json ( array ('error' => true, 'return' => "NOTALLOWED"), 200 );
			if ($ideaComment->delete ())
				return Response::json ( array ('error' => false, 'return' => "SUCCESS"), 200 );
			else
				return Response::json ( array ('error' => true, 'return' => "NOTDELETED"), 200 );
		}
		catch ( Exception $e ) {
			Log::error($e);
			return Response::json ( array ('error' => true, 'return' => $e->getMessage ()), 200 );
		}
	}
}
