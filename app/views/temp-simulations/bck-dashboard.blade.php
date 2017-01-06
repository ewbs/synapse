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
				<div class="tab-container">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#dashboard" data-toggle="tab"><span class="fa fa-home"></span> Mon dashboard</a></li>
						<li><a href="#mesprojets" data-toggle="tab"><span class="fa fa-lightbulb-o"></span> Mes projets</a></li>
						<li><a href="#mesdemarches" data-toggle="tab"><span class="fa fa-briefcase"></span> Mes démarches</a></li>
						<li><a href="#mesactions" data-toggle="tab"><span class="fa fa-magic"></span> Mes actions</a></li>
						<li><a href="#mescharges" data-toggle="tab"><span class="fa fa-calculator"></span> Mes charges administratives</a></li>
					</ul>
					<div class="tab-content">

						{{--

						------------------------------------------------------------------------------------------------
							Dashboard
						------------------------------------------------------------------------------------------------

						--}}

						<div class="tab-pane active cont" id="dashboard">

											<div class="row">
												<div class="col-md-5">
													<div class="header tabpaneheader">
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

												<div class="col-md-5 col-md-offset-2">
													<div class="header tabpaneheader">
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

											<div class="row clearfix bigspacer">
												<div class="col-md-4">
													<div class="header tabpaneheader">
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
															<div class="col-sm-3"><div class="pull-right"></div>Documentées<h2>72</h2></div>
															<div class="col-sm-3"><div class="pull-right"></div>Gains calculés<h2>71</h2></div>
															<div class="col-sm-3"><div class="pull-right"></div>Pièces<h2>120</h2></div>
															<div class="col-sm-3"><div class="pull-right"></div>Tâches<h2>194</h2></div>
														</div>
													</div>
												</div>
												<div class="col-md-3 col-md-offset-1">
													<div class="header tabpaneheader">
														<h3>Charges administratives</h3>
													</div>
													<div class="content">
														<div class="row">
															<div class="col-md-12">
																<div class="overflow-hidden">
																	<i class="fa fa-calculator fa-4x pull-left color-danger"></i>
																	<h3 class="no-margin">71 SCMs LIGHT</h3>
																	<p><span class="color-danger">Réalisés sur les démarches de votre catalogue</span></p>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-sm-6"><div class="pull-right"></div>Gains potentiels adm.<h3>12.487.174 €</h3></div>
															<div class="col-sm-6"><div class="pull-right"></div>Gains potentiels usagers<h3>124.000.250 €</h3></div>
														</div>
													</div>
												</div>
												<div class="col-md-3 col-md-offset-1">
													<div class="header tabpaneheader">
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

						{{--

						------------------------------------------------------------------------------------------------
							Mes projets (vue d'un projet)
						------------------------------------------------------------------------------------------------

						--}}

						<div class="tab-pane cont" id="mesprojets">

							                <div class="row">
								                <div class="col-md-12">
									                <h3>
                										<span class="text-primary">2015-196</span>
				                						Poursuivre la simplification des bourses d'étude (SAPE)
								                		<div class="pull-right">
                                                            <a href="" title="Retour à la liste" class="btn btn-flat btn-cancel" ><span class="fa fa-arrow-left"></span></a>
                                                            <a href="" title="Modifier ce projet" class="btn btn-flat btn-primary"><span class="fa fa-pencil"></span></a>
                                                            <a href="" title="Supprimer ce projet" class="btn btn-flat btn-danger"><span class="fa fa-trash-o"></span></a>
                                                        </div>
                                                    </h3>
                                                </div>
                                            </div>
							                <div class="content">
                								<div class="row">
				                					<div class="col-md-4">
							                			<div class="header tabpaneheader">
											               	<h4>Description</h4>
               											</div>
			                							<div class="content">
							                				<p class="text-justify">
											               		La Direction des Prêts et Allocations d’Etudes (DAPE) a pour mission de gérer l’octroi des allocations et prêts d’études secondaires et supérieures aux élèves et étudiants de condition peu aisée. Elle doit également gérer les réclamations et recours liés à ceux-ci. Environ 160.000 demandes sont introduites chaque année, dont environ 7.000 (en augmentation) concernent les bénéficiaires des CPAS. Depuis 2013, le projet d’informatisation des demandes de bourses d’études a permis de simplifier fortement les démarches des usagers en leur permettant d’introduire leur demande en ligne et en leur proposant un formulaire prérempli avec leurs données authentiques provenant du SPF Intérieur (données du Registre national) et du SPF Finances (avertissement extrait de rôle). Quoi qu'il en soit, le processus pourrait encore être simplifié et automatisé. Il faudrait notamment : - permettre la consultation systématique des sources authentiques au moment de l’encodage des données provenant des formulaires papier par les agents de la DAPE (prévu pour le 1/7/2016); - mettre en place un flux de données avec la BCSS relatif à présence dans la famille de personnes gravement handicapées : développement non prioritaire pour le comité sponsor du projet. - mettre en place un flux de données avec la BCSS relatif aux bénéficiaires du revenu d’intégration sociale (RIS) : développement non prioritaire pour le comité sponsor du projet.
												            </p>
											            </div>

                                                        <div class="header tabpaneheader bigspacer">
            										            <h4>Administrations impliquées</h4>
											            </div>
											            <div class="content">
												            <span class="label label-default">AGE</span>
											            </div>
											            <div class="header tabpaneheader mediumspacer">
												            <h4>Public(s) cible</h4>
											            </div>
											            <div class="content">
												            <span class="label label-default">Citoyen</span>
											            </div>

			        									<div class="header tabpaneheader mediumspacer">
					        								<h4>Ministre(s) compétent(s)</h4>
							        					</div>
									        			<div class="content">
											        		<span class="label label-default">Joëlle Milquet</span>
													        <span class="label label-default">Jean Claude Marcourt</span>
												        </div>

												        <div class="header tabpaneheader bigspacer">
													        <h4>Source du document</h4>
        												</div>
		        										<div class="content">
				        									<p>
						        								<strong><em>Analyse d'opportunité sur l'automatisation des droits dérivés des bénéficiaires des CPAS</em></strong><br>
								        						Page 9<br/>
										        				<a target="_blank href="https://intra.ewbs.be/tile/view/33543/ ;https://intra.ewbs.be/tile/view/34140/">https://intra.ewbs.be/tile/view/33543/ ;https://intra.ewbs.be/tile/view/34140/</a>
												        	</p>
												        </div>
									                </div>
									                <div class="col-md-4">
												        <div class="header tabpaneheader">
													        <h4>Etat</h4>
												        </div>
												        <div class="content">
													        <span class="label label-primary">Prioritaire</span>
													        <span class="label label-primary">{{Lang::get('admin/ideas/states.ENREALISATION')}}</span>
												        </div>
												        <div class="header tabpaneheader bigspacer">
													        <h4><span class="fa fa-briefcase"></span> Démarches liées</h4>
												        </div>
        												<div class="content">
		        											<ul class="list-group">
				        										<li class="list-group-item">
						        									<strong>2015-0231</strong>
								        							<a href="">Bénéficier d'une bourse d'études (= allocation d'études) dans l'enseignement supérieur</a>
										        				</li>
												        		<li class="list-group-item">
														        	<strong>2015-0293</strong>
        															<a href="">Bénéficier d'une bourse d'études (= allocation d'études) dans l'enseignement secondaire</a>
		        												</li>
				        									</ul>
						        						</div>
								        				<div class="header tabpaneheader bigspacer">
										        			<h4><span class="fa fa-magic"></span> Actions</h4>
												        </div>
        												<div class="content">
		        											<div class="table-responsive actions">
				        										<div id="DataTables_Table_0_wrapper" class="dataTables_wrapper form-inline" role="grid">
						        									<div class="row"><div class="col-sm-12"><div class="pull-right"><div class="dataTables_filter" id="DataTables_Table_0_filter"><label>Rechercher&nbsp;: <input type="text" aria-controls="DataTables_Table_0" class="form-control" placeholder="Rechercher ..."></label></div></div><div class="pull-left"><div id="DataTables_Table_0_length" class="dataTables_length"><label>Afficher <select size="1" name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="form-control"><option value="10" selected="selected">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> éléments</label></div></div><div id="DataTables_Table_0_processing" class="dataTables_processing" style="visibility: hidden;">Traitement en cours...</div><div class="clearfix"></div></div></div>
								        							<table class="table table-striped table-hover datatable dataTable" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
										        						<thead>
												        					<tr role="row">
														        				<th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Actions: activer pour trier la colonne par ordre d&amp;eacute;croissant">Actions</th>
																        		<th class="col-md-1 sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Etat: activer pour trier la colonne par ordre croissant" style="width: 64px;">Etat</th>
        																	</tr>
		        														</thead>
				        												<tbody role="alert" aria-live="polite" aria-relevant="all">
						        											<tr class="odd"><td class=" sorting_1"><strong>Dématérialiser le formulaire agrément éducation permanente</strong><br><em></em></td><td class=" "><span class="hidden">1</span><span class="label label-default">Initialisé</span></td></tr>
								        									<tr class="odd"><td class=" sorting_1"><strong>Dématérialiser le formulaire agrément éducation permanente</strong><br><em></em></td><td class=" "><span class="hidden">1</span><span class="label label-default">Initialisé</span></td></tr>
										        							<tr class="odd"><td class=" sorting_1"><strong>Dématérialiser le formulaire agrément éducation permanente</strong><br><em></em></td><td class=" "><span class="hidden">1</span><span class="label label-default">Initialisé</span></td></tr>
												        					<tr class="odd"><td class=" sorting_1"><strong>Dématérialiser le formulaire agrément éducation permanente</strong><br><em></em></td><td class=" "><span class="hidden">1</span><span class="label label-default">Initialisé</span></td></tr>
														        			<tr class="odd"><td class=" sorting_1"><strong>Dématérialiser le formulaire agrément éducation permanente</strong><br><em></em></td><td class=" "><span class="hidden">1</span><span class="label label-default">Initialisé</span></td></tr>
																        	<tr class="odd"><td class=" sorting_1"><strong>Dématérialiser le formulaire agrément éducation permanente</strong><br><em></em></td><td class=" "><span class="hidden">1</span><span class="label label-default">Initialisé</span></td></tr>
        																	<tr class="odd"><td class=" sorting_1"><strong>Dématérialiser le formulaire agrément éducation permanente</strong><br><em></em></td><td class=" "><span class="hidden">1</span><span class="label label-default">Initialisé</span></td></tr>
		        															<tr class="odd"><td class=" sorting_1"><strong>Dématérialiser le formulaire agrément éducation permanente</strong><br><em></em></td><td class=" "><span class="hidden">1</span><span class="label label-default">Initialisé</span></td></tr>
				        												</tbody></table>
						        									<div class="row">
								        								<div class="col-sm-12">
										        							<div class="pull-left"><div class="dataTables_info" id="DataTables_Table_0_info">Affichage des élements 1 à 8 sur 8 éléments</div></div>
												        					<div class="pull-right"><div class="dataTables_paginate paging_bs_normal"><ul class="pagination pagination-sm"><li class="prev disabled"><a href="#"><span class="fa fa-angle-left"></span>&nbsp;Précédent</a></li><li class="active"><a href="#">1</a></li><li class="next disabled"><a href="#">Suivant&nbsp;<span class="fa fa-angle-right"></span></a></li></ul></div></div><div class="clearfix"></div></div></div></div>
                                                            </div>
                                                        </div>
									                </div>

									                <div class="col-md-4">
												        <div class="header tabpaneheader">
													        <h3><span class="fa fa-comment-o"></span> Commentaires</h3>
												        </div>
												        <div class="content">
        													<div class="form-group"><textarea class="form-control" id="comments-content" placeholder="Ajouter un commentaire ..."></textarea></div>
		        											<div class="form-group"><button type="submit" id="comments-submit" class="btn btn-xs btn-primary pull-right">Ajouter mon commentaire</button></div>
				        									<div class="clear"></div>
						        							<div class="chat-wi">
								        						<div class="chat-content content" style="display: block;">
                                                                    <div class="chat-conv sent" data-comment-id="14">
										    	    				    <img class="comments-useravatar c-avatar ttip" alt="" src="https://www.gravatar.com/avatar/9a0876c00ca5bc5c558693d4ca27d150?s=50&amp;d=mm&amp;r=g">
    											    	        		<div class="c-bubble">
	    														        	<div class="msg">
		    															        <p><strong class="comments-username">Julian Davreux</strong></p>
        	    																<p class="comment-content">Bonjour tout le monde! Et bienvenue dans la V4 de Synapse. J'ai plein d'idées pour le nom de code de cette release ;-)</p>
		            															<div><small class="date">30/08/2016 14:30</small></div>
				            												</div>
						            									</div>
							    	        						</div>
								    		        				<div class="chat-conv">
									    			        			<img class="comments-useravatar c-avatar ttip" alt="" src="https://www.gravatar.com/avatar/1c210399ab48d391a93926a876fb387d?s=50&amp;d=mm&amp;r=g">
										    				        	<div class="c-bubble">
											    					        <div class="msg">
        										    							<strong class="comments-username">Didier Willame</strong> a modifié l'état du projet en <strong class="comments-state">En cours de réalisation</strong><br>
		        									    						<div><small class="date">14/06/2016 15:08</small></div>
				        								    				</div>
						        							    		</div>
								        						    </div>
    										        				<div class="chat-conv">
	    											        			<img class="comments-useravatar c-avatar ttip" alt="" src="https://www.gravatar.com/avatar/9a0876c00ca5bc5c558693d4ca27d150?s=50&amp;d=mm&amp;r=g">
		    												        	<div class="c-bubble">
			    													        <div class="msg">
        		    															<strong class="comments-username">Julian Davreux</strong> a modifié l'état du projet en <strong class="comments-state">Encodé</strong><br>
		        	    														<div><small class="date">30/12/2015 11:35</small></div>
				            												</div>
						            									</div>
								            						</div>
									        	        			</div>
								    	    			        </div>
							    					            <div class="header tabpaneheader bigspacer">
						    							            <h4>Informations complémentaires</h4>
        			    									    </div>
		            										    <div class="content">
			    	        									    <p><span class="fa fa-user"></span> Créé par <strong>Cédric Jeanmart</strong> (<a href="&#x6d;&#x61;i&#108;t&#111;&#58;&#x63;&#x65;d&#114;&#x69;&#99;&#x2e;j&#101;&#97;&#x6e;&#109;&#x61;r&#x74;&#64;e&#x6e;&#x73;&#x65;m&#x62;&#x6c;&#101;&#x73;&#105;&#109;p&#x6c;i&#102;&#105;&#111;&#110;&#115;.&#98;e">&#x63;&#x65;d&#114;&#x69;&#99;&#x2e;j&#101;&#97;&#x6e;&#109;&#x61;r&#x74;&#64;e&#x6e;&#x73;&#x65;m&#x62;&#x6c;&#101;&#x73;&#105;&#109;p&#x6c;i&#102;&#105;&#111;&#110;&#115;.&#98;e</a>)</p>
    		    				        							<p><span class="fa fa-calendar"></span> Le 02-10-2015 11:09</p>
	        							        					<p><span class="fa fa-users"></span> Relais eWBS <strong>Thierry Grégoire</strong></p>
    	    									        			<p><span class="fa fa-users"></span> Contact <strong>Marie-ange.lagasse@cfwb.be, Diretrice ff de la DAPE</strong></p>
			    									            </div>
                                                        </div>
								                    </div>
							                    </div>
                                            </div>

						</div>


						{{--

						------------------------------------------------------------------------------------------------
							Mes démarches
						------------------------------------------------------------------------------------------------

						--}}

						<div class="tab-pane cont" id="mesdemarches">

						</div>


						{{--

						------------------------------------------------------------------------------------------------
							Mes actions
						------------------------------------------------------------------------------------------------

						--}}

						<div class="tab-pane" id="mesactions">

						</div>


						{{--

						------------------------------------------------------------------------------------------------
							Mes charges
						------------------------------------------------------------------------------------------------

						--}}

						<div class="tab-pane" id="mescharges">

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
