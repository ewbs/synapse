@extends('site.layouts.container-fluid')
@section('title')Personnel eWBS @stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			<div class="header">
				<div class="pull-right">
					@if ($trash)
					<a class="btn btn-small btn-default" href="{{ $model->routeGetIndex() }}">Retour à la liste</a>
					@else
					<a href="{{ $model->routeGetCreate() }}" class="btn btn-small btn-primary"><i class="glyphicon glyphicon-plus-sign"></i> Ajouter un membre du personnel</a>
					@endif
				</div>
				<h3>Liste des membres du personnel eWBS @if ($trash) supprimés @endif</h3>
			</div>
			<div class="content">
				<div class="table-responsive">
					<table class="table table-hover datatable" data-ajaxurl="{{ $trash?$model->routeGetDataTrash():$model->routeGetData() }}" data-bFilter="true" data-bSort="true" data-bPaginate="true">
						<thead>
							<tr>
								@if ($trash)
								<th class="col-md-2">Supprimé le</th>
								@endif
								<th class="col-md-2">Login</th>
								<th class="col-md-2">Nom</th>
								<th class="col-md-2">Prénom</th>
								<th>Fonction</th>
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