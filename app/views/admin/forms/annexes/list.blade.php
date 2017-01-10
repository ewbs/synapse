@extends('site.layouts.default') {{-- Web site Title --}}
@section('title') Bienvenue sur Synapse @parent @stop {{-- Content --}}
@section('content')
<?php 
/*
 * @var boolean $trash
 */
?>
<div class="page-head">
	<h2><span class="fa fa-wpforms"></span> {{Lang::get('admin/annexes/messages.title');}}</h2>
</div>
<div class="cl-mcont">
	<div class="row">
		<div class="col-md-12">
			<div class="block-flat">
				<div class="header">
					<div class="pull-right">
						@if ($trash)
						<a class="btn btn-small btn-default" href="{{ $model->routeGetIndex() }}">Retour à la liste</a>
						@endif
					</div>
					@if (!$trash && $loggedUser->can('formslibrary_manage'))
					<div class="pull-right"><a href="{{ $model->routeGetCreate() }}" class="btn btn-small btn-primary"><i class="glyphicon glyphicon-plus-sign"></i> Ajouter une annexe</a></div>
					@endif
					<h3>Liste des annexes @if ($trash) supprimées @endif</h3>
				</div>
				<div class="content">
					<div class="table-responsive">
						<table class="table table-hover datatable" data-ajaxurl="{{ $trash?$model->routeGetDataTrash():$model->routeGetData() }}" data-bFilter="true" data-bSort="true" data-bPaginate="true">
							<thead>
								<tr>
									@if ($trash)
									<th class="col-md-2">Supprimé le</th>
									@endif
									<th class="col-md-1">#</th>
									<th>Nom</th>
									<th class="col-md-4">Relative à la pièce</th>
									@if ($trash)
										<th class="col-md-1">Actions</th>
									@endif
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
