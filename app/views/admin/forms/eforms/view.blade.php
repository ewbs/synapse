<?php
/**
 * @var Eform $modelInstance
 */
$lastRevision=$modelInstance->getLastRevisionEform();
?>

@extends('site.layouts.container-fluid')
@section('title')<span class="text-primary">{{$modelInstance->formatedId()}}</span> {{$lastRevision->title}} @stop
@section('content')
<div class="row">
	<div class="col-md-4">
		<div class="block-flat">
			<div class="header">
				<h3>Données Nostra</h3>
			</div>
			<div class="content">
				@if($modelInstance->description)
				<h4>Description</h4>
				<p>{{nl2br($modelInstance->description)}}</p>
				@endif
				
				<fieldset>
					@if($lastRevision->nostra_id)
					<p><strong>Id Nostra : </strong>{{ManageableModel::formatId($lastRevision->nostra_id)}}</p>
					@endif
					
					@if($lastRevision->form_id)
					<p><strong>Id slot : </strong>{{ManageableModel::formatId($lastRevision->form_id)}}</p>
					@endif
					
					@if($lastRevision->language)
					<p><strong>Langue : </strong>{{$lastRevision->language}}</p>
					@endif
					
					@if($lastRevision->priority)
					<p><strong>Priorité : </strong>{{$lastRevision->priority}}</p>
					@endif
					
					@if($lastRevision->format)
					<p><strong>Format : </strong>{{$lastRevision->format}}</p>
					@endif
					
					@if($lastRevision->url)
					<p><strong>Url : </strong><a href="{{$lastRevision->url}}" target="_blank">{{$lastRevision->url}}</a></p>
					@endif
					
					<p><strong>Formulaire intelligent : </strong>@if ($lastRevision->smart >0) oui @else non @endif</p>
					
					<p><strong>Signable électroniquement : </strong>@if ($lastRevision->esign >0) oui @else non @endif</p>
					
					<p><strong>Simplifié : </strong>@if ($lastRevision->simplified > 0) oui @else non @endif</p>
				</fieldset>
			</div>
		</div>
	</div>
	<div class="col-md-8">
		<div class="block-flat">
			<div class="header">
				<h3>Révisions</h3>
			</div>
			<div class="content">
				<fieldset>
					<div class="table-responsive">
						<table class="table table-hover datatable" data-ajaxurl="{{ route('eformsRevisionsGetData', $modelInstance->id) }}" data-bFilter="true" data-bSort="false" data-bPaginate="true">
							<thead>
								<tr>
									<th class="col-md-2">Révision</th>
									<th class="col-md-2">Etat courant</th>
									<th class="col-md-2">Etat suivant</th>
									<th>Commentaire</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</fieldset>
			</div>
		</div>
	</div>
</div>
@stop
