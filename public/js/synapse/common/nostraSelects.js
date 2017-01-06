/*
 *      Spécifique au module IDEAS
 *      jda@ewbs.be
 *      
 *      GESTION des champs NOSTRA
 *      
 *      DEPENDANCE : SELECT2
 *      
 *       doc à écrire ... non mais sérieux ...
 *       ... et code à réécrire ... c'est devenu 
 * 
 */


var domNostraSelects = null;                        // ensemble des selects nostra
var domSelectNostraPublics = null;                  // select publics
var domNostraPublicsCounter = null;                 // compteur publics
var domSelectNostraThematiquesADM = null;
var domNostraThematiquesADMCounter = null;
var domSelectNostraThematiquesABC = null;
var domNostraThematiquesABCCounter = null;
var domSelectNostraEvenements = null;
var domNostraEvenementsCounter = null;
var domSelectNostraDemarches = null;
var domNostraDemarchesCounter = null;
var selectedNostraPublics = null;                   // publics sélectionnés (options:selected)
var selectedNostraThematiquesABC = null;
var selectedNostraThematiquesADM = null;
var selectedNostraEvenements = null;
var selectedNostraDemarches = null;
var globalInnerRefresh = false;                     // flag global : on doit parfois doubler le refresh pour actualiser les contenus des combos.
var globalFirstDisplay= true;                       // flag global : premier affichage (pas un refresh)


/**
 * INIT
 * @param {type} param
 */
$(document).ready( function () {
	domNostraSelects = $("#nostra-selects");
	// pour le formulaire d'ajout/suppression
	if (domNostraSelects.length) {
		nostraSelects_init();
	}
});


/**
 * Initialisation dans le module IDEAS
 * @returns {undefined}
 */
function nostraSelects_init() {
	//init des variables utilisées
	domSelectNostraPublics = $("select#nostra_publics");
	domNostraPublicsCounter = $("span#countNostraPublics");
	domSelectNostraThematiquesADM = $("select#nostra_thematiquesadm");
	domNostraThematiquesADMCounter = $("span#countNostraThematiquesadm");
	domSelectNostraThematiquesABC = $("select#nostra_thematiquesabc");
	domNostraThematiquesABCCounter = $("span#countNostraThematiquesabc");
	domSelectNostraEvenements = $("select#nostra_evenements");
	domNostraEvenementsCounter = $("span#countNostraEvenements");
	domSelectNostraDemarches = $("select#nostra_demarches");
	domNostraDemarchesCounter = $("span#countNostraDemarches");
	
	//gel de l'écran et dégel
	$(document).ajaxStart( function () { modIdeas_freezeScreen(); } ); // démarrage de requêtes ajax : on freeze
	
	//on charge le select des publics
	modIdeas_selectNostraPublics_init();
	
	//on charge le select des démarches administratives
	modIdeas_selectNostraThematiquesADM_init();
	
	//on libère, quand tout est fini
	$(document).ajaxStop( function () { 
		//on place des écouteurs sur les selects
		$("select.nostra").on('select2:close', function (e) { modIdeas_refresh(); } );
		//on lance un refresh pour charger les bonnes valeurs en cas d'action EDIT
		modIdeas_refresh();
		 
		$(this).unbind("ajaxStart"); //on détruit ajaxStart, plus besoin
		$(this).unbind("ajaxStop"); //on détruit ajaxStop, plus besoin
		
		//on libere
		modIdeas_freeScreen();
	});
}


/**
 * Rafraichit les compteurs des selects
 * @returns {undefined}
 */
function modIdeas_counters () {
	domNostraPublicsCounter.html(domSelectNostraPublics.find('option').length);
	domNostraThematiquesABCCounter.html(domSelectNostraThematiquesABC.find('option').length);
	domNostraThematiquesADMCounter.html(domSelectNostraThematiquesADM.find('option').length);
	domNostraEvenementsCounter.html(domSelectNostraEvenements.find('option').length);
	if(domSelectNostraDemarches.length)
		domNostraDemarchesCounter.html(domSelectNostraDemarches.find('option').length);
}


/**
 * Charge le contenu pour le select des thematiques adm
 * @returns {undefined}
 */
function modIdeas_selectNostraThematiquesADM_init () {
	$.ajax({
		url: '/api/v1/damus/thematiquesadm'
	})
	.done(function (json) {
		$.each( json.thematiques, function (i, thematique) {
			domSelectNostraThematiquesADM.append('<option value="'+thematique.id+'" data-parent="0">'+thematique.title+'</option>');
			modIdeas__showNostraThematiquesADMChildren(thematique, 0);
		});
	});
}


/**
 * Charge le contenu pour le select des thématiques administratives
 * @returns {undefined}
 */
function modIdeas_selectNostraPublics_init () {
	$.ajax({
		url: '/api/v1/damus/publics'
	})
	.done(function (json) {
		$.each( json.publics, function (i, public) {
			domSelectNostraPublics.append('<option value="'+public.id+'" data-parent="0">'+public.title+'</option>');
			modIdeas__showNostraPublicChildren(public, 0);
		});
	});
}


/**
 * Refresh de la combo public (voir si il y a des parents à selectionner)
 * @returns {undefined}
 */
function modIdeas_selectNostraPublics_refresh() {
	//selection révusrive si besoin
	if (selectedNostraPublics !== null) {
		$.each(selectedNostraPublics, function (i, selectedOption) {
			//on va voir si il y a un parent à sélectionner...
			modIdeas__selectNostraPublicParent(selectedOption, 0);
		});
	}
	//refresh du select
	domSelectNostraPublics.change();
}


/**
 * Refresh de la combo thematiqueadm (voir si il y a des parents à selectionner)
 * @returns {undefined}
 */
function modIdeas_selectNostraThematiquesADM_refresh() {
	//selection révusrive si besoin
	if (selectedNostraThematiquesADM !== null) {
		$.each(selectedNostraThematiquesADM, function (i, selectedOption) {
			//on va voir si il y a un parent à sélectionner...
			modIdeas__selectNostraThematiqueADMParent(selectedOption, 0);
		});
	}
	//refresh du select
	domSelectNostraThematiquesADM.change();
}


function modIdeas_refresh () {
	//console.log("------------------------- REFRESH");
	modIdeas_freezeScreen();
	
	/*
	 * Logique du refresh
	 * On fera les appels de facon synchrone (car on dépend toujours du contenu
	 * d'un select pour populer un autre.
	 * En async, on devait démultiplier les appels et les performances n'étaient 
	 * pas super. En fait on pas pas vraiment du sync (c'est maaaaal) mais on va chainer
	 * les appels grace à $then().
	 * Si vous ne comprenez pas, allez lire la doc o_O
	 * On fera d'abord le tour des publics, un par un pour mettre à jour les combos
	 * thematiquesabc et evenements.
	 * puis on prendra les thematiques ADM
	 * et avec tout ca on mettra a jour les démarches       
	 */
	
	
	/*
	 * Tout d'abord, on regarde si c'est le premier refresh ou non
	 * Si oui, il peut s'agir d'un réaffichage de page, ou de l'affichage
	 * d'une page d'edit. on va alors prendre dans la page les valeurs sélectionnées
	 */
	if ( globalFirstDisplay == true ) {
		globalFirstDisplay = false;
		//on prend les variables depuis la view générée
		selectedNostraPublics = preSelectedNostraPublics;
		selectedNostraThematiquesABC = preSelectedNostraThematiquesabc;
		selectedNostraThematiquesADM = preSelectedNostraThematiquesadm;
		selectedNostraEvenements = preSelectedNostraEvenements;
		selectedNostraDemarches = preSelectedNostraDemarches;
		//on force le refresh
		domSelectNostraPublics.val(selectedNostraPublics).change();
		domSelectNostraThematiquesADM.val(selectedNostraThematiquesADM).change();
	}
	else {
		//refresh autre que le premier
		//on mémorise les sélections
		selectedNostraPublics = domSelectNostraPublics.val();
		selectedNostraThematiquesABC = domSelectNostraThematiquesABC.val();
		selectedNostraThematiquesADM = domSelectNostraThematiquesADM.val();
		selectedNostraEvenements = domSelectNostraEvenements.val();
		if(domSelectNostraDemarches.length)
			selectedNostraDemarches = domSelectNostraDemarches.val();
	}
	
	//on purge tout (sauf les publics et les thematiques adm)
	domSelectNostraThematiquesABC.children().remove();
	domSelectNostraThematiquesABC.change();
	domSelectNostraEvenements.children().remove();
	domSelectNostraEvenements.change();
	if(domSelectNostraDemarches.length) {
		domSelectNostraDemarches.children().remove();
		domSelectNostraDemarches.change();
	}
	
	//on selectionne les éventuels enfants (ces select peuvent avoir des élements hiérachiques)
	modIdeas_selectNostraThematiquesADM_refresh();
	modIdeas_selectNostraPublics_refresh();
	selectedNostraPublics = domSelectNostraPublics.val();
	selectedNostraThematiquesADM = domSelectNostraThematiquesADM.val();
	
	
	/*
	 * Démarrage de la mise à jour par appels ajax en "synchrone"
	 * On commence par mettre à jour les thematiques abc via les publics
	 */
	globalInnerRefresh = true;
	modIdeas_refresh_thematiquesabc();
}


/**
 * Cette fonction met à jour les thématiques ABC
 * Elle est la première appelée depuis un refresh
 * Le dernier appel ajax appelera modIdeas_refresh_evenements();
 * Si il n'y a pas de refresh à faire, on appelera direct modIdeas_refresh_evenements();
 * @returns {undefined}
 */
function modIdeas_refresh_thematiquesabc() {
	//console.log("refresh_thematiquesabc");
	
	if (selectedNostraPublics != null && selectedNostraPublics.length>0) {
		var oneCallDone = false; //si seoement l'otion -1 (entrée libre) est sélectionnée, on ne fera pas d'appels ajax. il faut détecter ce cas pour lancer la suite des opérations tout de même
		var data=null;
		var id = selectedNostraPublics[0];
		if (selectedNostraPublics[0] > 0) {
			oneCallDone = true;
			data = modIdeas_enqueueAjax_thematiquesabc(id, 0, selectedNostraPublics.length);
		}
		else {
			data = $.ajax(); //on crée un ajax vide, juste pour lui chainer les suivants
		}
		for (var i = 1; i < selectedNostraPublics.length; i++) {
		(function (i) {
			if (selectedNostraPublics[i] > 0) {
				oneCallDone = true;
				data = data.then(function() {
					return modIdeas_enqueueAjax_thematiquesabc(selectedNostraPublics[i], i, selectedNostraPublics.length);
				});
			}
		}(i));
		}
		if ( ! oneCallDone ) {
			modIdeas_refresh_evenements();
		}
	}
	else {
		modIdeas_refresh_demarches();
	}
}


/**
 * Cette fonction met à jour les evenements
 * Elle est la deuxieme appelée depuis un refresh
 * @returns {undefined}
 */
function modIdeas_refresh_evenements() {
	//console.log("refresh_evenements");
	
	domSelectNostraEvenements.children().remove();
	domSelectNostraEvenements.change();
	
	if (selectedNostraPublics != null && selectedNostraPublics.length>0) {
		var oneCallDone = false;
		var id = selectedNostraPublics[0];
		var data=null;
		if (selectedNostraPublics[0] > 0) {
			oneCallDone = true;
			data = modIdeas_enqueueAjax_evenements(id, 0, selectedNostraPublics.length);
		}
		else {
			data = $.ajax(); //on crée un ajax vide, juste pour lui chainer les suivants
		}
		for (var i = 1; i < selectedNostraPublics.length; i++) {
		(function (i) {
			if (selectedNostraPublics[i] > 0) {
				oneCallDone = true;
				data = data.then(function() {
					return modIdeas_enqueueAjax_evenements(selectedNostraPublics[i], i, selectedNostraPublics.length);
				});
			}
		}(i));
		}
		if ( ! oneCallDone ) {
			modIdeas_refresh_demarches();
		}
	}
	else {
		modIdeas_refresh_demarches();
	}
}

/**
 * Cette fonction met à jour les demarches
 * Elle est la dernière appelée depuis un refresh
 * @returns {undefined}
 */
function modIdeas_refresh_demarches() {
	
	if(domSelectNostraDemarches.length==0) { //Si le select des démarches n'est pas affiché, on ne va pas plus loin
		//mise à jour des compteur
		modIdeas_counters();
		//et on libère
		modIdeas_freeScreen();
		return;
	}
	//console.log("refresh_demarches");
	
	//on avait setté ces variables avant de faire le refresh des selects
	//mais dans les selects on sélectionne les parents par récursivité
	//et ces variables sont donc obsolètes.
	// --> on reselect
	selectedNostraPublics = domSelectNostraPublics.val();
	selectedNostraThematiquesABC = domSelectNostraThematiquesABC.val();
	selectedNostraThematiquesADM = domSelectNostraThematiquesADM.val();
	selectedNostraEvenements = domSelectNostraEvenements.val();
	//selectedNostraDemarches = domSelectNostraDemarches.val(); PAS CELLE CI ! SINON CA RESET LES DEMARCHES, BOULET!
	
	//on crée l'appel au WS des démarches de Damus en fonction ce qu'on va trouver dans les différents select
	var callToDemarchesWS = new Object();
	var callToDemarchesWSURL = '/api/v1/damus/demarches?';
	var parameterInURL = false; //flag pour dire si on a déjà inséré un paramètres dans l'url du WS (pour mettre un "&' devant une nouvelle inclusion);
	
	if ( selectedNostraPublics != null ) {
		callToDemarchesWS['publics'] = [];
		$.each(selectedNostraPublics, function (i, publicId) {
			if (publicId > 0) {
				callToDemarchesWS['publics'].push(publicId);
			}
		});
	}
	
	if ( selectedNostraThematiquesABC != null ) {
		callToDemarchesWS['thematiquesabc'] = [];
		$.each(selectedNostraThematiquesABC, function (i, thematiqueId) {
			if (thematiqueId > 0) {
				callToDemarchesWS['thematiquesabc'].push(thematiqueId);
			}
		});
	}
	
	if ( selectedNostraEvenements != null ) {
		callToDemarchesWS['evenements'] = [];
		$.each(selectedNostraEvenements, function (i, evenementId) {
			if (evenementId > 0) {
				callToDemarchesWS['evenements'].push(evenementId);
			}
		});
	}
	
	if ( selectedNostraThematiquesADM != null ) {
		callToDemarchesWS['thematiquesadm'] = [];
		$.each(selectedNostraThematiquesADM, function (i, thematiqueId) {
			if (thematiqueId > 0) {
				callToDemarchesWS['thematiquesadm'].push(thematiqueId);
			}
		});
	}
	
	if (typeof callToDemarchesWS['publics'] != "undefined" ) {
		if (callToDemarchesWS['publics'].length > 0) {
			callToDemarchesWSURL += (parameterInURL ? '&' : '') + 'publics=' + callToDemarchesWS['publics'].join();
			parameterInURL = true;
		}
	}
	
	if (typeof callToDemarchesWS['thematiquesabc'] != "undefined" ) {
		if (callToDemarchesWS['thematiquesabc'].length > 0) {
			callToDemarchesWSURL += (parameterInURL ? '&' : '') + 'thematiquesabc=' + callToDemarchesWS['thematiquesabc'].join();
			parameterInURL = true;
		}
	}
	
	if (typeof callToDemarchesWS['evenements'] != "undefined" ) {
		if (callToDemarchesWS['evenements'].length > 0) {
			callToDemarchesWSURL += (parameterInURL ? '&' : '') + 'evenements=' + callToDemarchesWS['evenements'].join();
			parameterInURL = true;
		}
	}
	
	if (typeof callToDemarchesWS['thematiquesadm'] != "undefined" ) {
		if (callToDemarchesWS['thematiquesadm'].length > 0) {
			callToDemarchesWSURL += (parameterInURL ? '&' : '') + 'thematiquesadm=' + callToDemarchesWS['thematiquesadm'].join();
			parameterInURL = true;
		}
	}
	
	if (parameterInURL) {
		//appel au WS
		$.ajax({
			url: callToDemarchesWSURL
		})
		.done(function (json) {
			if ( json.demarches.length > 0) {
				$.each( json.demarches, function (i, demarche) {
					//on ajoute que si ca n'existe pas déjà dans le select
					if (domSelectNostraDemarches.find('option[value="'+demarche.id+'"]').length == 0) {
						var option =  $('<option></option>');
						option.val(demarche.id);
						option.text(demarche.title);
						domSelectNostraDemarches.append(option);
					}
				});
			}
			//on reselectionne ce qui l'était, et on sélectionne les parents si nécessaire
			domSelectNostraDemarches.val(selectedNostraDemarches);
			domSelectNostraDemarches.change(); //refresh
			//mise à jour des compteur
			modIdeas_counters();
			//et on libère
			modIdeas_freeScreen();
		})
		.error( function () {
			alert('Erreur de communication avec Synapse');
			//mise à jour des compteur
			modIdeas_counters();
			//et on libère
			modIdeas_freeScreen();
		});
	}
	else {
		domSelectNostraDemarches.val(selectedNostraDemarches);
		domSelectNostraDemarches.change(); //refresh
		//mise à jour des compteur
		modIdeas_counters();
		//et on libère
		modIdeas_freeScreen();
	}
}


/**
 * Chainer les appels ajax dans une queue pour être sur qu'ils s'exécutent dans l'ordre
 * @param {type} nostraPublicId
 * @param {type} i
 * @param {type} total
 * @returns {unresolved}
 */
function modIdeas_enqueueAjax_thematiquesabc(nostraPublicId, i, total) {
	//on regarde si il s'agit du dernier appel
	lastOne=false;if (i+1 >= total) lastOne=true;
	
	// on recherche le nom du public
	var publicName = domSelectNostraPublics.find('option[value="'+nostraPublicId+'"]').text();
	return ($.ajax({
		url: '/api/v1/damus/thematiquesabc/'+nostraPublicId,
		dataType: 'json',
	}).done(function(json) {
		if (json.thematiques.length > 0) {
			var countOptions = 0;
			var optgroup = $('<optgroup>');
			optgroup.attr('label', publicName);
			optgroup.attr('data-public-id', nostraPublicId);
			$.each( json.thematiques, function (i, thematique) {
				var option =  $('<option></option>');
				option.val(thematique.id);
				option.attr('data-parent', 0);
				option.text(thematique.title);
				optgroup.append(option);
				modIdeas__showNostraThematiquesABCChildren(optgroup, thematique, 0);
				countOptions++; //juste pour savoir si il y a au moins une option
			});
			if (countOptions > 0) { //on ajoute l'optgroup si il contient au moins une option
				domSelectNostraThematiquesABC.append(optgroup);
			}
		}
		if (lastOne) {
			//on reselectionne ce qui l'était, et on sélectionne les parents si nécessaire
			domSelectNostraThematiquesABC.val(selectedNostraThematiquesABC);
			if (selectedNostraThematiquesABC != null) {
				$.each(selectedNostraThematiquesABC, function (i, selectedOption) {
					modIdeas__selectNostraThematiqueABCParent(selectedOption, 0);
				});
			}
			domSelectNostraThematiquesABC.change(); //refresh
			//on lance la mise à jout du select "evenements"
			modIdeas_refresh_evenements();
		}
	}).fail(function() {
		alert('Erreur de communication avec Synapse ...');
	}));
}


/**
 * Chainer les appels ajax dans une queue pour être sur qu'ils s'exécutent dans l'ordre
 * @param {type} nostraPublicId
 * @param {type} i
 * @param {type} total
 * @returns {undefined}
 */
function modIdeas_enqueueAjax_evenements(nostraPublicId, i, total) {
	//on regarde si il s'agit du dernier appel
	lastOne=false;if (i+1 >= total) lastOne=true;
	
	//La subtilité ici est qu'on devrait aller chercher les événements en fonction du public
	//mais si l'utilisateur a coché des thématiques, on va filtrer (faire un appel ws en passant des ids de thematiques pour restreindre les résultats
	var selectedThematiquesabcRelativeToThisPublic = []; //un nom de variable bien badass, ma gueule
	domSelectNostraThematiquesABC
		.find("optgroup[data-public-id="+nostraPublicId+"] option:selected")
		.each( function (i, th) { selectedThematiquesabcRelativeToThisPublic.push($(th).val()); });
	
	var ajaxUrl = '/api/v1/damus/evenements/'+nostraPublicId+'/'+selectedThematiquesabcRelativeToThisPublic.toString();
	var publicName = domSelectNostraPublics.find('option[value="'+nostraPublicId+'"]').text();
	
	return ($.ajax({
		url: ajaxUrl
	}).done(function (json) {
		if ( json.evenements.length > 0) {
			var countOptions = 0;
			var optgroup = $('<optgroup>');
			optgroup.attr('label', publicName);
			optgroup.attr('data-public-id', nostraPublicId);
			$.each( json.evenements, function (i, evenement) {
				var option =  $('<option></option>');
				option.val(evenement.id);
				option.attr('data-parent', 0);
				option.attr('data-thematiqueIds', evenement.nostra_thematiqueabc_id.toString());
				option.text(evenement.title);
				optgroup.append(option);
				countOptions++;
				//pas de récursivité dans les événements-->pasd'appel à autre fonction
			});
			if (countOptions > 0) {
				domSelectNostraEvenements.append(optgroup);
			}
		}
		if (lastOne) {
			//on reselectionne ce qui l'était, et on sélectionne les parents si nécessaire
			domSelectNostraEvenements.val(selectedNostraEvenements);
			domSelectNostraEvenements.change(); //refresh
			//et maintenant, la petite puputerie ... on selectionne dans le select "thematiques usager" les thématiques en lien
			//avec les événéments déclencheurs sélectionnés (oui, on peut sélectionner un événement en lien direct avec un public)
			//on fait ca ici, pour mettre à jour le select avant de déclencher l'update du select evenement
			//(sinon on peut avoir un problème de cohérence, après plusieurs select/deselect)
			//ceci implique de rafraichir à nouveau la combo "evenements déclencheur", car le fait de sélectionner une option en sélectionne
			//d'office une dans les thématiques ... ce qui doit limiter les résultats dans ce select                
			if (selectedNostraEvenements !== null && selectedNostraEvenements.length > 0 && globalInnerRefresh == true) {
				//console.log(selectedNostraEvenements + " / " + domSelectNostraEvenements.val());
				var thematiques = selectedNostraThematiquesABC;
				if (! Array.isArray(thematiques)) thematiques = [];
				console.log(thematiques);
				$.each(selectedNostraEvenements, function (i, evenementId) {
					try {
						var thematiqueToActivate = domSelectNostraEvenements.find('option[value="'+evenementId+'"]').attr("data-thematiqueIds").split();
						if (thematiqueToActivate != "undefined" && thematiqueToActivate.length > 0) {                   
							thematiques = thematiques.concat(thematiqueToActivate);
							/*$.each(thematiqueToActivate, function (i, elem) {
							    domSelectNostraThematiquesABC.find('option[value="'+elem+'"]').attr('selected', 'selected');
							});*/
						}
					}
					catch (err) {
					/* l'erreur qui peut arriver est qu'il ne trouve plus l'attribut à splitter ... on catche l'erreur de facon silencieuse ... et il n'y a rien à faire */
					}
				});
				console.log(thematiques);
				domSelectNostraThematiquesABC.val(thematiques);
				domSelectNostraThematiquesABC.change(); //refresh
				globalInnerRefresh = false;
				modIdeas_refresh_evenements();
			}
			else {
				//on lance la mise à jout du select "demarches", si on est pas dans un refresh intermédiaire
				modIdeas_refresh_demarches();
			}
		}
	})
	.fail(function() {
		alert('Erreur de communication avec Synapse ...');
	}));
}


/**
 * Récursive pour afficher des éléments enfants
 * @param {type} public
 * @param {type} level
 * @returns {Number}
 */
function modIdeas__showNostraPublicChildren (public, level) {
	if (level > 64) return(0);
	if (public.children !== 'undefined') {
		$.each( public.children, function (i, child) {
			domSelectNostraPublics.append('<option value="'+child.id+'" data-parent="'+child.parent_id+'">'+(Array(level+2).join(" - "))+child.title+'</option>');
			modIdeas__showNostraPublicChildren(child, level+1);
		});
	}
}


/**
 * Récursive pour sélectionner des parents
 * @param {type} public
 * @param {type} level
 * @returns {Number}
 */
function modIdeas__selectNostraPublicParent(public, level) {
	if (level > 64) return(0);
	var parent_id = domSelectNostraPublics.find("option[value="+public+"]").data('parent');
	//on select le parent si besoin
	if (parent_id > 0) {
		var oldValue = domSelectNostraPublics.val();
		domSelectNostraPublics.val( oldValue.concat(parent_id) );
		modIdeas__selectNostraPublicParent(parent_id, level+1);
	}
}


/**
 * Récursive pour afficher des éléments enfants
 * @param {type} thematique
 * @param {type} level
 * @returns {Number}
 */
function modIdeas__showNostraThematiquesADMChildren (thematique, level) {
	if (level > 64) return(0);
	if (thematique.children !== 'undefined') {
		$.each( thematique.children, function (i, child) {
			domSelectNostraThematiquesADM.append('<option value="'+child.id+'" data-parent="'+child.parent_id+'">'+(Array(level+2).join(" - "))+child.title+'</option>');
			modIdeas__showNostraThematiquesADMChildren(child, level+1);
		});
	}
}


/**
 * Récursive pour afficher des éléments enfants
 * @param {type} optgroup
 * @param {type} thematique
 * @param {type} level
 * @returns {Number}
 */
function modIdeas__showNostraThematiquesABCChildren (optgroup, thematique, level) {
	if (level > 64) return(0);
	if (thematique.children !== 'undefined') {
		$.each( thematique.children, function (i, child) {
			optgroup.append('<option value="'+child.id+'" data-parent="'+child.parent_id+'">'+(Array(level+2).join(" - "))+child.title+'</option>');
			modIdeas__showNostraThematiquesABCChildren(optgroup, child, level+1);
		});
	}
}


/**
 * Récursive pour sélectionner des parents
 * @param {type} thematique
 * @param {type} level
 * @returns {Number}
 */
function modIdeas__selectNostraThematiqueABCParent(thematique, level) {
	if (level > 64) return(0);
	var parent_id = domSelectNostraThematiquesABC.find("option[value="+thematique+"]").data('parent');
	//on select le parent si besoin
	if (parent_id > 0) {
		var oldValue = domSelectNostraThematiquesABC.val();
		domSelectNostraThematiquesABC.val( oldValue.concat(parent_id) );
		modIdeas__selectNostraThematiqueABCParent(parent_id, level+1);
	}
}


/**
 * Récursive pour sélectionner des parents
 * @param {type} thematique
 * @param {type} level
 * @returns {Number}
 */
function modIdeas__selectNostraThematiqueADMParent(thematique, level) {
	if (level > 64) return(0);
	var parent_id = domSelectNostraThematiquesADM.find("option[value="+thematique+"]").data('parent');
	//on select le parent si besoin
	if (parent_id > 0) {
		var oldValue = domSelectNostraThematiquesADM.val();
		domSelectNostraThematiquesADM.val( oldValue.concat(parent_id) );
		modIdeas__selectNostraThematiqueADMParent(parent_id, level+1);
	}
}


/**
 * Afficher une modale pour empêcher les saisies user
 * @returns {undefined}
 */
function modIdeas_freezeScreen() {
	domNostraSelects.overlay();
}


/**
 * Libéréééé, délivréééé, je ne m'afficherai plus jaaamaaaais ... 
 * @returns {undefined}
 */
function modIdeas_freeScreen() {
	domNostraSelects.overlayout();
}