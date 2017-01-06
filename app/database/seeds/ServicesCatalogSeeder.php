<?php

class ServicesCatalogSeeder extends Seeder {

	public function run() {

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