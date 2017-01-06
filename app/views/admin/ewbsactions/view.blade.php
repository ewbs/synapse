<?php
/**
 * Visualisation d'une action
 * 
 * @var EwbsAction $modelInstance
 * @var EwbsActionRevision $revision
 */
?>
@extends('site.layouts.default') {{-- Web site Title --}}
@section('title') Bienvenue sur Synapse @parent @stop {{-- Content --}}
@section('content')

<div class="page-head">
	<h2><span class="fa fa-magic"></span> Log d'action</h2>
</div>
<div class="cl-mcont">
	<div class="row">
		<div class="col-md-9">
			<div class="block-flat">
				<div class="header">
					<h3><span class="text-primary">{{$modelInstance->formatedId()}}</span> {{$modelInstance->name}}</h3>
				</div>
				<div class="content">
					@include('admin.ewbsactions.history-timeline', ['modelInstance'=>$modelInstance])
				</div>
			</div>
			
			<div class="block-flat" id="subactions">
				<div class="header">
					<h3>Sous-actions</h3>
				</div>
				<div class="content">
					<div class="table-responsive">
						<table class="table table-hover datatable" data-ajaxurl="{{ route('ewbsactionsSubGetData', $modelInstance->id) }}" data-bFilter="true" data-bSort="true" data-bPaginate="true">
							<thead>
								<tr>
									<th class="col-md-1">#</th>
									<th>Nom</th>
									<th class="col-md-1">Etat</th>
									<th class="col-md-1">Priorité</th>
									<th class="col-md-2">Révision</th>
									<th class="col-md-2">Actions</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
					@if ($modelInstance->canManage() && $modelInstance->sub)
						<div class="form-group">
							<a class="btn btn-sm btn-primary servermodal" href="{{route('ewbsactionsSubGetCreate', $modelInstance->id)}}" data-reload-datatable="#subactions table.datatable"><i class="fa fa-plus"></i> Ajouter une sous-action</a>
						</div>
					@endif
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="block-flat">
				<div class="content no-padding no-margin">
					@include('admin.modelInstance.partial-features')
				</div>
			</div>
			<div class="block-flat">
				<div class="header">
					<h3>Liens</h3>
				</div>
				<div class="content">
					@if ($modelInstance->demarche)
						<p>En lien avec la démarche <a href="{{ route('demarchesGetView', $modelInstance->demarche->id) }}">{{$modelInstance->demarche->nostraDemarche->title}}</a>.</p>
						@if ($modelInstance->demarche_piece_id)
							<p>En lien avec la pièce <i class="fa fa-clipboard"></i> {{$modelInstance->demarchePiece->piece->name}}.</p>
						@endif
						@if ($modelInstance->demarche_task_id)
							<p>En lien avec la tâche <i class="fa fa-task"></i> {{$modelInstance->demarcheTask->task->name}}.</p>
						@endif
					@elseif ($modelInstance->idea)
						<p>En lien avec le projet de simplif' <a href="{{ route('ideasGetView', $modelInstance->idea->id) }}">{{$modelInstance->idea->name}}</a>.</p>
						{{-- TODO: en lien avec le formulaire/annexe --}}
					@endif
				</div>
			</div>
			<div class="block-flat">
				<div class="header">
					<h3>Taxonomie</h3>
				</div>
				<div class="content">
					@foreach($modelInstance->tags as $tag)
						<span class="badge badge-default">{{$tag->name}}</span>
					@endforeach
				</div>
			</div>
		</div>
	</div>
</div>
@stop