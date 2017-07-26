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
				<h4>Filtrer :</h4>
				<form id="actions_filter" data-dontobserve="1">
					<div class="form-group">
						<div class="checkbox">
							<label>
								<input type="checkbox" class="icheck" name="createdbyme"/> Celles que j'ai créées
							</label>
						</div>
					</div>
					<div class="form-group">
						<div class="checkbox">
							<label>
								<input type="checkbox" class="icheck" name="assignedtome"/> Celles qui me sont assignées
							</label>
						</div>
					</div>
				</form>
				<hr/>
				<div class="table-responsive">
					<table class="table table-hover datatable" data-ajaxurl="{{ $model->routeGetFilteredData() }}" data-bFilter="true" data-bSort="true" data-bPaginate="true" data-useform="#actions_filter">
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