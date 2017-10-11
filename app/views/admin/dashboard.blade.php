@extends('site.layouts.container-fluid')
@section('title')Dashboard @stop
@section('content')
@include('site.layouts.userdashboard-menu')
<div class="row">
	<div class="col-md-4">
		<div class="block-flat">
			<div class="header">
				<h3>Projets de simplif'</h3>
			</div>
			<div class="content no-padding">
				<div class="row">
					<div class="col-sm-4"><div id="ideas-chart" style="height:160px;"></div></div>
					<div class="col-sm-8">
						<table class="no-borders no-strip padding-sm">
							<tbody class="no-border-x no-border-y">
							<tr>
								<td style="width:15px;"><div class="legend" data-color="#649BF4"></div></td>
								<td>En cours</td>
								<td class="text-right"><b>{{$countInProgressProjects}}</b></td>
							</tr>
							<tr>
								<td><div class="legend" data-color="#19B698"></div></td>
								<td>Terminés</td>
								<td class="text-right"><b>{{$countDoneProjects}}</b></td>
							</tr>
							<tr>
								<td><div class="legend" data-color="#BD3B47"></div></td>
								<td>Abandonnés</td>
								<td class="text-right"><b>{{$countCanceledProjects}}</b></td>
							</tr>
							<tr>
								<td><div class="legend" data-color="#fd9c35"></div></td>
								<td>Validés</td>
								<td class="text-right"><b>{{$countValidatedProjects}}</b></td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-4"><div class="pull-right"></div>Total des projets<h2>{{$countFilteredProjects}}</h2></div>
					<div class="col-sm-4"><div class="pull-right"></div>Prioritaires<h2>{{$countPrioritaryProjects}}</h2></div>
					<div class="col-sm-4"><div class="pull-right"></div>Génériques<h2>{{$countGenericProjects}}</h2></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-8">
	@include('admin.ewbsactions.blocs.expertises',['largedisplay'=>true])
	</div>
</div>

<div class="row">
	<div class="col-md-4">
		<div class="block-flat">
			<div class="header">
				<h3>Catalogue des démarches</h3>
			</div>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="overflow-hidden">
							<i class="fa fa-briefcase fa-4x pull-left color-primary"></i>
							<h3 class="no-margin">{{$countFilteredDemarches}} DEMARCHES</h3>
							<p><span class="color-primary">Dans votre catalogue personnalisé</span></p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-3"><div class="pull-right"></div>Documentées<h3>{{$countDocumentedDemarches}}</h3></div>
					<div class="col-sm-3"><div class="pull-right"></div>Gains calculés<h3>{{$countWithGainsDemarches}}</h3></div>
					<div class="col-sm-3"><div class="pull-right"></div>Pièces<h3>{{$countPiecesDemarches}}</h3></div>
					<div class="col-sm-3"><div class="pull-right"></div>Tâches<h3>{{$countTasksDemarches}}</h3></div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="block-flat">
			<div class="header">
				<h3>Charges administratives</h3>
			</div>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="overflow-hidden">
							<i class="fa fa-calculator fa-4x pull-left color-danger"></i>
							<h3 class="no-margin">{{$countWithGainsDemarches}} SCMS LIGHT</h3>
							<p><span class="color-danger">Effectués sur vos {{$countDocumentedDemarches}} démarches documentées</span></p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6"><div class="pull-right"></div>Gains potentiels adm.<h3>{{NumberHelper::moneyFormatNoDecimal($potentialAmountAdministration)}}</h3></div>
					<div class="col-sm-6"><div class="pull-right"></div>Gains potentiels usagers<h3>{{NumberHelper::moneyFormatNoDecimal($potentialAmountCitizen)}}</h3></div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="block-flat">
			<div class="header">
				<h3>Formulaires</h3>
			</div>
			<div class="content">
				<div class="row">
					<div class="col-md-12">
						<div class="overflow-hidden">
							<i class="fa fa-wpforms fa-4x pull-left color-success"></i>
							<h3 class="no-margin">{{$countFilteredForms}} FORMULAIRES</h3>
							<p><span class="color-success">Liés à vos {{$countDocumentedDemarches}} démarches documentées</span></p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-4"><div class="pull-right"></div>Simplifiés<h3>{{$countFilteredSimplifiedForms}}</h3></div>
					<div class="col-sm-4"><div class="pull-right"></div>Electroniques<h3>{{$countFilteredElectronicForms}}</h3></div>
					<div class="col-sm-4"><div class="pull-right"></div>e-Id<h3>{{$countFilteredEIDForms}}</h3></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<span class="fa fa-info-circle"></span> Votre dashboard est filtré sur : {{$txtUserFiltersAdministration}}
	</div>
</div>
@stop

@section('scripts')
<script>
$(document).ready( function () {

	var data = [
		{ label: "En cours", data: {{$countInProgressProjects}} },
		{ label: "Terminés", data: {{$countDoneProjects}} },
		{ label: "Abandonnés", data: {{$countCanceledProjects}} },
		{ label: "Validés", data: {{$countValidatedProjects}} },
	];


	$.plot('#ideas-chart', data, {
		series: {
			pie: {
				show: true,
				innerRadius: 0.5,
				shadow:{
					top: 5,
					left: 15,
					alpha:0.3
				},
				stroke:{
					width:0
				},
				label: {
					show: false
				},
				highlight:{
					opacity: 0.08
				}
			}
		},
		grid: {
			hoverable: true,
			clickable: true
		},
		colors: ["#5793f3", "#19B698","#dd4444","#fd9c35","#fec42c","#d4df5a","#5578c2"],
		legend: {
			show: false
		}
	});

	$("table td .legend").each(function(){
		var el = $(this);
		var color = el.data("color");
		el.css("background",color);
	});
});
</script>
@stop
