<?php
/**
 * @var boolean $trash
 * @var string $context spécifie le tab à utiliser : soit les eforms, soit les nostra_forms
 * @var int $countDocumented
 * @var int $countUndocumented
 */
?>
@extends('site.layouts.container-fluid')
@section('title'){{Lang::get('admin/eforms/messages.title')}} @stop
@section('content')
<div class="tab-container">
	@if(!$trash)
	<ul class="nav nav-tabs">
		<li {{($context == 'documented'   ? 'class="active"' : '')}}><a href="{{route('eformsGetIndex')}}"><span class="badge badge-primary">{{$countDocumented}}</span> Formulaires intégrés</a></li>
		<li {{($context == 'undocumented' ? 'class="active"' : '')}}><a href="{{route('eformsUndocumentedGetIndex')}}"><span class="badge badge-primary">{{$countUndocumented}}</span> Formulaires à intégrer</a></li>
	</ul>
	@endif
	<div{{$trash?'':' class="tab-content"'}}>
		<div class="tab-pane cont active">
			<div class="row">
				<div class="col-md-12">
					<div class="block-flat">
						<div class="header">
							<div class="pull-right">
								@if ($trash)
								<a class="btn btn-small btn-default" href="{{ $model->routeGetIndex() }}">Retour à la liste</a>
								@endif
							</div>
							@if ($context == 'documented')
								@if (!$trash && $loggedUser->can('formslibrary_manage'))
								<div class="pull-right"><a href="{{ $model->routeGetCreate() }}" class="btn btn-small btn-primary"><i class="glyphicon glyphicon-plus-sign"></i> Ajouter un formulaire</a></div>
								@endif
								<h3>Liste des formulaires @if ($trash) supprimés @endif</h3>
							@elseif ($context == 'undocumented')
								<h3>Liste des formulaires à documenter</h3>
							@endif
						</div>
						<div class="content">
							<div class="table-responsive">
								@if ($context == 'documented')
								<table class="table table-hover datatable" data-ajaxurl="{{ $trash?$model->routeGetDataTrash():$model->routeGetData() }}" data-bFilter="true" data-bSort="true" data-bPaginate="true">
									<thead>
									
										<tr>
											@if ($trash)
											<th class="col-md-2">Supprimé le</th>
											@endif
											<th class="col-md-1">ID Nostra</th>
											<th class="col-md-1">ID slot</th>
											<th>Nom</th>
											<th class="col-md-1">Annexes</th>
											<th class="col-md-2">Démarches</th>
											<th class="col-md-1">Révision</th>
											@if ($trash)
												<th class="col-md-1">Actions</th>
											@endif
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
								@elseif ($context == 'undocumented')
								<table class="table table-hover datatable" data-ajaxurl="{{ $model->routeGetDataUndocumented() }}" data-bFilter="true" data-bSort="true" data-bPaginate="true">
									<thead>
										<tr>
											<th class="col-md-1">ID Nostra</th>
											<th class="col-md-1">ID slot</th>
											<th>Nom</th>
											<th class="col-md-1">Langue</th>
											<th class="col-md-1">Ajouté le</th>
											@if ($trash)
												<th class="col-md-1">Actions</th>
											@endif
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop
