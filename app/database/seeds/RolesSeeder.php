<?php
class RolesSeeder extends Seeder {
	public function run() {
		
		// role : admin
		$adminRole = new Role ();
		$adminRole->name = 'admin';
		$adminRole->save ();
		
		// roles pour le module d'idées
		$IdeasManageRole = new Role ();
		$IdeasManageRole->name = 'idees_gerer';
		$IdeasManageRole->save ();
		$IdeasCreateRole = new Role ();
		$IdeasCreateRole->name = 'idees_creer';
		$IdeasCreateRole->save ();
		$IdeasConsultRole = new Role ();
		$IdeasConsultRole->name = 'idees_consulter';
		$IdeasConsultRole->save ();
		
		// roles pour le module de référentiel des démarches
		$RefDemManageRole = new Role ();
		$RefDemManageRole->name = 'demarches_gerer';
		$RefDemManageRole->save ();
		$RefDemCreateRole = new Role ();
		$RefDemCreateRole->name = 'demarches_creer';
		$RefDemCreateRole->save ();
		$RefDemConsultRole = new Role ();
		$RefDemConsultRole->name = 'demarches_consulter';
		$RefDemConsultRole->save ();
		
		// roles pour damus
		$RefDamusManageRole = new Role ();
		$RefDamusManageRole->name = 'damus_gerer';
		$RefDamusManageRole->save ();
		$RefDamusCreateRole = new Role ();
		$RefDamusCreateRole->name = 'damus_creer';
		$RefDamusCreateRole->save ();
		
		$user = User::where ( 'username', '=', 'julian' )->first ();
		$user->attachRole ( $adminRole );
		
		$user = User::where ( 'username', '=', 'mgrenson' )->first ();
		$user->attachRole ( $adminRole );

        $user = User::where ( 'username', '=', 'admin' )->first ();
        $user->attachRole ( $adminRole );
	}
}
