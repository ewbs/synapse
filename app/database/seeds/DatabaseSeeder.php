<?php
class DatabaseSeeder extends Seeder {
	public function run() {
		Eloquent::unguard ();
		
		// PARTIE 1 : PURGE TOTALE
		
		// users
		DB::statement ( 'TRUNCATE users RESTART IDENTITY CASCADE' );
		
		// roles
		DB::statement ( 'TRUNCATE roles RESTART IDENTITY CASCADE' );
		DB::statement ( 'TRUNCATE assigned_roles RESTART IDENTITY CASCADE' );
		
		// permissions
		DB::statement ( 'TRUNCATE permissions RESTART IDENTITY CASCADE' );
		DB::statement ( 'TRUNCATE permission_role RESTART IDENTITY CASCADE' );
		
		// ewbs_members
		DB::statement ( 'TRUNCATE ewbs_members RESTART IDENTITY CASCADE' );
		
		// ministres
		DB::statement ( 'TRUNCATE governements RESTART IDENTITY CASCADE' );
		DB::statement ( 'TRUNCATE ministers RESTART IDENTITY CASCADE' );
		DB::statement ( 'TRUNCATE governement_minister RESTART IDENTITY CASCADE' );
		
		// administrations
		DB::statement ( 'TRUNCATE regions RESTART IDENTITY CASCADE' );
		DB::statement ( 'TRUNCATE administrations RESTART IDENTITY CASCADE' );
		
		// idées
		DB::statement ( 'TRUNCATE ideas RESTART IDENTITY CASCADE' );
		DB::statement ( 'TRUNCATE administration_idea CASCADE' );
		DB::statement ( 'TRUNCATE idea_minister CASCADE' );
		DB::statement ( 'TRUNCATE idea_nostra_public CASCADE' );
		//DB::statement ( 'TRUNCATE idea_nostra_thematiqueabc CASCADE' );
		//DB::statement ( 'TRUNCATE idea_nostra_thematiqueadm CASCADE' );
		//DB::statement ( 'TRUNCATE idea_nostra_evenement CASCADE' );
		DB::statement ( 'TRUNCATE idea_nostra_demarche CASCADE' );
		DB::statement ( 'TRUNCATE "ideaStateModifications" RESTART IDENTITY CASCADE' );
		DB::statement ( 'TRUNCATE "ideaStates" RESTART IDENTITY CASCADE' );
		DB::statement ( 'TRUNCATE "ideaComments" RESTART IDENTITY CASCADE' );
		
		// démarches
		DB::statement ( 'TRUNCATE demarches RESTART IDENTITY CASCADE' );
		DB::statement ( 'TRUNCATE administration_demarche CASCADE' );
		
		// pieces et taches (scm et compagnie)
		//DB::statement ( 'TRUNCATE "demarchesTasks", "demarchesPieces", "demarchesTasksRates", "demarchesPiecesAndTasksTypes" RESTART IDENTITY CASCADE' );
		DB::statement ( 'TRUNCATE "demarchesTasks", "demarchesPieces", "demarchesPiecesAndTasksTypes" RESTART IDENTITY CASCADE' );

		// taxonomy
		DB::statement ( 'TRUNCATE "taxonomycategories" RESTART IDENTITY CASCADE' ); // ca supprimera les tags et les liens en cascade

		// catalogue de services
		DB::statement ( 'TRUNCATE "ewbsservices" RESTART IDENTITY CASCADE' ); // ca supprimera les liens vers tags en cascade


		// PARTIE 2 : INSERTS
		
		$this->call ( 'UsersSeeder' );
		$this->call ( 'PermissionsRolesSeeder' );
		$this->call ( 'EWBSMembersSeeder' );
		$this->call ( 'MinistersSeeder' );
		$this->call ( 'AdministrationsSeeder' );
		$this->call ( 'IdeasSeeder' );
		$this->call ( 'DemarchesSeeder' );
		$this->call ( 'PiecesAndTasksSeeder' );
		$this->call ( 'TaxonomySeeder' );
		$this->call ( 'ServicesCatalogSeeder' );
		
	}
}