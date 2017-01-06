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
					<h3><span class="text-primary">{{ManageableModel::formatId($nostraForm->nostra_id)}}</span> {{$nostraForm->title}}</h3>
				</div>
				<div class="content">
						<p><strong>Id slot : </strong>{{ManageableModel::formatId($nostraForm->form_id)}}</p>
						<p><strong>Langue : </strong>{{$nostraForm->language}}</p>
						<p><strong>Priorité : </strong>{{$nostraForm->priority}}</p>
						<p><strong>Format : </strong>{{$nostraForm->format}}</p>
						<p><strong>Url : </strong><a href="{{$nostraForm->url}}" target="_blank">{{$nostraForm->url}}</a></p>
						<p><strong>Formulaire intelligent : </strong>@if ($nostraForm->smart >0) oui @else non @endif</p>
						<p><strong>Signable électroniquement : </strong>@if ($nostraForm->esign >0) oui @else non @endif</p>
						<p><strong>Simplifié : </strong>@if ($nostraForm->simplified > 0) oui @else non @endif</p>
				</div>
			</div>
		</div>
		<?php /* @include('admin.forms.eforms.partial-sidebar') */ ?>
		<div class="col-md-4">
			<div class="block-flat">
				<div class="content">
					<div class="alert alert-warning alert-white rounded">
						<div class="icon"><i class="fa fa-exclamation-triangle"></i></div>
						Ce formulaire est bien présent dans Nostra mais n'est <strong>pas encore intégré</strong>.
						Il n'est donc pas encore exploitable dans les différents modules de Synapse.
					</div>
				</div>
			</div>
			<div class="block-flat">
				<div class="content">
					<a class="btn btn-cancel" href="{{{ $nostraForm->routeGetIndex() }}}">Retour à la liste</a>
					<a href="{{route('eformsGetCreateFromDamus', $nostraForm->id)}}" class="btn btn-primary">Intégrer à Synapse</a>
				</div>
			</div>
		</div>
	</div>
</div>
@stop