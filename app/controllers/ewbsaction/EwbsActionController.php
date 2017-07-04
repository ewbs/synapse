<?php

class EwbsActionController extends TrashableModelController {
	
	/**
	 * Initialisation
	 * 
	 * @param EwbsAction $model
	 */
	public function __construct(EwbsAction $model) {
		parent::__construct ($model);
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getList()
	 */
	protected function getList($onlyTrashed=false) {
		$params=['trash'=>$onlyTrashed];
		if(!$onlyTrashed) {
			$params['responsibles']=EwbsAction::distinctResponsibles()->get();
			$params['selectedResponsibles']=Auth::user()->sessionGet('ewbsactions_selectedResponsibles', []);
			
			$params['names']=EwbsAction::distinctNames()->get();
			$params['selectedNames']=Auth::user()->sessionGet('ewbsactions_selectedNames', []);
		}
		return View::make ('admin/ewbsactions/list', $params);
	}


	/**
	 * Pour garder la logique de getData qui appelle getDataJson, cette fonction ne fait qu'appeler getFilteredDataJson()
	 * @return mixed
	 */
	protected function getFilteredData() {
		return $this->getFilteredDataJson();
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getDataJson()
	 */
	protected function getDataJson($onlyTrashed=false) {
		
		$selectedResponsibles = Input::get('responsibles', []);
		Auth::user()->sessionSet('ewbsactions_selectedResponsibles', $selectedResponsibles);
		
		$selectedNames = Input::get('names', []);
		Auth::user()->sessionSet('ewbsactions_selectedNames', $selectedNames);
		
		$array=[];
		foreach ( EwbsAction::each()->forResponsibles($selectedResponsibles)->forNames($selectedNames)->joinSubActions()->joinTaxonomy()->get() as $item ) {
			$entry=[];
			$entry[]=str_pad ( $item->action_id, 5, "0", STR_PAD_LEFT );
			if($onlyTrashed) {
				$entry[]=DateHelper::sortabledatetime ( $item->deleted_at );
			}
			if ($onlyTrashed) {
				$string = '<strong>' . $item->name . '</strong><br/><em>' . $item->description . '</em><br/>';
			} else {
				//si on est dans la liste normale (pas la corbeille, le titre est clickable)
				$string = '<a title="' . Lang::get ( 'button.view' ) . '" href="' . route ( 'ewbsactionsGetView', $item->action_id ) . '"><strong>' . $item->name . '</strong><br/><em>' . $item->description . '</em></a>';
			}
			//on ajoute la taxo
			if (strlen($item->tags)) {
				$string .= '<br/><span class="fa fa-tags"></span> <small>' . $item->tags . '</small>';
			}
			$entry[] = $string;
			$entry[]=EwbsActionRevision::graphicState ( $item->state );
			$entry[]=( EwbsActionRevision::graphicPriority($item->priority) );

			if(!$item->subactions)$entry[]='';
			else {
				$subactions=explode(',', $item->subactions);
				$tooltip='<ul>';
				foreach($subactions as $subaction) $tooltip.="<li>".$subaction."</li>";
				$tooltip.='</ul>';
				$entry[]='<a class="btn btn-xs btn-default" " href="' . route ( 'ewbsactionsGetView', $item->action_id ) . '#subactions" data-toggle="popover" data-content="'.$tooltip.'" data-html="true">'.count($subactions).'</a>';
			}
			

			if($item->demarche_id) {
				$string = '<a href="' . route('demarchesGetView', $item->demarche_id) . '" target="_blank" title="' . Lang::get('admin/demarches/messages.item') . '"><i class="fa fa-briefcase"></i>' . $item->demarche_name . '</a><br/>';
				if ($item->demarche_piece_name) { //si on a une piece
					$string .= '<i class="fa"></i><span title="' . Lang::get('admin/demarches/messages.piece.piece') . '"><i class="fa fa-clipboard"></i>' . $item->demarche_piece_name . '</span>';
				}
				elseif ($item->demarche_task_name) { // si on  a une tâche
					$string .= '<i class="fa"></i><span title="' . Lang::get('admin/demarches/messages.task.task') . '"><i class="fa fa-tasks"></i>' . $item->demarche_task_name . '</span>';
				}
				elseif ($item->eform_id) { // si on a un formulaire
					$string .= '<i class="fa"></i><span title="' . Lang::get('admin/demarches/messages.eform.eform') . '"><i class="fa fa-wpforms"></i>' . $item->eform_name . '</span>';
				}
				$entry[] = $string;
			}
			elseif($item->idea_id) {
				$entry[] = '<a href="' . route('ideasGetView', $item->idea_id) . '" target="_blank" title="' . Lang::get('admin/ideas/messages.item') . '"><i class="fa fa-lightbulb-o"></i>' . $item->idea_name . '</a>';
			}
			elseif ($item->eform_id) {
				// ce cas sert à lister les actions liées à des formulaires non liés à des demarches
				$entry[] = '<a href="' . route('eformsGetView', $item->eform_id) . '" target="_blank" title="' . Lang::get('admin/demarches/messages.item') . '"><span title="' . Lang::get('admin/demarches/messages.eform.eform') . '"><i class="fa fa-wpforms"></i>' . $item->eform_name . '</span></a>';
			}
			else $entry[]='';
			
			$entry[]=DateHelper::sortabledatetime ( $item->created_at ) . '<br/>' . $item->username;
			if($onlyTrashed) {
				$entry[] = '<a title="' . Lang::get('button.restore') . '" href="' . route('ewbsactionsGetRestore', $item->action_id) . '" class="btn btn-xs btn-default">' . Lang::get('button.restore') . '</a>';
			}
			$array[]=$entry;
		}
		return Response::json (['aaData' => $array], 200 );
	}


	protected function getFilteredDataJson() {
		//TODO : voir si la corbeille est appelée en filtrage ... normalement non donc pour le moment je ne prend pas de parametre $onlyTrashed comme dans getDataJson
		//TODO : cette fonction sera peut être au final quasi identique à getData ... quand elle sera terminée on pourrait mutualiser ces deux fonctions (si pertinent)

		$array = [];
		foreach ( EwbsAction::filtered()->each()->joinSubActions()->joinTaxonomy()->get() as $item ) {
			$entry=[];
			$entry[]=str_pad ( $item->action_id, 5, "0", STR_PAD_LEFT );
			$string='<a title="' . Lang::get ( 'button.view' ) . '" href="' . route ( 'ewbsactionsGetView', $item->action_id ) . '"><strong>' . $item->name . '</strong><br/><em>' . $item->description . '</em></a>';
			//on ajoute la taxo
			if (strlen($item->tags)) {
				$string .= '<br/><span class="fa fa-tags"></span> <small>' . $item->tags . '</small>';
			}
			$entry[] = $string;
			$entry[]=EwbsActionRevision::graphicState ( $item->state );

			if(!$item->subactions)$entry[]='';
			else {
				$subactions=explode(',', $item->subactions);
				$tooltip='<ul>';
				foreach($subactions as $subaction) $tooltip.="<li>".$subaction."</li>";
				$tooltip.='</ul>';
				$entry[]='<a class="btn btn-xs btn-default" " href="' . route ( 'ewbsactionsGetView', $item->action_id ) . '#subactions" data-toggle="popover" data-content="'.$tooltip.'" data-html="true">'.count($subactions).'</a>';
			}


			if($item->demarche_id) {
				$string = '<a href="' . route('demarchesGetView', $item->demarche_id) . '" target="_blank" title="' . Lang::get('admin/demarches/messages.item') . '"><i class="fa fa-briefcase"></i>' . $item->demarche_name . '</a><br/>';
				if ($item->demarche_piece_name) { //si on a une piece
					$string .= '<i class="fa"></i><span title="' . Lang::get('admin/demarches/messages.piece.piece') . '"><i class="fa fa-clipboard"></i>' . $item->demarche_piece_name . '</span>';
				}
				elseif ($item->demarche_task_name) { // si on  a une tâche
					$string .= '<i class="fa"></i><span title="' . Lang::get('admin/demarches/messages.task.task') . '"><i class="fa fa-tasks"></i>' . $item->demarche_task_name . '</span>';
				}
				elseif ($item->eform_id) { // si on a un formulaire
					$string .= '<i class="fa"></i><span title="' . Lang::get('admin/demarches/messages.eform.eform') . '"><i class="fa fa-wpforms"></i>' . $item->eform_name . '</span>';
				}
				$entry[] = $string;
			}
			elseif($item->idea_id) {
				$entry[] = '<a href="' . route('ideasGetView', $item->idea_id) . '" target="_blank" title="' . Lang::get('admin/ideas/messages.item') . '"><i class="fa fa-lightbulb-o"></i>' . $item->idea_name . '</a>';
			}
			elseif ($item->eform_id) {
				// ce cas sert à lister les actions liées à des formulaires non liés à des demarches
				$entry[] = '<a href="' . route('eformsGetView', $item->eform_id) . '" target="_blank" title="' . Lang::get('admin/demarches/messages.item') . '"><span title="' . Lang::get('admin/demarches/messages.eform.eform') . '"><i class="fa fa-wpforms"></i>' . $item->eform_name . '</span></a>';
			}
			else $entry[]='';


			$array[]=$entry;
		}
		return Response::json (['aaData' => $array], 200 );

	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getManage()
	 */
	protected function getManage(ManageableModel $modelInstance=null){
		
		$aTaxonomy = TaxonomyCategory::orderBy('name')->get();
		$aSelectedTags = $modelInstance->tags->lists('id');
		$aExpertises=DB::table('expertises')->orderBy('order')->lists('name');
		
		//FIXME : Il faudrait aussi ajouter les conditions nécessaires pour inclure le user supprimé qui serait en fait celui lié à l'action courante (afin que le lien ne se perde pas)
		$aUsers=User::query()->leftjoin('ewbs_members', 'ewbs_members.user_id', '=', 'users.id')->whereNotNull('ewbs_members.id')->orWhere('users.id', '=', $this->getLoggedUser()->id)->orderBy('username')->get(['users.id', 'users.username']);
		
		$params=compact('modelInstance', 'aTaxonomy', 'aSelectedTags', 'aUsers');
		
		if($modelInstance){
			$params['revision']=$modelInstance->getLastRevision();
			
			// Insérer en début de tableau l'éventuel nom de l'action si elle ne correspond pas à une des expertises
			if(!in_array($modelInstance->name(), $aExpertises))
				array_unshift($aExpertises, $modelInstance->name());
		}
		$params['aExpertises']=$aExpertises;
		return $this->makeDetailView($modelInstance, 'admin/ewbsactions/manage', $params);
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::save()
	 */
	protected function save(ManageableModel $ewbsAction) {

		// l'ensemble a été validé dans le ModelController (postManage())
		
		$lastRevision=$ewbsAction->getLastRevision(true);
		
		$priority=$lastRevision?$lastRevision->priority:null; // Par défaut celle de la précédente révision
		if($this->getLoggedUser()->can('ewbsaction_prioritize') && $p=Input::get('priority')) { // et SSI on a le droit et qu'elle est passée, on prend celle-ci
			$priority=$p;
		}
		
		if($this->getLoggedUser()->hasRole('admin')) { // et SSI on a le droit et qu'elle est passée, on prend celle-ci
			$ewbsAction->sub=(Input::get('sub')?true:false);
		}
		$ewbsAction->name=Input::get('name');
		
		// on adapte la taxonomie
		$ewbsAction->tags()->sync( is_array( Input::get('tags') ) ? Input::get('tags') : []);

		// on crée une révision
		// et on sauve (on est déjà dans un DB::transact car cette méthode save est appelée depuis postManage() de ModelController
		$ewbsAction->addRevisionAttributes([
			'description' => Input::get('description'),
			'state' => Input::get('state', ($lastRevision ? $lastRevision->state : EwbsActionRevision::$STATE_TODO)),
			'priority' => $priority,
			'responsible_id' => Input::get('responsible_id')
		]);


		if (! $ewbsAction->save ()) {
			return $ewbsAction->errors ();
		}
		return true;
	}
	
	/**
	 * {@inheritDoc}
	 * @see ModelController::getLinks()
	 */
	protected function getLinks(ManageableModel $ewbsAction) {
		return [];
	}
	
	/**
	 * Visualisation d'une action
	 *
	 * @param Eform $eform
	 * @return View
	 */
	public function getView(EwbsAction $action) {
		return $this->makeDetailView($action, 'admin/ewbsactions/view');
	}
	
	/**
	 * 
	 * @param EwbsAction $action
	 * @return View
	 */
	public function subactionsGetData(EwbsAction $action) {
		$minimal=Input::get('minimal')?true:false;
		$array=[];
		foreach ($action->eachSub()->get() as $item ) {
			$entry=[];
			$entry[]=str_pad ( $item->action_id, 5, "0", STR_PAD_LEFT );
			$entry[] = '<strong>' . $item->name . '</strong><br/><em>' . $item->description . '</em>';
			$entry[]=EwbsActionRevision::graphicState ( $item->state );
			$entry[]=( EwbsActionRevision::graphicPriority($item->priority) );
			if(!$minimal) {
				$entry[]=DateHelper::sortabledatetime ( $item->created_at ) . '<br/>' . $item->username;
				$entry[] =
				'<a title="' . Lang::get('button.historical') . '" class="history btn btn-xs btn-default servermodal" href="' . route('ewbsactionsSubGetHistory', [$action->id, $item->action_id]) . '"><span class="fa fa-clock-o"></span></a>' .
				(
					(!$action->canManage()) ? '' :
					'<a title="' . Lang::get('button.edit') . '" class="edit btn btn-xs btn-default servermodal" href="' . route('ewbsactionsSubGetEdit', [$action->id, $item->action_id]) . '"><span class="fa fa-pencil"></span></a>' .
					'<a title="' . Lang::get('button.delete') . '" class="delete btn btn-xs btn-danger servermodal" href="' . route('ewbsactionsSubGetDelete', [$action->id, $item->action_id]) . '"><span class="fa fa-trash-o"></span></a>'
				);
			}
			
			$array[]=$entry;
		}
		return Response::json (['aaData' => $array], 200 );
	}
	
	/**
	 * Création d'une sous-action
	 *
	 * @param EwbsAction $parent
	 * @return View
	 */
	public function subactionsGetCreate(EwbsAction $parent) {
		$action=new EwbsAction();
		$action->sub=false;
		return $this->subactionsGetManage($parent, new EwbsAction());
	}
	
	/**
	 * Edition d'une sous-action
	 *
	 * @param EwbsAction $parent
	 * @param EwbsAction $action
	 * @return View
	 */
	public function subactionsGetEdit(EwbsAction $parent, EwbsAction $action) {
		return $this->subactionsGetManage($parent, $action);
	}
	
	/**
	 * Gestion d'une sous-action
	 *
	 * @param EwbsAction $parent
	 * @param EwbsAction $action
	 * @return View
	 */
	private function subactionsGetManage(EwbsAction $parent, EwbsAction $action) {
		return View::make ( 'admin/ewbsactions/subactions/modal-manage', compact('parent', 'action'));
	}
	
	/**
	 * Crée une sous-action
	 *
	 * @param EwbsAction $parent
	 * @return \Illuminate\View\View
	 */
	public final function subactionsPostCreate(EwbsAction $parent) {
		$action=new EwbsAction();
		$action->sub=false;
		return $this->subactionsPostManage( $parent, $action);
	}
	
	/**
	 * Met à jour une sous-action
	 *
	 * @param EwbsAction $parent
	 * @param EwbsAction $action
	 * @return \Illuminate\View\View
	 */
	public final function subactionsPostEdit(EwbsAction $parent, EwbsAction $action) {
		return $this->subactionsPostManage( $parent, $action );
	}
	
	/**
	 * Crée ou à jour une sous-action
	 *
	 * @param EwbsAction $parent
	 * @param EwbsAction $action
	 * @return \Illuminate\View\View
	 */
	private function subactionsPostManage(EwbsAction $parent, EwbsAction $action) {
		if(!$parent->canManage() || !$parent->sub) return $this->serverModalNoRight();
		
		try {
			$errors = new Illuminate\Support\MessageBag ();
			$validator = Validator::make ( Input::all (), $action->formRules () );
			if ($validator->fails ()) {
				$errors = $validator->errors ();
			} else {
				$action->name = Input::get ( 'name' );
				$action->addRevisionAttributes ( [
						'description' => Input::get ( 'description' ),
						'state' => Input::get ( 'state' )
				] );
				if($this->getLoggedUser()->can('ewbsaction_prioritize')) {
					$action->addRevisionAttributes (['priority'=>Input::get ( 'priority' )]);
				}
				if(!$action->parent_id) {
					$action->parent()->associate($parent);
				}
				if (! $action->save ()) {
					$errors = $action->errors ();
				}
			}
			if (! $errors->isEmpty ()) {
				Input::flash ();
				return View::make ('admin/ewbsactions/subactions/modal-manage', compact('parent', 'action'))->withErrors($errors)->with('error', Lang::get('general.manage.error'));
			}
		} catch ( Exception $e ) {
			Log::error ( $e );
			Input::flash ();
			return View::make ('admin/ewbsactions/subactions/modal-manage', compact('parent', 'action'))->withErrors($errors)->with('error', $e->getMessage());
		}
		return View::make ( 'notifications', ['success'=>Lang::get('general.success')] );
	}
	
	/**
	 * Historique des versions d'une sous-action
	 *
	 * @param EwbsAction $parent
	 * @param EwbsAction $action
	 * @return \Illuminate\View\View
	 */
	public function subactionsGetHistory(EwbsAction $parent, EwbsAction $action) {
		return View::make('admin/ewbsactions/subactions/modal-history', compact('action'));
	}
	
	/**
	 * Demande de suppression d'une sous-action
	 *
	 * @param EwbsAction $parent
	 * @param EwbsAction $action
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function subactionsGetDelete(EwbsAction $parent, EwbsAction $action) {
		return View::make('servermodal.delete', ['url'=>route('ewbsactionsSubPostDelete', [$parent->id, $action->id])]);
	}
	
	/**
	 * Suppression d'une sous-action
	 *
	 * @param EwbsAction $parent
	 * @param EwbsAction $action
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function subactionsPostDelete(EwbsAction $parent, EwbsAction $action) {
		if(!$parent->canManage()) return $this->serverModalNoRight();
		try {
			$action->delete ();
			return Response::make();
		} catch ( Exception $e ) {
			Log::error ( $e );
			return View::make('notifications', ['error'=>$e->getMessage () ]);
		}
	}
}
