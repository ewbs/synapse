<?php

/*
 * Ce script ne doit être appelé que lors d'un upgrade de 2.0 vers 3.0.
 * Ne l'appelez surtout pas pour monter une DB de test (les valeurs sont déjà ajoutées via PermissionsRolesSeeder.php)
 */
class UpgradeTo30 extends Seeder {
	
	public function run() {
		
		/**
		 * Permissions à insérer
		 */
		$permissions=[
			'ewbsactions_display'   => array('display_name' => 'Action : Consulter'),
			'ewbsactions_manage'    => array('display_name' => 'Action : Gérer'),
			'formslibrary_display'  => array('display_name' => 'Catalogue des formulaires : Consulter'),
			'formslibrary_manage'   => array('display_name' => 'Catalogue des formulaires : Gérer'),
			'jobs_manage'           => array('display_name' => 'Jobs : Gérer'),
		];
		
		/**
		 * Rôles à insérer et lier aux permissions
		 */
		$roles=[
			'ewbsactions_consulter'          =>array('permissions'=> array('ewbsactions_display')),
			'ewbsactions_gerer'              =>array('permissions'=> array('ewbsactions_manage')),
			'catalogueformulaires_consulter' =>array('permissions'=> array('formslibrary_display')),
			'catalogueformulaires_gerer'     =>array('permissions'=> array('formslibrary_manage', 'formslibrary_display')),
			'jobs_gerer'                     =>array('permissions'=> array('jobs_manage')),
		];
		
		$role_id_admin=Role::where ( 'name', '=', 'admin' )->first ()->id;
		DB::beginTransaction();
		try {
			$this->insertPermissionsRoles($permissions, $roles, $role_id_admin);
			$this->nostraFormsToEforms();
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


	private function nostraFormsToEforms() {
		$this->command->info("NostraForms vers Eforms :\n---------------------");
		$countUndocumented = NostraForm::whereNotIn('id', function ($query) {
			$query->select(DB::raw('COALESCE(nostra_form_id,0)'))->from('eforms');
		})->count();
		$this->command->info($countUndocumented." formulaires à intégrer...");
		$nostraForms = NostraForm::whereNotIn('id', function ($query) {
			$query->select(DB::raw('COALESCE(nostra_form_id,0)'))->from('eforms');
		})->get();
		foreach($nostraForms as $nostraForm) {
			/* on crée un nouveau formulaire */
			$eform = new Eform();
			$eform->nostra_form_id = $nostraForm->id;
			$eform->save();
		}
		$this->command->info("Intégration des formulaires terminée.");
	}
}