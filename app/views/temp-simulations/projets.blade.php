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
					<li class="active"><a href="/simulations/projets"><span class="fa fa-lightbulb-o"></span> Mes projets</a></li>
					<li><a href="/simulations/demarches"><span class="fa fa-briefcase"></span> Mes démarches</a></li>
					<li><a href="/simulations/actions"><span class="fa fa-magic"></span> Mes actions</a></li>
					<li><a href="/simulations/charges"><span class="fa fa-calculator"></span> Mes charges administratives</a></li>
                </ul>
            </div>
        </div>

		<div class="row">

                <div class="col-md-12">
                    <div class="pull-right">
                        <a href="" title="Retour à la liste" class="btn btn-flat btn-cancel" ><span class="fa fa-arrow-left"></span></a>
                        <a href="" title="Modifier ce projet" class="btn btn-flat btn-primary"><span class="fa fa-pencil"></span></a>
                        <a href="" title="Supprimer ce projet" class="btn btn-flat btn-danger"><span class="fa fa-trash-o"></span></a>
                    </div>
                    <h3>
                        <span class="text-primary">2015-196</span>
                        Poursuivre la simplification des bourses d'étude (SAPE)
                    </h3>
                </div>

        </div>

        <div class="row">

                <div class="col-md-4">

                                    <div class="block-flat">
                                        <div class="header">
                                            <h4>Description</h4>
                                        </div>
                                        <div class="content">
                                            <p class="text-justify read-more" data-readmore-link-text="Lire la suite">
                                                La Direction des Prêts et Allocations d’Etudes (DAPE) a pour mission de gérer l’octroi des allocations et prêts d’études secondaires et supérieures aux élèves et étudiants de condition peu aisée. Elle doit également gérer les réclamations et recours liés à ceux-ci. Environ 160.000 demandes sont introduites chaque année, dont environ 7.000 (en augmentation) concernent les bénéficiaires des CPAS. Depuis 2013, le projet d’informatisation des demandes de bourses d’études a permis de simplifier fortement les démarches des usagers en leur permettant d’introduire leur demande en ligne et en leur proposant un formulaire prérempli avec leurs données authentiques provenant du SPF Intérieur (données du Registre national) et du SPF Finances (avertissement extrait de rôle). Quoi qu'il en soit, le processus pourrait encore être simplifié et automatisé. Il faudrait notamment : - permettre la consultation systématique des sources authentiques au moment de l’encodage des données provenant des formulaires papier par les agents de la DAPE (prévu pour le 1/7/2016); - mettre en place un flux de données avec la BCSS relatif à présence dans la famille de personnes gravement handicapées : développement non prioritaire pour le comité sponsor du projet. - mettre en place un flux de données avec la BCSS relatif aux bénéficiaires du revenu d’intégration sociale (RIS) : développement non prioritaire pour le comité sponsor du projet.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="block-flat">
                                        <div class="header">
                                            <h4>Administrations impliquées</h4>
                                        </div>
                                        <div class="content">
                                            <span class="label label-default">AGE</span>
                                        </div>
                                    </div>

                                    <div class="block-flat">
                                        <div class="header">
                                            <h4>Public(s) cible</h4>
                                        </div>
                                        <div class="content">
                                            <span class="label label-default">Citoyen</span>
                                        </div>
                                    </div>

                                    <div class="block-flat">
                                        <div class="header">
                                            <h4>Ministre(s) compétent(s)</h4>
                                        </div>
                                        <div class="content">
                                            <span class="label label-default">Joëlle Milquet</span>
                                            <span class="label label-default">Jean Claude Marcourt</span>
                                        </div>
                                    </div>

                                    <div class="block-flat">
                                        <div class="header">
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
                    </div>


                    <div class="col-md-4">

                                    <div class="block-flat">
                                        <div class="header">
                                            <h4>Etat</h4>
                                        </div>
                                        <div class="content">
                                            <span class="label label-primary">Prioritaire</span>
                                            <span class="label label-primary">{{Lang::get('admin/ideas/states.ENREALISATION')}}</span>
                                        </div>
                                    </div>

                                    <div class="block-flat">
                                        <div class="header">
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
                                    </div>

                                    <div class="block-flat">
                                        <div class="header">
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

                    </div>

                    <div class="col-md-4">
                                    <div class="block-flat">
                                        <div class="header">
                                            <h4><span class="fa fa-comment-o"></span> Commentaires</h4>
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
                                        </div>
                                    </div>

                                    <div class="block-flat">
                                        <div class="header">
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



    <script type="text/javascript">

        $(document).ready( function () {

            $(".read-more").each ( function () {

                var maxWords = 60;
                var maxLength = 300;
                var linkText = $(this).data("readmore-link-text");
                var completeText = $(this).html();
                var $container = $(this);

                //trim le texte à la longueur max
                var shortText = completeText.substr(0, maxLength);
                //re-trim si on est au milieu d'un mot
                shortText = shortText.substr(0, Math.min(shortText.length, shortText.lastIndexOf(" ")));

                if (shortText.length < completeText.length) {
                    shortText += " ...";
                    $container.html(shortText);
                    $('<a href="#"><span class="fa fa-caret-down"></span> ' + linkText + '</a>')
                            .insertAfter($container)
                            .click( function () {
                                $container.html(completeText);
                                $(this).remove();
                            });
                }

            });

        });

    </script>


@stop
