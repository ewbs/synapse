@extends('site.layouts.container-fluid')
@section('title')Documentation de la démarche <em>{{ $nostraDemarche->title }}</em> @stop
@section('content')
<form id="create-edit-demarche-form" method="post" action="{{ $modelInstance->routeGetEdit() }}" autocomplete="off" class="form-horizontal">
	<input type="hidden" id="_token" name="_token" value="{{{ csrf_token() }}}" /> <input type="hidden" name="nostra_demarche" value="{{$nostraDemarche->id}}" />
	<div class="row">
		<div class="col-md-8">
			<div class="block-flat">
				<div class="header">
					<h4>Informations générales</h4>
				</div>
				<div class="content">

					<!-- administrations impliquées -->
					<?php $aSelectedAdministrations = Input::old('administrations', isset($aSelectedAdministrations) ? $aSelectedAdministrations : array()); ?>
					<div class="form-group">
						<label class="col-md-2 control-label" for="administrations">Administration(s)
							impliquée(s)</label>
						<div class="col-md-10">
							<select class="select2" multiple name="administrations[]" id="administrations">
								<?php
								// TODO : truc pas très propre que je fais dans la vue pour le moment, mais ce serait bien mieux à faire dans le controlleur.
								// on regarde si l'utilisateur connecté a des restrictions d'accès sur les administrations.
								// on n'affichera pas les administrions auquelles il n'a pas accès (on place les acceptées dans un tableau, simplement)
								// FIXME : Si jamais la démarche était déjà associée à une admin à laquelle on n'a pas droit, mais qu'elle est aussi associée à une admin à laquelle on a droit, on perd le lien correspondant à l'administration à laquelle on n'a pas droit lors de l'édition !
								$restrictedAdministrations = $loggedUser->getRestrictedAdministrationsIds ();
								?>
								@foreach($aRegions as $region)
								<optgroup label="{{$region->name}}">
									@foreach($region->administrations()->orderBy('name')->get() as $administration)
										@if (count($restrictedAdministrations) > 0)
											@if (in_array($administration->id, $restrictedAdministrations))
											<option value="{{$administration->id}}" {{ in_array($administration->id, $aSelectedAdministrations) ? ' selected' : '' }}>{{$administration->name}}</option>
											@endif
										@else
										<option value="{{$administration->id}}" {{ in_array($administration->id, $aSelectedAdministrations) ? ' selected' : '' }}>{{$administration->name}}</option>
										@endif
									@endforeach
								</optgroup>
								@endforeach
							</select> @optional
						</div>
					</div>
					<!-- ./ administrations impliquées -->

					<!-- volume -->
					<div class="form-group">
						<label class="col-md-2 control-label" for="">Volume</label>
						<div class="col-md-10">
							<select class="select2" name="volume">
								<option value=""></option>
								@foreach($aVolumes as $vol)
									<option {{{ Input::old('volume', isset($modelInstance) ? ($modelInstance->volume ? "selected" : "") : "") }}} value="{{$vol}}">{{$vol}}</option>
								@endforeach
							</select>
							@optional
						</div>
					</div>
					<!-- /volume -->

					<!-- Périmetre eWBS -->
					<div class="form-group">
						<label class="col-md-2 control-label" for="">Périmètre d'action eWBS ?</label>
						<div class="col-md-10">
							<div class="switch">
								<input type="checkbox" name="ewbs" {{{ Input::old('ewbs', isset($modelInstance) ? ($modelInstance->ewbs ? "checked" : "") : "") }}}/>
							</div>
						</div>
					</div>
					<!-- ./ Périmetre eWBS -->

					<!-- utilisation du formulaire electronique -->
					@if ( ! count($nostraDemarche->nostraForms) )
					<input type="hidden" name="eform_usage" value="0" />
					@else
					<div class="form-group {{{ $errors->has('eform_usage') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="gain_real">Usage e-formulaire (%)</label>
						<div class="col-md-10">
							<input name="eform_usage" class="bslider form-control"
								type="text" data-slider-max="100" data-slider-min="0"
								data-slider-value="{{{ Input::old('eform_usage', $modelInstance->eform_usage) }}}"
								value="{{{Input::old('eform_usage', $modelInstance->eform_usage) }}}" />
							{{ $errors->first('eform_usage', '<span class="help-inline">:message</span>') }}
						</div>
					</div>
					@endif
					<!-- ./ utilisation du formulaire electronique -->

					<!-- Personne de contact -->
					<div class="form-group">
						<label class="col-md-2 control-label" for="personne_de_contact">Personne de contact</label>
						<div class="col-md-10">
							<textarea style="height: 100px;" class="form-control" name="personne_de_contact" id="personne_de_contact" >{{ Input::old('personne_de_contact', isset($modelInstance) ? $modelInstance->personne_de_contact : "") }}</textarea>
						</div>
					</div>
					<!-- ./ Personne de contact -->

					<!-- commentaire -->
					<div class="form-group {{{ $errors->has('comment') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="name">Commentaire</label>
						<div class="col-md-10">
							<textarea style="height: 100px;" class="form-control" name="comment" id="comment">{{ Input::old('comment', isset($modelInstance) ? $modelInstance->comment : "") }}</textarea>
							@optional
							{{ $errors->first('comment', '<span class="help-inline">:message</span>')}}
						</div>
					</div>
					<!-- ./ commentaire -->

				</div>
			</div>

			<!-- Documentation -->
			<div class="block-flat">
				<div class="header">
					<h4>Documentation</h4>
				</div>
				<div class="content">
					<div class="form-group">
						<div class="col-sm-11 col-sm-offset-1" id="docLinksContainer">
							@if ( ! count($modelInstance->docLinks) )
							<p>Aucune documentation n'a été liée à cette démarche. Ajoutez en dès maintenant.</p>
							@else
								@foreach($modelInstance->docLinks as $link)
								<div class="onedocLink">
									<input type="hidden" name="docLinkId[]" value="{{$link->id}}" />
									<div class="form-group">
										<label class="col-sm-2 control-label">Titre du lien</label>
										<div class="col-sm-10">
											<input name="docLinkTitle[]" class="form-control" type="text" placeholder="Nommez votre lien" value="{{$link->name}}" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">URL du lien</label>
										<div class="col-sm-10">
											<input name="docLinkURL[]" class="form-control" type="text" placeholder="Indiquez le lien (http(s); file:// ou autre)" value="{{$link->url}}" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Description</label>
										<div class="col-sm-10">
											<textarea name="docLinkDescription[]" name="docLink[]" class="form-control" placeholder="Explication éventuelle">{{$link->description}}</textarea>
											@optional
										</div>
									</div>
									<a class="pull-right deleteDocLink"><span class="fa fa-remove"></span>
										supprimer ce lien</a>
									<div class="clearfix"></div>
									<hr />
								</div>
								@endforeach
							@endif
							{{-- Les input sont volontairement en disabled sinon ils passent aussi dans le POST du formulaire et je ne le veux pas ne pas modifier cela. jQuery les enable quand nécessaire. --}}
							<div id="newLinkPattern" class="hidden">
								<div class="onedocLink">
									<input disabled="disabled" type="hidden" name="docLinkId[]" value="-1" />
									<div class="form-group">
										<label class="col-sm-2 control-label">Titre du lien</label>
										<div class="col-sm-10">
											<input disabled="disabled" name="docLinkTitle[]" class="form-control" type="text" placeholder="Nommez votre lien" value="" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">URL du lien</label>
										<div class="col-sm-10">
											<input disabled="disabled" name="docLinkURL[]"
												class="form-control" type="text"
												placeholder="Indiquez le lien (http(s); file:// ou autre)"
												value="" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label">Commentaire</label>
										<div class="col-sm-10">
											<textarea disabled="disabled" name="docLinkDescription[]" class="form-control" placeholder="Explication éventuelle"></textarea>
										</div>
									</div>
									<a class="pull-right deleteDocLink"><span class="fa fa-remove"></span>supprimer ce lien</a>
									<div class="clearfix"></div>
									<hr />
								</div>
							</div>
							<a class="btn btn-default btn-sm" id="btn-add-docLink">Ajouter un lien de documentation</a>
						</div>
					</div>
				</div>
			</div>
			<!-- ./ Documentation externe -->

			<!-- Taxonomie -->
			<div class="block-flat">
				<div class="header">
					<h4>Taxonomie</h4>
				</div>
				<div class="content">
					<!-- tags -->
					<?php
					// recherche de l'élément à selectionner
					$selectedTags = [ ];
					if ($modelInstance)
						$selectedTags = $aSelectedTags; // passée par le controlleur (voir function getManage());
					if (Input::old ( 'tags' ))
						$selectedTags = Input::old ( 'tags' );
					?>
					<div class="form-group">
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
							@optional
						</div>
					</div>
				</div>
			</div>
			<!-- ./Taxonomie -->

			<div class="block-flat">
				<div class="header">
					<h3>Gains</h3>
				</div>
				<div class="content">
					@warning('<br />Par défaut les gains sont automatiquement calculés sur base des sommes des gains des différentes pièces et tâches liées à la démarche.<br />Si vous souhaitez modifier manuellement ces montants, ils ne seront donc plus automatiquement calculés suite aux modifications effectuées au niveau des pièces et tâches de la démarche.<br /><a id="unlockGains" class="btn btn-default" href="#">Modifier manuellement ces montants</a><a id="lockGains" class="btn btn-default" style="display: none" href="#">Remettre les montants initiaux</a>')
					<?php
					$gains = [
					'gain_potential_administration' => 'Gain potentiel administration',
					'gain_potential_citizen' => 'Gain potentiel usager',
					'gain_real_administration' => 'Gain effectif administration',
					'gain_real_citizen' => 'Gain effectif usager'
					];
					?>
					@foreach($gains as $name=>$label)
					<div class="form-group {{{ $errors->has($name) ? 'has-error' : '' }}}">
						<label for="{{$name}}" class="col-md-4">{{{ $label }}}</label>
						<div class="col-md-8">
							<div class="input-group">
								<input name="{{$name}}"
									data-old="{{{($lastRevision?$lastRevision->$name:'')}}}"
									class="form-control decimalNumber lockedGain" type="text"
									value="{{{Input::old($name, (($lastRevision && $lastRevision->$name)?(NumberHelper::decimalFormat($lastRevision->$name)):'')) }}}"
									placeholder="calculé ({{{ NumberHelper::decimalFormat($calculatedGains?$calculatedGains->$name:0) }}})"
									disabled /> <span class="input-group-addon">€</span>
							</div>
						</div>
						{{ $errors->first($name, '<span class="help-inline">:message</span>
						<script type="text/javascript">var mustUnlockGains=true;</script>
						')}}
					</div>
					@endforeach
					<!-- ./ Gains -->
				</div>
			</div>
		</div>
		<div class="col-md-4">
			@include('admin.demarches.blocs.projets_lies',['manage'=>true])
			@include('admin.demarches.blocs.infos_nostra',['manage'=>true])
		</div>
	</div>

	<div class="row">
		<div class="block-flat">
			<div class="content no-padding">
				<div class="form-group no-padding no-margin">
					<div class="col-md-12">
						<a class="btn btn-lg" href="{{ $modelInstance ? $modelInstance->routeGetView() : (isset($returnTo) && $returnTo ? route($returnTo) : $model->routeGetIndex()) }}">{{Lang::get('button.cancel')}}</a>
						<button type="submit" class="btn btn-primary btn-lg">{{Lang::get('button.save')}}</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
@stop

@section('scripts')
<script lang="javascript">
	$(document).ready(function () {
		$("#sidebar-collapse").trigger("click"); //fermer la sidebar
	});
</script>
@stop
