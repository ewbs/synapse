@extends('site.layouts.default') {{-- Web site Title --}}
@section('title') Bienvenue sur Synapse @parent @stop {{-- Content --}}
@section('content')

<div class="page-head">
	<h2>Jobs</h2>
</div>

<div class="cl-mcont">
	<div class="row">
		<div class="col-md-12">
			<div class="block-flat">
				<div class="header">
					<h3>Liste des jobs en attente</h3>
				</div>
				<div class="content">
					<div class="table-responsive">
						<table id="datatable" class="table table-hover">
							<thead>
								<tr>
									<th class="col-md-1">#</th>
									<th class="col-md-2">Créé le</th>
									<th class="col-md-1">Etat</th>
									<th class="col-md-1">Essais</th>
									<th class="col-md-1">Queue</th>
									<th>Job</th>
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
				{ 'bSortable'  : false, 'aTargets': [6] },
				{ 'bSearchable': false, 'aTargets': [6] }
			],
			"sAjaxSource": "{{ route('jobsGetData') }}",
		});
	});
</script>
@stop
