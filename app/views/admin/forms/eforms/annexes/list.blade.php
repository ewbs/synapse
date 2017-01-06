@extends('site.layouts.default') {{-- Web site Title --}}
@section('title') Bienvenue sur Synapse @parent @stop {{-- Content --}}
@section('content')
<?php 
/*
 * @var Eform $modelInstance
 */
$lastRevision=$modelInstance->getLastRevisionEform();
?>
<div class="page-head">
	<h2><span class="text-primary"><span class="fa fa-wpforms"></span> {{$modelInstance->formatedId()}}</span> {{$lastRevision->title}}</h2>
</div>
<div class="cl-mcont">
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
</div>
@stop