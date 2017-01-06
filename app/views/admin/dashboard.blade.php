@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
	Bienvenue sur Synapse
	@parent
@stop

{{-- Content --}}
@section('content')
	<div class="cl-mcont">

		@include('site.layouts.userdashboard-menu')

		<div class="row">

			<div class="col-md-6">
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

			<div class="col-md-6">
				<div class="block-flat">
					<div class="header">
						<h3>Actions en cours</h3>
					</div>
					<div class="content no-padding">
						<table class="red">
							<tbody class="no-border-x no-border-y">
							<tr>
								<td style="width:40px;"><i class="fa fa-connectdevelop"></i></td>
								<td>Demandes à l'équipe Nostra</td>
								<td class="text-right"><span class="badge badge-default">{{$countNostraActions}}</span></td>
							</tr>
							<tr>
								<td style="width:40px;"><i class="fa fa-clipboard"></i></td>
								<td>Sur des pièces et des tâches</td>
								<td class="text-right"><span class="badge badge-default">{{$countDemarcheComponentsActions}}</span></td>
							</tr>
							<tr>
								<td style="width:40px;"><i class="fa fa-wpforms"></i></td>
								<td>Sur des formulaires</td>
								<td class="text-right"><span class="badge badge-default">{{$countFormsActions}}</span></td>
							</tr>
							<tr>
								<td style="width:40px;"><i class="fa fa-briefcase"></i></td>
								<td>Sur des démarches</td>
								<td class="text-right"><span class="badge badge-default">{{$countDemarchesActions}}</span></td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>
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
									<p><span class="color-danger">effectués sur vos {{$countDocumentedDemarches}} démarches documentées</span></p>
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
	<?php
/* variable pour cette page : array de couleurs pour les publics
$distributionColors = array (
		"#5793f3",
		"#dd4d79",
		"#bd3b47",
		"#dd4444",
		"#fd9c35",
		"#fec42c",
		"#d4df5a",
		"#5578c2",
		"#68a4f4",
		"#ee5e8a",
		"#ce4c58" 
);
?>

@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
Bienvenue sur Synapse
@parent
@stop

{{-- Content --}}
@section('content')
<div class="page-head">
	<h2><span class="fa fa-home"></span> Bienvenue sur Synapse</h2>
</div>

<div class="cl-mcont">
	<div class="row">

		{{-- MODULE PROJETS (IDEAS) --}}

		@if (isset($modIdeas) && is_array($modIdeas))
		<div class="col-md-6 col-sm-6">
			<div class="block-flat" style="margin-bottom:0;">
				<div class="header">
					<h3>
						<span class="fa fa-lightbulb-o"></span> Projets de simplification
					</h3>
				</div>
				<div class="content">
					<div class="stat-data">
						<div class="stat-blue">
							<h2>{{$modIdeas['count']}}</h2>
							<span>{{$modIdeas['count']>1?'Projets encodés':'Projet encodé'}}</span>
						</div>
					</div>
					<div class="stat-data">
						<div class="stat-number">
							<div>
								<h2>{{$modIdeas['countPrioritary']}}</h2>
							</div>
							<div>{{$modIdeas['countPrioritary']>1?'Prioritaires':'Prioritaire'}}<br>
								<span></span>
							</div>
						</div>
						<div class="stat-number">
							<div>
								<h2>{{$modIdeas['countTransversal']}}</h2>
							</div>
							<div>{{$modIdeas['countTransversal']>1?'Génériques':'Générique'}}<br>
								<span></span>
							</div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>

			<div class="tab-container tab-left">
				<ul class="nav nav-tabs flat-tabs">
					<li class="active"><a href="#tab-ideas-1" data-toggle="tab"><i class="fa fa-users"></i></a></li>
					<li><a href="#tab-ideas-2" data-toggle="tab"><i class="fa fa-building"></i></a></li>
				</ul>
				<div class="tab-content">
					{{-- Répartition par publics cibles --}}
					<div class="tab-pane active cont fade in" id="tab-ideas-1">
						<div class="content no-padding">
							<h4>Répartition par publics</h4>
							<div class="row">
								<div class="col-sm-4">
									<div id="piec_nostraPublics"
										 style="height: 160px; padding: 0px; position: relative;"></div>
								</div>
								<div class="col-sm-8">
									<table class="no-borders no-strip padding-sm">
										<tbody class="no-border-x no-border-y">
										<?php
										$count = 0;
										foreach ( $modIdeas ['nostraPublicsDistribution'] as $npd ) {
											print ('<tr>
												<td style="width:15px;"><div class="legend" data-color="' . $distributionColors [$count] . '"></div></td>
												<td>' . $npd['title'] . '</td>
												<td class="text-right"><b>' . $npd['compte'] . '</b></td>
												</tr>') ;
											$count ++;
										}
										?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					{{-- Répartition par DG --}}
					<div class="tab-pane cont fade" id="tab-ideas-2">
						<h4>Répartition par administrations</h4>
						<table class="table table-condensed">
							<tbody>
							@foreach($modIdeas ['administrationsDistribution'] as $ad)
								<tr>
									<td>{{$ad['name']}}</td>
									<td>{{$ad['compte']}}</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>

		</div> <!-- ./col -->


		{{-- MODULE DEMARCHES --}}

		@endif

		<div class="col-md-6 col-sm-6">

			@if (isset($modDemarches) && is_array($modDemarches))
				<div class="block-flat">
					<div class="header">
						<h3>
							<span class="fa fa-briefcase"></span> Référentiel des démarches
						</h3>
					</div>
					<div class="content">
						<div class="stat-data">
							<div class="stat-blue">
								<h2>{{$modDemarches['count']}}</h2>
								<span>{{$modDemarches['count']>1?'Démarches documentées':'Démarche documentée'}}</span>
							</div>
						</div>
						<div class="stat-data">
							<div class="stat-number">
								<div>
									<h2>{{ NumberHelper::decimalFormat($modDemarches['gainPotentialCitizen']) }}€</h2>
								</div>
								<div>
									<span>potentiel usager</span><br/>
									<span></span>
								</div>
							</div>
							<div class="stat-number">
								<div>
									<h2>{{ NumberHelper::decimalFormat($modDemarches['gainPotentialAdministration']) }} €</h2>
								</div>
								<div>
									<span>potentiel adminstration</span><br/>
									<span></span>
								</div>
							</div>
						</div>
						<div class="clear"></div>
					</div>
				</div>
			@endif

			@if (isset($modActions) && is_array($modActions))
				<div class="block-flat" style="margin-bottom:0;">
					<div class="header">
						<h3>
							<span class="fa fa-magic"></span> Actions eWBS
						</h3>
					</div>
					<div class="content">
						<div class="stat-data">
							<div class="stat-blue">
								<h2>{{$modActions['count']}}</h2>
								<span>{{$modActions['count']>1?'Actions':'Action'}}</span>
							</div>
						</div>
						<div class="stat-data">
							<div class="stat-number">
								<div>
									<h2>{{$modActions['countTodo']}}</h2>
								</div>
								<div>{{$modActions['countTodo']>1?'actions en cours':'action en cours'}}<br>
									<span></span>
								</div>
							</div>
							<div class="stat-number">
								<div>
									<h2>{{$modActions['countDone']}}</h2>
								</div>
								<div>{{$modActions['countDone']>1?'actions terminées':'action terminée'}}<br>
									<span></span>
								</div>
							</div>
						</div>
						<div class="clear"></div>
					</div>
				</div>
			@endif

		</div>

	</div>
</div>
{{-- Scripts --}}
<script type="text/javascript">
	var nostraPublicDistributionData = [
		@if (isset($modIdeas) && is_array($modIdeas))
		<?php
		foreach ( $modIdeas ['nostraPublicsDistribution'] as $npd ) {
			print ("{ label: \"".$npd['title']."\", data:".$npd['compte']." },") ;
		}
		?>
		@endif
	];
	
	var nostraPublicDistributionDataColors = [
	@if (isset($modIdeas) && is_array($modIdeas))
	<?php
	foreach ($distributionColors as $color ) {
		print ('"' . $color . '"' . ',') ;
	}
	?>
	@endif
	];
	</script>
@stop
*/ ?>

