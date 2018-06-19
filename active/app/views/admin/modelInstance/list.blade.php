@extends('site.layouts.default') {{-- Web site Title --}}
@section('title') Bienvenue sur Synapse @parent @stop {{-- Content --}}
@section('content')
<?php //TODO : Ceci est actuellement un simple test, la vue n'est pas appelée ?>
<div class="page-head">
	<h2>{{{ Lang::get ( 'admin/'.$model->getModelLabel().'/messages.title' ) }}}</h2>
</div>
<div class="cl-mcont">
	<div class="row">
		<div class="col-md-12">
			<div class="block-flat">
				<div class="header">
					<div class="pull-right">
						@if($trash)
						<a class="btn btn-small btn-default" href="{{ $model->routeGetIndex() }}">Retour à la liste</a>
						@else
						<a href="{{ $model->RouteGetCreate() }}" class="btn btn-small btn-primary"><i class="glyphicon glyphicon-plus-sign"></i> Ajouter une administration</a>
						@endif
					</div>
					<h3>{{{ Lang::get ( 'admin/'.$model->getModelLabel().'/messages.'.(($trash)?'trash':'list').'.subtitle' ) }}}</h3>
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
									<th class="col-md-4">Région</th>
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
		$('#datatable').dataTable({
			"aoColumnDefs": [
				{ 'bSortable'  : false, 'aTargets': [@if($trash) '3' @else '2' @endif] },
				{ 'bSearchable': false, 'aTargets': [@if($trash) '3' @else '2' @endif] }
			],
			"sAjaxSource": "{{ $trash?route('administrationsGetDataTrash'):route('administrationsGetData') }}",
		});
	});
</script>
@stop
