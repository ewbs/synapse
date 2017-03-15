/*
 *      Spécifique au module DEMARCHES, ecran du catalogue (liste des démarches)
 *      jda@ewbs.be
 *      
 *      DEPENDANCE : SELECT2, iCHECK
 * 
 */

var $domCheckboxOnlyDocumented = null;
var $domCheckboxOnlyActions = null;
var $domDivDemarchesList = null;
var $domSpanDemarchesCounter = null;
var $domSelectNostraPublics = null;
var $domSelectAdministrations = null;
var $domDivNoItem = null;
var $domInputTextSearch = null;
var $domAExport = null;
var $domToken = null;
var domSelectNostraPublicsContent = null;
var domSelectAdministrationsContent = "";
var token = null;
var ajaxDemarcheList = "/admin/demarches/datahtml/?";
var ajaxDemarcheExport = "/admin/demarches/export/?";



/**
 * INIT
 */
$(document).ready(function () {
	
	/*
	 * Gestion de la liste dans l'écran "catalogue des démarches" (outils de filtrage des démarches)
	 */
	if ($("input#demarches_onlyDocumented").length > 0) {
		$domCheckboxOnlyDocumented = $("input#demarches_onlyDocumented");
		$domCheckboxOnlyActions = $("input#demarches_onlyActions");
		$domDivDemarchesList = $("div#demarches_list");
		$domSpanDemarchesCounter = $("span#demarches_counter");
		$domSelectNostraPublics = $("select#nostra_publics");
		$domSelectAdministrations = $("select#administrations");
		$domDivNoItem = $("div#noItemsFound");
		$domInputTextSearch = $("input#demarche_textsearch");
		$domAExport = $("button#demarches_export");
		
		//chargement des publics cibles
		modDemarches_selectNostraPublics();
		
		//écouteurs
		$domCheckboxOnlyDocumented.on('ifChanged', function () {
			modDemarches_refreshList();
		});
		$domCheckboxOnlyActions.on('ifChanged', function () {
			modDemarches_refreshList();
		});
		$domInputTextSearch.on('keyup', function () {
			modDemarches_textsearch($(this).val());
		});
		$domAExport.click(function () {
			modDemarches_export();
		});
		//les select2 sont assez lourds. Si on ne fait que cliquer dessus sans avoir rien changer il est
		//préférable d'éviter un reload. pour cela : à l'ouverture du select, on regarde ce qu'il y a dedans
		//à la fermeture on regarde aussi et on ne lancera un refresh de la liste que si il existe une différence
		//on fait des comparaison sur string... car la comparaison d'objet jQuery est toujours différente ($(this).val()).
		$domSelectNostraPublics.on('select2:open', function () {
			domSelectNostraPublicsContent = $(this).val();
			if (domSelectNostraPublicsContent == null) {
				domSelectNostraPublicsContent = "";
			}
		});
		$domSelectNostraPublics.on('select2:close', function () {
			var tempContent = $(this).val();
			if (tempContent == null) {
				tempContent = "";
			}
			if ((domSelectNostraPublicsContent.toString() != tempContent.toString()) || tempContent.length < 1) {
				modDemarches_refreshList();
			}
		});
		$domSelectAdministrations.on('select2:open', function () {
			domSelectAdministrationsContent = $(this).val();
			if (domSelectAdministrationsContent == null) {
				domSelectAdministrationsContent = "";
			}
		});
		$domSelectAdministrations.on('select2:close', function () {
			var tempContent = $(this).val();
			if (tempContent == null) {
				tempContent = "";
			}
			if ((domSelectAdministrationsContent.toString() != tempContent.toString()) || tempContent.length < 1) {
				modDemarches_refreshList();
			}
		});
		//fin des écouteurs    
		
		
		//$(document).ajaxStart( function () { modDemarches_freezeScreenList(); } ); // démarrage de requêtes ajax : on freeze
		$(document).ajaxStop(function () {
			modDemarches_freeScreenList();
		}); // fin des requêtes ajax : on libère l'écran
		
		//premier refresh
		modDemarches_refreshList();
	}
});


/**
 * Afficher la liste des démarches dans l'écran de catalogue.
 * Selon les critères de filtrage choisis (documentées, publics, admin, ...)
 * @returns {undefined}
 */
function modDemarches_refreshList() {
	modDemarches_freezeScreenList(); //on freeze l'écran
	$domDivDemarchesList.html("");
	$domDivNoItem.hide();
	
	//appel au WS
	$.ajax({
		url: modDemarches_getUrlWithFilters(ajaxDemarcheList)
	})
	.done(function (html) {
		$domDivDemarchesList.html(html);
		$domSpanDemarchesCounter.html("(" + $domDivDemarchesList.find('input#counter').val() + ")");
	});
}


/**
 * Afficher une modale pour empêcher les saisies user
 * @returns {undefined}
 */
function modDemarches_freezeScreenList() {
	$("#pcont,.page-aside").overlay();
}


/**
 * Libéréééé, délivréééé, je ne m'afficherai plus jaaamaaaais ... 
 * @returns {undefined}
 */
function modDemarches_freeScreenList() {
	$("#pcont,.page-aside").overlayout();
}


/**
 * Populate du select des publics nostra (filtre de recherche)
 * Appelle la fonction modDemarches__showNostraPublicChildren de facon récursive pour afficher un arbre des publics
 * @returns {undefined}
 */
function modDemarches_selectNostraPublics() {
	$.ajax({
		url: '/api/v1/damus/publics'
	})
	.done(function (json) {
		$.each(json.publics, function (i, public) {
			$domSelectNostraPublics.append('<option value="' + public.id + '" data-parent="0">' + public.title + '</option>');
			modDemarches__showNostraPublicChildren(public, 0);
		});
	});
}


/**
 * Populate du select des publics nostra (filtre de recherche) avec les publics enfants
 * Appellé depuis la fonction modDemarches__showNostraPublics
 * @returns {undefined}
 */
function modDemarches__showNostraPublicChildren(public, level) {
	if (level > 64)
		return(0);
	if (public.children !== 'undefined') {
		$.each(public.children, function (i, child) {
			$domSelectNostraPublics.append('<option value="' + child.id + '" data-parent="' + child.parent_id + '">' + (Array(level + 2).join(" - ")) + child.title + '</option>');
			modDemarches__showNostraPublicChildren(child, level + 1);
		});
	}
}


/**
 * Recherche textuelle dans la liste des démarches.
 * On passe en paramètre la chain de texte à retrouver et on cache ce qui ne correspond pas avec un jQuery.hide();
 * 
 * @param {type} what
 * @returns {undefined}
 */
function modDemarches_textsearch(what) {
	var count=0;
	$domDivDemarchesList.children('div.item').each(function() {
		var that=$(this);
		if($(this).is(':contains("'+what+'")')) {
			that.show();
			count++;
		}
		else that.hide();
	});
	$domSpanDemarchesCounter.html("(" + count + ")");
}


/**
 * Lance l'export des démarches, en fonction des critères de filtre choisis
 * 
 * @returns {undefined}
 */
function modDemarches_export() {
	window.open(modDemarches_getUrlWithFilters(ajaxDemarcheExport));
}

/**
 * Complète l'url données avec les filtres de la colonne de gauche
 * 
 * @param url
 * @returns string
 */
function modDemarches_getUrlWithFilters(url) {
	var parameterInURL = false; //flag pour dire si on a déjà inséré un paramètres dans l'url

	// seulement les documentees ?
	if ($domCheckboxOnlyDocumented.is(":checked")) {
		url += (parameterInURL ? '&' : '') + 'onlyDocumented=1';
		parameterInURL = true;
	}
	
	//seulement les actions initiées ou en cours ?
	if ($domCheckboxOnlyActions.is(":checked")) {
		url += (parameterInURL ? '&' : '') + 'onlyActions=1';
		parameterInURL = true;
	}
	
	//filtre sur les publics
	var selectedNostraPublics = $domSelectNostraPublics.val();
	if (selectedNostraPublics != null) {
		var tempArray = [];
		$.each(selectedNostraPublics, function (i, publicId) {
			tempArray.push(publicId);
		});
		if (tempArray.length > 0) {
			url += (parameterInURL ? '&' : '') + 'publics=' + tempArray.join();
			parameterInURL = true;
		}
	}

	//filtre sur les administrations
	var selectedAdministrations = $domSelectAdministrations.val();
	if (selectedAdministrations != null) {
		var tempArray = [];
		$.each(selectedAdministrations, function (i, admId) {
			tempArray.push(admId);
		});
		if (tempArray.length > 0) {
			url += (parameterInURL ? '&' : '') + 'administrations=' + tempArray.join();
			parameterInURL = true;
		}
	}
	return url;
}