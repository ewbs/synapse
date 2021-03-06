@extends('site.layouts.container-fluid')
@section('title')Mes charges administratives @stop
@section('content')
@include('site.layouts.userdashboard-menu')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			<div class="header">
				<div class="pull-right"><a id="demarcheExport" href="javascript:void(0);" class="btn btn-small btn-default"><i class="glyphicon glyphicon-download"></i> Exporter au format XLS</a></div>
				<h3>Mes charges administratives</h3>
			</div>
			<div class="content">
				<div class="row">
					<div class="col-md-6">
						{{-- TOP DES PIECES LES PLUS DEMANDEES --}}
						<table class="no-border">
							<thead></thead>
							<tbody class="no-border-y">
								<tr>
									<td class="text-center primary-emphasis col-md-3">
										<span class="fa fa-trophy fa-2x"></span><br/>Pièces les plus demandées
									</td>
									<td>
										<ul class="banded">
											@if (count($aTopExecutedPieces))
											@for($i=0; $i < count($aTopExecutedPieces); $i++)
											<li>
												{{$aTopExecutedPieces[$i]['displayname']}}
												<span class="badge pull-right">{{NumberHelper::numberFormat($aTopExecutedPieces[$i]['count_items'])}} pièces</span>
											</li>
											@endfor
											@else <li>Aucune</li>
											@endif
										</ul>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-md-6">
						{{-- TOP DES TACHES LES PLUS EXECUTEES --}}
						<table class="no-border">
							<thead></thead>
							<tbody class="no-border-y">
							<tr>
								<td class="text-center primary-emphasis col-md-3">
									<span class="fa fa-trophy fa-2x"></span><br/>Tâches les plus exécutées
								</td>
								<td>
									<ul class="banded">
										@if (count($aTopExecutedTasks))
										@for($i=0; $i < count($aTopExecutedTasks); $i++)
										<li>
											{{$aTopExecutedTasks[$i]['displayname']}}
											<span class="badge pull-right">{{NumberHelper::numberFormat($aTopExecutedTasks[$i]['count_items'])}} fois</span>
											</div>
										</li>
										@endfor
										@else <li>Aucune</li>
										@endif
									</ul>
								</td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						{{-- TOP DES PIECES AVEC LE PLUS GROS GAIN POTENTIEL --}}
						<table class="no-border">
							<thead></thead>
							<tbody class="no-border-y">
							<tr>
								<td class="text-center primary-emphasis col-md-3">
									<span class="fa fa-euro fa-2x"></span><br/>Pièces les plus coûteuses
								</td>
								<td>
									<ul class="banded">
										@if (count($aTopValuablePieces))
										@for($i=0; $i < count($aTopValuablePieces); $i++)
										<li>
											{{$aTopValuablePieces[$i]['displayname']}}
											<span class="badge pull-right">{{NumberHelper::moneyFormat($aTopValuablePieces[$i]['gpagpc'])}}</span>
										</li>
										@endfor
										@else <li>Aucune</li>
										@endif
									</ul>
								</td>
							</tr>
							</tbody>
						</table>
					</div>
					<div class="col-md-6">
						{{-- TOP DES TACHES AVEC LE PLUS GROS GAIN POTENTIEL --}}
						<table class="no-border">
							<thead></thead>
							<tbody class="no-border-y">
							<tr>
								<td class="text-center primary-emphasis col-md-3">
									<span class="fa fa-euro fa-2x"></span><br/>Tâches les plus coûteuses
								</td>
								<td>
									<ul class="banded">
										@if (count($aTopValuableTasks))
										@for($i=0; $i < count($aTopValuableTasks); $i++)
										<li>
											{{$aTopValuableTasks[$i]['displayname']}}
											<span class="badge pull-right">{{NumberHelper::moneyFormat($aTopValuableTasks[$i]['gpagpc'])}}</span>
										</li>
										@endfor
										@else <li>Aucune</li>
										@endif
									</ul>
								</td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>
				<hr/>
				<div class="table-responsive">
					<table id="datatable" class="table table-hover">
						<thead>
						<tr>
							<th class="col-md-1">#</th>
							<th>Nom</th>
							<th>Volume</th>
							<th>Pieces</th>
							<th>Tâches</th>
							<th>Gains pot. usager</th>
							<th>Gains pot. admin</th>
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

<div class="row">
	<div class="col-md-12">
		<span class="fa fa-info-circle"></span> Votre liste de démarches est filtrée sur : {{$txtUserFiltersAdministration}}
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
	$(document).ready(function() {
		var ajaxUrl = "{{ route('demarchesGetFilteredChargesData') }}";

		var $tableDemarches = $('#datatable').dataTable( {
			"aoColumnDefs": [
				{ "sClass": "text-right", "aTargets": [ 3, 4, 5, 6 ] },
				{ 'bVisible': false, 'aTargets': [7] }
			],
			"aaSorting" : [[0, "desc"]],
			"sAjaxSource": ajaxUrl,
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