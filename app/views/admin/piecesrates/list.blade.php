@extends('site.layouts.default') {{-- Web site Title --}}
@section('title') Bienvenue sur Synapse @parent @stop {{-- Content --}}
@section('content')

<div class="page-head">
	<h2>Tarifs des tâches</h2>
</div>

<div class="cl-mcont">
	<div class="row">
		<div class="col-md-12">
			<div class="block-flat">
				<div class="header">
					<div class="pull-right">
						@if ($trash)
						<a class="btn btn-small btn-default" href="{{ $model->routeGetIndex() }}">Retour à la liste</a>
						@elseif($loggedUser->can('pieces_tasks_manage'))
						<a href="{{ $model->routeGetCreate() }}" class="btn btn-small btn-primary"><i class="glyphicon glyphicon-plus-sign"></i> Ajouter un tarif</a>
						@endif
					</div>
					<h3>Liste des tarifs @if ($trash) supprimés @endif</h3>
				</div>
				<div class="content">
					<div class="table-responsive">
						<table id="datatable" class="table table-hover">
							<thead>
								<tr>
									@if ($trash)
									<th class="col-md-2">Supprimé le</th>
									@endif
									<th>Nom</th>
									<th class="col-md-2">Public</th>
									<th class="col-md-1">Prix/h</th>
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
</div>
@stop

{{-- Scripts --}}
@section('scripts')
<script type="text/javascript">
$(document).ready(function() {
	$('#datatable').dataTable( {
		"aoColumnDefs": [
			{ 'bSortable'  : false, 'aTargets': [@if ($trash) 4 @else 3 @endif] },
			{ 'bSearchable': false, 'aTargets': [@if ($trash) 4 @else 3 @endif] }
		],
		"sAjaxSource": "{{ $trash?$model->routeGetDataTrash():$model->routeGetData() }}",
	});
});
</script>
@stop
