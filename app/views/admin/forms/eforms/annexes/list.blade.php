<?php 
/**
 * @var Eform $modelInstance
 */
$lastRevision=$modelInstance->getLastRevisionEform();
?>

@extends('site.layouts.container-fluid')
@section('title')<span class="text-primary">{{$modelInstance->formatedId()}}</span> {{$lastRevision->title}} @stop
@section('content')
<div class="row">
	<div class="col-md-8">
		<div class="block-flat">
			<div class="header">
				<h3>Annexes</h3>
			</div>
			<div class="content">
				<div class="table-responsive">
					<table id="datatable-eforms-annexes" class="table table-hover datatable" data-ajaxurl="{{ route('eformsAnnexesGetData', $modelInstance->id) }}" data-bFilter="true" data-bSort="true" data-bPaginate="true">
						<thead>
							<tr>
								<th class="col-md-2">Nom</th>
								<th class="col-md-2">Etat courant</th>
								<th class="col-md-2">Etat suivant</th>
								<th>Commentaire</th>
								<th class="col-md-2">Révision</th>
								<th class="col-md-2">Actions</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
				@if($modelInstance->canManage())
				<div class="form-group">
					<button type="submit" class="btn btn-sm btn-primary servermodal" href="{{route('eformsAnnexesGetCreate', [$modelInstance->id])}}" data-reload-datatable="table#datatable-eforms-annexes"><i class="fa fa-plus"></i> Ajouter une annexe</button>
				</div>
				@endif
			</div>
		</div>
	</div>
	@include('admin.forms.eforms.partial-sidebar')
</div>
@stop