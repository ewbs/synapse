$(document).ready(function () {
	
	/*
	 * Gestion des champs de gain
	 */
	$('#unlockGains').on('click', function (e) {
		e.preventDefault();
		$('.lockedGain').each(function() {
			$(this).prop("disabled", false);
		});
		$(this).hide();
		$('#lockGains').show();
	});
	
	$('#lockGains').click(function (e) {
		e.preventDefault();
		$('.lockedGain').each(function() {
			var that=$(this);
			that.val(that.attr('data-old'));
			that.prop("disabled", true);
		});
		$(this).hide();
		$('#unlockGains').show();
	});
	
	if (typeof mustUnlockGains != 'undefined') {
		$('#unlockGains').trigger( "click" );
	}
	
	/*
	 * Modale pour consulter une démarche dans le détail (depuis l'écran view d'une démarche)
	 * Appel à Nostra dans ce cas
	 */
	if ($("#show-modal-complete-record").length) {
		
		$('#show-modal-complete-record').click(function (e) {
			
			var $button = $(this);
			var $modal = $($button.data("target"));
			var ajaxURL = $modal.data("nostra-url");
			
			$button.attr("disabled", "disabled"); //désactive le bouton durant l'appel (pour éviter les clics intempestifs
			e.preventDefault();
			
			$.ajax({
				url: ajaxURL,
			})
			.done(function (data) {
				$modalBody = $modal.find("#modalCompleteBody");
				$modalBody.html("");
				
				// Titres
				$modal.find("#modalCompleteTitle").html(data['node_title']);
				// Autres titres
				if (typeof data['title_user_long'] != "undefined" || typeof data['title_user_short'] != "undefined") {
					$modalBody.append('<h5 class="color-primary">Autres titres</h5>');
					var $temp = $('<ul>');
					if (typeof data['title_user_long'] != "undefined") {
						$temp.append('<li><strong>Titre long : </strong>' + data['title_user_long'] + '</li>');
					}
					if (typeof data['title_user_short'] != "undefined") {
						$temp.append('<li><strong>Titre court : </strong>' + data['title_user_short'] + '</li>');
					}
					$modalBody.append($temp);
				}
				// Description
				if (typeof data['body'] != "undefined") {
					$modalBody.append('<h5 class="color-primary">Description</h5>');
					$modalBody.append('<div>' + data['body'] + '</div>');
				}
				// Interlocuteurs
				if (typeof data['stakeholders'] != "undefined") {
					$modalBody.append('<h5 class="color-primary">Interlocuteur(s)</h5>');
					$temp = $("<ul>");
					var html = "";
					for (var index = 0; index < data['stakeholders'].length; ++index) {
						html += "<li><strong>";
						if (typeof data['stakeholders'][index]['title'] != "undefined") {
							html += data['stakeholders'][index]['title'];
						}
						else {
							html += "!!nom vide!!";
						}
						html += ': </strong> ';
						if (typeof data['stakeholders'][index]['output'] != "undefined") {
							html += data['stakeholders'][index]['output'];
						}
						html += '</li>';
					}
					$temp.append(html);
					$modalBody.append($temp);
				}
				// Administrations
				if (data['administrations']) {
					$modalBody.append('<h5 class="color-primary">Administration(s)</h5>');
					$temp = $("<ul>");
					var html = "";
					for (index = 0; index < data['administrations'].length; ++index) {
						html += "<li><strong>";
						if (typeof data['administrations'][index]['title'] != "undefined") {
							html += data['administrations'][index]['title'];
						}
						else {
							html += "!!nom vide!!";
						}
						html += ': </strong> ';
						if (typeof data['administrations'][index]['address'] != "undefined") {
							html += data['administrations'][index]['address'] + " - ";
						}
						if (typeof data['administrations'][index]['postcode'] != "undefined") {
							html += data['administrations'][index]['postcode'] + " - ";
						}
						if (typeof data['administrations'][index]['url'] != "undefined") {
							html += '<a href="' + data['administrations'][index]['url'] + '" target="_blank"><span class="fa fa-external-link"></span> Consulter le site</a>';
						}
						html += '</li>';
					}
					$temp.append(html);
					$modalBody.append($temp);
				}
				// Références légales
				if (data['legal_references']) {
					$modalBody.append('<h5 class="color-primary">Référence(s) légale(s)</h5>');
					$temp = $("<ul>");
					var html = "";
					for (index = 0; index < data['legal_references'].length; ++index) {
						html += "<li><strong>";
						if (typeof data['legal_references'][index]['title'] != "undefined") {
							html += data['legal_references'][index]['title'];
						}
						else {
							html += "!!nom vide!!";
						}
						html += ': </strong> ';
						if (typeof data['legal_references'][index]['body'] != "undefined") {
							html += data['legal_references'][index]['body'];
						}
						html += '</li>';
					}
					$temp.append(html);
					$modalBody.append($temp);
				}

				$button.removeAttr("disabled");
				$modal.modal();
			})
			.fail(function () {
				$button.removeAttr("disabled");
				$modal.modal();
			});
		});
	}
	
	/*
	 * Gestion des liens de documentations
	 */
	if ($("a#btn-add-docLink").length) {
		
		$("a#btn-add-docLink").click( function () {
			var $html = $("<div/>").html($("div#newLinkPattern").html()).contents(); //crée un obj jQuery sur base du contenu (html) de l'élément. "contents()" force la création d'un objet jquery
			$html.removeClass("hidden");
			$html.find(":disabled").prop("disabled", false);
			$html.insertBefore($(this));
			
			$("a.deleteDocLink")
				.unbind("click")
				.click( function () {
					$(this).parent(".onedocLink").remove();
				});
			
		});
		
		$("a.deleteDocLink")
				.unbind("click")
				.click( function () {
					$(this).parent(".onedocLink").remove();
		});
	}
});