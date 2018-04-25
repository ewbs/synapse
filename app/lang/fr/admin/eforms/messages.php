<?php
return [
	'supermenu' => 'eForms',
	'menu' => 'Formulaires',
	'title' => 'Catalogue des formulaires',
	'item' => 'Formulaire',
	
	'revisions' => 'Révisions', 
		
	'does_not_exist' => 'Ce formulaire n\'existe pas.',

	'not_linked' => 'Non lié',
	
	'manage' => [
		'success' => 'Le formulaire a été sauvegardé. Merci.<br/>Vous pouvez si vous le souhaitez créer ou modifier les actions via la fonction "Actions" disponible dans le panel de droite.' 
	],
	
	'delete' => [
		'subtitle' => 'Suppression d\'un formulaire',
		'linked' => 'Ce formulaire est lié à un ou plusieurs éléments et ne peut être supprimé.',
		'detail' => 'Vous allez supprimer le formulaire <strong>:name</strong>. Cette opération est irréversible.',
		'error' => 'Une erreur inconnue est survenue et le formulaire n\'a pas été supprimé. Veuillez réessayer.',
		'success' => 'Le formulaire a été supprimé.'
	],
	
	'restore' => [
		'subtitle' => 'Restaurer un formulaire',
		'detail' => 'Vous allez restaurer le formulaire <strong>:name</strong>',
		'error' => 'Une erreur inconnue est survenue et le formulaire n\'a pas été restauré. Veuillez réessayer.',
		'success' => 'Le formulaire a été restauré.'
	],
	
	'actions' => [
		'manage' => [
				'success' => 'L\'action a été sauvegardée. Merci.'
		],
	],

	'exceptions' => [
		'no_eform_id' => 'Aucun id de formulaire envoyé',
	]
];
