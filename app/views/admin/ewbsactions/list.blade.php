@extends('site.layouts.container-fluid')
@section('title')Log d'actions @stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			<div class="header">
				<div class="pull-right">
					@if ($trash)
					<a class="btn btn-small btn-default" href="{{ $model->routeGetIndex() }}">Retour à la liste</a>
					@endif
				</div>
				<h3>Liste des actions @if ($trash) supprimées @endif</h3>
			</div>
			<div class="content">
				@if(!$trash)
				<h4>Filtrer :</h4>
				<form id="actions_filter" data-dontobserve="1">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Par assignations</label>
								<select class="select2" multiple name="responsibles[]">
									@foreach($responsibles as $responsible)
										<option value="{{{$responsible->id}}}" {{in_array($responsible->id, $selectedResponsibles) ? 'selected="selected"':''}}>{{{$responsible->username}}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Par types/noms</label>
								<select class="select2" multiple name="names[]">
									@foreach($names as $name)
										<option value="{{{$name->name}}}" {{in_array($name->name, $selectedNames) ? 'selected="selected"':''}}>{{{$name->name}}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Par administrations</label>
								<select class="select2 form-control" multiple name="administrations[]">
									@foreach($regions as $region)
										<optgroup label="{{$region->name}}">
											@foreach($region->administrations as $administration)
												<option value="{{$administration->id}}" {{in_array($administration->id, $selectedAdministrations) ? 'selected="selected"':''}}>{{{$administration->name}}}</option>
											@endforeach
										</optgroup>
									@endforeach
								</select>
							</div>
						</div>
					</div>
				</form>
				<hr/>
				@endif
				<div class="table-responsive">
					<table class="table table-hover datatable" data-ajaxurl="{{ $trash?$model->routeGetDataTrash():$model->routeGetData() }}" data-bFilter="true" data-bSort="true" data-bPaginate="true" data-useform="#actions_filter">
						<thead>
							<tr>
								@if ($trash)
								<th class="col-md-2">Supprimé le</th>
								@endif
								<th class="col-md-1">#</th>
								<th>Nom</th>
								<th class="col-md-1">Etat</th>
								<th class="col-md-1">Priorité</th>
								<th class="col-md-3">Elément lié</th>
								<th class="col-md-2">Assignation</th>
								<th class="col-md-1">Révision</th>
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
@stop
