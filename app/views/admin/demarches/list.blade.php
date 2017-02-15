@extends('site.layouts.container-fluid')
@section('title')Catalogue des démarches @stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			<div class="header">
				@if(!$trash)
				<h3>Liste des démarches</h3>
				<div class="pull-right"><a id="demarcheExport" href="javascript:void(0);" class="btn btn-small btn-default"><i class="glyphicon glyphicon-download"></i> Exporter au format XLS</a></div>
				<div class="pull-right"><a href="{{ route('damusGetRequestCreateDemarche') }}" class="btn btn-small btn-primary"><i class="glyphicon glyphicon-plus-sign"></i> Demander l'ajout d'une démarche dans NOSTRA</a></div>
				@else
				<h3>Liste des démarches supprimées</h3>
				@endif
			</div>
			<div class="content">
				@if($trash)
				@warning("Ces démarches sont supprimées car elles ont été présentes dans le flux fourni par Nostra, mais désactivées par après pour l'usage Synapse.<br/>La réactivation d'une démarche côté Nostra aura donc pour effet de la restaurer dans Synapse.")
				@else
				<h4>Recherche avancée :</h4>
				<form id="catalogDemarches_form" class="form-inline" data-dontobserve="1">
					<div class="row no-padding no-margin">
						<div class="col-md-6">
							<div class="row no-padding no-margin">
								<div class="col-md-12">
									<div class="form-group">
										<div class="checkbox">
											<label>
												<input type="checkbox" class="icheck" id="catalogDemarches_onlyDocumented" {{Auth::user()->sessionGet('catalogDemarches_onlyDocumented') ? 'checked="checked"':''}} /> Uniquement les démarches documentées
											</label>
										</div>
									</div>
								</div>
							</div>
							<div class="row no-padding no-margin">
								<div class="col-md-12">
									<div class="form-group">
										<div class="checkbox">
											<label>
												<input type="checkbox" class="icheck" id="catalogDemarches_onlyWithActions" {{Auth::user()->sessionGet('catalogDemarches_onlyWithActions') ? 'checked="checked"':''}} /> Uniquement les démarches avec actions en cours
											</label>
										</div>
									</div>
								</div>
							</div>
							<div class="row no-padding no-margin">
								<div class="col-md-12">
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
							</div>
						</div>
						<div class="col-md-6">
							<div class="row no-padding no-margin">
								<div class="col-md-12">
									<div class="form-group">
										Avec au moins
										<input class="form-control" style="width:4em;" type="text" id="catalogDemarches_minPieces" value="{{Auth::user()->sessionGet('catalogDemarches_minPieces') ? Auth::user()->sessionGet('dashboardDemarches_minPieces'):'0'}}" />
										pièces et
										<input class="form-control" style="width:4em;" type="text" id="catalogDemarches_minTasks" value="{{Auth::user()->sessionGet('catalogDemarches_minTasks') ? Auth::user()->sessionGet('dashboardDemarches_minTasks'):'0'}}" />
										tâches et
										<input class="form-control" style="width:4em;" type="text" id="dashboardDemarches_minForms" value="{{Auth::user()->sessionGet('dashboardDemarches_minForms') ? Auth::user()->sessionGet('dashboardDemarches_minForms'):'0'}}" />
										formulaires
									</div>
								</div>
							</div>
							<div class="row no-padding no-margin"><div class="col-md-12"><div class="form-group"></div></div></div>
							<div class="row no-padding no-margin">
								<div class="col-md-12">
									<div class="form-group">
										<label>Par administrations</label>
										<select class="select2 nostra" multiple name="administrations[]" id="administrations">
											<?php
											$selectedAdministrations = Auth::user()->sessionGet('catalogDemarches_administrations') ? explode(',', Auth::user()->sessionGet('catalogDemarches_administrations')) : [];
											?>
											@foreach($aRegions as $region)
												<optgroup label="{{$region->name}}">
													@foreach($region->administrations as $administration)
														<option value="{{$administration->id}}" {{in_array($administration->id, $selectedAdministrations) ? 'selected="selected"':''}}>{{$administration->name}}</option>
													@endforeach
												</optgroup>
											@endforeach
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
				<hr/>
				@endif
				<div class="table-responsive">
					<table id="datatable" class="table table-hover">
						<thead>
						<tr>
							<th class="col-md-1">#</th>
							<th>Nom</th>
							<th>Volume</th>
							<th>P/T/F</th>
							<th>Public(s) Cible(s)</th>
							<th>Administrations</th>
							<th>Actions</th>
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
<script type="text/javascript">

	var nostraPublicsContent = null;
	var administrationsContent = null;

	function getAjaxUrl() {
		//déterminer l'appel ajax sur base des paramètres du formulaire
		var ajaxUrl = "{{ $trash? $model->routeGetDataTrash() : $model->routeGetData() }}?";

		// documentées ?
		if ($("#catalogDemarches_onlyDocumented").is(":checked")) {
			ajaxUrl += "&onlyDocumented=1";
		}

		// avec actions ?
		if ($("#catalogDemarches_onlyWithActions").is(":checked")) {
			ajaxUrl += "&onlyWithActions=1";
		}

		// nombre de pièces minimum ?
		var minPieces = $("#catalogDemarches_minPieces").val();
		if ( Math.floor(minPieces) == minPieces && $.isNumeric(minPieces) ) { //est ce un entier ?
			if (minPieces > 0) {
				ajaxUrl += "&minPieces=" + minPieces;
			}
		}

		// nombre de tâches minimum ?
		var minTasks = $("#catalogDemarches_minTasks").val();
		if ( Math.floor(minTasks) == minTasks && $.isNumeric(minTasks) ) { //est ce un entier ?
			if (minTasks > 0) {
				ajaxUrl += "&minTasks=" + minTasks;
			}
		}

		// nombre de formulaires minimum ?
		var minForms = $("#dashboardDemarches_minForms").val();
		if ( Math.floor(minForms) == minForms&& $.isNumeric(minForms) ) { //est ce un entier ?
			if (minForms> 0) {
				ajaxUrl += "&minForms=" + minForms;
			}
		}

		// public cible ?
		var selectedNostraPublics = $('select#nostra_publics').val();
		if (selectedNostraPublics != null) {
			var tempArray = [];
			$.each(selectedNostraPublics, function (i, publicId) {
				tempArray.push(publicId);
			});
			if (tempArray.length > 0) {
				ajaxUrl += '&publics=' + tempArray.join();
			}
		}

		// administrations ?
		var selectedAdministrations = $('select#administrations').val();
		if (selectedAdministrations != null) {
			var tempArray = [];
			$.each(selectedAdministrations, function (i, admId) {
				tempArray.push(admId);
			});
			if (tempArray.length > 0) {
				ajaxUrl += '&administrations=' + tempArray.join();
			}
		}


		return ajaxUrl;
	}

	$(document).ready(function() {

		$("input.icheck").on('ifChanged', function(event) { $(event.target).trigger('change'); }) //les checkboxes avec iCheck ne propage pas l'événement change, au profit d'un événement "ifchanged". On l'intercepte donc et on repropage le change


		//les select2 sont assez lourds. Si on ne fait que cliquer dessus sans avoir rien changer il est
		//préférable d'éviter un reload. pour cela : à l'ouverture du select, on regarde ce qu'il y a dedans
		//à la fermeture on regarde aussi et on ne lancera un refresh de la liste que si il existe une différence
		//on fait des comparaison sur string... car la comparaison d'objet jQuery est toujours différente ($(this).val()).
		$('select#nostra_publics').on('select2:open', function () {
			nostraPublicsContent = $(this).val();
			if (nostraPublicsContent == null) {
				nostraPublicsContent = "";
			}
		});
		$('select#nostra_publics').on('select2:close', function () {
			var tempContent = $(this).val();
			if (tempContent == null) {
				tempContent = "";
			}
			if ((nostraPublicsContent.toString() != tempContent.toString()) || tempContent.length < 1) {
				$tableDemarches.fnReloadAjax(getAjaxUrl());
			}
		});
		$('select#administrations').on('select2:open', function () {
			administrationsContent = $(this).val();
			if (administrationsContent == null) {
				administrationsContent = "";
			}
		});
		$('select#administrations').on('select2:close', function () {
			var tempContent = $(this).val();
			if (tempContent == null) {
				tempContent = "";
			}
			if ((administrationsContent.toString() != tempContent.toString()) || tempContent.length < 1) {
				$tableDemarches.fnReloadAjax(getAjaxUrl());
			}
		});


		$("form#catalogDemarches_form input").change( function () {
			$tableDemarches.fnReloadAjax(getAjaxUrl());
		});




		var $tableDemarches = $('#datatable').dataTable( {
			"aoColumnDefs": [
				{ 'bSortable'  : false, 'aTargets': [6] },
				{ 'bSearchable': false, 'aTargets': [6] },
				{ 'bVisible': false, 'aTargets': [7] }
			],
			"aaSorting" : [[0, "desc"]],
			"sAjaxSource": getAjaxUrl(),
		});


		/*
		 * Gestion de l'export
		 */
		$("#demarcheExport").click( function () {
			var ids = $tableDemarches.fnGetColumnData(7);
			$("input#demarches_ids").val(ids);
			$("form#formExportDemarches").submit();
		});
	});
</script>
@stop