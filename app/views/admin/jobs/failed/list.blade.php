@extends('site.layouts.container-fluid')
@section('title')Jobs en échec @stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			<div class="header">
				<h3>Liste des jobs en échec</h3>
			</div>
			<div class="content">
				<div class="table-responsive">
					<table id="datatable" class="table table-hover">
						<thead>
							<tr>
								<th class="col-md-1">#</th>
								<th class="col-md-2">Echoué le</th>
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
@stop

{{-- Scripts --}}
@section('scripts')
<script type="text/javascript">
	$(document).ready(function() {
		$('#datatable').dataTable({
			"aoColumnDefs": [
				{ 'bSortable'  : false, 'aTargets': [4] },
				{ 'bSearchable': false, 'aTargets': [4] }
			],
			"sAjaxSource": "{{ route('failedjobsGetData') }}",
		});
	});
</script>
@stop
