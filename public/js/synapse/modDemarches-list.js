var nostraPublicsContent = '';
var administrationsContent = '';

/**
 * Déterminer l'appel ajax sur base des paramètres du formulaire
 * @returns
 */
function getDemarchesAjaxUrl() {
	var ajaxUrl = $('#datatable').attr('data-ajaxurl');
	if(ajaxUrl.indexOf('?') == -1) ajaxUrl+='?';

	// documentées ?
	if ($("#catalogDemarches_onlyDocumented").is(":checked")) {
		ajaxUrl += "&onlyDocumented=1";
	}
    // hors nostra ?
    if ($("#catalogDemarches_onlyHorsNostra").is(":checked")) {
        ajaxUrl += "&onlyHorsNostra=1";
    }

	// avec actions ?
	if ($("#catalogDemarches_onlyWithActions").is(":checked")) {
		ajaxUrl += "&onlyWithActions=1";
	}

	// nombre de pièces minimum ?
	var minPieces = $("#catalogDemarches_minPieces").val();
	if ( Math.floor(minPieces) == minPieces && $.isNumeric(minPieces) ) { //est ce un entier ?
		if (minPieces > 0) {
			params['minPieces']=minPieces;
			ajaxUrl += "&minPieces=" + minPieces;
		}
	}

	// nombre de tâches minimum ?
	var minTasks = $("#catalogDemarches_minTasks").val();
	if ( Math.floor(minTasks) == minTasks && $.isNumeric(minTasks) ) { //est ce un entier ?
		if (minTasks > 0) {
			params['minTasks']=minTasks;
			ajaxUrl += "&minTasks=" + minTasks;
		}
	}

	// nombre de formulaires minimum ?
	var minForms = $("#dashboardDemarches_minForms").val();
	if ( Math.floor(minForms) == minForms&& $.isNumeric(minForms) ) { //est ce un entier ?
		if (minForms> 0) {
			ajaxUrl += "&minForms=" + minForms;
		}
	}

	// public cible ?
	var selectedNostraPublics = $('select#nostra_publics').val();
	if (selectedNostraPublics != null) {
		var tempArray = [];
		$.each(selectedNostraPublics, function (i, publicId) {
			tempArray.push(publicId);
		});
		if (tempArray.length > 0) {
			ajaxUrl += '&publics=' + tempArray.join();
		}
	}

	// administrations ?
	var selectedAdministrations = $('select#administrations').val();
	if (selectedAdministrations != null) {
		var tempArray = [];
		$.each(selectedAdministrations, function (i, admId) {
			tempArray.push(admId);
		});
		if (tempArray.length > 0) {
			ajaxUrl += '&administrations=' + tempArray.join();
		}
	}
	return ajaxUrl;
}

$(document).ready(function() {

	var $tableDemarches = $('#datatable').dataTable( {
		"aoColumnDefs": [
			{ 'bSortable'  : false, 'aTargets': [6] },
			{ 'bSearchable': false, 'aTargets': [6] },
			{ 'bVisible': false, 'aTargets': [7] }
		],
		"aaSorting" : [[0, "desc"]],
		"sAjaxSource": getDemarchesAjaxUrl(),
	});

	$("input.icheck").on('ifChanged', function(event) { $(event.target).trigger('change'); }) //les checkboxes avec iCheck ne propage pas l'événement change, au profit d'un événement "ifchanged". On l'intercepte donc et on repropage le change

	//les select2 sont assez lourds. Si on ne fait que cliquer dessus sans avoir rien changer il est
	//préférable d'éviter un reload. pour cela : à l'ouverture du select, on regarde ce qu'il y a dedans
	//à la fermeture on regarde aussi et on ne lancera un refresh de la liste que si il existe une différence
	//on fait des comparaison sur string... car la comparaison d'objet jQuery est toujours différente ($(this).val()).
	$('select#nostra_publics').on('select2:open', function () {
		nostraPublicsContent = $(this).val();
		if (nostraPublicsContent == null) {
			nostraPublicsContent = "";
		}
	});
	$('select#nostra_publics').on('select2:close', function () {
		var tempContent = $(this).val();
		if (tempContent == null) {
			tempContent = "";
		}
		if ((nostraPublicsContent.toString() != tempContent.toString()) || tempContent.length < 1) {
			$tableDemarches.fnReloadAjax(getDemarchesAjaxUrl());
		}
	});
	$('select#administrations').on('select2:open', function () {
		administrationsContent = $(this).val();
		if (administrationsContent == null) {
			administrationsContent = "";
		}
	});
	$('select#administrations').on('select2:close', function () {
		var tempContent = $(this).val();
		if (tempContent == null) {
			tempContent = "";
		}
		if ((administrationsContent.toString() != tempContent.toString()) || tempContent.length < 1) {
			$tableDemarches.fnReloadAjax(getDemarchesAjaxUrl());
		}
	});

	$("form#catalogDemarches_form input").change( function () {
		$tableDemarches.fnReloadAjax(getDemarchesAjaxUrl());
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