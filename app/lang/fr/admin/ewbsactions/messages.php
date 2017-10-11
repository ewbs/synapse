<?php
return [
	'menu' => 'Log d\'actions',
	'title' => 'Actions',
	
	'does_not_exist' => 'Cette action n\'existe pas.',
	
	'manage' => [
		'success' => 'L\'action a été sauvegardée. Merci.' 
	],
	
	'delete' => [
		'subtitle' => 'Suppression d\'une action',
		'linked' => 'Cette action est liée à un ou plusieurs éléments et ne peut être supprimée.',
		'detail' => 'Vous allez supprimer l\'action <strong>:name</strong>. Cette opération est irréversible.',
		'error' => 'Une erreur inconnue est survenue et l\'action n\'a pas été supprimée. Veuillez réessayer.',
		'success' => 'L\'action a été supprimée.' 
	],
	
	'priority' => [
		'low' => 'Basse',
		'normal' => 'Normale',
		'high' => 'Haute',
		'critical' => 'Critique',
	],
	
	'restore' => [
		'subtitle' => 'Restaurer une action',
		'detail' => 'Vous allez restaurer l\'action <strong>:name</strong>',
		'error' => 'Une erreur inconnue est survenue et l\'action n\'a pas été restaurée. Veuillez réessayer.',
		'success' => 'L\'action a été restaurée.' 
	],
		
	'state' => [
		'todo' => 'A faire',
		'progress' => 'En cours',
		'done' => 'Terminé',
		'standby' => 'En standby',
		'givenup' => 'Abandonné',
	],
		
	'wording'=> [
		'todo' => ':count action à faire|:count actions à faire',
		'progress' => ':count action en cours|:count actions en cours',
		'done' => ':count action terminée|:count actions terminées',
		'standby' => ':count action en standby|:count actions en standby',
		'givenup' => ':count action abandonnée|:count actions abandonnées',
	],
		
	'listener' => [
		'parent'=> [
			'saved'=> 'Mise à jour automatique de l\'état suite à changement sur la sous-action ":subaction"',
			'restored'=> 'Restauration automatique suite à restautation de la sous-action ":subaction"',
		],
	],
];
