<?php
return [
	'menu' => 'Administrations',
	'title' => 'Administrations',

	'list' => [
		'subtitle' => 'Liste des administrations',
	],
		
	'trash' => [
		'subtitle' => 'Liste des administrations supprimées',
	],
	
	'manage' => [
		'success' => 'L\'administration été sauvegardée. Merci.',
	],
	
	'delete' => [
		'subtitle' => 'Suppression d\'une administration',
		'linked' => 'Cette administration est liée à un ou plusieurs éléments',
		'detail' => 'Vous allez supprimer l\'administration <strong>:name</strong>. Cette opération est irréversible.',
		'error' => 'Une erreur inconnue est survenue et l\'administration n\'a pas été supprimée. Veuillez réessayer.',
		'success' => 'L\'administration a été supprimée.' 
	],
	
	'restore' => [
		'subtitle' => 'Restaurer une administration',
		'detail' => 'Vous allez restaurer l\'administration <strong>:name</strong>',
		'error' => 'Une erreur inconnue est survenue et l\'administration n\'a pas été restaurée. Veuillez réessayer.',
		'success' => 'L\'administration été restaurée.' 
	]
];
