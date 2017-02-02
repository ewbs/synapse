@extends('site.layouts.container-fluid')
@section('title')Catalogue des pièces justificatives @stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			<div class="header">
				<div class="pull-right">
					@if ($trash)
					<a class="btn btn-small btn-default" href="{{ $model->routeGetIndex() }}">Retour à la liste</a>
					@elseif($loggedUser->can('pieces_tasks_manage'))
					<a href="{{ $model->routeGetCreate() }}" class="btn btn-small btn-primary"><i class="glyphicon glyphicon-plus-sign"></i> Ajouter une pièce</a>
					@endif
				</div>
				<h3>Liste des pièces justificatives @if ($trash) supprimées @endif</h3>
			</div>
			<div class="content">
				<div class="table-responsive">
					<table class="table table-hover datatable" data-ajaxurl="{{ $trash?$model->routeGetDataTrash():$model->routeGetData() }}" data-bFilter="true" data-bSort="true" data-bPaginate="true">
						<thead>
							<tr>
								@if ($trash)
								<th class="col-md-2">Supprimé le</th>
								@endif
								<th>Nom</th>
								<?php /* <th class="col-md-2">Nature</th> */ ?>
								<th class="col-md-1">Coût adm.</th>
								<th class="col-md-1">Coût usager</th>
								<th class="col-md-1">Actions</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@stop