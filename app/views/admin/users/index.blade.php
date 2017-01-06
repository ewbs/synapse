@extends('site.layouts.default') {{-- Web site Title --}}
@section('title') Bienvenue sur Synapse @parent @stop {{-- Content --}}
@section('content')

<div class="page-head">
	<h2>Utilisateurs & Rôles</h2>
</div>

<div class="cl-mcont">
	<div class="row">
		<div class="col-md-12">
			<div class="block-flat">
				<div class="header">
					<div class="pull-right">
						<a href="{{{ URL::secure('admin/users/create') }}}"
							class="btn btn-small btn-primary"><i
							class="glyphicon glyphicon-plus-sign"></i> Ajouter un utilisateur</a>
					</div>
					<h3>Liste des utilisateurs</h3>
				</div>
				<div class="content">
					<div class="table-responsive">
						<table id="datatable" class="table table-hover">
							<thead>
								<tr>
									<th class="col-md-2">Nom</th>
									<th class="col-md-2">E-mail</th>
									<th class="col-md-2">Peut se connecter</th>
									<th class="col-md-2">Créé le</th>
									<th class="col-md-2">Rôles</th>
									<th class="col-md-2">Actions</th>
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
			$('#datatable').dataTable({
				"aoColumnDefs": [
					{ 'bSortable'  : false, 'aTargets': [5] },
					{ 'bSearchable': false, 'aTargets': [5] }
				],
				"sAjaxSource": "{{ URL::secure('admin/users/data') }}",
			});
		});
	</script>
	@stop