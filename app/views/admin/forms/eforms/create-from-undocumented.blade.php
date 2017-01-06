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
					<h3>Sélectionnez le formulaire existant à fusionner, ou créez en un nouveau</h3>
					<form class="form-horizontal col-md-offset-1" method="post" autocomplete="off" action="{{ $nostraForm->routePostCreate($nostraForm->id) }}">
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
							<button type="submit" class="btn btn-primary">{{Lang::get('button.save')}}</button>
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