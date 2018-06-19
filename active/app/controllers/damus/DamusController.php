<?php
class DamusController extends BaseController {


	protected function routeGetIndex() { return route('damusGetIndex'); }
	
	/**
	 *
	 * {@inheritDoc}
	 * @see BaseController::getSection()
	 */
	protected function getSection(){
		return 'damus';
	}
	
	private static $TOKEN_SIZE=20;
	
	public function getIndex() {
		
		$damusRootPublics = NostraPublic::root()->orderBy("title")->get();
		$damusPublicsCount = NostraPublic::count();
		$damusRootThematiquesABC = NostraThematiqueabc::root()->orderBy("title")->get();
		$damusThematiquesABCCount = NostraThematiqueabc::count();
		$damusEvenements = NostraEvenement::orderBy('title')->get();
		$damusEvenementsCount = count($damusEvenements); //ici on est pas obligé de faire une requete, puisqu'on prend tout sans limitation à un niveau de hiérarchie
		$damusRootThematiquesADM = NostraThematiqueadm::root()->orderBy("title")->get();
		$damusThematiquesADMCount = NostraThematiqueadm::count();
		$damusDemarches = NostraDemarche::orderBy("title")->get();
		$damusDemarchesCount = count($damusDemarches);
		
		return View::make ( 'admin/damus/manage', 
							compact('damusRootPublics', 'damusPublicsCount', 'damusRootThematiquesABC', 'damusThematiquesABCCount', 
									'damusEvenements', 'damusEvenementsCount', 'damusRootThematiquesADM', 'damusThematiquesADMCount',
									'damusDemarches', 'damusDemarchesCount') );
	}
	
	
	public function getDetailPublic(NostraPublic $public) {
		
		return View::make ( 'admin/damus/detail-public', compact('public') );
		
	}
		
	public function getDetailThematiqueABC(NostraThematiqueabc $thematique) {
		
		return View::make ( 'admin/damus/detail-thematiqueabc', compact('thematique') );
		
	}
	
	public function getDetailThematiqueADM(NostraThematiqueadm $thematique) {
		
		return View::make ( 'admin/damus/detail-thematiqueadm', compact('thematique') );
		
	}
	
	public function getDetailEvenement(NostraEvenement $evenement) {
		
		return View::make ( 'admin/damus/detail-evenement', compact('evenement') );
		
	}
	
	public function getDetailDemarche(NostraDemarche $demarche) {
		
		$synapseDemarche = Demarche::where("nostra_demarche_id", "=", $demarche->id)->first();
		
		return View::make ( 'admin/damus/detail-demarche', compact('demarche', 'synapseDemarche') );
		
	}
	
	/**
	 * Formulaire de demande d'ajout d'une démarche NOSTRA
	 * @return Redirect|View
	 */
	public function getRequestCreateDemarche() {
		if(!$this->getLoggedUser()->can('demarches_encode')) return $this->redirectNoRight(route('demarchesGetIndex'));
		return View::make ( 'admin/damus/request/demarche-create');
	}
	
	/**
	 * Envoi de la demande d'ajout d'une démarche NOSTRA
	 * @return Redirect
	 */
	public function postRequestCreateDemarche() {
		if(!$this->getLoggedUser()->can('demarches_encode')) return $this->redirectNoRight(route('ideasGetIndex'));

		$name=Input::get('name');
		$nostra_publics=Input::get('nostra_publics' );
		$nostra_thematiquesabc=Input::get('nostra_thematiquesabc' );
		$nostra_evenements=Input::get('nostra_evenements' );
		$nostra_thematiquesadm=Input::get('nostra_thematiquesadm' );
		$documents=Input::get('documents' );
		$forms=Input::get('forms' );
		$simplified=Input::get('simplified' );
		$german_version=Input::get('german_version' );
		$type=Input::get('type' );
		$comment=Input::get('comment');
		
		//Note : Ce serait plus propre de mettre les intitulés dans des traductions, mais comme cela va sans doute changer prochainement, laissons tjs ainsi...
		$description='Nom du projet :'.$name.PHP_EOL.PHP_EOL;
		$description.='Public(s) cible : '.implode(', ', array_map(function($e) {return $e['title'];}, array_values(NostraPublic::find(Input::get('nostra_publics' ), ['title'])->toArray()))).PHP_EOL.PHP_EOL;
		if(!empty($nostra_thematiquesabc))
			$description.='Thématiques(s) usager : '.implode(', ', array_map(function($e) {return $e['title'];}, array_values(NostraThematiqueabc::find($nostra_thematiquesabc, ['title'])->toArray()))).PHP_EOL.PHP_EOL;
		if(!empty($nostra_evenements))
			$description.='Evénements déclencheurs : '.implode(', ', array_map(function($e) {return $e['title'];}, array_values(NostraEvenement::find($nostra_evenements, ['title'])->toArray()))).PHP_EOL.PHP_EOL;
		if(!empty($nostra_thematiquesadm))
			$description.='Thématiques(s) administrative(s) : '.implode(', ', array_map(function($e) {return $e['title'];}, array_values(NostraThematiqueadm::find($nostra_thematiquesadm, ['title'])->toArray()))).PHP_EOL.PHP_EOL;
		if($documents)
			$description.='Documents liés : '.$documents.PHP_EOL.PHP_EOL;
		if($forms)
			$description.='Formulaires liés : '.$forms.PHP_EOL.PHP_EOL;
		$description.='Simplifié : '.($simplified?'Oui':'Non').PHP_EOL.PHP_EOL;
		$description.='Version allemande : '.($german_version?'Oui':'Non').PHP_EOL.PHP_EOL;
		if($type)
			$description='Type : '.$type.PHP_EOL.PHP_EOL;
		$description.='Motivation de la demande : '.$comment;

		
		$action=new EwbsAction();
		$action->name=Lang::get('admin/damus/messages.request.demarche.create.title');
		$action->addRevisionAttributes(['description'=>$description]);
		$action->token=substr(md5(rand()),0,self::$TOKEN_SIZE);
		$action->save();

		$description.=PHP_EOL.PHP_EOL;
		$description.='Demandé par : '.Auth::user()->username.' (<a href="mailto:'.Auth::user()->email.'">'.Auth::user()->email.'</a>)';
	
		Mail::queueOn('nostra', 'emails.damus.request.action', ['link'=>route('damusGetResponse', [$action->id, $action->token]), 'request'=>$description], function(\Illuminate\Mail\Message $message) {
			$message->to(Config::get('app.nostra.mail'))->subject(Lang::get('admin/damus/messages.request.mail.source').' : '.Lang::get('admin/damus/messages.request.demarche.create.title'));
		});
	
		return Redirect::secure(route('demarchesGetIndex'))->with('success', Lang::get('admin/damus/messages.request.success'));
	}
	
	/**
	 * Formulaire de demande de correction de donnée d'une démarche NOSTRA
	 * @param Demarche $demarche
	 * @return Redirect|View
	 */
	public function getRequestDemarche(Demarche $demarche) {
		if(!$demarche->canManage()) return $this->redirectNoRight(route('demarchesGetView', $demarche->id));
		return View::make ( 'admin/damus/request/demarche-edit', compact('demarche'));
	}
	
	/**
	 * Envoi de la demande de correction de donnée d'une démarche NOSTRA
	 * @param Demarche $demarche
	 * @return Redirect
	 */
	public function postRequestDemarche(Demarche $demarche) {
		if(!$demarche->canManage()) return $this->redirectNoRight(route('demarchesGetView', $demarche->id));
		$nostraDemarche=$demarche->nostraDemarche; /* @var $nostraDemarche NostraDemarche */
		
		$detail=
			'Concernant la démarche #'.$nostraDemarche->nostra_id.' - '.$nostraDemarche->title.' : '.PHP_EOL.PHP_EOL.
			Input::get('subject').PHP_EOL.PHP_EOL.
			Input::get('comment');
		//Note : Ce serait plus propre de mettre les intitulés dans des traductions, mais comme cela va sans doute changer prochainement, laissons tjs ainsi...
	
		$action=new EwbsAction();
		$action->sub=false;
		$action->demarche_id=$demarche->id;
		$action->name=Lang::get('admin/damus/messages.request.demarche.edit.title');
		$action->addRevisionAttributes(['description'=>$detail]);
		$action->token=substr(md5(rand()),0,self::$TOKEN_SIZE);
		$action->save();

		$detail .= PHP_EOL.PHP_EOL;
		$detail .= 'Demandé par : '.Auth::user()->username.' (<a href="mailto:'.Auth::user()->email.'">'.Auth::user()->email.'</a>)';

		Mail::queueOn('nostra', 'emails.damus.request.action', ['link'=>route('damusGetResponse', [$action->id, $action->token]), 'request'=>$detail], function(\Illuminate\Mail\Message $message) {
			$message->to(Config::get('app.nostra.mail'))->subject(Lang::get('admin/damus/messages.request.mail.source').' : '.Lang::get('admin/damus/messages.request.demarche.edit.title'));
		});
	
		return Redirect::secure(route('demarchesGetView', $demarche->id))->with('success', Lang::get('admin/damus/messages.request.success'));
	}
	
	/**
	 * Formulaire de demande d'ajout d'un élément NOSTRA
	 * @param Idea $idea
	 * @return Redirect|View
	 */
	public function getRequestIdea(Idea $idea) {
		if(!$idea->canManage()) return $this->redirectNoRight(route('ideasGetView', $idea->id));
		return View::make ( 'admin/damus/request/idea', compact('idea') );
	}
	
	/**
	 * Envoi de la demande d'ajout d'un élément NOSTRA
	 * @param Idea $idea
	 * @return Redirect
	 */
	public function postRequestIdea(Idea $idea) {
		if(!$idea->canManage()) return $this->redirectNoRight(route('ideasGetIndex'));
		$subject=Input::get('subject');
		$comment=$subject.PHP_EOL.PHP_EOL.Input::get('comment');
		
		$action=new EwbsAction();
		$action->sub=false;
		$action->idea_id=$idea->id;
		$action->name=Lang::get('admin/damus/messages.request.idea.title');
		$action->addRevisionAttributes(['description'=>$comment]);
		$action->token=substr(md5(rand()),0,self::$TOKEN_SIZE);
		$action->save();

		$comment.=PHP_EOL.PHP_EOL;
		$comment.='Demandé par : '.Auth::user()->username.' (<a href="mailto:'.Auth::user()->email.'">'.Auth::user()->email.'</a>)';
		
		Mail::queueOn('nostra', 'emails.damus.request.action', ['link'=>route('damusGetResponse', [$action->id, $action->token]), 'request'=>$comment], function(\Illuminate\Mail\Message $message) {
			$message->to(Config::get('app.nostra.mail'))->subject(Lang::get('admin/damus/messages.request.mail.source').' : '.Lang::get('admin/damus/messages.request.idea.title'));
		});
		
		return Redirect::secure(route('ideasGetEdit', $idea->id).'#nostraRequest')->with('success', Lang::get('admin/damus/messages.request.success'));
	}
	
	/**
	 * Formulaire de demande d'ajout d'un élément NOSTRA
	 * @param Eform $eform
	 * @return Redirect|View
	 */
	public function getRequestEform(Eform $eform) {
		if(!$eform->canManage()) return $this->redirectNoRight(route('eformsGetView', $eform->id));
		return View::make ( 'admin/damus/request/eform', compact('eform') );
	}
	
	/**
	 * Envoi de la demande d'ajout d'un élément NOSTRA
	 * @param Eform $eform
	 * @return Redirect
	 */
	public function postRequestEform(Eform $eform) {
		if(!$eform->canManage()) return $this->redirectNoRight(route('eformsGetView', $eform->id));
		$subject=Input::get('subject');
		$comment=$subject.PHP_EOL.PHP_EOL.Input::get('comment');
		$action=new EwbsAction();
		$action->eform()->associate($eform);
		$action->name=Lang::get('admin/damus/messages.request.eform.title');
		$action->addRevisionAttributes(['description'=>$comment]);
		$action->token=substr(md5(rand()),0,self::$TOKEN_SIZE);
		$action->save();

		$comment.=PHP_EOL.PHP_EOL;
		$comment.='Demandé par : '.Auth::user()->username.' (<a href="mailto:'.Auth::user()->email.'">'.Auth::user()->email.'</a>)';
	
		Mail::queueOn('nostra', 'emails.damus.request.action', ['link'=>route('damusGetResponse', [$action->id, $action->token]), 'request'=>$comment], function(\Illuminate\Mail\Message $message) {
			$message->to(Config::get('app.nostra.mail'))->subject(Lang::get('admin/damus/messages.request.mail.source').' : '.Lang::get('admin/damus/messages.request.eform.title'));
		});
	
		return Redirect::secure(route('eformsGetEdit', $eform->id).'#nostraRequest')->with('success', Lang::get('admin/damus/messages.request.success'));
	}
			
	/**
	 * Formulaire de clôture d'une demande d'ajout d'un élément NOSTRA
	 * @param EwbsAction $action
	 * @param string $token
	 * @return Redirect|View
	 */
	public function getResponse(EwbsAction $action, $token) {
		// Erreur de token ?
		if(strlen($token)<self::$TOKEN_SIZE)
			return Redirect::route('getIndex')->with('error', Lang::get('admin/damus/messages.response.error.tokensize'));
		if(!$token || $action->token!=$token) // Note : bien vérifier que le token ne soit pas vide, sinon cela matcherait avec toutes les actions qui n'ont pas de token !
			return Redirect::route('getIndex')->with('error', Lang::get('admin/damus/messages.response.error.tokenmatch'));
		
		// L'action provenait de quel type de demande ?
		if($action->idea_id)
			$type='idea';
		elseif($action->eform_id)
			$type='eform';
		elseif($action->demarche_id)
			$type='demarche.edit';
		else
			$type='demarche.create';
		
		return View::make ("site/damus/response", compact('action', 'token', 'type'));
	}
	
	/**
	 * Confirmation de la prise en charge ou clôture d'une demande d'ajout d'un élément NOSTRA
	 * 
	 * @param EwbsAction $action
	 * @param string $token
	 * @return Redirect|View
	 */
	public function postResponse(EwbsAction $action, $token) {
		// Erreur de token ?
		if(strlen($token)<self::$TOKEN_SIZE)
			return Redirect::route('damusGetResponse', [$action->id, $token])->with('error', Lang::get('admin/damus/messages.response.error.tokensize'));
		if($action->token && $action->token!=$token)
			return Redirect::route('damusGetResponse', [$action->id, $token])->with('error', Lang::get('admin/damus/messages.response.error.tokenmatch'));
		// Action déjà clôturée ?
		$revision=$action->getLastRevision();
		if($revision->state==EwbsActionRevision::$STATE_DONE || $revision->state==EwbsActionRevision::$STATE_GIVENUP)
			return Redirect::route('damusGetResponse',[$action->id, $token])->with('error', Lang::get('admin/damus/messages.response.error.closed'));
		
		$detail=Lang::get("admin/damus/messages.response.mail.explanation").PHP_EOL.Input::get('detail');
		
		// L'action provenait de quel type de demande ?
		if($action->idea_id) {
			$type='idea';
			$link=route('ideasGetEdit', $action->idea_id);
		}
		elseif($action->eform_id) {
			$type='eform';
			$link=route('eformsGetView', $action->eform_id);
		}
		elseif($action->demarche_id) {
			$type='demarche.edit';
			$link=route('demarchesGetView', $action->demarche_id);
		}
		else {
			$type='demarche.create';
			$link=route('demarchesGetIndex');
		}
		
		// Si la tâche n'était pas encore démarrée, il s'agit alors de la notification de prise en charge
		if($revision->state==EwbsActionRevision::$STATE_TODO) {
			$step='process';
			$state=EwbsActionRevision::$STATE_PROGRESS;
			$detail=Lang::get('admin/damus/messages.response.mail.process').
			PHP_EOL.PHP_EOL.
			$detail;
		}
		// Sinon il s'agit de la clôture
		else {
			$step='close';
			// L'état de l'action est mis à jour en fonction de la raison fournie par l'équipe NOSTRA : par défaut done, sauf si la demande était refusée (=> givenup)
			$reason=Input::get('reason');
			$state=EwbsActionRevision::$STATE_DONE;
			if($reason=='refused') $state=EwbsActionRevision::$STATE_GIVENUP;
			$detail=
				Lang::get("admin/damus/messages.response.{$type}.reasons.{$reason}.title").
				' ('.Lang::get("admin/damus/messages.response.{$type}.reasons.{$reason}.info").').'.
				PHP_EOL.PHP_EOL.
				$detail;
		}
		
		$action->setState($state);
		$action->setDescription($detail);
		$action->save();
		
		$to=null;
		$firstRevision=$action->getFirstRevision();
		if($firstRevision) {
			$user=$firstRevision->user;
			if($user) $to=$user->email;
			else Log::warning('Response email from NOSTRA to user : no user found => email could not be sent');
		}
		
		if($to)
			Mail::queueOn('nostra', "emails.damus.response.action", ['link'=>$link, 'request'=>$revision->description, 'response'=>$detail], function(\Illuminate\Mail\Message $message) use($revision, $type, $step, $to) {
				$message->to($to)->subject(Lang::get("admin/damus/messages.request.{$type}.title").' - '.Lang::get("admin/damus/messages.response.subtitle.{$step}"));
			});
		else
			Log::warning('Response email from NOSTRA to user : no email address found => email could not be sent');
		
		return Redirect::route('damusGetResponse',[$action->id, $token])->with('success', Lang::get("admin/damus/messages.response.{$step}.success"));
	}
	
	/**
	 * Cette fonction fait un appel à Nostra pour obtenir le json d'un record en détail
	 * Il le retourne tel quel ou fait une 500 ou 404 en cas de probleme.
	 *
	 * @param type $nostra_id
	 */
	public function nostraGetDemarche($nostra_id) {
		try {
			$client=new GuzzleHttp\Client();
			$res=$client->get(str_replace('{{demarcheId}}', $nostra_id, Config::get ( 'app.nostraV2_demarcheDetail' )));
			$demarche=$res->json()['fiche'][0];
			return View::make('admin/damus/nostra/modal-demarche', compact('demarche'));
		}
		catch(Exception $e) {
			Log::error($e);
			return View::make('notifications', ['error'=>Lang::get('general.baderror',['exception'=>$e->getMessage()])]);
		}
	}
}