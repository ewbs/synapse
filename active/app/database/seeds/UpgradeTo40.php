<?php

/*
 * Ce script ne doit être appelé que lors d'un upgrade de 3.1 vers 4.0.
 * Ne l'appelez surtout pas pour monter une DB de test (les valeurs sont déjà ajoutées via PermissionsRolesSeeder.php)
 */
class UpgradeTo40 extends Seeder {

	public function run() {

		/**
		 * Permissions à insérer
		 */
		$permissions=[
			'taxonomy_display'        => array ('display_name' => 'Tags : Consulter'),
			'taxonomy_manage'         => array ('display_name' => 'Tags : Gérer'),
			'servicescatalog_display' => array ('display_name' => 'Catalogue de services : Consulter'),
			'servicescatalog_manage'  => array ('display_name' => 'Catalogue de services : Gérer'),
			'ewbsaction_prioritize'   => array ('display_name' => 'Action : Prioriser'),
		];

		/**
		 * Rôles à insérer et lier aux permissions
		 */
		$roles=[
			'tags_consulter'          =>array('permissions'=> array('taxonomy_display')),
			'tags_gerer'              =>array('permissions'=> array('taxonomy_manage', 'taxonomy_display')),
			'services_consulter'      =>array('permissions'=> array('servicescatalog_display')),
			'services_gerer'          =>array('permissions'=> array('servicescatalog_manage', 'servicescatalog_display')),
			'ewbsactions_prioriser'   =>array('permissions'=> array('ewbsaction_prioritize')),
		];

		$role_id_admin=Role::where ( 'name', '=', 'admin' )->first ()->id;
		DB::beginTransaction();
		try {
			$this->insertPermissionsRoles($permissions, $roles, $role_id_admin);
			$this->insertTaxonomy();
			$this->insertServices();
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


	private function insertTaxonomy() {

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


	private function insertServices() {
		$items = [
			"Diagnostic de simplification administrative",
			"Analyse des pièces et des données d'une démarche administrative",
			"Accompagnement à l'analyse des pièces et des données d'une démarche administrative",
			"Estimation des gains de charges administratives",
			"Accompagnement à l'estimation des gains de charges administratives",
			"Analyse des textes juridiques en termes de simplification administrative",
			"Accompagnement à l'analyse des textes juridiques en termes de simplification administrative",
			"Simplification de formulaires",
			"Accompagnement à la simplification de formulaires",
			"Dématérialisation de formulaires",
			"Accompagnement à la dématérialisation de formulaires",
			"Accompagnement à l'intégration de données formulaire dans un Back-Office",
			"Traduction de formulaires",
			"Hébergement de formulaires",
			"Mise en ligne de formulaires",
			"Mise hors ligne de fomrulaires",
			"Fourniture d'information au sujet de l'échange de données",
			"Accompagnement à l'obtention de l'autorisation d'accéder à des données authentiques",
			"Accompagnement à l'ouverture technique d'un flux d'échange de données",
			"Accès à des données authentiques via BCED Web Interface",
			"Avis juridique dans le cadre du partage de données",
			"Analyse de risque dans le cadre du partage de données",
			"Sensibilisation des agents à la sécurité de l'information età la vie privée",
			"Support aux conseillers en sécurité",

		];

		$completeItems = [];
		foreach ($items as $item) {
			array_push($completeItems, [
				'name' => $item,
				'description' => '',
				'created_at' => new DateTime (),
				'updated_at' => new DateTime ()
			]);
		}

		DB::table ( 'ewbsservices' )->insert ( $completeItems );
	}

}