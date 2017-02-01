@extends('site.layouts.container-fluid')
@section('title')Intégration du formulaire <em><span class="text-primary">{{ManageableModel::formatId($nostraForm->nostra_id)}}</span> {{$nostraForm->title}}</em> @stop
@section('content')
<div class="row">
	<div class="col-md-4">
		<div class="block-flat">
			<div class="header"><h3>Données Nostra</h3></div>
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
			</div>
		</div>
	</div>
	<div class="col-md-8">
		<div class="block-flat">
			<div class="header"><h3>Sélectionnez le formulaire existant à fusionner, ou créez-en un nouveau</h3></div>
			<div class="content">
				<form class="form-horizontal" method="post" autocomplete="off" action="{{ $nostraForm->routePostCreate($nostraForm->id) }}">
					<input type="hidden" name="_token" id="_token" value="{{{ csrf_token() }}}" />
					@if (count($possibleExistingForms))
						<strong class="text-danger">
						@if (count($possibleExistingForms) > 1)
							Les formulaires suivants ressemblent à ce formulaire Nostra :
						@else
							Le formulaire suivant ressemble à ce formulaire Nostra :
						@endif
						</strong>
						<div class="form-group">
							@foreach ($possibleExistingForms as $form)
								<div class="radio">
									<label><strong>
										<input type="radio" name="eform" class="icheck" value="{{$form->id}}"> {{$form->title}}</strong>
									</label>
								</div>
							@endforeach
						</div>
						<hr/>
					@endif
					<div class="form-group">
						@foreach ($eForms as $form)
							<div class="radio">
								<label>
									<input type="radio" name="eform" class="icheck" value="{{$form->id}}"> {{$form->title}}
								</label>
							</div>
						@endforeach
					</div>
					<hr/>
					<div class="form-group">
						<div class="radio">
							<label>
								<input type="radio" name="eform" class="icheck" value="-1" checked> Créer un nouveau formulaire dans Synapse
							</label>
						</div>
					</div>
					<div class="form-group">
						<a class="btn btn-cancel" href="{{{ $nostraForm->routeGetIndex() }}}">Annuler</a>
						<button type="submit" class="btn btn-primary">{{Lang::get('button.save')}}</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@stop