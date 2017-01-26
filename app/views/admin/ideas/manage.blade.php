<?php
// on va plusierus fois l'utiliser ... on le stocke en var pour éviter de refaire la requete à chaque fois.
if ($modelInstance) {
	$ideaState = $modelInstance->getLastStateModification()->ideaState;
	if (!count($availableStates))
		unset($availableStates);
}
?>


@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
	Bienvenue sur Synapse
	@parent
@stop

{{-- Content --}}
@section('content')

	<div class="page-head">
		@if ($modelInstance)
			<div class="pull-right">
				@include('admin.modelInstance.partial-features')
			</div>
		@endif
		<h2>
			<span class="text-primary"><span class="fa fa-lightbulb-o"></span></span> {{($modelInstance ? 'Edition' : 'Création')}} d'un projet de simplification
		</h2>
	</div>

	<div class="cl-mcont">

		{{-- Create-Edit Form --}}
		<form class="form-horizontal" method="post" autocomplete="off" action="{{ ($modelInstance) ? $modelInstance->routePostEdit() : $model->routePostCreate() }}">
			<!-- CSRF Token -->
			<input type="hidden" name="_token" id="_token" value="{{{ csrf_token() }}}" />
			<!-- ./ csrf token -->
			<input type="hidden" name="formsubmit" value="1" /> {{-- Ceci sert à savoir si le formulaire a été posté. pour dérterminer un comportement dans le JS --}}


			<div class="row">

				<div class="col-md-6">

					<!--
							--------------------------------------------------------------------------------------------
								BLOC "ETAT DU PROJET"
								. etat
								N'apparait que lors de l'édition d'un projet
							--------------------------------------------------------------------------------------------
					-->
					@if ($modelInstance)
						<div class="block-flat">
							<div class="header"><h4>Etat du projet</h4></div>
							<div class="content">
								<input type="hidden" name="changestate" id="changestate" value="0" />
								<ol class="breadcrumb">
									<li>{{$ideaState->name == 'ENCODEE' ? Lang::get('admin/ideas/states.label-ENCODEE') : Lang::get('admin/ideas/states.ENCODEE')}}</li>
									<li>{{$ideaState->name == 'REVUE' ? Lang::get('admin/ideas/states.label-REVUE') : Lang::get('admin/ideas/states.REVUE')}}</li>
									<li>{{$ideaState->name == 'VALIDEE' ? Lang::get('admin/ideas/states.label-VALIDEE') : Lang::get('admin/ideas/states.VALIDEE')}}</li>
									<li>{{$ideaState->name == 'ENREALISATION' ? Lang::get('admin/ideas/states.label-ENREALISATION') : Lang::get('admin/ideas/states.ENREALISATION')}}</li>
									<li>{{$ideaState->name == 'REALISEE' ? Lang::get('admin/ideas/states.label-REALISEE') : Lang::get('admin/ideas/states.REALISEE')}}</li>
									<li>{{$ideaState->name == 'SUSPENDUE' ? Lang::get('admin/ideas/states.label-SUSPENDUE') : Lang::get('admin/ideas/states.SUSPENDUE')}}</li>
									<li>{{$ideaState->name == 'ABANDONNEE' ? Lang::get('admin/ideas/states.label-ABANDONNEE') : Lang::get('admin/ideas/states.ABANDONNEE')}}</li>
									@if (isset($availableStates))
										<li class="pull-right">
											<a href="javascript:void(0);" id="state-button-modify" class="btn btn-primary btn-xs pull-right">Modifier l'état</a>
											<a href="javascript:void(0);" id="state-button-cancel" class="btn btn-danger btn-xs pull-right">Annuler</a>
										</li>
									@endif
								</ol>
								@if (isset($availableStates))
									<div class="form-group state-formgroup">
										<label class="col-md-2 control-label" for="state">Nouvel état</label>
										<div class="col-md-10">
											<select class="form-control" name="state" id="state">
												@foreach ($availableStates as $state)
													<option value="{{$state}}" {{{$ideaState->name == $state ? 'selected': ''}}}>{{Lang::get('admin/ideas/states.'.$state)}}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="form-group state-formgroup">
										<label class="col-md-2 control-label" for="statecomment">Commentaire</label>
										<div class="col-md-10">
											<textarea class="form-control" name="statecomment" id="statecomment" placeholder="Commentaire et/ou explicatif éventuel du changement d'état"></textarea>
										</div>
									</div>
								@endif
							</div>
						</div>
					@endif




					<!--
							--------------------------------------------------------------------------------------------
								BLOC "MON PROJET"
								. nom
								. description
								. prioritaire
								. générique
								. référence externe
							--------------------------------------------------------------------------------------------
					-->
					<div class="block-flat">
						<div class="header"><h4>Mon projet</h4></div>
						<div class="content">
							<!-- nom -->
							<div class="form-group {{{ $errors->has('name') ? 'has-error' : '' }}}">
								<label class="col-md-2 control-label" for="name">Nom du projet</label>
								<div class="col-md-10">
									<input class="form-control" type="text" name="name" id="name" value="{{{ Input::old('name', $modelInstance ? $modelInstance->name : '') }}}" />
									{{ $errors->first('name', '<span class="help-inline">:message</span>') }}
								</div>
							</div>
							<!-- ./ nom -->
							<!-- description -->
							<div class="form-group {{{ $errors->has('description') ? 'has-error' : '' }}}">
								<label class="col-md-2 control-label" for="name">Description</label>
								<div class="col-md-10">
									<textarea style="height: 100px;" class="form-control" name="description" id="description">{{{ Input::old('description', $modelInstance ? $modelInstance->description : null) }}}</textarea>
									{{ $errors->first('description', '<span class="help-inline">:message</span>') }}
								</div>
							</div>
							<!-- ./ description -->
							<!-- switches -->
							@if ($loggedUser->can('ideas_manage') && ! $loggedUser->hasRestrictionsByAdministrations ())
								<div class="form-group">
									<label class="col-md-2 control-label" for="">Prioritaire ?</label>
									<div class="col-md-10">
										<div class="switch">
											<input type="checkbox" name="prioritary"{{ Input::old('prioritary', $modelInstance ? ($modelInstance->prioritary ? ' checked' : '') : '') }} />
										</div>
									</div>
								</div>
							@endif
							<div class="form-group">
								<label class="col-md-2 control-label" for="">Générique ?</label>
								<div class="col-md-10">
									<div class="switch">
										<input type="checkbox" name="transversal"{{ Input::old('transversal', $modelInstance ? ($modelInstance->transversal ? ' checked' : '') : '') }} />
									</div>
								</div>
							</div>
							<!-- ./ switches -->
							<!-- reference -->
							<div class="form-group {{{ $errors->has('reference') ? 'has-error' : '' }}}">
								<label class="col-md-2 control-label" for="reference">Référence</label>
								<div class="col-md-10">
									<input class="form-control" type="text" name="reference" id="reference" value="{{{ Input::old('reference', $modelInstance ? $modelInstance->reference : '') }}}" placeholder="Référence externe du projet. Ex: identifiant dans un contrat d'administration" />
									{{ $errors->first('reference', '<span class="help-inline">:message</span>') }}
								</div>
							</div>
							<!-- ./ reference -->
						</div>
					</div>



					<!--
							--------------------------------------------------------------------------------------------
								BLOC "TAXONOMIE"
								. tags
							--------------------------------------------------------------------------------------------
					-->
					<div class="block-flat">
						<div class="header"><h4>Taxonomie</h4></div>
						<div class="content">
							<!-- tags -->
							<?php
							// recherche de l'élément à selectionner
							$selectedTags = [];
							if ($modelInstance)
								$selectedTags = $aSelectedTags; //passée par le controlleur (voir function getManage());
							if (Input::old('tags'))
								$selectedTags = Input::old('tags');
							?>
							<div class="form-group {{{ $errors->has('tags') ? 'has-error' : '' }}}">
								<div class="col-md-12">
									<select class="form-control select2" name="tags[]" id="tags" multiple>
										@foreach($aTaxonomy as $category)
											<optgroup label="{{$category->name}}">
												@foreach($category->tags as $tag)
													<option value="{{$tag->id}}"{{ in_array($tag->id, $selectedTags) ? ' selected': '' }}>{{$tag->name}}</option>
												@endforeach
											</optgroup>
										@endforeach
									</select>
									{{ $errors->first('tags', '<span class="help-inline">:message</span>') }}
								</div>
							</div>
						</div>
					</div>




					<!--
							--------------------------------------------------------------------------------------------
								BLOC "COMMENTAIRE"
								. commentaire
								N'apparait que lors de la création d'un projet
							--------------------------------------------------------------------------------------------
					-->
					@if (!$modelInstance)
						<div class="block-flat">
							<div class="header"><h4>Commentaire</h4></div>
							<div class="content">
								<div class="form-group">
									<div class="col-md-12">
										<textarea class="form-control" style="height: 100px;" id="comment" name="comment">{{ Input::old('comment', '') }}</textarea>
										<small class="pull-right">(facultatif)</small>
									</div>
								</div>
							</div>
						</div>
					@endif

				</div>






				<div class="col-md-6">


					<!--
							--------------------------------------------------------------------------------------------
								BLOC "NOSTRA"
								. lien avec démarches
								. ou lien avec publics cibles
								Affiche également les données nostra (non éditable)
								L'attribut data-haspost est important pour les scripts exécutés sur cette page !
							--------------------------------------------------------------------------------------------
					-->
					<div class="block-flat">
						<div class="header"><h4>Lier mon projet à une démarche</h4></div>
						<div class="content">
							<div class="form-group" id="modideas_nostralight_demarche_select">
								<div class="col-md-12">
									<?php
									// recherche de l'élément à selectionner
									$selectedNostraDemarches = [];
									if (Input::old('nostra_demarches'))
										$selectedNostraDemarches = Input::old('nostra_demarches');
									else {
										if ($modelInstance)
											$selectedNostraDemarches = $aSelectedNostraDemarches; //passée par le controlleur (voir function getManage());
										
										// Lier le projet à une démarche passée en paramètre (on vient alors d'une démarche que l'on souhaitait lier à ce projet)
										$demarchetolink=Input::get('demarchetolink'); 
										if($demarchetolink && !in_array($demarchetolink, $selectedNostraDemarches))
											array_push($selectedNostraDemarches, $demarchetolink);
									}
									?>
									<select class="select2" multiple name="nostra_demarches[]" id="nostra_demarches" data-haspost="{{Input::old('formsubmit')}}">
										@foreach($aNostraDemarches as $demarche)
											<option value="{{$demarche->id}}"{{ in_array($demarche->id, $selectedNostraDemarches) ? ' selected': '' }}>{{$demarche->title}}</option>
										@endforeach
									</select>
									<div id="modideas_nostralight_demarche_detail"></div>
								</div>
							</div>
							<div class="form-group" id="modideas_nostralight_demarche_button">
								<div class="col-md-12">
									<a id="modideas_nostralight_demarche_button_button" class="btn btn-info btn-sm">Je ne trouve pas ma démarche et préfère lier à un public</a>
								</div>
							</div>
							<div class="form-group" id="modideas_nostralight_public">
								<div class="col-md-12">
									<p>Si vous ne pouvez pas relier votre projet à une démarche, sélectionnez au minimum un public cible :</p>
									<select class="select2" multiple name="nostra_publics[]" id="nostra_publics">
										<?php
										// recherche de l'élément à selectionner
										$selectedNostraPublics = [];
										if ($modelInstance)
											$selectedNostraPublics = $aSelectedNostraPublics; //passée par le controlleur (voir function getManage());
										if (Input::old('nostra_publics'))
											$selectedNostraPublics = Input::old('nostra_publics');
										?>
										@foreach($aNostraPublics as $public)
											<option value="{{$public->id}}"{{ in_array($public->id, $selectedNostraPublics) ? ' selected': '' }}>{{$public->title}}</option>
										@endforeach
									</select>
									<p>
										<br/><a class="btn btn-sm btn-default" id="modideas_nostralight_public_cancel">Je préfère lier mon projet à une démarche</a>
										<button type="submit" id="nostraRequest" name="nostraRequest" value="true" class="btn btn-sm btn-info">Demander l'ajout d'élément dans Nostra *</button>
										<br/>
									</p>
									<small>* Avant de demander un ajout d'élément, veillez à remplir toutes les informations demandées dans ce formulaire.</small>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
									@if ($errors->has('nostra_demarches') || $errors->has('nostra_publics'))
										<strong class="color-danger">Vous devez renseigner au moins une démarche OU un public cible</strong>
									@endif
								</div>
							</div>
						</div>
					</div>


					<!--
							--------------------------------------------------------------------------------------------
								BLOC "LIENS"
								. administrations concernees
								. ministresimpliques
								. relai ewbs
								. contact administration
							--------------------------------------------------------------------------------------------
					-->
					<div class="block-flat">
						<div class="content no-padding">
							<!-- administrations impliquées -->
							<strong>Administrations impliquées</strong>
							<?php $aSelectedAdministrations = Input::old('administrations', isset($aSelectedAdministrations) ? $aSelectedAdministrations : array()); ?>
							<div class="form-group">
								<div class="col-md-12">
									<select class="select2" multiple name="administrations[]" id="administrations">
										<?php
										// TODO : truc pas très propre que je fais dans la vue pour le moment, mais ce serait bien mieux à faire dans le controlleur.
										// on regarde si l'utilisateur connecté a des restrictions d'accès sur les administrations.
										// on n'affichera pas les administrions auquelles il n'a pas accès (on place les acceptées dans un tableau, simplement)
										//FIXME : Si jamais l'idée était déjà associée à une admin à laquelle on n'a pas droit, mais qu'elle est aussi associée à une admin à laquelle on a droit, on perd le lien correspondant à l'administration à laquelle on n'a pas droit lors de l'édition !
										$restrictedAdministrations = $loggedUser->getRestrictedAdministrationsIds();
										?>
										@foreach($aRegions as $region)
											<optgroup label="{{$region->name}}">
												@foreach($region->administrations()->orderBy('name')->get() as $administration)
													@if (count($restrictedAdministrations) > 0)
														@if (in_array($administration->id, $restrictedAdministrations))
															<option value="{{$administration->id}}"{{ in_array($administration->id, $aSelectedAdministrations) ? ' selected' : '' }}>{{$administration->name}}</option>
														@endif
													@else
														<option value="{{$administration->id}}"{{ in_array($administration->id, $aSelectedAdministrations) ? ' selected' : '' }}>{{$administration->name}}</option>
													@endif
												@endforeach
											</optgroup>
										@endforeach
									</select>
									<small class="pull-right">(facultatif)</small>
								</div>
							</div>
							<!-- ./ administrations impliquées -->

							<!-- ministre compétent -->
							<strong>Ministre(s) compétent(s)</strong>
							<?php $aSelectedMinisters = Input::old('ministers', isset($aSelectedMinisters) ? $aSelectedMinisters : array()); ?>
							<div class="form-group">
								<div class="col-md-12">
									<select class="select2" multiple name="ministers[]" id="ministers">
										@foreach($aGovernements as $governement)
											<optgroup label="{{$governement->name}}">
												@foreach($governement->ministers as $minister)
													<option value="{{$minister->id}}"{{ in_array($minister->id, $aSelectedMinisters) ? ' selected' : '' }}>{{$minister->lastname}} {{$minister->firstname}}</option>
												@endforeach
											</optgroup>
										@endforeach
									</select>
									<small class="pull-right">(facultatif)</small>
								</div>
							</div>
							<!-- ./ ministre compétent -->

							<!-- relai ewbs -->
							<?php
							$selectedMember = Input::old('ewbs_contact', $modelInstance ? $modelInstance->ewbs_member_id : null);
							?>
							<strong>Relai eWBS</strong>
							<div class="form-group {{{ $errors->has('ewbs_contact') ? 'has-error' : '' }}}">
								<div class="col-md-12">
									<select class="form-control" name="ewbs_contact" id="ewbs_contact">
										<option></option>
										@foreach($ewbsMembers as $ewbsmember)
											<option value="{{$ewbsmember->id}}"{{ $selectedMember== $ewbsmember->id ? ' selected': '' }}>{{strtoupper($ewbsmember->lastname)}} {{$ewbsmember->firstname}}</option>
										@endforeach
									</select>
									{{ $errors->first('ewbs_contact', '<span class="help-inline">:message</span>') }}
								</div>
							</div>
							<!-- ./ relais ewbs -->

							<!-- contact administration -->
							<strong>Contact administration</strong>
							<div class="form-group {{{ $errors->has('ext_contact') ? 'has-error' : '' }}}">
								<div class="col-md-12">
									<input class="form-control" type="text" name="ext_contact" id="ext_contact" value="{{{ Input::old('ext_contact', $modelInstance ? $modelInstance->ext_contact : null) }}}" />
									{{ $errors->first('ext_contact', '<span class="help-inline">:message</span>') }} <small class="pull-right">(facultatif)</small>
								</div>
							</div>
							<!-- ./ contact administration -->
						</div>
					</div>



					<!--
							--------------------------------------------------------------------------------------------
								BLOC "DOCUMENTATION"
								. titre du document
								. page
								. lien vers le document
							--------------------------------------------------------------------------------------------
					-->
					<div class="block-flat">
						<div class="header"><h4>Source documentaire</h4></div>
						<div class="content">
							<div>
								<div class="col-md-12">
									<p>Vous pouvez renseigner une source documentaire, comme le plan opérationnel d'une DG, un DPR/DPC, une publication ou tout autre document.</p>
								</div>
							</div>

							<div class="form-group {{{ $errors->has('doc_source_title') ? 'has-error' : '' }}}">
								<label class="col-md-3 control-label" for="doc_source_title">Titre du document</label>
								<div class="col-md-9">
									<input class="form-control" type="text" name="doc_source_title" id="doc_source_title"  placeholder="Exemple : Plan Opérationnel 2015 DGO6"
										   value="{{{ Input::old('doc_source_title', $modelInstance ? $modelInstance->doc_source_title : null) }}}"/>
									{{ $errors->first('doc_source_title', '<span class="help-inline">:message</span>') }} <small class="pull-right">(facultatif)</small>
								</div>
							</div>
							<div class="form-group {{{ $errors->has('doc_source_page') ? 'has-error' : '' }}}">
								<label class="col-md-3 control-label" for="doc_source_page">Numéro de page</label>
								<div class="col-md-9">
									<input class="form-control" type="text" name="doc_source_page" id="doc_source_page"
										   value="{{{ Input::old('doc_source_page', $modelInstance ? $modelInstance->doc_source_page : null) }}}" />
									{{ $errors->first('doc_source_page', '<span class="help-inline">:message</span>') }} <small class="pull-right">(facultatif)</small>
								</div>
							</div>
							<div class="form-group {{{ $errors->has('doc_source_link') ? 'has-error' : '' }}}">
								<label class="col-md-3 control-label" for="doc_source_link">Lien vers le document</label>
								<div class="col-md-9">
									<input class="form-control" type="text" name="doc_source_link" id="doc_source_title" placeholder="Lien vers le document (adresse vers KP, internet, intranet ou autre)"
										   value="{{{ Input::old('doc_source_link', $modelInstance ? $modelInstance->doc_source_link : null) }}}"/>
									{{ $errors->first('doc_source_title', '<span class="help-inline">:message</span>') }} <small class="pull-right">(facultatif)</small>
								</div>
							</div>
							<!-- ./ source documentaire -->
						</div>
					</div>

				</div>

			</div>


			<div class="row">
				<div class="block-flat">
					<div class="content no-padding">
						<div class="form-group no-padding no-margin">
							<div class="col-md-12">
								<button type="submit" class="btn btn-primary btn-lg">{{Lang::get('button.save')}}</button>
								<a class="btn btn-link btn-lg" href="{{ $modelInstance ? $modelInstance->routeGetView() : (isset($returnTo) && $returnTo ? route($returnTo) : $model->routeGetIndex()) }}">{{Lang::get('button.cancel')}}</a>
							</div>
						</div>
					</div>
				</div>
			</div>

		</form>

	</div>

@stop

{{-- //TODO: mettre ca dans un .js --}}

@section('scripts')
	<script type="text/javascript">

		var apiNostraDemarcheLinkURL = '{{{route('apiGetDemarcheLinks')}}}';

		$(document).ready( function () {

			// quand on modifie le contenu du select2 des démarches, on rafraichit les détails
			$('#nostra_demarches').on('select2:close', function (e) { refreshDemarcheDetailHtml(); });

			// si on clique sur le bouton "je ne trouve pas ma démarche"
			$("a#modideas_nostralight_demarche_button_button").on('click', function() {
				$("#modideas_nostralight_public").show();
				$("#modideas_nostralight_demarche_select").hide();
				$("#modideas_nostralight_demarche_button").hide();
			} );

			// si on clique sur le bouton "annuler" dans le choix des publics
			$("a#modideas_nostralight_public_cancel").on('click', function(){
				$("#modideas_nostralight_public").hide();
				$("#modideas_nostralight_demarche_select").show();
				$("#modideas_nostralight_demarche_button").show();
			});

			// et à l'affichage de la page, on refresh cela; selon que l'on doive afficher
			// le panneau publics ou le panneau démarche
			/*  && $("#nostra_demarches").data('haspost') != 1 */
			if ( $("#nostra_demarches").val() !== null || $("#nostra_demarches").data('haspost') != 1 ) {
				$("a#modideas_nostralight_public_cancel").trigger('click');
				refreshDemarcheDetailHtml();
			} else {
				$("a#modideas_nostralight_demarche_button_button").trigger('click');
			}
		});


		function refreshDemarcheDetailHtml() {
			$('#modideas_nostralight_demarche_detail').html('');
			$('a#modideas_nostralight_demarche_button_button').show();

			$.ajax({
				url: apiNostraDemarcheLinkURL,
				dataType: 'json',
				method: 'POST',
				data: {'ids':$('#nostra_demarches').val()}
			})
			.done(function (json) {
				var html = '<small>';
				if ( json.error == false) {
					if (json.publics.length > 0) {
						html += '<strong>Publics cibles : </strong>';
						$.each( json.publics, function (i, public) { html += '<span class="badge badge-default">'+public.title+'</span>'; });
					}
					if (json.thematiquesabc.length > 0) {
						html += '<br/><strong>Thématiques usager : </strong>';
						$.each( json.thematiquesabc, function (i, theme) { html += '<span class="badge badge-default">'+theme.title+'</span>'; });
					}
					if (json.thematiquesadm.length > 0) {
						html += '<br/><strong>Thématiques administratives : </strong>';
						$.each( json.thematiquesadm, function (i, theme) { html += '<span class="badge badge-default">'+theme.title+'</span>'; });
					}
					if (json.thematiquesabc.length > 0) {
						html += '<br/><strong>Evénements déclencheurs : </strong>';
						$.each( json.evenements, function (i, evt) { html += '<span class="badge badge-default">'+evt.title+'</span>'; });
					}
					//et si ca s'est bien passé, on enleve le bouton
					$('a#modideas_nostralight_demarche_button_button').hide();
				}
				$('#modideas_nostralight_demarche_detail').html(html+'</small>');
			})
			.error( function () {
				alert('Erreur de communication avec Synapse');
			});
		}

	</script>
@stop