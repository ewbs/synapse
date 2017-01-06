@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
	Mes projets de simplif' sur Synapse
	@parent
@stop

{{-- Content --}}
@section('content')
	<div class="cl-mcont">

		@include('site.layouts.userdashboard-menu')

		<div class="row">
			<div class="col-md-12">
				<div class="block-flat">
					<div class="header">
						<div class="pull-right"><a href="{{ route('ideasGetExport') }}" class="btn btn-small btn-default"><i class="glyphicon glyphicon-download"></i> Exporter au format XLS</a></div>
						@if ($loggedUser->can('ideas_encode'))
							<div class="pull-right"><a href="{{ $model->routeGetCreate() }}" class="btn btn-small btn-primary"><i class="glyphicon glyphicon-plus-sign"></i> Ajouter un projet</a></div>
						@endif
						<h3>Mes projets de simplification</h3>
					</div>
					<div class="content">
						<div class="table-responsive">
							<table id="datatable" class="table table-hover">
								<thead>
								<tr>
									<th class="col-md-1">#</th>
									<th>Nom</th>
									<th>Marqueurs</th>
									<th class="col-md-1">Etat</th>
									<th class="col-md-2">DG(s)</th>
									<th class="col-md-2">Public(s) Cible(s)</th>
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

		<div class="row">
			<div class="col-md-12">
				<span class="fa fa-info-circle"></span> Votre liste de projets est filtrée sur : {{$txtUserFiltersAdministration}}
			</div>
		</div>

@stop


{{-- Scripts --}}

@section('scripts')
		<script type="text/javascript">
			$(document).ready(function() {
				$('#datatable').dataTable( {
					"aoColumnDefs": [],
					"aaSorting" : [[0, "desc"]],
					"sAjaxSource": "{{ $model->routeGetFilteredData() }}",
				});
			});
		</script>
@stop