<?php
class EWBSMembersSeeder extends Seeder {
	public function run() {
		$users = array (
				array ( // julian
						'user_id' => '1',
						'lastname' => 'Davreux',
						'firstname' => 'Julian',
						'jobtitle' => 'Mercenaire',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // elodie
						'user_id' => '2',
						'lastname' => 'Delvaux',
						'firstname' => 'Elodie',
						'jobtitle' => 'Réseau des correspondants Simplif\'',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // bernard
						'user_id' => '3',
						'lastname' => 'Dubuisson',
						'firstname' => 'Bernard',
						'jobtitle' => 'Directeur (Production & Gestion)',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // mgrenson
						'user_id' => '4',
						'lastname' => 'Grenson',
						'firstname' => 'Michel',
						'jobtitle' => 'Bras droit du mercenaire',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime ()
				),
		);
		
		DB::table ( 'ewbs_members' )->insert ( $users );
	}
}
