<?php
class PermissionsRolesSeeder extends Seeder {
	public function run() {
		
		/**
		 * Permissions à insérer
		 */
		$permissions = array (
			'manage_users'           => array ('display_name' => 'Gérer les utilisateurs'),
			'manage_roles'           => array ('display_name' => 'Gérer les rôles'),
			
			'ideas_display'          => array ('display_name' => 'Idées : Consulter'),
			'ideas_encode'           => array ('display_name' => 'Idées : Créer'),
			'ideas_manage'           => array ('display_name' => 'Idées : Gérer'),
			
			'demarches_display'      => array ('display_name' => 'Référentiel des démarches : Consulter'),
			'demarches_encode'       => array ('display_name' => 'Référentiel des démarches : Créer'),
			'demarches_manage'       => array ('display_name' => 'Référentiel des démarches : Gérer'),
			
			'damus_encode'           => array ('display_name' => 'Damus : Créer'),
			'damus_manage'           => array ('display_name' => 'Damus : Gérer'),
			
			'administrations_manage' => array ('display_name' => 'Administration : Gérer'),
			
			'pieces_tasks_display'   => array ('display_name' => 'Pièce / Tâche : Consulter'),
			'pieces_tasks_manage'    => array ('display_name' => 'Pièce / Tâche : Gérer'),
				
			'ewbsactions_display'    => array ('display_name' => 'Action : Consulter'),
			'ewbsactions_manage'     => array ('display_name' => 'Action : Gérer'),
			'ewbsaction_prioritize'  => array ('display_name' => 'Action : Prioriser'),
			
			'formslibrary_display'   => array ('display_name' => 'Catalogue des formulaires : Consulter'),
			'formslibrary_manage'    => array ('display_name' => 'Catalogue des formulaires : Gérer'),
			
			'jobs_manage'            => array ('display_name' => 'Jobs : Gérer'),

			'taxonomy_display'       => array ('display_name' => 'Taxonomie : Consulter'),
			'taxonomy_manage'        => array ('display_name' => 'Taxonomie : Gérer'),

			'servicescatalog_display'=> array ('display_name' => 'Catalogue de services : Consulter'),
			'servicescatalog_manage' => array ('display_name' => 'Catalogue de services : Gérer'),

		);
		
		/**
		 * Rôles à insérer et lier aux permissions
		 */
		$roles=array(
			'idees_consulter'         =>array('permissions'=> array('ideas_display')),
			'idees_creer'             =>array('permissions'=> array('ideas_display', 'ideas_encode')),
			'idees_gerer'             =>array('permissions'=> array('ideas_display', 'ideas_encode', 'ideas_manage')),
			
			'demarches_consulter'     =>array('permissions'=> array('demarches_display')),
			'demarches_creer'         =>array('permissions'=> array('demarches_display', 'demarches_encode')),
			'demarches_gerer'         =>array('permissions'=> array('demarches_display', 'demarches_encode', 'demarches_manage')),
			
			'damus_creer'             =>array('permissions'=> array('damus_encode')),
			'damus_gerer'             =>array('permissions'=> array('damus_encode', 'damus_manage')),
				
			'administrations_gerer'   =>array('permissions'=> array('administrations_manage')),
			
			'pieces_taches_consulter' =>array('permissions'=> array('pieces_tasks_display')),
			'pieces_taches_gerer'     =>array('permissions'=> array('pieces_tasks_display', 'pieces_tasks_manage')),
				
			'ewbsactions_consulter'   =>array('permissions'=> array('ewbsactions_display')),
			'ewbsactions_gerer'       =>array('permissions'=> array('ewbsactions_manage')),
			'ewbsactions_prioriser'   =>array('permissions'=> array('ewbsaction_prioritize')),
			
			'catalogueformulaires_consulter' =>array('permissions'=> array('formslibrary_display')),
			'catalogueformulaires_gerer'     =>array('permissions'=> array('formslibrary_manage', 'formslibrary_display')),
			
			'jobs_gerer'              =>array('permissions'=> array('jobs_manage')),

			'taxonomie_consulter'     =>array('permissions'=> array('taxonomy_display')),
			'taxonomie_gerer'         =>array('permissions'=> array('taxonomy_manage', 'taxonomy_display')),

			'services_consulter'      =>array('permissions'=> array('servicescatalog_display')),
			'services_gerer'          =>array('permissions'=> array('servicescatalog_manage', 'servicescatalog_display')),
		);
		
		
		// Rôle admin : lui associer d'office toutes les permissions
		$adminPermissions=array();
		foreach (array_keys($permissions) as $permission) $adminPermissions[]=$permission;
		$roles['admin']['permissions']=$adminPermissions;
		
		
		// --------------------------
		// Nettoyage (surtout pr test)
		// --------------------------
		DB::table('permission_role')->delete();
		DB::table('roles')->delete();
		DB::table('permissions')->delete();
		
		
		// -------------------------
		// Insertion des permissions
		// -------------------------
		$this->command->info("Permissions :\n----------------");
		foreach ($permissions as $name=>$properties) {
			$properties['name']=$name;
			$permissions[$name]['id'] = DB::table ( 'permissions' )->insertGetId( $properties );
			$this->command->info("\tInsert: {$name}");
		}
		
		
		// -------------------
		// Insertion des rôles
		// -------------------
		$this->command->info("Roles :\n----------------");
		foreach ($roles as $name=>$properties) {
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
		
		
		// ----------------
		// 2 users en admins
		// -----------------
		$adminRoleId=Role::where(array('name'=>'admin'))->first()->id;
		User::where ( 'username', '=', 'julian'   )->first()->attachRole( $adminRoleId );
		User::where ( 'username', '=', 'mgrenson' )->first()->attachRole( $adminRoleId );
		User::where ( 'username', '=', 'admin' )->first()->attachRole( $adminRoleId );
	}
}