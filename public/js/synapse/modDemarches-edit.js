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