<?php
/**
 * @var Eform $modelInstance
 */
$lastRevision=($modelInstance) ? $modelInstance->getLastRevisionEform() : null;
$nostraEditable=!($modelInstance && $modelInstance->nostra_form_id); // Les champs nostra ne peuvent être éditables que si l'eform n'est pas lié à un nostra_form
$aPiecesStates = DemarchePieceState::all ();
$disponible_en_ligne_items = Eform::disponibleEnLigne();
$deposable_en_ligne_items = Eform::deposableEnLigne();
$dematerialisation_items = Eform::dematerialisation();
$dematerialisation_canal_items = Eform::dematerialisationCanal();
$intervention_ewbs_items = Eform::interventionEwbs();


$language = Input::old('language', $lastRevision ? $lastRevision->language : '');
$priority = Input::old('priority', $lastRevision ? $lastRevision->priority : '');
$format = Input::old('format', $lastRevision ? $lastRevision->format : '');
$current_state = Input::old('current_state', $lastRevision ? $lastRevision->current_state_id : '');
$next_state = Input::old('next_state', $lastRevision ? $lastRevision->next_state_id : '');
?>

@extends('site.layouts.container-fluid')
@section('title'){{ ($modelInstance ? 'Edition' : 'Création') }} d'un formulaire @stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			<div class="header"><h3>{{ ($modelInstance ? 'Edition' : 'Création') }} d'un formulaire</h3></div>
			<div class="content">
				<form class="form-horizontal" method="post" autocomplete="off" action="{{ ($modelInstance) ? $modelInstance->routePostEdit() : $model->routePostCreate() }}">
					<input type="hidden" name="_token" id="_token" value="{{{ csrf_token() }}}" />

					<div class="form-group">
						<label class="col-md-2 control-label" for="name">Description</label>
						<div class="col-md-10">
							<textarea style="height: 100px;" class="form-control" name="description" id="description">{{{ Input::old('description', $modelInstance ? $modelInstance->description : null) }}}</textarea>
							@optional
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label" for="name">Disponible en ligne</label>
						<div class="col-md-10">
							<select class="form-control select2" name="disponible_en_ligne" id="disponible_en_ligne" data-placeholder="Veuillez choisir une option">
								@foreach($disponible_en_ligne_items as $key => $value)
									<option value="{{ $key }}"{{ Input::old('disponible_en_ligne', $modelInstance ? $modelInstance->disponible_en_ligne : '')==$key ?' selected':'' }}>{{ $value }}</option>
								@endforeach
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="name">Déposable en ligne</label>
						<div class="col-md-10">
							<select class="form-control select2" name="deposable_en_ligne" id="deposable_en_ligne" data-placeholder="Veuillez choisir une option">
								@foreach($deposable_en_ligne_items as $key => $value)
									<option value="{{ $key }}"{{ Input::old('deposable_en_ligne', $modelInstance ? $modelInstance->deposable_en_ligne : '')==$key ?' selected':'' }}> {{ $value }}</option>
								@endforeach
							</select>
						</div>
					</div>

					<div class="form-group {{{ $errors->has('dematerialisation') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="name">Dématérialisation</label>
						<div class="col-md-10">
							<select class="form-control select2 " name="dematerialisation" id="dematerialisation" data-placeholder="Veuillez choisir une option">
								@foreach($dematerialisation_items as $key => $value)
									<option value="{{ $key }}"{{ Input::old('dematerialisation', $modelInstance ? $modelInstance->dematerialisation : '')==$key ?' selected':'' }}> {{ $value }}</option>
								@endforeach
							</select>
							{{ $errors->first('dematerialisation', '<span class="help-inline red">:message</span>') }}
							<div class="dematerialisation_date" style="margin-top: 10px; {{Input::old('dematerialisation', $modelInstance ? $modelInstance->dematerialisation : '')=="oui" ? 'display: block' : 'display: none'}}">
								<input type="text" class="form-control" name="dematerialisation_date" id="dematerialisation_date" placeholder="mois/année"
									   value="{{ Input::old('dematerialisation_date', $modelInstance ? $modelInstance->dematerialisation_date : '') }}"
								/>
								<small class="pull-right">mois/année</small>
							</div>
							<div class="dematerialisation_canal" style="margin-top: 10px; {{Input::old('dematerialisation', $modelInstance ? $modelInstance->dematerialisation : '')=="deja_effectue" ? 'display: block' : 'display: none'}}">
								Canal de dématérialisation : <br/>
								<select class="form-control select2" name="dematerialisation_canal" id="dematerialisation_canal" data-placeholder="Veuillez choisir une option">
									@foreach($dematerialisation_canal_items as $key => $value)
										<option value="{{ $key }}"{{ Input::old('dematerialisation_canal', $modelInstance ? $modelInstance->dematerialisation_canal : '')==$key ?' selected':'' }}> {{ $value }}</option>
									@endforeach
								</select>
							</div>
							<div class="dematerialisation_canal_autres" style="margin-top: 10px; {{Input::old('dematerialisation_canal', $modelInstance ? $modelInstance->dematerialisation_canal : '')=="autres" ? 'display: block' : 'display: none'}}">
								<input type="text" class="form-control" name="dematerialisation_canal_autres" id="dematerialisation_canal_autres" placeholder="Indiquer un canal de dématérialisation"
									   value="{{ Input::old('dematerialisation_canal_autres', $modelInstance ? $modelInstance->dematerialisation_canal_autres : '') }}"
								/>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="name">Intervention eWBS</label>
						<div class="col-md-10">
							<select class="form-control select2" name="intervention_ewbs" id="intervention_ewbs" data-placeholder="Veuillez choisir une option">
								@foreach($intervention_ewbs_items as $key => $value)
									<option value="{{ $key }}"{{ Input::old('intervention_ewbs', $modelInstance ? $modelInstance->intervention_ewbs : '')==$key ?' selected':'' }}> {{ $value }}</option>
								@endforeach
							</select>
							<div id="ajouteruneaction" style="@if(Input::old('intervention_ewbs', $modelInstance ? $modelInstance->intervention_ewbs : '') === 'oui') display: block; @else display: none; @endif text-align:right; padding-top: 15px;">
								@if($modelInstance && $modelInstance->canManage())
									<button type="submit" class="btn btn-sm btn-primary servermodal" href="{{route('eformsActionsGetCreate', [$modelInstance->id])}}" data-reload-datatable="table#datatable-eforms-actions"><i class="fa fa-plus"></i> Ajouter une action</button>
								@endif
							</div>
						</div>
					</div>

					<div class="form-group {{{ $errors->has('references_contrat_administration') ? 'has-error' : '' }}}">
						<label class="col-md-2 control-label" for="name">Références Contrat d’administration</label>
						<div class="col-md-10">
							<input class="form-control" type="text" id="references_contrat_administration" name="references_contrat_administration"
								   value="{{ Input::old('references_contrat_administration', $modelInstance ? $modelInstance->references_contrat_administration : '') }}"
							/>
							{{ $errors->first('references_contrat_administration', '<span class="help-inline">:message</span>') }}
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="name">Remarques</label>
						<div class="col-md-10">
							<textarea style="height: 100px;" class="form-control" name="remarques" id="remarques">{{{ Input::old('remarques', $modelInstance ? $modelInstance->remarques : null) }}}</textarea>
							@optional
						</div>
					</div>

					{{-- Champs Nostra, avec gestion du fait que si l'eform est lié à un nostra_form, les champs nostra ne sont plus éditables (et il faut alors inviter l'utilisateur à demander une modif => cf. workflow créant une action et envoyant un mail à l'équipe NOSTRA) --}}
					<fieldset>
						<legend>Données Nostra</legend>

						<div class="form-group {{{ $errors->has('title') ? 'has-error' : '' }}}">
							<label class="col-md-2 control-label" for="title">Nom du formulaire</label>
							<div class="col-md-10">
								<input class="form-control" type="text" name="title" id="title" value="{{ Input::old('title', $lastRevision ? $lastRevision->title : '') }}"{{ ($nostraEditable ? '':' readonly') }}/>
								{{ $errors->first('title', '<span class="help-inline">:message</span>') }}
							</div>
						</div>

						<div class="form-group {{{ $errors->has('form_id') ? 'has-error' : '' }}}">
							<label class="col-md-2 control-label" for="form_id">ID Slot</label>
							<div class="col-md-10">
								<input class="form-control" type="text" name="form_id" id="form_id" value="{{ Input::old('form_id', $lastRevision ? $lastRevision->form_id : '') }}"{{ ($nostraEditable ? '':' readonly') }}/>
								@optional
								{{ $errors->first('form_id', '<span class="help-inline">:message</span>') }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="language">Langue</label>
							<div class="col-md-10">
								<select class="form-control" name="language" id="language"{{ ($nostraEditable ? '':' disabled') }}/>
									<option></option>
									@foreach(NostraForm::distinctColumn('language') as $item)
									<option value="{{ $item->language }}"{{ $language==$item->language ?' selected':'' }}>{{ $item->language }}</option>
									@endforeach
								</select>
								@optional
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="priority">Priorité</label>
							<div class="col-md-10">
								<select class="form-control" name="priority" id="priority"{{ ($nostraEditable ? '':' disabled') }}/>
									<option></option>
									@foreach(NostraForm::distinctColumn('priority') as $item)
									<option value="{{ $item->priority }}"{{ $priority==$item->priority ?' selected':'' }}>{{ $item->priority }}</option>
									@endforeach
								</select>
								@optional
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="format">Format</label>
							<div class="col-md-10">
								<select class="form-control" name="format" id="format"{{ ($nostraEditable ? '':' disabled') }}/>
									<option></option>
									@foreach(NostraForm::distinctColumn('format') as $item)
									<option value="{{ $item->format }}"{{ $format==$item->format ?' selected':'' }}>{{ $item->format }}</option>
									@endforeach
								</select>
								@optional
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="url">Url</label>
							<div class="col-md-10">
								<input class="form-control" type="text" name="url" id="url" value="{{ Input::old('url', $lastRevision ? $lastRevision->url : '') }}"{{ ($nostraEditable ? '':' readonly') }}/>
								@optional
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="smart">Formulaire intelligent</label>
							<div class="col-md-10">
								<div class="switch">
									<input type="checkbox" name="smart" value="1" {{ Input::old('smart', $lastRevision && $lastRevision->smart ? ' checked' : '') }}{{ ($nostraEditable ? '':' disabled') }}/>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="esign">Signable électroniquement</label>
							<div class="col-md-10">
								<div class="switch">
									<input type="checkbox" name="esign" value="1"{{ Input::old('esign', $lastRevision && $lastRevision->esign ? ' checked' : '') }}{{ ($nostraEditable ? '':' disabled') }}/>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="simplified">Simplifié</label>
							<div class="col-md-10">
								<div class="switch">
									<input type="checkbox" name="simplified" value="1"{{ Input::old('simplified', $lastRevision && $lastRevision->simplified ? ' checked' : '') }}{{ ($nostraEditable ? '':' disabled') }}/>
								</div>
							</div>
						</div>
						@if(!$nostraEditable)
						<div class="col-md-10 col-md-offset-2">
						@warning('<p>Ces données ne sont pas directement modifiables, car elles proviennent de Nostra.</p><button type="submit" id="nostraRequest" name="nostraRequest" value="true" class="btn btn-primary">Demander une modification à l\'équipe Nostra</button>')
						</div>
						@endif
					</fieldset>

					{{-- Champs sauvés au niveau de la révision --}}
					{{--<fieldset>
						<legend>Révision</legend>

						<div class="form-group">
							<label class="col-md-2 control-label" for="current_state">Etat courant</label>
							<div class="col-md-10">
								<select class="form-control" name="current_state" id="current_state">
									<option></option>
									@foreach($aPiecesStates as $item)
									<option value="{{ $item->id }}"{{ $current_state==$item->id ?' selected':'' }}>{{ $item->code }} : {{ $item->name }}</option>
									@endforeach
								</select>
								@optional
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="next_state">Etat suivant</label>
							<div class="col-md-10">
								<select class="form-control" name="next_state" id="next_state">
									<option></option>
									@foreach($aPiecesStates as $item)
									<option value="{{ $item->id }}"{{ $next_state==$item->id ?' selected':'' }}>{{ $item->code }} : {{ $item->name }}</option>
									@endforeach
								</select>
								@optional
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="comment">Commentaire</label>
							<div class="col-md-10">
								<textarea style="height: 100px;" class="form-control" name="comment" id="comment">{{{ Input::old('comment', null) }}}</textarea>
								@optional
							</div>
						</div>
					</fieldset>--}}

					<div class="form-group">
						<div class="col-md-offset-2 col-md-10">
							<a class="btn btn-cancel" href="{{ $modelInstance ? $modelInstance->routeGetView() : $model->routeGetIndex() }}">{{Lang::get('button.cancel')}}</a>
							<button type="submit" class="btn btn-primary">{{Lang::get('button.save')}}</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@stop

@section('scripts')
	<script lang="javascript">
        $(document).ready( function () {

            $("#deposable_en_ligne").change(function() {
                if(this.value === 'oui_formulaire_web_ou_application_en_ligne'){
                    $("#dematerialisation").val('deja_effectue').trigger('change');
                }
            });

            $("#dematerialisation").change(function() {
                if(this.value === 'oui'){
                    $(".dematerialisation_canal").hide();
                    $(".dematerialisation_date").show();
                }
                else if(this.value === 'deja_effectue'){
                    $(".dematerialisation_date").hide();
                    $(".dematerialisation_canal").show();
                }
                else {
                    $(".dematerialisation_date").hide();
                    $(".dematerialisation_canal").hide();
                }
            });

            $("#dematerialisation_canal").change(function() {
                if(this.value === 'autres'){
                    $(".dematerialisation_canal_autres").show();
                }
                else {
                    $(".dematerialisation_canal_autres").hide();
                }
            });

            $("#intervention_ewbs").change(function() {
                console.log(this.value);
               if(this.value === 'oui'){
                   $("#ajouteruneaction").show();
			   } else {
                   $("#ajouteruneaction").hide();
			   }
			});
        });
	</script>
@stop