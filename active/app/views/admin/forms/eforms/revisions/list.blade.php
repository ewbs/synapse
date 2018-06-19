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
				<h3>Révisions</h3>
			</div>
			<div class="content">
				<fieldset>
					<div class="table-responsive">
						<table class="table table-hover datatable" data-ajaxurl="{{ route('eformsRevisionsGetData', $modelInstance->id) }}" data-bFilter="true" data-bSort="false" data-bPaginate="true">
							<thead>
								<tr>
									<th class="col-md-2">Révision</th>
									<th class="col-md-2">Etat courant</th>
									<th class="col-md-2">Etat suivant</th>
									<th>Commentaire</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</fieldset>
			</div>
		</div>
	</div>
	@include('admin.forms.eforms.partial-sidebar')
</div>
@stop