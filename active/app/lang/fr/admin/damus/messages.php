<?php
return [
	
	'menu' => 'Damus',
	
	'does_not_exist' => 'Cette élément de Damus n\'existe pas.',
	'not_allowed' => 'Vous ne disposez pas des autorisations nécessaires pour manipuler cet élément de Damus',
	'demarche_inuse' => 'Cette démarche est utilisée dans le Référentiel des Démarches. Il n\'est pas permis de la supprimer.',
	
	'create' => [
		'error' => 'Veuillez vérifier les infos que vous avez entré.<br/>Les erreur sont affichées dans le formulaire.',
		'baderror' => 'Une erreur inconnue est survenue. Merci de réessayer.<br/>Si le problème persiste, merci de signaler l\'erreur au support.<br/>L\'erreur reçue est :<br/>',
		'success' => 'Le nouvel élément Damus été sauvegardée. Merci.',
		'nopublics' => 'Vous devez sélectionner au moins un public cible',
		'nothematiques' => 'Vous devez sélectionner au moins une thématique',
		'noevenements' => 'Vous devez sélectionner au moins un événement déclencheur' 
	],
	
	'edit' => [
		'error' => 'Veuillez vérifier les infos que vous avez entré.<br/>Les erreur sont affichées dans le formulaire.',
		'baderror' => 'Une erreur inconnue est survenue. Merci de réessayer.<br/>Si le problème persiste, merci de signaler l\'erreur au support.<br/>L\'erreur reçue est :<br/>',
		'success' => 'L\'élément Damus a été sauvegardé. Merci.',
		'nopublics' => 'Vous devez sélectionner au moins un public cible',
		'nothematiques' => 'Vous devez sélectionner au moins une thématique',
		'noevenements' => 'Vous devez sélectionner au moins un événement déclencheur' 
	],
	
	'delete' => [
		'error' => 'Une erreur inconnue est survenue et l\'élément n\'a pas été supprimé. Veuillez réessayer.',
		'baderror' => 'Une erreur inconnue est survenue. Merci de réessayer.<br/>Si le problème persiste, merci de signaler l\'erreur au support.<br/>L\'erreur reçue est :<br/>',
		'success' => 'L\'élément a été supprimé de Damus.' 
	],
	
	'restore' => [
		'error' => 'Une erreur inconnue est survenue et l\'élément n\'a pas été restauré. Veuillez réessayer.',
		'baderror' => 'Une erreur inconnue est survenue. Merci de réessayer.<br/>Si le problème persiste, merci de signaler l\'erreur au support.<br/>L\'erreur reçue est :<br/>',
		'success' => 'L\'élément été restauré.' 
	],
	
	'request'=>[
		'demarche'=>[
			'create'=>[
				'title'=>'Demande d\'ajout d\'une démarche dans NOSTRA',
			],
			'edit'=>[
				'title'=>'Demande de correction de données d\'une démarche dans NOSTRA',
			],
		],
		'eform'=>[
			'title'=>'Demande de modification d\'un formulaire dans NOSTRA',
		],
		'idea'=>[
			'title'=>'Demande d\'ajout d\'un élément dans NOSTRA',
		],
		'mail'=>[
			'source'=>'Synapse',
			'request'=>'Description de la demande',
			'link'=>'Lien de suivi de la demande pour notifier au demandeur du traitement effectué',
		],
		'success'=>'La demande a bien été soumise à l\'équipe NOSTRA. Merci.<br/>Vous serez averti par email lorsqu\'elle sera prise en charge puis traitée.',
	],
	
	'response'=>[
		'close'=>[
			'action'=>'Clôturer la demande',
			'detail'=>'Veuillez motiver votre décision à l\'émetteur de la demande.<br/>Il sera notifié et recevra cette explication :',
			'success'=>'La demande a bien été clôturée. Merci.',
		],
		'process'=>[
			'action'=>'Notifier de la prise en charge de la demande',
			'detail'=>'Vous pouvez apporter des précisions à l\'émetteur de la demande, par exemple une indication de temps nécessaire pour traiter sa demande :',
			'success'=>'La demande a bien été renseignée comme prise en charge. Merci.',
		],
		
		'demarche'=>[
			'create'=>[
				'reasons'=>[
					'complete'=>[
						'title'=>'La demande a été traitée sans modification',
						'info'=>'L\'équipe NOSTRA a inséré la démarche et ajouté les informations fournies par l\'utilisateur Synapse sans y apporter la moindre modification ou ajout',
					],
					'partial'=>[
						'title'=>'La demande a été traitée avec modifications',
						'info'=>'L\'équipe NOSTRA a inséré la démarche mais a apporté des modifications aux informations fournies par l\'utilisateur Synapse (corrections ou autres)',
					],
					'refused'=>[
						'title'=>'La demande a été refusée',
						'info'=>'L\'équipe NOSTRA n\'accomplira pas la demande pour diverses raisons : la demande est hors périmètre, les éléments demandés existent déjà,...',
					],
				],
			],
			'edit'=>[
				'reasons'=>[
					'complete'=>[
						'title'=>'La demande a été traitée sans modification',
						'info'=>'L\'équipe NOSTRA a corrigé les données selon les informations fournies par l\'utilisateur Synapse sans y apporter la moindre modification ou ajout',
					],
					'partial'=>[
						'title'=>'La demande a été traitée avec modifications',
						'info'=>'L\'équipe NOSTRA a corrigé les données mais a apporté des modifications aux informations fournies par l\'utilisateur Synapse',
					],
					'refused'=>[
						'title'=>'La demande a été refusée',
						'info'=>'L\'équipe NOSTRA n\'accomplira pas la demande pour diverses raisons : la demande est hors périmètre, les éléments demandés existent déjà,...',
					],
				],
			],
		],
		'eform'=>[
			'reasons'=>[
				'complete'=>[
					'title'=>'La demande a été totalement traitée',
					'info'=>'Les éléments demandés ont été adaptés dans NOSTRA et seront disponibles dans Synapse',
				],
				'partial'=>[
					'title'=>'La demande a été partiellement traitée',
					'info'=>'Une partie des éléments demandés ont été adaptés dans NOSTRA et seront disponibles dans Synapse, mais certains ont été refusés, ou modifiés avant adaptation dans NOSTRA',
				],
				'refused'=>[
					'title'=>'La demande a été refusée',
					'info'=>'Aucun élément demandé n\'a été adapté dans NOSTRA',
				],
			],
		],
		'error'=>[
			'closed'=>'Cette demande est déjà clôturée.',
			'tokenmatch'=>'Vous n\'avez pas le droit d\'effectuer cette action.',
			'tokensize'=>'Le token présent dans l\'URL n\'a pas la longueur attendue.<br/>Merci de vous assurer d\'avoir considéré l\'adresse complète.',
		],
		'idea'=>[
			'reasons'=>[
				'complete'=>[
					'title'=>'La demande a été totalement traitée',
					'info'=>'Les éléments demandés ont été insérés dans NOSTRA et seront disponibles dans Synapse',
				],
				'partial'=>[
					'title'=>'La demande a été partiellement traitée',
					'info'=>'Une partie des éléments demandés ont été insérés dans NOSTRA et seront disponibles dans Synapse, mais certains ont été refusés, ou modifiés avant ajout dans NOSTRA',
				],
				'refused'=>[
					'title'=>'La demande a été refusée',
					'info'=>'Aucun élément demandé n\'a été inséré dans NOSTRA',
				],
			],
		],
		'mail'=>[
			'explanation'=>'Informations complémentaires : ',
			'link'=>'Elément lié à votre demande',
			'process'=>'Votre demande a été prise en charge par l\'équipe NOSTRA, et sera donc traitée prochainement.',
			'request'=>'Rappel de votre demande',
			'response'=>'Suivi effectué par l\'équipe NOSTRA',
		],
		'reasons'=>'Raison de clôture :',
		'subtitle'=>[
			'request'=>'Détails de la demande',
			'process'=>'Prise en charge de la demande',
			'close'=>'Clôture de la demande',
		],
	],
];
