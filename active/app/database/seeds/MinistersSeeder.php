<?php
class MinistersSeeder extends Seeder {
	public function run() {
		$govs = array (
				array ( // 1
						'name' => 'Wallonie',
						'shortname' => 'Wallonie',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 2
						'name' => 'Fédération Wallonie-Bruxelles',
						'shortname' => 'FWB',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				) 
		);
		
		DB::table ( 'governements' )->insert ( $govs );
		
		$ministers = array (
				array ( // 1
						'firstname' => 'Christophe',
						'lastname' => 'Lacroix',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 2
						'firstname' => 'Rachid',
						'lastname' => 'Madrane',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 3
						'firstname' => 'Paul',
						'lastname' => 'Magnette',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 4
						'firstname' => 'Rudy',
						'lastname' => 'Demotte',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 5
						'firstname' => 'René',
						'lastname' => 'Collin',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 6
						'firstname' => 'Joëlle',
						'lastname' => 'Milquet',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 7
						'firstname' => 'Carlo',
						'lastname' => 'Di Antonio',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 8
						'firstname' => 'Jean-Claude',
						'lastname' => 'Marcourt',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 9
						'firstname' => 'Isabelle',
						'lastname' => 'Simonis',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 10
						'firstname' => 'Eliane',
						'lastname' => 'Tillieux',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 11
						'firstname' => 'Maxime',
						'lastname' => 'Prévot',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 12
						'firstname' => 'Paul',
						'lastname' => 'Furlan',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 13
						'firstname' => 'André',
						'lastname' => 'Flahaut',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				) 
		)
		;
		
		DB::table ( 'ministers' )->insert ( $ministers );
		//FIXME Remplacer les dates par des rangedate
		$govs = array (
				array (
						'minister_id' => 1,
						'governement_id' => 1,
						'function' => 'Ministre du Budget, de la Fonction publique et de la Simplification administrative' 
				),
				array (
						'minister_id' => 2,
						'governement_id' => 2,
						'function' => 'Ministre de l\'Aide à la jeunesse, des Maisons de justice et de la Promotion de Bruxelles' 
				),
				array (
						'minister_id' => 3,
						'governement_id' => 1,
						'function' => 'Ministre-Président de la Wallonie' 
				),
				array (
						'minister_id' => 4,
						'governement_id' => 2,
						'function' => 'Ministre-Président de la Fédération Wallonie-Bruxelles' 
				),
				array (
						'minister_id' => 5,
						'governement_id' => 1,
						'function' => 'Ministre de l\'Agriculture, de la Nature, de la Ruralité, du Tourisme, des Sports et des Infrastructures sportives' 
				),
				array (
						'minister_id' => 5,
						'governement_id' => 2,
						'function' => 'Ministre des Sports' 
				),
				array (
						'minister_id' => 6,
						'governement_id' => 2,
						'function' => 'Vice-Présidente de la Fédération Wallonie-Bruxelles, Ministre de l’Education, de la Culture et de l\'Enfance' 
				),
				array (
						'minister_id' => 7,
						'governement_id' => 1,
						'function' => 'Ministre de l\'Environnement, de l\'Aménagement du territoire, de la Mobilité et des Transports, des Aéroports et du Bien-être animal' 
				),
				array (
						'minister_id' => 8,
						'governement_id' => 1,
						'function' => 'Vice-Président et Ministre de l\'Economie, de l\'Industrie, de l\'Innovation et du Numérique' 
				),
				array (
						'minister_id' => 8,
						'governement_id' => 2,
						'function' => 'Vice-Président, Ministre de l\'Enseignement Supérieur, de la Recherche et des Médias' 
				),
				array (
						'minister_id' => 9,
						'governement_id' => 2,
						'function' => 'Ministre de l\'Enseignement de promotion sociale, de la Jeunesse, des Droits des femmes et de l\'Egalité des chances' 
				),
				array (
						'minister_id' => 10,
						'governement_id' => 1,
						'function' => 'Ministre de l\'Emploi et de la Formation' 
				),
				array (
						'minister_id' => 11,
						'governement_id' => 1,
						'function' => 'Vice-Président et Ministre des Travaux publics, de la Santé, de l\'Action sociale et du Patrimoine' 
				),
				array (
						'minister_id' => 12,
						'governement_id' => 1,
						'function' => 'Ministre des Pouvoirs locaux, de la Ville, du Logement et de l\'Energie' 
				),
				array (
						'minister_id' => 13,
						'governement_id' => 2,
						'function' => 'Ministre du Budget, de la Fonction publique et de la Simplification administrative' 
				) 
		)
		;
		
		DB::table ( 'governement_minister' )->insert ( $govs );
	}
}
