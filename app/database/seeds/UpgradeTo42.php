<?php

/*
 * Ce script ne doit être appelé que lors d'un upgrade de 4.1 vers 4.2.
 * Ne l'appelez surtout pas pour monter une DB de test (les valeurs sont déjà ajoutées via PermissionsRolesSeeder.php)
 */
class UpgradeTo42 extends Seeder {

	public function run() {

		/**
		 * Permissions à insérer
		 */
		$permissions=[
			'ministers_manage' => array ('display_name' => 'Ministres : Gérer'),
		];
		
		$role_id_admin=Role::where ( 'name', '=', 'admin' )->first ()->id;
		DB::beginTransaction();
		try {
			$this->insertPermissionsRoles($permissions, $role_id_admin);
			DB::commit();
		}
		catch(Exception $e) {
			DB::rollBack();
			$this->command->error('Erreur durant l\'exécution, rollback sur la transaction : '.$e->getMessage());
			Log::error($e);
		}
	}
	
	private function insertPermissionsRoles($permissions, $role_id_admin) {

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
	}
}