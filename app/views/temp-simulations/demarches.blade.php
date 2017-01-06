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
				    <li><a href="/simulations/"><span class="fa fa-home"></span> Mon dashboard</a></li>
					<li><a href="/simulations/projets"><span class="fa fa-lightbulb-o"></span> Mes projets</a></li>
					<li class="active"><a href="/simulations/demarches"><span class="fa fa-briefcase"></span> Mes démarches</a></li>
					<li><a href="/simulations/actions"><span class="fa fa-magic"></span> Mes actions</a></li>
					<li><a href="/simulations/charges"><span class="fa fa-calculator"></span> Mes charges administratives</a></li>
                </ul>
            </div>
        </div>

		<div class="row">

                <div class="col-md-12">
                    <h3>
                        <span class="text-primary">2015-89</span>
                        Bénéficier d'un prêt d'études
                    </h3>
                </div>

        </div>

        <div class="row">

                <div class="col-md-4">

                                    <div class="block-flat">
                                        <div class="header">
                                            <h4><span class="fa fa-connectdevelop"></span> Infos Nostra</h4>
                                        </div>
                                        <div class="content">
                                            <ul class="list-group">
                                                <li class="list-group-item">
                                                    <p><strong>Publics cibles : </strong> Citoyen</p>
                                                    <p><strong>Thématiques usager : </strong> Les études et la formation</p>
                                                    <p><strong>Thématiques administration : </strong> Enseignement</p>
                                                    <p><strong>Evénements déclencheurs : </strong> Entamer des études dans l'enseignement fondamental et secondaire, Entamer des études dans l'enseignement supérieur, Obtenir des aides financières pour ses études</p>
                                                </li>
                                                <li class="list-group-item">
                                                    <strong>Formulaires :</strong>

                                                    Aucun
                                                    <p class="color-danger">
                                                        <span class="badge badge-danger"><i class="fa fa-exclamation"></i></span>
                                                        Il y a une incohérence entre les formulaires renseignés dans Nostra et dans Synapse
                                                    </p></li>
                                                <li class="list-group-item">
                                                    <strong>Documents :</strong>
                                                    Aucun
                                                </li>
                                                <li class="list-group-item">
                                                    <strong>Simplifié : </strong>  non 			</li>
                                                <li class="list-group-item">
                                                    <strong>Version allemande : </strong>
                                                    non 			</li>
                                                <li class="list-group-item">
                                                    <strong>Type : </strong>
                                                    <span class="label label-primary">Droit</span> - Obligation - Information
                                                </li>
                                            </ul>
                                            <button class="btn btn-info"><span class="fa fa-question-circle"></span> Voir la démarche en détail</button>
                                            <a class="btn btn-warning" href=""><i class="fa fa-bug" aria-hidden="true"></i>Signaler une erreur</a>
                                        </div>
                                    </div>

                    </div>


                    <div class="col-md-4">

                                    <div class="block-flat">
                                        <div class="header">
                                            <h4><span class="fa fa-wpforms"></span> Formulaires</h4>
                                        </div>
                                        <div class="content">
                                            <div class="table-responsive">
                                                <div id="datatable-eforms-table_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="pull-right">
                                                                <div class="dataTables_filter" id="datatable-eforms-table_filter">
                                                                    <label>Rechercher&nbsp;: <input type="text" aria-controls="datatable-eforms-table" class="form-control" placeholder="Rechercher ..."></label>
                                                                </div>
                                                            </div>
                                                            <div class="pull-left">
                                                                <div id="datatable-eforms-table_length" class="dataTables_length">
                                                                    <label>Afficher <select size="1" name="datatable-eforms-table_length" aria-controls="datatable-eforms-table" class="form-control"><option value="10" selected="selected">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> éléments</label>
                                                                </div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                    </div>
                                                    <table id="datatable-eforms-table" class="table table-striped table-hover datatable dataTable" data-bfilter="true" data-bsort="true" data-bpaginate="true" aria-describedby="datatable-eforms-table_info" style="width: 100%;">
                                                        <thead>
                                                            <tr role="row">
                                                                <th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="datatable-eforms-table" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Nom: activer pour trier la colonne par ordre d&amp;eacute;croissant">Nom</th>
                                                                <th class="sorting" role="columnheader" tabindex="0" aria-controls="datatable-eforms-table" rowspan="1" colspan="1" aria-label="Annexes: activer pour trier la colonne par ordre croissant">Annexes</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody role="alert" aria-live="polite" aria-relevant="all">
                                                            <tr class="odd">
                                                                <td class=" sorting_1">Demande de bourse d'étude</td><td class="">2</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="pull-left">
                                                                <div class="dataTables_info" id="datatable-eforms-table_info">Affichage des élements 1 à 1 sur 1 éléments</div>
                                                            </div>
                                                            <div class="pull-right"><div class="dataTables_paginate paging_bs_normal"><ul class="pagination pagination-sm"><li class="prev disabled"><a href="#"><span class="fa fa-angle-left"></span>&nbsp;Précédent</a></li><li class="active"><a href="#">1</a></li><li class="next disabled"><a href="#">Suivant&nbsp;<span class="fa fa-angle-right"></span></a></li></ul></div></div><div class="clearfix"></div></div></div></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="block-flat">
                                        <div class="header">
                                            <h4><span class="fa fa-clipboard"></span> Pièces & tâches</h4>
                                        </div>
                                        <div class="content">
                                            <div class="table-responsive">
                                                <div id="tasks-table_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="pull-right">
                                                                <div class="dataTables_filter" id="tasks-table_filter">
                                                                    <label>Rechercher&nbsp;: <input type="text" aria-controls="tasks-table" class="form-control" placeholder="Rechercher ..."></label>
                                                                </div>
                                                            </div>
                                                            <div class="pull-left">
                                                                <div id="tasks-table_length" class="dataTables_length">
                                                                    <label>Afficher <select size="1" name="tasks-table_length" aria-controls="tasks-table" class="form-control"><option value="10" selected="selected">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> éléments</label></div>
                                                            </div>
                                                            <div id="tasks-table_processing"
                                                                 class="dataTables_processing"
                                                                 style="visibility: hidden;">Traitement en cours...
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                    </div>
                                                    <table class="table table-striped table-hover dataTable" aria-describedby="tasks-table_info" style="width: 100%;">
                                                        <thead>
                                                        <tr role="row">
                                                            <th class="sorting_asc" role="columnheader" tabindex="0"
                                                                aria-controls="tasks-table" rowspan="1" colspan="1"
                                                                aria-sort="ascending"
                                                                aria-label="Nom: activer pour trier la colonne par ordre d&amp;eacute;croissant">
                                                                Nom
                                                            </th>
                                                            <th class="col-md-1 sorting" role="columnheader"
                                                                tabindex="0" aria-controls="tasks-table" rowspan="1"
                                                                colspan="1"
                                                                aria-label="Volume: activer pour trier la colonne par ordre croissant">
                                                                Volume
                                                            </th>
                                                            <th class="col-md-1 sorting" role="columnheader"
                                                                tabindex="0" aria-controls="tasks-table" rowspan="1"
                                                                colspan="1"
                                                                aria-label="Gain pot. admin: activer pour trier la colonne par ordre croissant">
                                                                Gain pot. admin
                                                            </th>
                                                            <th class="col-md-1 sorting" role="columnheader"
                                                                tabindex="0" aria-controls="tasks-table" rowspan="1"
                                                                colspan="1"
                                                                aria-label="Gain pot. usager: activer pour trier la colonne par ordre croissant">
                                                                Gain pot. usager
                                                            </th>
                                                        </tr>
                                                        </thead>

                                                        <tbody role="alert" aria-live="polite" aria-relevant="all">
                                                        <tr class="odd">
                                                            <td class=" sorting_1"><strong>Envoi postal non normalisé (&lt;=16) - normal (citoyen)</strong></td>
                                                            <td class="">1 x 280&nbsp;000</td>
                                                            <td class="">0,00&nbsp;€</td>
                                                            <td class="">5&nbsp;244&nbsp;400,00&nbsp;€</td>
                                                        </tr>
                                                        <tr class="even">
                                                            <td class=" sorting_1"><strong>Formulation d'une demande complémentaire</strong></td>
                                                            <td class="">1 x 30&nbsp;000</td>
                                                            <td class="">144&nbsp;000,00&nbsp;€</td>
                                                            <td class="">144&nbsp;000,00&nbsp;€</td>
                                                        </tr>
                                                        <tr class="odd">
                                                            <td class=" sorting_1"><strong>Avertissement Extrait de Rôle (citoyen)</strong></td>
                                                            <td class="">1 x 135&nbsp;000</td>
                                                            <td class="">0,00&nbsp;€</td>
                                                            <td class="">5&nbsp;244&nbsp;400,00&nbsp;€</td>
                                                        </tr>
                                                        <tr class="even">
                                                            <td class=" sorting_1"><strong>Composition de ménage</strong></td>
                                                            <td class="">2 x 30&nbsp;000</td>
                                                            <td class="">144&nbsp;000,00&nbsp;€</td>
                                                            <td class="">144&nbsp;000,00&nbsp;€</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="pull-left">
                                                                <div class="dataTables_info" id="tasks-table_info">
                                                                    Affichage des élements 1 à 3 sur 3 éléments
                                                                </div>
                                                            </div>
                                                            <div class="pull-right">
                                                                <div class="dataTables_paginate paging_bs_normal">
                                                                    <ul class="pagination">
                                                                        <li class="prev disabled"><a href="#"><span
                                                                                        class="fa fa-angle-left"></span>&nbsp;Précédent</a>
                                                                        </li>
                                                                        <li class="active"><a href="#">1</a></li>
                                                                        <li class="next disabled"><a href="#">Suivant&nbsp;<span
                                                                                        class="fa fa-angle-right"></span></a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                    </div>

                    <div class="col-md-4">

                                    <div class="block-flat">
                                        <div class="content no-padding">
                                            <div class="btn-group" role="group">
                                                <a href="" title="Retour à la liste" class="btn btn-flat btn-cancel" ><span class="fa fa-arrow-left"></span></a>
                                                <a href="" title="Modifier ce projet" class="btn btn-flat btn-default"><span class="fa fa-pencil"></span><span class="hidden-xs hidden-sm hidden-md"> Editer</span></a>
                                                <a href="" title="Actions" class="btn btn-default"><span class="fa fa-magic"></span><span class="hidden-xs hidden-sm hidden-md"> Actions</span></a></li>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="fa fa-clipboard"></span> <span class="hidden-xs hidden-sm hidden-md">Pièces & tâches</span><span class="caret"></span></button>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="" title="Pièces et tâches"><span class="fa fa-clipboard"></span> Voir les pièces & tâches</a></li>
                                                        <li><a href="" title="Télécharger le SCM Light"><span class="fa fa-download"></span> Télécharger le SCM light</a></li>
                                                        <li><a href="" title="Envoyer le SCM Light"><span class="fa fa-upload"></span> Envoyer le SCM Light</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="block-flat">
                                        <div class="header">
                                            <h4><span class="fa fa-calculator"></span> Gains de charge (effectif / potentiel)</h4>
                                        </div>
                                        <div class="content">
                                            <p><strong>Usager : </strong>115.200 € / 144.000 €</p>
                                            <div class="progress progress-striped active">
                                                <div class="progress-bar progress-bar-success" style="width: 80%">80%</div>
                                            </div>
                                            <p><strong>Administration : </strong>10.000 € / 100.000 €</p>
                                            <div class="progress progress-striped active">
                                                <div class="progress-bar progress-bar-danger" style="width: 10%">10%</div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="block-flat">
                                        <div class="header">
                                            <h4><span class="fa fa-lightbulb-o"></span> Projets liés</h4>
                                        </div>
                                        <div class="content">
                                            <div class="alert alert-warning alert-white rounded">
                                                <div class="icon">
                                                    <i class="fa fa-exclamation-triangle"></i>
                                                </div>
                                                <strong>Attention! </strong>Cette démarche n'est liée à aucun projet de simplif'. Il est recommandé d'en créer au moins un pour assurer le suivi et le reporting.	</div>
                                        </div>
                                    </div>

                                    <div class="block-flat">
                                        <div class="header">
                                            <h4>Informations complémentaires</h4>
                                        </div>
                                        <div class="content">
                                            <p>
                                                <span class="fa fa-user"></span> Créé par <strong>Didier Willame</strong>
                                                (<a href="mailto:didier.willame@ensemblesimplifions.be">didier.willame@ensemblesimplifions.be</a>)
                                                <br/>
                                                <span class="fa fa-calendar"></span> Le 26-10-2015 11:36:26
                                            </p>
                                        </div>
                                    </div>

                    </div>
        </div>
    </div>

@stop
