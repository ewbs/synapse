@extends('site.layouts.default') {{-- Web site Title --}}
@section('title') Bienvenue sur Synapse @parent @stop {{-- Content --}}
@section('content')

<div class="page-head">
	<h2><span class="fa fa-lightbulb-o"></span> Projets de simplification</h2>
</div>
<div class="cl-mcont">
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
						<table id="datatable" class="table table-hover">
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
	</div>

</div>
@stop {{-- Scripts --}}
	
@section('scripts')
<script type="text/javascript">
	$(document).ready(function() {
		$('#datatable').dataTable( {
			"aoColumnDefs": [
				{ 'bSortable'  : false, 'aTargets': [@if ($trash) 6 @else 5 @endif] },
				{ 'bSearchable': false, 'aTargets': [@if ($trash) 6 @else 5 @endif] }
			],
			"aaSorting" : [[0, "desc"]],
			"sAjaxSource": "{{ $trash?$model->routeGetDataTrash():$model->routeGetData() }}",
		});
	});
</script>
@stop
