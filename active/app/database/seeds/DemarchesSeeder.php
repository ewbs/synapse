<?php
class DemarchesSeeder extends Seeder {
	public function run() {
		$ideas = array (
				array ( // 1
						'nostra_demarche_id' => 1, // aucune idée de ce que c'est :-)
						'user_id' => 1, // julian
						'mandatory' => 1,
						'volume' => 1000,
						'ewbs' => 1,
						'dematerialized' => 1,
						'gain_potential' => 1000000,
						'gain_real' => 2500,
						'eform_usage' => 3,
						'comment' => 'Cette démarche été générée automatiquement par le gestionnaire de DB de Synapse afin de tester l\'insertion de données dans la base.',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				) 
		);
		
		// DB::table('demarches')->insert( $ideas ); //ne fonctionne pas en PGSQL car il check que nostra_demarche_id existe bien :(
		
		// relation "demarche à une ou plusieurs administrations"
		
		// $ideas = array( array('demarche_id'=>1,'administration_id'=>1), array('demarche_id'=>1,'administration_id'=>54) );
		// DB::table('administration_demarche')->insert( $ideas );
	}
}
