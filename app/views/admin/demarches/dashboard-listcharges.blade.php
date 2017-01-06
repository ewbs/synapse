@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
	Mes démarches sur Synapse
	@parent
@stop

{{-- Content --}}
@section('content')
	<div class="cl-mcont">

		@include('site.layouts.userdashboard-menu')

		<div class="row">
			<div class="col-md-12">
				<div class="block-flat">
					<div class="header">
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
											<td class="text-center primary-emphasis col-md-3" @if(count($aTopExecutedPieces)) rowspan="{{count($aTopExecutedPieces)}}" @endif>
												<span class="fa fa-trophy fa-2x"></span><br/>Pièces les plus demandées
											</td>
											<td>
												@if (count($aTopExecutedPieces))
													{{$aTopExecutedPieces[0]['displayname']}}
												@endif
											</td>
											<td class="text-right">
												@if (count($aTopExecutedPieces))
													{{$aTopExecutedPieces[0]['count_items']}} pièces
												@endif
											</td>
										</tr>
										@for($i=1; $i < count($aTopExecutedPieces); $i++)
											<tr>
												<td>{{$aTopExecutedPieces[$i]['displayname']}}</td>
												<td class="text-right">{{$aTopExecutedPieces[$i]['count_items']}} pièces</td>
											</tr>
										@endfor
									</tbody>
								</table>
							</div>
							<div class="col-md-6">
								{{-- TOP DES TACHES LES PLUS EXECUTEES --}}
								<table class="no-border">
									<thead></thead>
									<tbody class="no-border-y">
									<tr>
										<td class="text-center primary-emphasis col-md-3" @if(count($aTopExecutedTasks)) rowspan="{{count($aTopExecutedTasks)}}" @endif>
											<span class="fa fa-trophy fa-2x"></span><br/>Tâches les plus exécutées
										</td>
										<td>
											@if (count($aTopExecutedTasks))
												{{$aTopExecutedTasks[0]['displayname']}}
											@endif
										</td>
										<td class="text-right">
											@if (count($aTopExecutedTasks))
												{{$aTopExecutedTasks[0]['count_items']}} fois
											@endif
										</td>
									</tr>
									@for($i=1; $i < count($aTopExecutedTasks); $i++)
										<tr>
											<td>{{$aTopExecutedTasks[$i]['displayname']}}</td>
											<td class="text-right">{{$aTopExecutedTasks[$i]['count_items']}} fois</td>
										</tr>
									@endfor
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
										<td class="text-center primary-emphasis col-md-3" @if(count($aTopValuablePieces)) rowspan="{{count($aTopValuablePieces)}}" @endif>
											<span class="fa fa-euro fa-2x"></span><br/>Pièces les plus coûteuses
										</td>
										<td>
											@if (count($aTopValuablePieces))
												{{$aTopValuablePieces[0]['displayname']}}
											@endif
										</td>
										<td class="text-right">
											@if (count($aTopValuablePieces))
												{{NumberHelper::moneyFormatNoDecimal($aTopValuablePieces[0]['gpagpc'])}}
											@endif
										</td>
									</tr>
									@for($i=1; $i < count($aTopValuablePieces); $i++)
										<tr>
											<td>{{$aTopValuablePieces[$i]['displayname']}}</td>
											<td class="text-right">{{NumberHelper::moneyFormatNoDecimal($aTopValuablePieces[$i]['gpagpc'])}}</td>
										</tr>
									@endfor
									</tbody>
								</table>
							</div>
							<div class="col-md-6">
								{{-- TOP DES TACHES AVEC LE PLUS GROS GAIN POTENTIEL --}}
								<table class="no-border">
									<thead></thead>
									<tbody class="no-border-y">
									<tr>
										<td class="text-center primary-emphasis col-md-3" @if(count($aTopValuableTasks)) rowspan="{{count($aTopValuableTasks)}}" @endif>
											<span class="fa fa-euro fa-2x"></span><br/>Tâches les plus coûteuses
										</td>
										<td>
											@if (count($aTopValuableTasks))
												{{$aTopValuableTasks[0]['displayname']}}
											@endif
										</td>
										<td class="text-right">
											@if (count($aTopValuableTasks))
												{{NumberHelper::moneyFormatNoDecimal($aTopValuableTasks[0]['gpagpc'])}}
											@endif
										</td>
									</tr>
									@for($i=1; $i < count($aTopValuableTasks); $i++)
										<tr>
											<td>{{$aTopValuableTasks[$i]['displayname']}}</td>
											<td class="text-right">{{NumberHelper::moneyFormatNoDecimal($aTopValuableTasks[$i]['gpagpc'])}}</td>
										</tr>
									@endfor
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

@stop


{{-- Scripts --}}

@section('scripts')
		<script type="text/javascript">
			$(document).ready(function() {

				var ajaxUrl = "{{ route('demarchesGetFilteredChargesData') }}";

				var $tableDemarches = $('#datatable').dataTable( {
					"aoColumnDefs": [
						{ "sClass": "text-right", "aTargets": [ 3, 4, 5, 6 ] }
					],
					"aaSorting" : [[0, "desc"]],
					"sAjaxSource": ajaxUrl,
				});

			});
		</script>
@stop