<?php
$lastRevision=($eform) ? $eform->getLastRevisionEform() : null;
$aPiecesStates = DemarchePieceState::all ();
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
		<div class="col-md-8">
			<div class="block-flat">
				<div class="header">
					<h3><span class="text-primary">Intégration du formulaire <em>{{ManageableModel::formatId($nostraForm->nostra_id)}}</span> {{$nostraForm->title}}</em></h3>
				</div>
				<div class="content">
					<p>
						<strong>Id slot : </strong>{{ManageableModel::formatId($nostraForm->form_id)}}<br/>
						<strong>Langue : </strong>{{$nostraForm->language}}<br/>
						<strong>Priorité : </strong>{{$nostraForm->priority}}<br/>
						<strong>Format : </strong>{{$nostraForm->format}}<br/>
						<strong>Url : </strong><a href="{{$nostraForm->url}}" target="_blank">{{$nostraForm->url}}</a><br/>
						<strong>Formulaire intelligent : </strong>@if ($nostraForm->smart >0) oui @else non @endif<br/>
						<strong>Signable électroniquement : </strong>@if ($nostraForm->esign >0) oui @else non @endif<br/>
						<strong>Simplifié : </strong>@if ($nostraForm->simplified > 0) oui @else non @endif
					</p>
					<h3>
						@if (is_object($eform))
							Vous avez choisi de <span class="text-primary">fusionner ce formulaire</span> Nostra avec un formulaire existant dans Synapse
						@else
							Vous avez choisi de <span class="text-primary">créer un nouveau formulaire</span> dans Synapse sur base de ce formulaire Nostra
						@endif
					</h3>
					<h4>
						Résultat de cette opération :
					</h4>
					@if (is_object($eform))
						<p>Le formulaire présent dans Synapse va être fusionné avec ce formulaire Nostra.</p>
						<?php
							$changes = [];
							if ($nostraForm->form_id	!= $eform->form_id) 	{ array_push($changes, "L'id de slot va passer de <em>".(strlen($eform->form_id)?$eform->form_id:'indéfini')."</em> à <em>".$nostraForm->form_id."</em>."); }
							if ($nostraForm->title 		!= $eform->title) 		{ array_push($changes, "Le nom du formulaire va passer de <em>".(strlen($eform->title)?$eform->title:'indéfini')."</em> à <em>".$nostraForm->title."</em>."); }
							if ($nostraForm->language 	!= $eform->language)	{ array_push($changes, "La langue va passer de <em>".(strlen($eform->language)?$eform->language:'indéfini')."</em> à <em>".$nostraForm->language."</em>."); }
							if ($nostraForm->url 		!= $eform->url) 		{ array_push($changes, "L'url va passer de <em>".(strlen($eform->url)?$eform->url:'indéfini')."</em> à <em>".$nostraForm->url."</em>."); }
							if ($nostraForm->smart 		!= $eform->smart) 		{ array_push($changes, "Formulaire intelligent va passer de <em>".(strlen($eform->smart)?$eform->smart:'indéfini')."</em> à <em>".$nostraForm->smart."</em>."); }
							if ($nostraForm->priority 	!= $eform->priority)	{ array_push($changes, "La priorité va passer de <em>".(strlen($eform->priority)?$eform->priority:'indéfini')."</em> à <em>".$nostraForm->priority."</em>."); }
							if ($nostraForm->esign 		!= $eform->esign) 		{ array_push($changes, "La signature va passer de <em>".(strlen($eform->esign)?$eform->esign:'indéfini')."</em> à <em>".$nostraForm->esign."</em>."); }
							if ($nostraForm->format 	!= $eform->format) 		{ array_push($changes, "Le format va passer de <em>".(strlen($eform->format)?$eform->format:'indéfini')."</em> à <em>".$nostraForm->format."</em>."); }
							if ($nostraForm->simplified != $eform->simplified)	{ array_push($changes, "Simplifié va passer de <em>".(strlen($eform->simplified)?$eform->simplified:'indéfini')."</em> à <em>".$nostraForm->simplified."</em>."); }
						?>
						@if (count($changes))
							<p>Les modifications suivantes vont être apportées au formulaire existant :</p>
							<ul>
								@foreach($changes as $change)
									<li>{{$change}}</li>
								@endforeach
							</ul>
						@endif

					@else
						<p>Un nouveau eform va ête créé dans Synapse avec les informations tirées de Nostra (voir ci-dessus).</p>
					@endif

					<hr/>
					<form class="form-horizontal" method="post" autocomplete="off" action="{{ $nostraForm->routePostCreateValidation($nostraForm->id) }}">
						<input type="hidden" name="_token" id="_token" value="{{{ csrf_token() }}}" />
						<input type="hidden" name="eform" id="eform" value="{{$eform ? $eform->id : '-1'}}" />
						<div class="form-group {{{ $errors->has('description') ? 'has-error' : '' }}}">
							<label class="col-md-2 control-label" for="name">Description</label>
							<div class="col-md-10">
								<textarea style="height: 100px;" class="form-control" name="description" id="description">{{{ Input::old('description', $eform ? $eform->description : null) }}}</textarea>
								{{ $errors->first('description', '<span class="help-inline">:message</span>') }}
							</div>
						</div>

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
								<a class="btn btn-default" href="{{$nostraForm->routeGetIndex()}}">{{Lang::get('button.cancel')}}</a>
								<button type="submit" class="btn btn-primary">{{Lang::get('button.save')}}</button>
							</div>
						</div>

					</form>
				</div>
			</div>
		</div>
		<?php /* @include('admin.forms.eforms.partial-sidebar') */ ?>
		<div class="col-md-4">
			<div class="block-flat">
				<a class="btn btn-cancel" href="{{{ $nostraForm->routeGetIndex() }}}">Retour à la liste</a>
			</div>
		</div>
	</div>
</div>
@stop