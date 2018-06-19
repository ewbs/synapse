<?php
class TaxonomySeeder extends Seeder {
	public function run() {
		
		/*
		 * ***************************************************************************************
		 * CATEGORIES DE TAGS
		 * ***************************************************************************************
		 */
		
		$items = array (
				array ( // 1
					'name' => 'Acteurs',
					'created_at' => new DateTime (), 'updated_at' => new DateTime ()
				),
				array ( // 2
					'name' => 'Pilotage et écosystème',
					'created_at' => new DateTime (), 'updated_at' => new DateTime ()
				),
				array ( // 3
					'name' => 'Compétences',
					'created_at' => new DateTime (), 'updated_at' => new DateTime ()
				),
				array ( // 4
					'name' => 'Règlementation et charges',
					'created_at' => new DateTime (), 'updated_at' => new DateTime ()
				),
				array ( // 5
					'name' => 'Processus',
					'created_at' => new DateTime (), 'updated_at' => new DateTime ()
				),
				array ( // 6
					'name' => 'Collecte et partage',
					'created_at' => new DateTime (), 'updated_at' => new DateTime ()
				),
				array ( // 7
					'name' => 'eGov',
					'created_at' => new DateTime (), 'updated_at' => new DateTime ()
				),
				array ( // 8
					'name' => 'Sécurité et VP',
					'created_at' => new DateTime (), 'updated_at' => new DateTime ()
				),
				array ( // 9
					'name' => 'Nos équipes',
					'created_at' => new DateTime (), 'updated_at' => new DateTime ()
				),
				array ( // 10
					'name' => 'Expertises',
					'created_at' => new DateTime (), 'updated_at' => new DateTime ()
				),
				array ( // 11
					'name' => 'Communication',
					'created_at' => new DateTime (), 'updated_at' => new DateTime ()
				),
				array ( // 12
					'name' => 'Démarche usager',
					'created_at' => new DateTime (), 'updated_at' => new DateTime ()
				),
		);
		
		DB::table ( 'taxonomycategories' )->insert ( $items );
		
		/*
		 * ***************************************************************************************
		 * TAGS
		 * ***************************************************************************************
		 */
		
		$items = [];

		// CATEGORIE ACTEURS
		$items[1] = [ 'Correspondants simplif\'', 'Cabinets', ];

		// CATEGORIE PILOTAGE ET ECOSYSTEME
		$items[2] = [ 'Analyse de risque', 'Approche intégrée', 'Bonnes pratiques', 'Communauté de pratique',
						'Contrat d\'administration', 'Contrat de gestion', 'Coq\'Pit', 'Déclaration politique',
						'Gestion de projets', 'Gestion des connaissance', 'Gestion du changement', 'Innovation',
						'Plan Ensemble Simplifions', 'Plan Marshall', 'Offre de services', 'Plan d\'action BCED',
						'Plan d\'action Conseil', 'Plan d\'action Production&Gestion', ];

		// CATEGORIE COMPETENCES
		$items[3] = [ 'Culture', 'Environnement et ressources naturelles', 'Fonction publique',
						'Relations internationes et Europe', 'Transfert de compétences', 'Sport', 'Action sociale santé',
						'Recherche et technologie', 'Agriculture, ruralité', 'Cartographie', 'Logement', 'Pouvoir locaux',
						'Economie', 'Transport et mobilité', 'Energie', 'Tourisme', 'Santé (6ème réforme de l\'Etat',
						'Emploi formation', ];

		// CATEGORIE REGLEMENTATION ET CHARGES
		$items[4] = [ 'Analyse et principe de confiance', 'Analyse des pièces et données', 'Analyse SCM-light',
						'Charges administratives', 'Envoi recommandé', 'Principe de confiance', 'Réglementation', ];

		// CATEGORIE PROCESSUS
		$items[5] = [ 'Processus générique acheter', 'Processus générique recouvrer', 'Processus générique réglementer',
						'Processus générique transversal', 'Optimisation processus', 'Analyse des processus', ];

		// COLLECTE ET PARTAGE
		$items[6] = [ 'Cadastre de l\'emploi non-marchand', 'Catalogue des sources authentiques',
						'Création d\'une source authentique', 'Flux de données', 'Formulaire',
						'Labellisation d\'une source authentique', 'Partage de données', 'Qualité des données',
						'Sources authentiques' ];

		// CATEGORIE eGOV
		$items[7] = [ 'ABC des démarches', 'Authentification', 'BCED-WI', 'Carte e-ID', 'Dématérialisation',
						'e-Gouvernement', 'Espace personnel', 'GED', 'Gestion des identités et des accès', 'Guichet unique',
						'Intégration back-office', 'Mon Espace (NEP)', 'Nostra', 'Synapse' ];

		// SECURITE ET VIE PRIVEE
		$items[8] = [ 'Avis de sécurité', 'Commission vie privée', 'Protection des données', 'Politique et sécurité',
						'Respect de la vie privée', 'Sécurité informatique' ];

		// NOS EQUIPES
		$items[9] = [ 'Equipe Conseil', 'Equipe Production & Gestion', 'BCED' ];

		// EXPERTISES
		$items[10] = [ 'Orientation usager', 'Charges administratives', 'Utilisation du droit', 'Optimisation des processuss',
						'Principe de confiance', 'Démarches et services en ligne', 'Partage de données', 'Sécurité et vie privée', ];

		// COMMUNICATION
		$items[11] = [ 'Catalogue de services', 'Communication', 'Evénement', 'Formation à la Simplif\'', 'Sensibilisation',
						'Stratégie de communication' ];

		// DEMARCHE USAGER
		$items[12] = [ 'Démarche administrative', 'Démarche usager', 'Enquête', 'Parcours usager', 'Scénario utilisateur' ];



		// on remet tou dans un arrat pour l'inser
		$completeItems = [];

		foreach ($items as $categorieId => $tags) {
			foreach ($tags as $tag) {
				array_push($completeItems, [
					'name' => $tag,
					'taxonomy_category_id' => $categorieId,
					'created_at' => new DateTime (),
					'updated_at' => new DateTime ()
				]);
			}
		}

		DB::table ( 'taxonomytags' )->insert ( $completeItems );

	}
}
