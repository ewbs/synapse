<?php
/*
 * @var Eform $modelInstance
 */
$lastRevision=($modelInstance) ? $modelInstance->getLastRevisionEform() : null;
$nostraEditable=!($modelInstance && $modelInstance->nostra_form_id); // Les champs nostra ne peuvent être éditables que si l'eform n'est pas lié à un nostra_form
$aPiecesStates = DemarchePieceState::all ();

$language = Input::old('language', $lastRevision ? $lastRevision->language : '');
$priority = Input::old('priority', $lastRevision ? $lastRevision->priority : '');
$format = Input::old('format', $lastRevision ? $lastRevision->format : '');
$current_state = Input::old('current_state', $lastRevision ? $lastRevision->current_state_id : '');
$next_state = Input::old('next_state', $lastRevision ? $lastRevision->next_state_id : '');
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
	<h2><span class="fa fa-wpforms"></span> {{Lang::get('admin/eforms/messages.title');}}</h2>
</div>
<div class="cl-mcont">
	<div class="row">
		<div class="col-md-{{ ($modelInstance)?8:12 }}">
			<div class="block-flat">
				<div class="header"><h3>{{ ($modelInstance ? 'Edition' : 'Création') }} d'un formulaire</h3></div>
				<div class="content">
					<form class="form-horizontal" method="post" autocomplete="off" action="{{ ($modelInstance) ? $modelInstance->routePostEdit() : $model->routePostCreate() }}">
						<input type="hidden" name="_token" id="_token" value="{{{ csrf_token() }}}" />
						
						<div class="form-group {{{ $errors->has('description') ? 'has-error' : '' }}}">
							<label class="col-md-2 control-label" for="name">Description</label>
							<div class="col-md-10">
								<textarea style="height: 100px;" class="form-control" name="description" id="description">{{{ Input::old('description', $modelInstance ? $modelInstance->description : null) }}}</textarea>
								{{ $errors->first('description', '<span class="help-inline">:message</span>') }}
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
									{{ $errors->first('form_id', '<span class="help-inline">:message</span>') }}
								</div>
							</div>
							
							<div class="form-group {{{ $errors->has('language') ? 'has-error' : '' }}}">
								<label class="col-md-2 control-label" for="language">Langue</label>
								<div class="col-md-10">
									<select class="form-control" name="language" id="language"{{ ($nostraEditable ? '':' disabled') }}/>
										<option></option>
										@foreach(NostraForm::distinctColumn('language') as $item)
										<option value="{{ $item->language }}"{{ $language==$item->language ?' selected':'' }}>{{ $item->language }}</option>
										@endforeach
									</select>
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
								</div>
							</div>
							
							<div class="form-group {{{ $errors->has('url') ? 'has-error' : '' }}}">
								<label class="col-md-2 control-label" for="url">Url</label>
								<div class="col-md-10">
									<input class="form-control" type="text" name="url" id="url" value="{{ Input::old('url', $lastRevision ? $lastRevision->url : '') }}"{{ ($nostraEditable ? '':' readonly') }}/>
									{{ $errors->first('url', '<span class="help-inline">:message</span>') }}
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
						<fieldset>
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
								</div>
							</div>
							
							<div class="form-group {{{ $errors->has('comment') ? 'has-error' : '' }}}">
								<label class="col-md-2 control-label" for="comment">Commentaire</label>
								<div class="col-md-10">
									<textarea style="height: 100px;" class="form-control" name="comment" id="comment">{{{ Input::old('comment', null) }}}</textarea>
									{{ $errors->first('comment', '<span class="help-inline">:message</span>') }}
								</div>
							</div>
						</fieldset>
						
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
		@if ($modelInstance)
			@include('admin.forms.eforms.partial-sidebar')
		@endif
	</div>
</div>
@stop