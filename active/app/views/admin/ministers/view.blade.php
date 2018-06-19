<?php
/**
 * Visualisation d'un ministre
 * 
 * @var Minister $modelInstance
 */
$ideas=$modelInstance->ideas()->getQuery()->orderBy('id')->get(['id', 'name']);
?>
@extends('site.layouts.container-fluid')
@section('title')<span class="text-primary">{{$modelInstance->formatedId()}}</span> {{$modelInstance->name()}} @stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			<div class="header">
				<h3>Fonctions</h3>
			</div>
			<div class="content">
				<div class="table-responsive mandates">
					<table class="table table-hover datatable" data-ajaxurl="{{route('ministersMandatesGetData', $modelInstance->id)}}" data-bFilter="true" data-bSort="true" data-bPaginate="true" data-desc="true">
						<thead>
							<tr>
								<th>Début</th>
								<th>Fin</th>
								<th>Gouvernement</th>
								<th>Fonction</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
				@if($modelInstance->canManage())
				<div class="form-group">
					<a class="btn btn-sm btn-primary servermodal" href="{{route('ministersMandatesGetCreate', [$modelInstance->id])}}" data-reload-datatable="div.mandates table.datatable"><i class="fa fa-plus"></i> Ajouter une fonction</a>
				</div>
				@endif
			</div>
		</div>
		<div class="block-flat">
			<div class="header">
				<h3>Projets liés</h3>
			</div>
			<div class="content">
				@if($ideas->isEmpty())
					<p>Ce ministre n'est lié à aucun projet de simplif'.</p>
				@else
				<ul class="list-group">
					@foreach ($ideas as $idea)
					<li class="list-group-item">
						<strong>{{DateHelper::year($idea->created_at) . '-' . str_pad ( $idea->id, 4, "0", STR_PAD_LEFT )}}</strong>
						<a href="{{ route('ideasGetView', $idea->id) }}">{{$idea->name}}</a>
					</li>
					@endforeach
				</ul>
				@endif
			</div>
		</div>
	</div>
</div>
@stop