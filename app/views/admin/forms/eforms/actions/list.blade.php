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
					<h3><span class="fa fa-magic"></span> Actions</h3>
				</div>
				<div class="content">
					<div class="table-responsive">
						<table id="datatable-eforms-actions" class="table table-hover datatable" data-ajaxurl="{{ route('eformsActionsGetData', $modelInstance->id) }}" data-bsort="true" data-bfilter="true" data-bpaginate="true">
							<thead>
								<tr>
									<th>Nom</th>
									<th class="col-md-1">Etat</th>
									<th class="col-md-1">Priorité</th>
									<th class="col-md-1">Révision</th>
									<th class="col-md-2">Actions</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
					@if($modelInstance->canManage())
					<div class="form-group">
						<button type="submit" class="btn btn-sm btn-primary servermodal" href="{{route('eformsActionsGetCreate', [$modelInstance->id])}}" data-reload-datatable="table#datatable-eforms-actions"><i class="fa fa-plus"></i> Ajouter une action</button>
					</div>
					@endif
				</div>
			</div>
		</div>
		@include('admin.forms.eforms.partial-sidebar')
	</div>
</div>
@stop