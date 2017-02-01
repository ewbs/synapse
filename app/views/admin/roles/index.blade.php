@extends('site.layouts.container-fluid')
@section('title')Rôles @stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			<div class="header">
				<div class="pull-right">
					<a href="{{{ URL::secure('admin/roles/create') }}}"
						class="btn btn-small btn-primary"><i
						class="glyphicon glyphicon-plus-sign"></i> Ajouter un rôle</a>
				</div>
				<h3>Liste des rôles</h3>
			</div>
			<div class="content">
				<div class="table-responsive">
					<table id="datatable" class="table table-hover">
						<thead>
							<tr>
								<th>Nom</th>
								<th class="col-md-2">Utilisateurs</th>
								<th class="col-md-2">Créé le</th>
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

@section('scripts')
<script type="text/javascript">
	$(document).ready(function() {
			$('#datatable').dataTable( {
			"aoColumnDefs": [
				{ 'bSortable'  : false, 'aTargets': [3] },
				{ 'bSearchable': false, 'aTargets': [3] }
			],
			"sAjaxSource": "{{ URL::secure('admin/roles/data') }}",
		});
	});
</script>
@stop
