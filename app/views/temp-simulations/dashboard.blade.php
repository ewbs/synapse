@extends('temp-simulations.layouts.default')
@section('title')
	Bienvenue
	@parent
@stop



{{-- Content --}}
@section('content')
	<div class="cl-mcont">
		<div class="row">
			<div class="col-sm-12 col-md-12">
				<ul class="nav nav-pills">
				    <li class="active"><a href="/simulations/"><span class="fa fa-home"></span> Mon dashboard</a></li>
					<li><a href="/simulations/projets"><span class="fa fa-lightbulb-o"></span> Mes projets</a></li>
					<li><a href="/simulations/demarches"><span class="fa fa-briefcase"></span> Mes démarches</a></li>
					<li><a href="/simulations/actions"><span class="fa fa-magic"></span> Mes actions</a></li>
					<li><a href="/simulations/charges"><span class="fa fa-calculator"></span> Mes charges administratives</a></li>
                </ul>
            </div>
        </div>

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
                                                        <td class="text-right"><b>54</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td><div class="legend" data-color="#19B698"></div></td>
                                                        <td>Terminés</td>
                                                        <td class="text-right"><b>18</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td><div class="legend" data-color="#BD3B47"></div></td>
                                                        <td>Abandonnés</td>
                                                        <td class="text-right"><b>10</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td><div class="legend" data-color="#fd9c35"></div></td>
                                                        <td>Initiés</td>
                                                        <td class="text-right"><b>24</b></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4"><div class="pull-right"></div>Projets en cours<h2>106</h2></div>
                                        <div class="col-sm-4"><div class="pull-right"></div>Prioritaires<h2>34</h2></div>
                                        <div class="col-sm-4"><div class="pull-right"></div>Génériques<h2>20</h2></div>
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
                                                <td class="text-right"><span class="badge badge-default">6</span></td>
                                            </tr>
                                            <tr>
                                                <td style="width:40px;"><i class="fa fa-clipboard"></i></td>
                                                <td>Sur des pièces et des tâches</td>
                                                <td class="text-right"><span class="badge badge-default">4</span></td>
                                            </tr>
                                            <tr>
                                                <td style="width:40px;"><i class="fa fa-wpforms"></i></td>
                                                <td>Sur des formulaires</td>
                                                <td class="text-right"><span class="badge badge-default">4</span></td>
                                            </tr>
                                            <tr>
                                                <td style="width:40px;"><i class="fa fa-briefcase"></i></td>
                                                <td>Sur des démarches</td>
                                                <td class="text-right"><span class="badge badge-default">12</span></td>
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
												<h3 class="no-margin">84 DEMARCHES</h3>
												<p><span class="color-primary">Dans votre catalogue personnalisé</span></p>
                                            </div>
                                        </div>
                                    </div>
									<div class="row">
									    <div class="col-sm-3"><div class="pull-right"></div>Documentées<h3>72</h3></div>
										<div class="col-sm-3"><div class="pull-right"></div>Gains calculés<h3>71</h3></div>
										<div class="col-sm-3"><div class="pull-right"></div>Pièces<h3>120</h3></div>
										<div class="col-sm-3"><div class="pull-right"></div>Tâches<h3>194</h3></div>
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
											    <h3 class="no-margin">136.487.424 €</h3>
											    <p><span class="color-danger">de charges administratives épargnées sur 71 démarches</span></p>
											</div>
                                        </div>
                                    </div>
									<div class="row">
									    <div class="col-sm-6"><div class="pull-right"></div>Gains potentiels adm.<h3>12.487.174 €</h3></div>
										<div class="col-sm-6"><div class="pull-right"></div>Gains potentiels usagers<h3>124.000.250 €</h3></div>
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
								    			<h3 class="no-margin">80 FORMULAIRES</h3>
									    		<p><span class="color-success">Liés à vos démarches</span></p>
                                            </div>
                                        </div>
                                    </div>
									<div class="row">
									    <div class="col-sm-4"><div class="pull-right"></div>Simplifiés<h3>64</h3></div>
										<div class="col-sm-4"><div class="pull-right"></div>Electroniques<h3>70</h3></div>
										<div class="col-sm-4"><div class="pull-right"></div>e-Id<h3>10</h3></div>
                                    </div>
                                </div>
                            </div>
                        </div>
        </div>
    </div>



	<script>
		$(document).ready( function () {

			var data = [
				{ label: "En cours", data: 54},
				{ label: "Terminés", data: 18},
				{ label: "Abandonnés", data: 10},
				{ label: "Initiés", data: 14},
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
