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
					<table class="table table-hover datatable" data-ajaxurl="{{ $model->routeGetFilteredData() }}" data-bFilter="true" data-bSort="true" data-bPaginate="true">
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