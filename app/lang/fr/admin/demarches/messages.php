<?php
return [
	'menu' => 'Catalogue des démarches',
	'title' => 'Catalogue des démarches',
	'item' => 'Démarche',
	
	'features' => [
		'edit'=>'Documenter',
		'components'=>'Pièces et tâches',
		'downloadSCMLight'=>'Télécharger le SCM Light',
		'uploadSCMLight'=>'Envoyer un SCM Light',
	],
	
	'manage' => [
		'success' => 'La démarche a été sauvegardée. Merci.',
		'linkerror' => 'Veuillez indiquer des liens de documentation corrects',
		'nodemarche-error' => 'Vous devez sélectionner une démarche',
	],
	
	'delete' => [
		'error' => 'Une erreur inconnue est survenue et la démarche n\'a pas été supprimée. Veuillez réessayer.',
		'baderror' => 'Une erreur inconnue est survenue. Merci de réessayer.<br/>Si le problème persiste, merci de signaler l\'erreur au support.<br/>L\'erreur reçue est :<br/>',
		'success' => 'La démarche a été supprimée.',
		'pieces' => 'Supprimer la pièce',
		'tasks' => 'Supprimer la tâche',
		'actions' => 'Supprimer l\'action',
	],
	
	'export' => [
		'baderror' => 'Une erreur inconnue est survenue. Merci de réessayer.<br/>Si le problème persiste, merci de signaler l\'erreur au support.<br/>L\'erreur reçue est :<br/>' 
	],
	
	'restore' => [
		'error' => 'Une erreur inconnue est survenue et la démarche n\'a pas été restaurée. Veuillez réessayer.',
		'baderror' => 'Une erreur inconnue est survenue. Merci de réessayer.<br/>Si le problème persiste, merci de signaler l\'erreur au support.<br/>L\'erreur reçue est :<br/>',
		'success' => 'La démarche a été restaurée.' 
	],
	
	'scm' => [
		'empty' => 'Cette démarche ne contient ni pièces ni tâches.<br/>Ajoutez en pour créer une analyse SCM Light.',
		'export-error' => 'Une erreur inconnue est survenue. Merci de réessayer.<br/>Si le problème persiste, merci de signaler l\'erreur au support.<br/>L\'erreur reçue est :<br/>',
		'delete' => [
			'error' => 'Une erreur inconnue est survenue et le fichier SCM Light n\'a pas été supprimé. Veuillez réessayer.',
			'success' => 'Le fichier SCM Light a été supprimé.'
		]
	],
	'eform' => [
		'eform' => 'Formulaire',
	],
	'piece' => [
		'piece' => 'Pièce',
		'name' => 'Nom de la pièce, ou variante du nom de la pièce si celle-ci est liée plusieurs fois à cette démarche',
		'choose' => [
			'title' => 'Sélection d\'une pièce à lier à la démarche',
			'button' => 'Suivant',
		],
		'create' => [
			'title' => 'Liaison d\'une pièce à la démarche',
			'button' => 'Lier la pièce',
		],
		'edit' => [
			'title' => 'Edition de la pièce liée à la démarche',
			'name' => 'Nom de la tâche, ou variante du nom de la tâche si celle-ci est liée plusieurs fois à cette démarche',
		],
		'history' => [
			'title' => 'Historique de la pièce liée à la démarche',
		],
		'label'=> 'Choisissez une pièce dans le catalogue',
		'created_exists' => 'Une pièce liée à cette démarche a déjà une date de création identique',
		'lastrevisionpiece-error' => 'Une erreur s\'est produite lors de la tentative de récupération des données de la pièce sélectionnée. Merci de réessayer.<br/>Si le problème persiste, merci de signaler l\'erreur au support.',
	],
	'task' => [
		'task' => 'Tâche',
		'name' => 'Nom de la tâche, ou variante du nom de la tâche si celle-ci est liée plusieurs fois à cette démarche',
		'choose' => [
			'title' => 'Sélection d\'une tâche à lier à la démarche',
			'button' => 'Suivant',
		],
		'create' => [
			'title' => 'Liaison d\'une tâche à la démarche',
			'button' => 'Lier la tâche',
		],
		'edit' => [
			'title' => 'Edition de la tâche liée à la démarche',
		],
		'history' => [
			'title' => 'Historique de la tâche liée à la démarche',
		],
		'label'=> 'Choisissez une tâche dans le catalogue',
			
		'created_exists' => 'Une tâche liée à cette démarche a déjà une date de création identique',
		'lastrevisiontask-error' => 'Une erreur s\'est produite lors de la tentative de récupération des données de la tâche sélectionnée. Merci de réessayer.<br/>Si le problème persiste, merci de signaler l\'erreur au support.',
	],
	
	'idea' => [
		'modalUpdate' => [
			'title' => 'Assistant projets de simplif',
			'intro' => 'La démarche en cours est liée au projet de simplif suivant.<br/>Souhaitez-vous ajouter un commentaire et modifier éventuellement le statut de ce projet ?|La démarche en cours est liée aux projets de simplif suivants.<br/>Souhaitez-vous ajouter un commentaire et modifier éventuellement le statut de ces projets ?',
			'update' => 'Mettre à jour le projet|Mettre à jour les projets',
		],
		'modalLink' => [
			'title' => 'Liaison d\'une démarche à un projet de simplif\'',
		]
	],
	
	'action' => [
		'distributed'=>'Réparti sur :count démarche|Réparti sur :count démarches',
		'modal' => [
			'title' => 'Assistant actions',
			'intro' => [
				'create' => [
					'eform' => 'Souhaitez-vous créer une action automatiquement liée au formulaire ":name" ?',
					'piece' => 'Souhaitez-vous créer une action automatiquement liée la pièce ":name" ?',
					'task'  => 'Souhaitez-vous créer une action automatiquement liée à la tâche ":name" ?',
				],
				'update' => [
					'eform' => 'Le formulaire ":name" est lié à l\'action suivante.<br/>Souhaitez-vous mettre à jour cette action ?|Le formulaire ":name" est lié aux actions suivantes.<br/>Souhaitez-vous mettre à jour ces actions ?',
					'piece' => 'La pièce ":name" est liée à l\'action suivante.<br/>Souhaitez-vous mettre à jour cette action ?|La pièce ":name" est liée aux actions suivantes.<br/>Souhaitez-vous mettre à jour ces actions ?',
					'task'  => 'La tâche ":name" est liée à l\'action suivante.<br/>Souhaitez-vous mettre à jour cette action ?|La tâche ":name" est liée aux actions suivantes.<br/>Souhaitez-vous mettre à jour ces actions ?',
				],
			],
			'update' => 'Mettre à jour l\'action|Mettre à jour les actions',
		],
		'longtitle'=>'Actions sur les démarches',
		'title'=>'Actions',
	],
		
	'eforms' => [
		'manage' => [
			'success' => 'Le formulaire a été relié à la démarche. Merci.'
		],
	],
];
