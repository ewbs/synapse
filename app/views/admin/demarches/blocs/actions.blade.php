<?php 
/*
 * @var ManageableModel $modelInstance
 * @var boolean $manage
 */
if(!isset($manage)) $manage=false;
if(!isset($minimal)) $minimal=false;
?>


<div class="block-flat">
	<div class="header">
		<h4><span class="fa fa-magic"></span> Actions</h4>
	</div>
	<div class="content">
		<div class="table-responsive">
			<table id="actions" class="table table-hover datatable"
				   data-ajaxurl="{{ route('demarchesActionsGetData', $modelInstance->id)}}?manage={{$manage?1:0}}&minimal={{$minimal?1:0}}" data-bsort="true" data-bfilter="true" data-bpaginate="true">
				<thead>
				<tr>
					<th>Nom</th>
					<th>Etat</th>
					<th>Priorité</th>
					<th>Elément lié</th>
					@if(!$minimal)
						<th>Sous-actions</th>
						<th>Révision</th>
						<th{{$manage?' class="col-md-2"':''}}></th>
					@endif
				</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
		@if ($manage)
			<div class="form-group">
				<a class="btn btn-sm btn-primary servermodal" href="{{route('demarchesActionsGetCreate', $modelInstance->id)}}" data-reload-datatable="table#actions"><i class="fa fa-plus"></i> Ajouter une action</a>
			</div>
		@endif
	</div>
</div>