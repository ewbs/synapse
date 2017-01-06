/*
 *      Spécifique au module DEMARCHES - SCM
 *      jda@ewbs.be
 *      
 *      DEPENDANCE : DROPZONE
 *      
 * 
 */

//globales
var token = null;
var $scmDropZone = null;
var $scmError = null; // confirmation erreur
var $scmErrorMessageContainer = null;
var $scmWarning = null; // confirmation attention
var $scmSuccess = null; // confirmation success
var $scmNothing = null; // confirmation rien n'a été fait
var $scmFileInProgress = null; // boite d'attente de traitement ("patientez ...")
var $scmResults = null; // tableau des résultats de l'import
var $scmTableLineTemplateOfSuccess = null; // ligne de résultat dans le tableau : ligne traitée
var $scmTableLineTemplateOfError = null;  // ligne de résultat dans le tableau : ligne en erreur
var $scmTableLineTemplateOfNothing = null; // ligne de résultat dans le tableau : ligne non traitée
var $scmInstructions = null; // paragraphe d"'explications affiché au début
/**
 * INIT
 * @param {type} param
 */
$(document).ready(function () {
	
	if ($("#_token").length) {
		token = $("#_token").val();
	}
	
	$scmDropZone = $("#dropzone-scm-upload");
	if($scmDropZone.length) {
		$scmNothing = $("#scm-nothing");
		$scmWarning = $("#scm-warning");
		$scmSuccess = $("#scm-success");
		$scmError = $("#scm-error");
		$scmErrorMessageContainer = $("#scm-error-content");
		
		$scmFileInProgress = $("#file-in-progress");
		$scmResults = $("table#scm-results");
		$scmTableLineTemplateOfSuccess = $("#scm-result-template-tr-success tbody");
		$scmTableLineTemplateOfError = $("#scm-result-template-tr-error tbody");
		$scmTableLineTemplateOfNothing = $("#scm-result-template-tr-nothing tbody");
		$scmInstructions = $("#scm-instructions");
		
		var scmDrop = new Dropzone("#dropzone-scm-upload", 
			{
				url: $scmDropZone.attr('action'),
				maxFileSize: 2,
				maxFiles: 1,
				uploadMultiple: false,
				parallelUploads: 1,
				createImageThumbnails: false,
				previewsContainer: "",
				acceptedFiles: ".xls,.xlsx,.xlsm",
				dictDefaultMessage: "Cliquez ici ou déposez votre SCM Light dans cette zone.",
				dictFallbackMessage: "Votre navigateur ne prend pas en charge le glisser/déposer.",
				dictFallbackText: "Votre navigateur ne prend pas en charge les fonctions super géniales de Synapse ... mais ne perdez pas espoir, voici un bon vieux formulaire d'envoi de fichier.",
				dictInvalidFileType: "Ce type de fichier est incorrect. Utilisez un fichier Excel (xslx).",
				dictFileTooBig: "Ce fichier est trop gros. La taille maximale autorisée est de {{maxFilesize}}. Votre fichier pèse {{filesize}}.",
				dictResponseError: "Quelque chose d'étrange s'est produit ... veuillez réessayer plus tard."
			});
			
		scmDrop.on("success", function(file, response) {
			scmDrop.disable();
			$scmDropZone.remove();			
			$scmFileInProgress.removeClass("hidden");
			processSCM(response.return, $scmDropZone.attr('data-urlprocess'));
		});
		
		scmDrop.on("addedfile", function(file) {
			$scmError.addClass("hidden");
		});
		
		scmDrop.on("error", function(file, response) {
			scmDrop.removeFile(file);
			if (typeof (response) == "object") { //erreur complexe, retournée par Synapse
				$scmErrorMessageContainer.html("Une erreur inconnue est survenue : " + response.return);
				$scmError.removeClass("hidden");
			}
			else { //erreur simple
				$scmErrorMessageContainer.html(response);
				$scmError.removeClass("hidden");
			}
		});
		
		
	}


});



/**
 * Lance un appel à Synapse pour traiter un fichier SCM.
 * Sur base du retour, on générera une liste de modifications détectée par Synapse et l'utilisateur devra valider ou non les modifications.
 * @param {type} fileName
 * @returns {undefined}
 */
function processSCM(theFileName, url) {
	
	$.ajax({
		url: url,
		method: "POST",
		data: {
			_token: token,
			fileName: theFileName
		}
	}).done( function (data) {
		$("#file-in-progress").addClass("hidden");
		if (data.error == false) {
			// on a recu un retour de l'application.
			// on épluche ce retour, et on notifiera l'utilisateur en fonction de ce qu'on trouve dedans :-)
			var unprocessed = 0;
			var processed = 0;
			var error = 0;
		
			// epluchage des résultats et construction du tableau résumé des résultats
			if (data.return.processedLines.length > 0) {
				$.each(data.return.processedLines, function (i, processedLine) {
					console.log(processedLine);
					switch (processedLine.action) {
						case 'unprocessed':	//ligne non traitée
							unprocessed++;
							var $tableLine = $( $scmTableLineTemplateOfNothing.html() );
							break;
						case 'processed': //ligne traitée
							processed++;
							var $tableLine = $( $scmTableLineTemplateOfSuccess.html() );
							break;
						case 'error': //ligne en erreur
							error++;
							var $tableLine = $( $scmTableLineTemplateOfError.html() );
							break;
						default:
							var $tableLine = $( $scmTableLineTemplateOfNothing.html() ); //ne sert normalement à rien ...
							break;
					}
					$tableLine.find("td.scm-td-line").html(processedLine.line);
					$tableLine.find("td.scm-td-type").html(processedLine.type);
					$tableLine.find("td.scm-td-result").html(processedLine.result);
					$scmResults.find("tbody").append($tableLine);		
				});
			}
			
			// affichage des résultats
			if ( processed > 0 && unprocessed == 0 && error == 0 ) { // aucune erreur et on a traité des lignes
				$scmSuccess.removeClass("hidden");
				$scmResults.removeClass("hidden");
			}
			else if ( unprocessed == 0 && processed == 0 && error == 0) { // rien n'a été fait ...
				$scmNothing.removeClass("hidden");
			} else  { // il y a des lignes non traitées ou des avertissement
				$scmWarning.removeClass("hidden");
				$scmResults.removeClass("hidden");
			}
			$scmInstructions.hide(); // on masque les instructions d'upload
		
			//console.log (data.return.processedLines);
		}
		else { // un erreur grave a été retournée par Synapse
			$scmErrorMessageContainer.html(data.return);
			$scmError.removeClass("hidden");
		}
	}).fail( function (jqXHR, textStatus, errorThrown) {
		$("#file-in-progress").addClass("hidden");
		$scmErrorMessageContainer.html("Erreur de communication avec Synapse");
		$scmError.removeClass("hidden");
	});
	
}