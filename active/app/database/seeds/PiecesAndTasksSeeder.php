<?php
class PiecesAndTasksSeeder extends Seeder {
	public function run() {
		
		/*
		 * ***************************************************************************************
		 * TARIFS
		 * ***************************************************************************************
		 */
		/*$rates = array (
				array ( // 1
						'name' => 'Tarif haut',
						'hour_rate' => 61,
						'description' => 'Le tarif haut a été considéré pour les tâches à responsabilité comme par exemple la prise de connaissance du dispositif relatif au permis d\'urbanisme ou la préparation et la signature du dossier de demande.',
						'who' => 'citizen',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 2
						'name' => 'Tarif haut',
						'hour_rate' => 61,
						'description' => 'Le tarif haut a été considéré pour les tâches à responsabilité comme par exemple la prise de connaissance du dispositif relatif au permis d\'urbanisme ou la préparation et la signature du dossier de demande.',
						'who' => 'administration',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 3
						'name' => 'Tarif moyen',
						'hour_rate' => 34,
						'description' => 'Le tarif moyen a été considéré pour les autres tâches, comme par exemple l\'obtention d\'un extrait cadastral ou encore le téléchargement des formulaires à compléter.',
						'who' => 'citizen',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 4
						'name' => 'Tarif moyen',
						'hour_rate' => 34,
						'description' => 'Le tarif moyen a été considéré pour les autres tâches, comme par exemple l\'obtention d\'un extrait cadastral ou encore le téléchargement des formulaires à compléter.',
						'who' => 'administration',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 5
						'name' => 'Tarif bas',
						'hour_rate' => 29,
						'description' => 'Le tarif bas a été considéré pour les tâches purement administratives ou de secrétariat comme par exemple la réception et l\'archivage d\'un accusé de réception ou d\'une décision.',
						'who' => 'citizen',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 6
						'name' => 'Tarif bas',
						'hour_rate' => 29,
						'description' => 'Le tarif bas a été considéré pour les tâches purement administratives ou de secrétariat comme par exemple la réception et l\'archivage d\'un accusé de réception ou d\'une décision.',
						'who' => 'administration',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array ( // 7
						'name' => 'Tarif de préparation de l\'envoi de documents (citoyen)',
						'hour_rate' => 19.85,
						'description' => 'Il s\'agit du coût de rédaction du courrier accompagnant l\'envoi (postal, email, fax) et du coût du rassemblement des documents pour une mise sous pli (lorsque envoi postal) ou de téléchargement des documents (envoi par mail).',
						'who' => 'citizen',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 8
						'name' => 'Tarif de préparation de l\'envoi de documents (entreprise interne-bas&haut)',
						'hour_rate' => 33.80,
						'description' => 'Il s\'agit du coût de rédaction du courrier accompagnant l\'envoi (postal, email, fax) et du coût du rassemblement des documents pour une mise sous pli (lorsque envoi postal) ou de téléchargement des documents (envoi par mail).',
						'who' => 'citizen',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 9
						'name' => 'Tarif des activites citoyen (autre que bas)',
						'hour_rate' => 19.85,
						'description' => 'Tarif spécifique aux petites activités citoyen.',
						'who' => 'citizen',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
							
		);
		
		DB::table ( 'demarchesTasksRates' )->insert ( $rates );*/
		
		/*
		 * ***************************************************************************************
		 * TYPES
		 * ***************************************************************************************
		 */
		
		$tasksTypes = array (
				array (
						'for' => 'all', // 'task', 'piece', 'all'
						'name' => 'Loi',
						'description' => '',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array (
						'for' => 'all', // 'task', 'piece', 'all'
						'name' => 'Arrêté',
						'description' => '',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array (
						'for' => 'all', // 'task', 'piece', 'all'
						'name' => 'Circulaire',
						'description' => '',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array (
						'for' => 'all', // 'task', 'piece', 'all'
						'name' => 'Autre',
						'description' => '',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				) 
		);
		
		DB::table ( 'demarchesPiecesAndTasksTypes' )->insert ( $tasksTypes );
		
		/*
		 * ***************************************************************************************
		 * PIECES
		 * ***************************************************************************************
		 */
		
		$pieces = array (
			
				/**
				 * COUTS LIES A LA COLLECTE ET L'OBTENTION DES DOCUMENTS
				 */
			
				array (
						'name' => 'Composition de ménage',
						'description' => 'Nombre indicatif de pages : 1. Prix comprenant collecte et obtention. La préparation et l\'envoi ne sont pas comptés.',
						'cost_administration_currency' => '3.40',
						'cost_citizen_currency' => '14.90',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Avertissement extrait de rôle (AER) (citoyen) - Duplicata',
						'description' => 'Nombre indicatif de pages : 2. Prix comprenant collecte et obtention. La préparation et l\'envoi ne sont pas comptés.',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '5.00',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Extrait de matrice cadastrale (citoyen)',
						'description' => 'Nombre indicatif de pages : 1. Prix comprenant collecte et obtention. La préparation et l\'envoi ne sont pas comptés.',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '17.20',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Extrait du plan cadastral (citoyen)',
						'description' => 'Nombre indicatif de pages : 1. Prix comprenant collecte et obtention. La préparation et l\'envoi ne sont pas comptés.',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '21.70',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Participations dans d\'autres sociétés et actionnariat (Annexe de formulaire de primes)',
						'description' => 'Nombre indicatif de pages : 4. Prix comprenant collecte et obtention. La préparation et l\'envoi ne sont pas comptés.',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '20.30',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Déclaration mutifonctionnelles à la banque carrefour de la Sécurité Sociale (DMFA)',
						'description' => 'Nombre indicatif de pages : 5. Prix comprenant collecte et obtention. La préparation et l\'envoi ne sont pas comptés.',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '18.50',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Preuve du respect des législations et réglementations sociales (ONSS)',
						'description' => 'Nombre indicatif de pages : 1. Prix comprenant collecte et obtention. La préparation et l\'envoi ne sont pas comptés.',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '5.70',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Preuve du respect des législations et réglementations fiscales en matière de TVA',
						'description' => 'Nombre indicatif de pages : 1. Prix comprenant collecte et obtention. La préparation et l\'envoi ne sont pas comptés.',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '5.70',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Preuve du respect des législations et réglementations fiscales en matière de contributions directes',
						'description' => 'Nombre indicatif de pages : 1. Prix comprenant collecte et obtention. La préparation et l\'envoi ne sont pas comptés.',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '5.70',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Avertissement extrait de rôle (AER) (entreprise) - Duplicata',
						'description' => 'Nombre indicatif de pages : 2. Prix comprenant collecte et obtention. La préparation et l\'envoi ne sont pas comptés.',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '7.30',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Attestation de non-faillite',
						'description' => 'Nombre indicatif de pages : 1. Prix comprenant collecte et obtention. La préparation et l\'envoi ne sont pas comptés.',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '38.50',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Renseignements comptables basiques à énumérer (chiffre d\'affaire, total bilan et amortissement)',
						'description' => 'Prix comprenant collecte et obtention. La préparation et l\'envoi ne sont pas comptés.',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '5.70',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'bilan de l\'exercice précédent',
						'description' => 'Nombre indicatif de pages : 10. Prix comprenant collecte et obtention. La préparation et l\'envoi ne sont pas comptés.',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '10.00',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Comptes annuels',
						'description' => 'Nombre indicatif de pages : 25. Prix comprenant collecte et obtention. La préparation et l\'envoi ne sont pas comptés.',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '15.10',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Déclaration de cessation d\'activité',
						'description' => 'Nombre indicatif de pages : 2. Prix comprenant collecte et obtention. La préparation et l\'envoi ne sont pas comptés.',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '8.50',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Extrait de matrice cadastral (entreprise)',
						'description' => 'Nombre indicatif de pages : 1. Prix comprenant collecte et obtention. La préparation et l\'envoi ne sont pas comptés.',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '17.30',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Extrait du plan cadastral (entreprise)',
						'description' => 'Nombre indicatif de pages : 1. Prix comprenant collecte et obtention. La préparation et l\'envoi ne sont pas comptés.',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '22.80',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Informations pour l\'inscription Précompte immobilier et Impôt personne physique (document 173X)',
						'description' => 'Nombre indicatif de pages : 20. Prix comprenant collecte et obtention. La préparation et l\'envoi ne sont pas comptés.',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '5.70',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
			
								
		);
		
		DB::table ( 'demarchesPieces' )->insert ( $pieces );
		
		/*
		 * ***************************************************************************************
		 * TACHES
		 * ***************************************************************************************
		 */
		
		$tasks = array (
			
				/**
				 * COUT LIES A L'ENVOI DES DOCUMENTS
				 */
			
				//envoi postal normalisé
				array (
						'name' => 'Envoi postal normalisé (<=9) - normal (citoyen)',
						'description' => 'Envoi postal normalisé effectué par un citoyen',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '13.77',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (<=9) - recommandé simple (citoyen)',
						'description' => 'Envoi postal par recommandé simple effectué par un citoyen',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '18.47',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (<=9) - recommandé avec accusé (citoyen)',
						'description' => 'Envoi postal par recommandé avec accusé de réception effectué par un citoyen',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '19.67',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (<=9) - normal (PME)',
						'description' => 'Envoi postal normalisé effectué par une PME',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '14.68',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (<=9) - recommandé simple (PME)',
						'description' => 'Envoi postal par recommandé simple effectué par une PME',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '19.38',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (<=9) - recommandé avec accusé (PME)',
						'description' => 'Envoi postal par recommandé avec accusé de réception effectué par une PME',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '20.58',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (<=9) - normal (grande entreprise)',
						'description' => 'Envoi postal normalisé effectué par une grande entreprise',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '1.62',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (<=9) - recommandé simple (grande entreprise)',
						'description' => 'Envoi postal par recommandé simple effectué par une grande entreprise',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '6.32',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (<=9) - recommandé avec accusé (grande entreprise)',
						'description' => 'Envoi postal par recommandé avec accusé de réception effectué par une grande entreprise',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '7.52',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				//envoi postal non normalisé (<=16)
				array (
						'name' => 'Envoi postal non normalisé (<=16) - normal (citoyen)',
						'description' => 'Envoi postal non normalisé (16 pages au plus) effectué par un citoyen',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '14.48',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal non normalisé (<=16) - recommandé simple (citoyen)',
						'description' => 'Envoi postal de 16 pages au plus par recommandé simple effectué par un citoyen',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '19.18',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal non normalisé (<=16) - recommandé avec accusé (citoyen)',
						'description' => 'Envoi postal de 16 pages au plus par recommandé avec accusé de réception effectué par un citoyen',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '20.38',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (<=16) - normal (PME)',
						'description' => 'Envoi postal non normalisé (16 pages au plus) effectué par une PME',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '15.36',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (<=16) - recommandé simple (PME)',
						'description' => 'Envoi postal de 16 pages au plus par recommandé simple effectué par une PME',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '20.06',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (<=16) - recommandé avec accusé (PME)',
						'description' => 'Envoi postal de 16 pages au plus par recommandé avec accusé de réception effectué par une PME',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '21.26',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (<=16) - normal (grande entreprise)',
						'description' => 'Envoi postal non normalisé (16 pages au plus) effectué par une grande entreprise',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '2.26',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (<=16) - recommandé simple (grande entreprise)',
						'description' => 'Envoi postal de 16 pages au plus par recommandé simple effectué par une grande entreprise',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '6.96',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (<=16) - recommandé avec accusé (grande entreprise)',
						'description' => 'Envoi postal de 16 pages au plus par recommandé avec accusé de réception effectué par une grande entreprise',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '8.16',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				//envoi postal non normalisé (20 - 66)
				array (
						'name' => 'Envoi postal non normalisé (20-66) - normal (citoyen)',
						'description' => 'Envoi postal non normalisé (20 à 66 pages) effectué par un citoyen',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '15.19',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal non normalisé (20-66) - recommandé simple (citoyen)',
						'description' => 'Envoi postal de 20 à 66 pages par recommandé simple effectué par un citoyen',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '19.89',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal non normalisé (20-66) - recommandé avec accusé (citoyen)',
						'description' => 'Envoi postal de 20 à 66 pages par recommandé avec accusé de réception effectué par un citoyen',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '21.09',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (20-66) - normal (PME)',
						'description' => 'Envoi postal non normalisé (20 à 66 pages) effectué par une PME',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '16.05',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (20-66) - recommandé simple (PME)',
						'description' => 'Envoi postal de 20 à 66 pages par recommandé simple effectué par une PME',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '20.75',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (20-66) - recommandé avec accusé (PME)',
						'description' => 'Envoi postal de 20 à 66 pages par recommandé avec accusé de réception effectué par une PME',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '21.95',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (20-66) - normal (grande entreprise)',
						'description' => 'Envoi postal non normalisé (20 à 66 pages) effectué par une grande entreprise',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '2.89',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (20-66) - recommandé simple (grande entreprise)',
						'description' => 'Envoi postal de 20 à 66 pages par recommandé simple effectué par une grande entreprise',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '7.59',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (20-66) - recommandé avec accusé (grande entreprise)',
						'description' => 'Envoi postal de 20 à 66 pages par recommandé avec accusé de réception effectué par une grande entreprise',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '8.79',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				//envoi postal non normalisé (70 - 196)
				array (
						'name' => 'Envoi postal non normalisé (70-196) - normal (citoyen)',
						'description' => 'Envoi postal non normalisé (70 à 196 pages) effectué par un citoyen',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '16.61',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal non normalisé (70-196) - recommandé simple (citoyen)',
						'description' => 'Envoi postal de 70 à 196 pages par recommandé simple effectué par un citoyen',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '21.31',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal non normalisé (70-196) - recommandé avec accusé (citoyen)',
						'description' => 'Envoi postal de 70 à 196 pages par recommandé avec accusé de réception effectué par un citoyen',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '22.51',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (70-196) - normal (PME)',
						'description' => 'Envoi postal non normalisé (70 à 196 pages) effectué par une PME',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '17.42',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (70-196) - recommandé simple (PME)',
						'description' => 'Envoi postal de 70 à 196 pages par recommandé simple effectué par une PME',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '22.12',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (70-196) - recommandé avec accusé (PME)',
						'description' => 'Envoi postal de 70 à 196 pages par recommandé avec accusé de réception effectué par une PME',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '23.32',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (70-196) - normal (grande entreprise)',
						'description' => 'Envoi postal non normalisé (70 à 196 pages) effectué par une grande entreprise',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '4.16',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (70-196) - recommandé simple (grande entreprise)',
						'description' => 'Envoi postal de 70 à 196 pages par recommandé simple effectué par une grande entreprise',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '8.86',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (70-196) - recommandé avec accusé (grande entreprise)',
						'description' => 'Envoi postal de 70 à 196 pages par recommandé avec accusé de réception effectué par une grande entreprise',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '10.06',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				//envoi postal non normalisé (200 - 396)
				array (
						'name' => 'Envoi postal non normalisé (200-396) - normal (citoyen)',
						'description' => 'Envoi postal non normalisé (200 à 396 pages) effectué par un citoyen',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '18.03',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal non normalisé (200-396) - recommandé simple (citoyen)',
						'description' => 'Envoi postal de 200 à 396 pages par recommandé simple effectué par un citoyen',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '22.73',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal non normalisé (200-396) - recommandé avec accusé (citoyen)',
						'description' => 'Envoi postal de 200 à 396 pages par recommandé avec accusé de réception effectué par un citoyen',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '23.93',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (200-396) - normal (PME)',
						'description' => 'Envoi postal non normalisé (200 à 396 pages) effectué par une PME',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '18.79',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (200-396) - recommandé simple (PME)',
						'description' => 'Envoi postal de 200 à 396 pages par recommandé simple effectué par une PME',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '23.49',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (200-396) - recommandé avec accusé (PME)',
						'description' => 'Envoi postal de 200 à 396 pages par recommandé avec accusé de réception effectué par une PME',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '24.69',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (200-396) - normal (grande entreprise)',
						'description' => 'Envoi postal non normalisé (200 à 396 pages) effectué par une grande entreprise',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '5.43',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (200-396) - recommandé simple (grande entreprise)',
						'description' => 'Envoi postal de 200 à 396 pages par recommandé simple effectué par une grande entreprise',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '10.13',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi postal normalisé (200-396) - recommandé avec accusé (grande entreprise)',
						'description' => 'Envoi postal de 200 à 396 pages par recommandé avec accusé de réception effectué par une grande entreprise',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '11.33',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				// fax et email
				array (
						'name' => 'Envoi par fax (citoyen)',
						'description' => 'Envoi d\'un document par fax par un citoyen',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '2.15',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi par fax (PME)',
						'description' => 'Envoi d\'un document par fax par une PME',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '2.15',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi par fax (grande entreprise)',
						'description' => 'Envoi d\'un document par fax par une grande entreprise',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '2.15',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi par e-mail (citoyen)',
						'description' => 'Envoi d\'un document par e-mail par un citoyen',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '0.66',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi par e-mail (PME)',
						'description' => 'Envoi d\'un document par e-mail par une PME',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '0.66',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoi par e-mail (grande entreprise)',
						'description' => 'Envoi d\'un document par e-mail par une grande entreprise',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '0.66',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
			
				/**
				 * COUTS LIES A LA PREPARATION DE L'ENVOI
				 */
			
				array (
						'name' => 'Préparation de l\'envoi (citoyen)',
						'description' => 'Il s\'agit du coût de rédaction du courrier accompagnant l\'envoi (postal, email, fax) et du coût du rassemblement des documents pour une mise sous pli (lorsque envoi postal) ou de téléchargement des documents (envoi par mail).',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '4.96',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Préparation de l\'envoi (PME)',
						'description' => 'Il s\'agit du coût de rédaction du courrier accompagnant l\'envoi (postal, email, fax) et du coût du rassemblement des documents pour une mise sous pli (lorsque envoi postal) ou de téléchargement des documents (envoi par mail).',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '8.45',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Préparation de l\'envoi (Grandes entreprises)',
						'description' => 'Il s\'agit du coût de rédaction du courrier accompagnant l\'envoi (postal, email, fax) et du coût du rassemblement des documents pour une mise sous pli (lorsque envoi postal) ou de téléchargement des documents (envoi par mail).',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '8.45',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
			
				/**
				 * COUTS LIES A LA REALISATION DE TACHES FREQUEMMENT EFFECTUEES PAR LES USAGERS
				 */
			
				// Activités spécifiques à l'administration
				array (
						'name' => 'Encodage en back office par un agent d\'administration (5 donnes)',
						'description' => 'Encodage de 5 données par un agent dans un back office.',
						'cost_administration_currency' => '1.50',
						'cost_citizen_currency' => '0.00',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Encodage en back office par un agent d\'administration (10 donnes)',
						'description' => 'Encodage de 10 données par un agent dans un back office.',
						'cost_administration_currency' => '2.90',
						'cost_citizen_currency' => '0.00',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Formulation d\'une demande complémentaire',
						'description' => 'Cette tâche inclut la rédaction du courrier.',
						'cost_administration_currency' => '4.80',
						'cost_citizen_currency' => '0.00',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				// Activités spécifiques aux entreprises
				array (
						'name' => 'Test PME',
						'description' => 'Compléter un test PME.',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '14.20',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Remplissage d\'une fiche signalétique',
						'description' => 'Fiche reprenant les informations de base telles que nom, adresse, activité, statut juridique, renseignement comptable etc.',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '11.30',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Transmettre le numéro de compte bancaire',
						'description' => '',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '10.20',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				// Activités pouvant être réalisées par le citoyen ou l'entreprise
				array (
						'name' => 'Rédiger une lettre (citoyen)',
						'description' => '',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '4.00',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Rédiger une lettre (entreprise)',
						'description' => '',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '6.80',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Rédiger une lettre de créance (citoyen)',
						'description' => '',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '5.00',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Rédiger une lettre de créance (entreprise)',
						'description' => '',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '15.30',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Déplacement à la commune ou administration (citoyen)',
						'description' => 'Déplacement pour aller chercher une attestation ou un certificat. Ce coût inclut les petits frais liés au déplacement.',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '14.20',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Déplacement à la commune ou administration (entreprise)',
						'description' => 'Déplacement pour aller chercher une attestation ou un certificat. Ce coût inclut les petits frais liés au déplacement.',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '17.90',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Imprimer/photocopier un document (citoyen)',
						'description' => '',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '0.70',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Imprimer/photocopier un document (entreprise)',
						'description' => '',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '1.00',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoyer un document par fax (citoyen)',
						'description' => '',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '2.20',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoyer un document par fax (entreprise)',
						'description' => '',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '4.60',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoyer un document par e-mail (citoyen)',
						'description' => '',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '0.70',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Envoyer un document par e-mail (entreprise)',
						'description' => '',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '1.00',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Recevoir une lettre (citoyen)',
						'description' => '',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '0.70',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Recevoir une lettre (entreprise)',
						'description' => '',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '1.00',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Archiver une lettre (citoyen)',
						'description' => '',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '1.30',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Archiver une lettre (entreprise)',
						'description' => '',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '1.90',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Archiver un paiement (citoyen)',
						'description' => '',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '0.30',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Archiver un paiement (entreprise)',
						'description' => '',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '0.50',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Signer un document (citoyen)',
						'description' => '',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '0.70',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Signer un document (entreprise)',
						'description' => '',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '2.00',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Réponse à une demande d\'info complémentaire de l\'administration (citoyen)',
						'description' => '',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '3.90',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'name' => 'Réponse à une demande d\'info complémentaire de l\'administration (entreprise)',
						'description' => '',
						'cost_administration_currency' => '0.00',
						'cost_citizen_currency' => '6.20',
						'type_id' => 1,
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				
		);
		
		DB::table ( 'demarchesTasks' )->insert ( $tasks );
		
		
		
		/*
		 * ***************************************************************************************
		 * ETATS DES PIECES ET TACHES
		 * ***************************************************************************************
		 */
		
		$states = array (
			
				/**
				 * COUTS LIES A LA PREPARATION DE L'ENVOI
				 */
			
				array (
						'code' => 'NONDEMAT',
						'name' => 'Non dématérialisable',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'code' => 'PASDEMAT',
						'name' => 'Pas dématérialisé',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'code' => 'OL_DISPO',
						'name' => 'Disponible en ligne',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'code' => 'OL_DEPO',
						'name' => 'Déposable en ligne',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'code' => 'OL_DEPO_PR',
						'name' => 'Déposable en ligne et pré-rempli',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'code' => 'SUPPRIME',
						'name' => 'Supprimé',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				
			
		);
		
		DB::table ( 'demarchesPiecesStates' )->insert ( $states );
		
		
		
		$states = array (
			
				/**
				 * COUTS LIES A LA PREPARATION DE L'ENVOI
				 */
			
				array (
						'code' => 'AJOUTEE',
						'name' => 'Ajoutée',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'code' => 'MAINTENUE',
						'name' => 'Maintenue',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'code' => 'SIMPLIFIEE',
						'name' => 'Simplifiée',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'code' => 'ALOURDIE',
						'name' => 'Alourdie',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				array (
						'code' => 'SUPPRIMEE',
						'name' => 'Supprimée',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				), 
				
			
		);
		
		DB::table ( 'demarchesTasksStates' )->insert ( $states );
		
		
	}
}
