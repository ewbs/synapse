<?php
return array (
	'titles' => array (
		'activity'                      => 'Activité',
		'id'                            => 'ID',
		'name'                          => 'Nom',
		'volume'                        => 'Dossiers par an',
		'frequency'                     => 'Fréquence',
		'cost_citizen_currency'         => 'Coût unitaire usager',
		'cost_administration_currency'  => 'Coût unitaire administration',
		'gain_potential_citizen'        => 'Gain potentiel usager',
		'gain_potential_administration' => 'Gain potentiel administration',
		'gain_real_citizen'             => 'Gain effectif usager',
		'gain_real_administration'      => 'Gain effectif administration',
		'comment'                       => 'Commentaire',
	),
	
	'types' => array(
		'piece' => 'Pièce',
		'task'  => 'Tâche',
	),
	'adjustments' => 'Ajustements réalisés par l\'analyste',
	'eof'	=> '#EOF#',
	'totals'=> 'Totaux',
	
	// traitement d'un fichier uploadé
	'process' => array(
		'piece_not_found'    => 'Cette pièce n\'existe pas dans le catalogue et a été ignorée',
		'task_not_found'     => 'Cette tâche n\'existe pas dans le catalogue et a été ignoréee',
		'eof_not_found'      => 'Erreur durant le traitement du fichier : Le marqueur #EOF# n\'a pas été trouvé',
		'data_error'         => 'Donnée invalide en colonne :column : :value',
		'new_piece'          => 'Nouvelle pièce ajoutée à la démarche : {NAME}}',
		'new_task'           => 'Nouvelle tâche ajoutée à la démarche : {{NAME}}',
		'nochange_piece'     => 'La pièce <em>{{NAME}}</em> a été ignorée car elle n\'a subi aucun changement.',
		'nochange_task'      => 'La tâche <em>{{NAME}}</em> a été ignorée car elle n\'a subi aucun changement.',
		'change_piece'       => 'Modification de <em>{{CHANGENAME}}</em> pour la pièce <em>{{NAME}}</em> : {{OLDVALUE}} -> {{NEWVALUE}}',
		'change_task'        => 'Modification de <em>{{CHANGENAME}}</em> pour la tâche <em>{{NAME}}</em> : {{OLDVALUE}} -> {{NEWVALUE}}',
		'total_adjusted'     => 'Le total calculé sur le gain {{FIELD}} a été ajusté : {{CALCULATEDVALUE}} -> {{ADJUSTEDVALUE}}',
	),
);