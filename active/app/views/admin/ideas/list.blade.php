@extends('site.layouts.container-fluid')
@section('title')Projets de simplification @stop
@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="block-flat">
				<div class="header">
					@if ($trash)
					<div class="pull-right"><a href="{{ $model->routeGetIndex() }}" class="btn btn-small btn-default">Retour à la liste</a></div>
					@else
					<div class="pull-right"><a href="{{ route('ideasGetExport') }}" class="btn btn-small btn-default"><i class="glyphicon glyphicon-download"></i> Exporter au format XLS</a></div>
						@if ($loggedUser->can('ideas_encode'))
						<div class="pull-right"><a href="{{ $model->routeGetCreate() }}" class="btn btn-small btn-primary"><i class="glyphicon glyphicon-plus-sign"></i> Ajouter un projet</a></div>
						@endif
					@endif
					<h3>Liste des projets de simplification @if ($trash) supprimés @endif</h3>
				</div>
				<div class="content">
					<div class="table-responsive">
						<table class="table table-hover datatable" data-ajaxurl="{{ $trash?$model->routeGetDataTrash():$model->routeGetData() }}" data-bFilter="true" data-bSort="true" data-bPaginate="true">
							<thead>
								<tr>
									@if ($trash)
									<th class="col-md-2">Supprimé le</th>
									@endif
									<th class="col-md-1">#</th>
									<th>Nom</th>
									<th>Marqueurs</th>
									<th class="col-md-1">Etat</th>
									<th class="col-md-2">DG(s)</th>
									<th class="col-md-2">Public(s) Cible(s)</th>
									@if ($trash)
										<th class="col-md-1">Actions</th>
									@endif
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
@stop
