@extends('site.layouts.container-fluid')
@section('title')Mes actions @stop
@section('content')
@include('site.layouts.userdashboard-menu')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			<div class="header">
				<h3>Mes actions</h3>
			</div>
			<div class="content">
				<div class="table-responsive">
					<table id="datatable" class="table table-hover">
						<thead>
						<tr>
							<th>#</th>
							<th>Nom</th>
							<th>Etat</th>
							<th>Sous actions</th>
							<th>Elements liés</th>
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
		<span class="fa fa-info-circle"></span> Votre liste d'actions est filtrée sur : {{$txtUserFiltersAdministration}}
	</div>
</div>
@stop


@section('scripts')
<script type="text/javascript">
	$(document).ready(function() {

		var $tableDemarches = $('#datatable').dataTable( {
			"aoColumnDefs": [],
			"aaSorting" : [[0, "desc"]],
			"sAjaxSource": "{{ $model->routeGetFilteredData() }}",
		});

	});
</script>
@stop