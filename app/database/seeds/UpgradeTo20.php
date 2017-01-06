<?php

/*
 * Ce script ne doit être appelé que lors d'un upgrade de 1.1 vers 2.0.
 * Ne l'appelez surtout pas pour monter une DB de test (les valeurs sont déjà ajoutées via PermissionsRolesSeeder.php)
 */
class UpgradeTo20 extends Seeder {
	
	public function run() {
		
		/**
		 * Permissions à insérer
		 */
		$permissions = array (
			'administrations_manage' => array ('display_name' => 'Administration : Gérer'),
			'pieces_tasks_display'   => array ('display_name' => 'Pièce / Tâche : Consulter'),
			'pieces_tasks_manage'    => array ('display_name' => 'Pièce / Tâche : Gérer'),
		);
		
		/**
		 * Rôles à insérer et lier aux permissions
		 */
		$roles=array(
			'administrations_gerer'   =>array('permissions'=> array('administrations_manage')),
			'pieces_taches_consulter' =>array('permissions'=> array('pieces_tasks_display')),
			'pieces_taches_gerer'     =>array('permissions'=> array('pieces_tasks_display', 'pieces_tasks_manage')),
		);
		
		$role_id_admin = Role::where ( 'name', '=', 'admin' )->first ()->id;
		
		
		/**
		 * Types de pieces à insérer
		 */
		$tasksTypes =	array ( 
								array ( 'for' => 'all', 'name' => 'Loi', 'description' => '', 'created_at' => new DateTime (), 'updated_at' => new DateTime ()  ),
								array ( 'for' => 'all',  'name' => 'Arrêté', 'description' => '', 'created_at' => new DateTime (), 'updated_at' => new DateTime ()  ),
								array ( 'for' => 'all',  'name' => 'Circulaire', 'description' => '', 'created_at' => new DateTime (), 'updated_at' => new DateTime ()  ),
								array ( 'for' => 'all',  'name' => 'Autre', 'description' => '', 'created_at' => new DateTime (), 'updated_at' => new DateTime ()  ) 
						);
		
		/**
		 * Pièces
		 */
		$pieces =	array (
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
		
		/**
		 * Tâches
		 */
		$tasks = array (
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
					array (
						'name' => 'Test PME',
						'description' => 'Informations permettant de déterminer si une entreprise est une PME ou une grande entreprise. VDMC considère comme hypothèse que les résultats du test PME peuvent être utilisés deux fois par an par l\'entreprise.',
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
		
		
		/**
		 * ETATS DES PIECES ET TACHES
		 */
		$statesP = array (
			
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
		
		
		$statesT = array (
			
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
				
		
		DB::beginTransaction();
		try {
			$this->insertPermissionsRoles($permissions, $roles, $role_id_admin);
			$this->insertPiecesAndTasks($tasksTypes, $pieces, $tasks, $statesP, $statesT);
			
			// Autres opérations à effectuer pour cette migration ?
			
			DB::commit();
		}
		catch(Exception $e) {
			DB::rollBack();
			$this->command->error('Erreur durant l\'exécution, rollback sur la transaction : '.$e->getMessage());
			Log::error($e);
		}
	}
	
	
	private function insertPermissionsRoles($permissions, $roles, $role_id_admin) {
		
		// -------------------------
		// Insertion des permissions
		// -------------------------
		$this->command->info("Permissions :\n----------------");
		foreach($permissions as $name=>$properties) {
			$permissions[$name]['id'] = DB::table ( 'permissions' )->where('name', $name)->pluck('id');
			if($permissions[$name]['id']) {
				$this->command->info("\tExists: {$name}");
				continue;
			}
			$properties['name']=$name;
			$permissions[$name]['id'] = DB::table ( 'permissions' )->insertGetId($properties);
			DB::table ( 'permission_role' )->insert(array('role_id' => $role_id_admin, 'permission_id' => $permissions[$name]['id']));
			$this->command->info("\tInsert: {$name} and link to role admin");
		}
		
		// -------------------
		// Insertion des rôles
		// -------------------
		$this->command->info("Roles :\n----------------");
		foreach ($roles as $name=>$properties) {
			if(DB::table ( 'roles' )->where('name', $name)->pluck('id')) {
				$this->command->info("\tExists: {$name}");
				continue;
			}
			$role=new Role();
			$role->name = $name;
			$role->save();
			$this->command->info("\tInsert: {$name}");
			foreach($properties['permissions'] as $permission) {
				DB::table ( 'permission_role' )->insert ( array('role_id' => $role->id, 'permission_id' => $permissions[$permission]['id']) );
				$this->command->info("\t\t+ link to permission {$permission}");
			}
			$properties['id'] = $role->id;
		}
		
	}
	
	
	private function insertPiecesAndTasks($tasksTypes, $pieces, $tasks, $statesP, $statesT) {
		
		$this->command->info("\nTypes de pièces et tâches ... ");
		DB::table ( 'demarchesPiecesAndTasksTypes' )->insert ( $tasksTypes );
		$this->command->info("OK!\n");
		
		$this->command->info("Pièces ... ");
		DB::table ( 'demarchesPieces' )->insert ( $pieces );
		$this->command->info("OK!\n");
		
		$this->command->info("Tâches ... ");
		DB::table ( 'demarchesTasks' )->insert ( $tasks );
		$this->command->info("OK!\n");
		
		$this->command->info("Etats des pièces et tâches ... ");
		DB::table ( 'demarchesPiecesStates' )->insert ( $statesP );
		DB::table ( 'demarchesTasksStates' )->insert ( $statesT );
		$this->command->info("OK!\n");
		
		
	}
}