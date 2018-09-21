@extends('site.layouts.container-fluid')
@section('title')Catalogue des démarches @stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			<div class="header">
				@if(!$trash)
				<div class="pull-right"><a id="demarcheExport" href="javascript:void(0);" class="btn btn-small btn-default"><i class="glyphicon glyphicon-download"></i> Exporter au format XLS</a></div>
				<div class="pull-right"><a href="{{ route('demarchesGetCreate_') }}" class="btn btn-small btn-primary"><i class="glyphicon glyphicon-plus-sign"></i> Créer une démarche</a></div>
				<div class="pull-right"><a href="{{ route('damusGetRequestCreateDemarche') }}" class="btn btn-small btn-primary"><i class="glyphicon glyphicon-plus-sign"></i> Demander l'ajout d'une démarche dans NOSTRA</a></div>
				<h3>Liste des démarches</h3>
				@else
				<h3>Liste des démarches supprimées</h3>
				@endif
			</div>
			<div class="content">
				@if($trash)
				@warning("Ces démarches sont supprimées car elles ont été présentes dans le flux fourni par Nostra, mais désactivées par après pour l'usage Synapse.<br/>La réactivation d'une démarche côté Nostra aura donc pour effet de la restaurer dans Synapse.")
				@else
				<h4>Filtrer :</h4>
				<form id="catalogDemarches_form" data-dontobserve="1">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<div class="checkbox no-padding">
									<label>
										<input type="checkbox" class="icheck" id="catalogDemarches_onlyDocumented" {{Auth::user()->sessionGet('catalogDemarches_onlyDocumented') ? 'checked="checked"':''}} /> Uniquement les démarches documentées
									</label>
								</div>
							</div>
							<div class="form-group">
								<div class="checkbox no-padding">
									<label>
										<input type="checkbox" class="icheck" id="catalogDemarches_onlyHorsNostra" {{Auth::user()->sessionGet('catalogDemarches_onlyHorsNostra') ? 'checked="checked"':''}} /> Uniquement les démarches "Hors Nostra"
									</label>
								</div>
							</div>
							<div class="form-group">
								<div class="checkbox no-padding">
									<label>
										<input type="checkbox" class="icheck" id="catalogDemarches_onlyWithActions" {{Auth::user()->sessionGet('catalogDemarches_onlyWithActions') ? 'checked="checked"':''}} /> Uniquement les démarches avec actions en cours
									</label>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Par publics cibles</label>
								<select class="select2 nostra" multiple name="nostra_publics[]" id="nostra_publics">
									<?php
										$selectedPublics = Auth::user()->sessionGet('catalogDemarches_publics') ? explode(',', Auth::user()->sessionGet('catalogDemarches_publics')) : [];
									?>
									@foreach($aPublics as $public)
										<option value="{{$public->id}}" {{in_array($public->id, $selectedPublics) ? 'selected="selected"':''}}>{{$public->title}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Par administrations</label>
								<select class="select2 nostra" multiple name="administrations[]" id="administrations">
									<?php
									$selectedAdministrations = Auth::user()->sessionGet('catalogDemarches_administrations') ? explode(',', Auth::user()->sessionGet('catalogDemarches_administrations')) : [];
									?>
									@foreach($aRegions as $region)
										<optgroup label="{{$region->name}}">
											@foreach($region->administrations()->orderBy('name')->get() as $administration)
												<option value="{{$administration->id}}" {{in_array($administration->id, $selectedAdministrations) ? 'selected="selected"':''}}>{{$administration->name}}</option>
											@endforeach
										</optgroup>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-md-12 form-inline">
							<div class="form-group">
								Avec au moins
								<input class="form-control input-sm text-right" style="width:4em;" type="text" id="catalogDemarches_minPieces" value="{{Auth::user()->sessionGet('catalogDemarches_minPieces') ? Auth::user()->sessionGet('dashboardDemarches_minPieces'):'0'}}" />
								pièces<span class="hidden-xs"> &#160; / &#160; </span><div class="visible-xs"><br/></div>
								<input class="form-control input-sm text-right" style="width:4em;" type="text" id="catalogDemarches_minTasks" value="{{Auth::user()->sessionGet('catalogDemarches_minTasks') ? Auth::user()->sessionGet('dashboardDemarches_minTasks'):'0'}}" />
								tâches<span class="hidden-xs"> &#160; / &#160; </span><div class="visible-xs"><br/></div>
								<input class="form-control input-sm text-right" style="width:4em;" type="text" id="dashboardDemarches_minForms" value="{{Auth::user()->sessionGet('dashboardDemarches_minForms') ? Auth::user()->sessionGet('dashboardDemarches_minForms'):'0'}}" />
								formulaires
							</div>
						</div>
					</div>
				</form>
				<hr/>
				@endif
				<div class="table-responsive">
					<table id="datatable" class="table table-hover" data-ajaxurl="{{ $trash? $model->routeGetDataTrash() : $model->routeGetData() }}">
						<thead>
						<tr>
							<th class="col-md-1">#</th>
							{{--<th>NostraID</th>--}}
							<th>Nom</th>
							<th>Volume</th>
							<th>P/T/F</th>
							<th>Public(s) Cible(s)</th>
							<th>Administrations</th>
							<th>Actions</th>
							<th></th>
							<th></th>
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
{{-- ce fomulaire invisible sert à l'export des démarches (la page d'export est appelée en POST --}}
<form class="hidden" id="formExportDemarches" method="post" action="{{route('demarchesPostExport')}}" target="_blank">
	<input type="hidden" id="_token" name="_token" value="{{{ csrf_token() }}}"/>
	<input type="hidden" id="demarches_ids" name="demarches_ids" value=""/>
</form>
@stop

@section('scripts')
{{ HTML::script('js/synapse/modDemarches-list.js') }}
@stop