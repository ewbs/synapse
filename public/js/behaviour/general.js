/*
 * 
 * C'est un peu le bordel, mais je vais ranger (jda)
 * Update mgr : Mouais, depuis l'etnic alors ? Moi je m'engage pas à ranger, suis pas fou mdrrr ;)
 */

/**
 * Stocke les ids de datatables initialisés dans la page.
 * Certains datatables ajoutent à la volée des events sur des champs d'un formulaire lié au datatable via l'attribut data-useform
 * => cette variable sert à s'assurer que l'event n'est défini que lors de l'initialisation de chaque datatable.
 */
var datatablesAjax=[];

var App = function() {
	var config = {
	// pas de config /// pour l'instant !
	};
	
	/*
	 * Text Editors var textEditor = function(){ //Ckeditor
	 * $('textarea.ckeditor').ckeditor(); CKEDITOR.disableAutoInline = true;
	 * $(".inline-editable").each(function(){ CKEDITOR.inline($(this)[0]); });
	 * };//End of textEditor
	 */
	
	/**
	 * Etendre les options par défaut des datatables
	 */
	if($.fn.dataTable) {
		$.extend( true, $.fn.dataTable.defaults, {
			"bAutoWidth": false,
			"bProcessing": true,
			"fnDrawCallback": function ( oSettings ) {
				$('.dataTables_filter input').addClass('form-control').attr('placeholder','Rechercher ...');
				$('.dataTables_length select').addClass('form-control');
				$(this).width("100%"); // fix de la largeur, car léger souci après un fnDestroy
			},
			"oLanguage" : { "sUrl": "/js/jquery.datatables/french.lang" }
		});
		$('table.datatable[data-ajaxurl]').each(function(){
			initDatatableAjax($(this));
		});
	}
	
	/**
	 * Méthode permettant d'initialiser des datatables ajax (qui ont donc au moins un attribut "data-ajaxurl")
	 * 
	 * A la particularité de demander au plugin le destroy du datatable s'il existait déjà (ce qui permet donc le déclenchement d'un refresh).
	 * Permet actuellement de définir via des attributs html5 :
	 * - Si le datatable doit permettre le filtre, le tri et la pagination (data-bfilter, data-bpaginate, data-bsort)
	 *   (Précision : alors que le plugin datatables active ces 3 options par défaut, ici ils sont justement désactivés par défaut.)
	 * - Si les résultats doivent être présentés en ordre descendant de la 1e colonne (data-desc), ou ascendant si le paramètre n'est pas passé
	 * - Un formulaire à lier au datatable via l'attribut data-useform : Tous les champs de ce formulaire seront alors ajoutés aux paramètres passés à la requête ajax
	 */
	
	function initDatatableAjax(datatable) {
		datatable=datatable.dataTable({
			'bDestroy': true,
			'bFilter': (datatable.data('bfilter'))?true:false,
			'bPaginate': (datatable.data('bpaginate'))?true:false,
			'bSort': (datatable.data('bsort'))?true:false,
			"aaSorting": [ [0,datatable.data('desc')?'desc':'asc'] ],
			'sAjaxSource': datatableAjaxGetUrl(datatable),
		});
		
		if(datatablesAjax.indexOf(datatable.context.id)==-1) {
			var useform=$(datatable.data('useform'));
			if(useform.length) {
				useform.change(function(e){ // Détecter changement sur contenu du form
					// TODO : Repasser à l'occasion dans cette partie, car je n'ai pas compris pourquoi le passage par la méthode d'init ne fonctionnait pas dans ce cas, alors que le reload oui
					datatable.fnReloadAjax(datatableAjaxGetUrl(datatable));
					//initDatatableAjax(datatable);
				});
				useform.on('ifChanged', function(event){ // Specifique pour icheck, qui passe au travers du simple change
					datatable.fnReloadAjax(datatableAjaxGetUrl(datatable));
				});
			}
			datatablesAjax.push(datatable.context.id);
		}
	}
	
	/**
	 * Déterminer l'url d'un datatable Ajax
	 * 
	 * Prend en compte la présence d'un formulaire ciblé par l'attribut data-useform :
	 * S'il existe, il considère tous les champs de ce formulaire et les passe en paramètres supplémentaires à la requête ajax de base.
	 * Cela permet par ex. d'implémenter des filtres liés aux tableaux.
	 * 
	 * @param datatable
	 * @returns
	 */
	function datatableAjaxGetUrl(datatable) {
		var url=datatable.data('ajaxurl');
		var useform=$(datatable.data('useform'));
		
		if(useform.length) {
			var parameters=useform.serialize();
			if(parameters) url+=(url.indexOf('?')>-1?'&':'?')+parameters;
		}
		return url;
	}
	
	/* Sidebar */
	function toggleSideBar(_this) {
		var b = $("#sidebar-collapse")[0];
		var w = $("#cl-wrapper");

		if (w.hasClass("sb-collapsed")) {
			$(".fa", b).addClass("fa-angle-left").removeClass("fa-angle-right");
			w.removeClass("sb-collapsed");
		} else {
			$(".fa", b).removeClass("fa-angle-left").addClass("fa-angle-right");
			w.addClass("sb-collapsed");
		}
		// updateHeight();
	}
	
	function updateHeight() {
		if (!$("#cl-wrapper").hasClass("fixed-menu")) {
			var button = $("#cl-wrapper .collapse-button").outerHeight();
			var navH = $("#head-nav").height();
			// var document = $(document).height();
			var cont = $("#pcont").height();
			var sidebar = ($(window).width() > 755 && $(window).width() < 963) ? 0 : $("#cl-wrapper .menu-space .content").height();
			var height = windowH = $(window).height();
			
			if (sidebar < windowH && cont < windowH) {
				if (($(window).width() > 755 && $(window).width() < 963)) {
					height = windowH;
				} else {
					height = windowH - button - navH;
				}
			} else if ((sidebar < cont && sidebar > windowH)
					|| (sidebar < windowH && sidebar < cont)) {
				height = cont + button + navH;
			} else if (sidebar > windowH && sidebar > cont) {
				height = sidebar + button;
			}

			// var height = ($("#pcont").height() <
			// $(window).height())?$(window).height():$(document).height();
			$("#cl-wrapper .menu-space").css("min-height", height);
		} else {
			$("#cl-wrapper .nscroller").nanoScroller({
				preventPageScrolling : true
			});
		}
	}
	
	return {
		init : function(options) {
			// Etendre la config d'origine (si besoin)
			$.extend(config, options);

			/* VERTICAL MENU */
			$(".cl-vnavigation li ul").each(function() {
				$(this).parent().addClass("parent");
			});

			$(".cl-vnavigation li ul li.active").each(function() {
				$(this).parent().show().parent().addClass("open");
				// setTimeout(function(){updateHeight();},200);
			});

			$(".cl-vnavigation").delegate(".parent > a", "click", function(e) {
				$(".cl-vnavigation .parent.open > ul").not(
						$(this).parent().find("ul")).slideUp(300, 'swing', function() {
					$(this).parent().removeClass("open");
				});

				var ul = $(this).parent().find("ul");
				ul.slideToggle(300, 'swing', function() {
					var p = $(this).parent();
					if (p.hasClass("open")) {
						p.removeClass("open");
					} else {
						p.addClass("open");
					}
					// var menuH = $("#cl-wrapper .menu-space .content").height();
					// var height = ($(document).height() <
					// $(window).height())?$(window).height():menuH;
					// updateHeight();
					$("#cl-wrapper .nscroller").nanoScroller({
						preventPageScrolling : true
					});
				});
				e.preventDefault();
			});

			/* Small devices toggle */
			$(".cl-toggle").click(function(e) {
				var ul = $(".cl-vnavigation");
				ul.slideToggle(300, 'swing', function() {
				});
				e.preventDefault();
			});

			/* Collapse sidebar */
			$("#sidebar-collapse").click(function() {
				toggleSideBar();
			});

			if ($("#cl-wrapper").hasClass("fixed-menu")) {
				var scroll = $("#cl-wrapper .menu-space");
				scroll.addClass("nano nscroller");
				
				function update_height() {
					var button = $("#cl-wrapper .collapse-button");
					var collapseH = button.outerHeight();
					var navH = $("#head-nav").height();
					var height = $(window).height() - ((button.is(":visible")) ? collapseH : 0) - navH;
					scroll.css("height", height);
					$("#cl-wrapper .nscroller").nanoScroller({
						preventPageScrolling : true
					});
				}
				
				$(window).resize(function() {
					update_height();
				});
				
				update_height();
				$("#cl-wrapper .nscroller").nanoScroller({
					preventPageScrolling : true
				});
			} else {
				$(window).resize(function() {
					// updateHeight();
				});
				// updateHeight();
			}
			
			/* Pie charts (dashboard) */
			if ($("#piec_nostraPublics").length) {
				$.plot('#piec_nostraPublics', nostraPublicDistributionData, {
					series : {
						pie : {
							show : true,
							innerRadius : 0.50,
							shadow : {
								top : 5,
								left : 15,
								alpha : 0.3
							},
							stroke : {
								width : 0
							},
							label : {
								show : false
							},
							highlight : {
								opacity : 0.08
							}
						}
					},
					grid : {
						hoverable : true,
						clickable : true
					},
					colors : nostraPublicDistributionDataColors,
					legend : {
						show : false
					}
				});

				$("table td .legend").each(function() {
					var el = $(this);
					var color = el.data("color");
					el.css("background", color);
				});
			}

			/* Rendre le sélecteur :contains case-insensitive & accent-insensitive */
			jQuery.expr[':'].contains = function(a, i, m) {
				var rExps = [
					{re: /[\xC0-\xC6]/g, ch: "A"},
					{re: /[\xE0-\xE6]/g, ch: "a"},
					{re: /[\xC8-\xCB]/g, ch: "E"},
					{re: /[\xE8-\xEB]/g, ch: "e"},
					{re: /[\xCC-\xCF]/g, ch: "I"},
					{re: /[\xEC-\xEF]/g, ch: "i"},
					{re: /[\xD2-\xD6]/g, ch: "O"},
					{re: /[\xF2-\xF6]/g, ch: "o"},
					{re: /[\xD9-\xDC]/g, ch: "U"},
					{re: /[\xF9-\xFC]/g, ch: "u"},
					{re: /[\xC7-\xE7]/g, ch: "c"},
					{re: /[\xD1]/g, ch: "N"},
					{re: /[\xF1]/g, ch: "n"}
				];

				var element = $(a).text();
				var search = m[3];

				$.each(rExps, function() {
					element = element.replace(this.re, this.ch);
					search = search.replace(this.re, this.ch);
				});

				return element.toUpperCase().indexOf(search.toUpperCase()) >= 0;
			};

			/* SubMenu hover */
			var tool = $("<div id='sub-menu-nav' style='position:fixed;z-index:9999;'></div>");

			function showMenu(_this, e) {
				if (($("#cl-wrapper").hasClass("sb-collapsed") || ($(window).width() > 755 && $(window).width() < 963)) && $("ul", _this).length > 0) {
					$(_this).removeClass("ocult");
					var menu = $("ul", _this);
					if (!$(".dropdown-header", _this).length) {
						var head = '<li class="dropdown-header">' + $(_this).children().html() + "</li>";
						menu.prepend(head);
					}

					tool.appendTo("body");
					var top = ($(_this).offset().top + 8) - $(window).scrollTop();
					var left = $(_this).width();

					tool.css({
						'top' : top,
						'left' : left + 8
					});
					tool.html('<ul class="sub-menu">' + menu.html() + '</ul>');
					tool.show();

					menu.css('top', top);
				} else {
					tool.hide();
				}
			}

			$(".cl-vnavigation li").hover(
					function(e) {
						showMenu(this, e);
					},
					function(e) {
						tool.removeClass("over");
						setTimeout(function() {
							if (!tool.hasClass("over") && !$(".cl-vnavigation li:hover").length > 0) {
								tool.hide();
							}
						}, 500);
					});

			tool.hover(function(e) {
				$(this).addClass("over");
			}, function() {
				$(this).removeClass("over");
				tool.fadeOut("fast");
			});

			$(document).click(function() {
				tool.hide();
			});
			$(document).on('touchstart click', function(e) {
				tool.fadeOut("fast");
			});

			tool.click(function(e) {
				e.stopPropagation();
			});

			$(".cl-vnavigation li").click(function(e) {
				if ((($("#cl-wrapper").hasClass("sb-collapsed") || ($(window).width() > 755 && $(window).width() < 963)) && $("ul", this).length > 0) && !($(window).width() < 755)) {
					showMenu(this, e);
					e.stopPropagation();
				}
			});

			$(".cl-vnavigation li").on('touchstart click', function() {
				// alert($(window).width());
			});

			$(window).resize(function() {
				// updateHeight();
			});

			var domh = $("#pcont").height();
			$(document).bind('DOMSubtreeModified', function() {
				var h = $("#pcont").height();
				if (domh != h) {
					// updateHeight();
				}
			});

			/* Return to top */
			var offset = 220;
			var duration = 500;
			var button = $('<a href="#" class="back-to-top"><i class="fa fa-angle-up"></i></a>');
			button.appendTo("body");

			jQuery(window).scroll(function() {
				if (jQuery(this).scrollTop() > offset) {
					jQuery('.back-to-top').fadeIn(duration);
				} else {
					jQuery('.back-to-top').fadeOut(duration);
				}
			});

			jQuery('.back-to-top').click(function(event) {
				event.preventDefault();
				jQuery('html, body').animate({scrollTop : 0}, duration);
				return false;
			});

			initJsComponents($('body'));
			
			
			
			/* Modals */
			$(".modal:not(.noAuto)").modal();
			
			
			/**
			 * Liens appelant des modales entièrement générées côté serveur
			 */
			$('body').on('click', 'a.servermodal, button.servermodal', function(e) {
				e.preventDefault();
				var that=$(this);
				var url=that.attr('href');
				
				// Petit hack temporaire pour que le JS de pièces et tâches puisse passer des params en + au lien qui est présent dans l'html (il faudrait réécrire tout cela en succession de servermodal)
				var parameters=that.data('parameters');
				if(parameters) url+=(url.indexOf('?')>-1?'&':'?')+$.param(parameters);
				
				$.ajax({
					url: url
				})
				.done(function (str) {
					loadServerModal(str, that);
				})
				.error( function () {
					alert('Erreur de communication avec Synapse');
				});
			});
			
			/**
			 * Gère l'affichage de modales générées côté serveur suite à un appel ajax
			 */
			var datatablereload=false;
			function loadServerModal(responseText, from) {
				servermodal=$(responseText);
				// Placer la modale en fin du div de la partie centrale. Attention il semble que le mettre au niveau du body plutôt que dans un div + ciblé ait une importance sur les listeners d'évéments JS.
				$('body').append(servermodal);
				
				servermodal.modal({
					backdrop: 'static', // Empêcher la fermeture au clic sur le backdrop, car on veut garder la main sur le traitement à faire lorsqu'on annule !
				})
				.on('hidden.bs.modal', function (e) {
					$(this).remove(); // Supprimer complètement la modale lors de la fermeture, car on ne veut pas la garder dans le DOM
				})
				
				// Lors de la soumission d'un formulaire, l'appeler en ajax et placer le contenu retourné dans le contenu de la modale
				.on('click', '*:submit', function( e ) {
					e.preventDefault();
					var $button=$(this);
					var $form = $button.closest('form');
					
					// Sérialiser les éléments du formulaire input, textarea, select (attention d'autres types d'éléments ne sont a priori pas inclus selon la doc jquery...)
					var data = $form.serialize();
					
					// Ajouter aux paramètres la valeur du bouton de soumission s'il a un nom et une valeur (a priori pas utilisé pr l'instant, mais pourrait être utile pour repérer l'action liée au bouton)
					if($button.attr('name') && $button.attr('value') )
						data+="&"+$button.attr('name')+"="+$button.attr('value');
					
					// Ajouter aux paramètres l'ID de la modale qui déclenche l'appel ajax (pas nécessaire pr l'instant finalement ?)
					//var $modal=$form.closest('div.modal');
					//if($modal.attr('id')) data+="&fromModal="+$modal.attr('id');
					
					$.ajax({
						url: $form.attr('action'),
						type: 'POST',
						data: data,
						//TODO : Il faudrait sans doute analyser les codes de retour à la recherche d'un 301 ou 302, afin de le répercuter au niveau de la page ? (pas utilisé pr l'instant mais ça pourrait venir)
						success: function(str) {
							// Fermer la modale
							servermodal.modal('toggle');
							// Voir s'il y a un datatable à rafraichir (soit via un attribut data-reload-datatable, soit si le lien se trouvait dans un datatable)
							var datatable=null;
							var reloadDatatable=from.data('reload-datatable');
							if(reloadDatatable && reloadDatatable!=='undefined')
								datatable=$(reloadDatatable);
							else
								datatable=from.closest('table.datatable');
							if(datatable.length>0) {
								initDatatableAjax(datatable.dataTable());
								datatablereload=true;
							}
							
							// Si on a une réponse non vide, la charger en tant que modale (c'est ce qui permet de chaîner les modales, it's a kind of maagic...)
							if(str && str.length>1) { // Note : >1 est bizarre, mais il semble qu'une réponse vide ( Response::make() ) fasse malgré tout un caractère. Soit.
								loadServerModal(str, from);
							}
						}
					});
				})
				
				// Lors d'un clic sur un bouton de fermeture ou annulation, recharcher le datatable le + proche si la variable datatablereload est à true
				.on('click', 'a.close, button.close, .btn.cancel', function( e ) {
					if(datatablereload) {
						datatable=from.closest('table.datatable');
						if(datatable) {
							initDatatableAjax(datatable.dataTable());
							datatablereload=true;
						}
					}
				});
				
				// Si la modale contient elle-même un datatable, il faut l'initialiser
				servermodal.find('table.datatable[data-ajaxurl]').each(function(){
					initDatatableAjax($(this));
				});
				
				initJsComponents(servermodal);
			}
			
			
			/* Eviter qu'on ne quitte un formulaire en cours */
			var formEditing=false;
			$('div.cl-mcont form').each(function() {
				var that=$(this);
				if(that.find('input[type="text"], select, textarea').length==0) return; // Si on a au moins un champ à remplir !
				formEditing=true;
				that.submit(function( event ) {
					formEditing=false;
				});
				that.find('a.btn-cancel').click(function(e) { // Le lien Annuler est l'exception
					formEditing=false;
				});
				if (that.data('dontobserve')=="1") { formEditing = false; } //eviter d'observer certains formulaires (comme un form de recherche dans une liste
			});
			if(formEditing) {
				$(window).bind('beforeunload', function() {
					if(formEditing)
						return "Vous avez actuellement un formulaire en cours d'édition non sauvegardé.";
				});
			}
			
			function initJsComponents(top){
				/* Tooltips */
				if (config.tooltip) {
					top.tooltip({
						selector: '[data-toggle="tooltip"]'
					});
				}
				
				/* Popover */
				if (config.popover) {
					top.popover({
						selector: '[data-toggle="popover"]',
						trigger: 'hover',
						placement: 'top',
					});
				}
				
				/* NanoScroller */
				if (config.nanoScroller) {
					var nscroller=top.find(".nscroller");
					if(nscroller.length>0)
						top.find(".nscroller").nanoScroller();
				}

				/* Switch */
				if (config.bootstrapSwitch) {
					var sw=top.find(".switch");
					if(sw.length>0)
						sw.bootstrapSwitch();
				}

				/* DateTimePicker */
				// FIXME : Il faudra régler le format du datetimepicker pour avoir ce format de date "yyyy-mm-dd" (+ gérer impact sur le champ datetime utilisé dans les composants de démarches)
				top.find(".datetimepicker").each(function() {
					$.fn.datetimepicker.dates['fr']['today'] = 'Maintenant';
					$(this).datetimepicker({
						autoclose : 1,
						initialDate : new Date(),
						language : 'fr',
						todayBtn : 1,
						todayHighlight : 1,
						weekStart : 1
					});
				});
				
				/* DatePicker */
				top.find(".datepicker").each(function() {
					$(this).datetimepicker({
						autoclose : 1,
						format : 'yyyy-mm-dd',
						initialDate : new Date(),
						minView : 2,
						language : 'fr',
						startView : 2,
						todayBtn : 1,
						todayHighlight : 1,
						weekStart : 1
					});
				});
				
				/* TimePicker */
				top.find(".timepicker").each(function() {
					$.fn.datetimepicker.dates['fr']['today'] = 'Maintenant';
					$(this).datetimepicker({
						autoclose : 1,
						format : 'hh:ii',
						initialDate : new Date(),
						maxView : 1,
						minView : 0,
						language : 'fr',
						startView : 1,
						todayBtn : 1,
						todayHighlight : 1,
						weekStart : 1,
					});
				});
				
				/* Numbers */
				var integerNumber=top.find('input.integerNumber');
				if(integerNumber.length>0) {
					integerNumber.number( true, 0, ',', ' ' );
				}
				var decimalNumber=top.find('input.decimalNumber');
				if(decimalNumber.length>0) {
					decimalNumber.number( true, 2, ',', ' ');
				}
				
				/* Select2 */
				if (config.select2) {
					var select2=top.find(".select2");
					if(select2.length>0) {
						select2.select2({
							width : '100%',
							templateResult: function(item) {
								var element=$(item.element);
								var picturemargin=10;
								var picturewidth=element.data('picturewidth');
								var picture=element.data('picture');
								var line2=element.data('line2');
								var line3=element.data('line3');
								if(!line2 && !line3 && !picture) return item.text;
								
								if(picture) {
									if(!picturewidth) picturewidth=40;
									picture='<div class="picture" style="width: '+picturewidth+'px"><img src="'+picture+'" width="'+picturewidth+'"/></div>';
								}
								else {
									picture='';
									picturewidth=0;
									picturemargin=0;
								}
								
								var line1='<div class="line1'+(line2||line3?' title':'')+'">'+item.text+'</div>';
								if(line2) line2='<div class="line2">'+line2+'</div>';
								else line2='';
								if(line3) line3='<div class="line3">'+line3+'</div>';
								else line3='';
								var meta='<div class="meta" style="margin-left:'+(picturewidth+picturemargin)+'px">'+line1+line2+line3+'</div>';
								
								return '<div class="item clearfix">'+picture+meta+'</div>';
							},
							escapeMarkup: function(m) { return m; }
						});
					}
				}
				
				/* Slider */
				if (config.slider) {
					var bslider=top.find('.bslider');
					if(bslider.length>0) bslider.slider({
						tooltip : 'always'
					});
				}
				
				/* Input & Radio Buttons */
				if (jQuery().iCheck) {
					top.find('.icheck').iCheck({
						checkboxClass : 'icheckbox_square-blue checkbox',
						radioClass : 'iradio_square-blue'
					});
				}
			}
			
			
		},
		toggleSideBar : function() {
			toggleSideBar();
		},
		dataTables : function() {
			dataTables();
		}
	};
}();

$(function() {
	$("body").css({
		opacity : 1,
		'margin-left' : 0
	});
});