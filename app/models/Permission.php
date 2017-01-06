<?php
use Zizaco\Entrust\EntrustPermission;

/**
 * Permissions
 *
 * @property int            $id             (PK)
 * @property string         $name           Obligatoire, unique, maximum 255 caractères
 * @property string         $display_name   Obligatoire, unique, maximum 255 caractères
 *
 * @author jdavreux
 */
class Permission extends EntrustPermission {
	public function preparePermissionsForDisplay($permissions) {
		// Get all the available permissions
		$availablePermissions = $this->orderBy('display_name')->get()->toArray();
		
		foreach ( $permissions as &$permission ) {
			array_walk ( $availablePermissions, function (&$value) use(&$permission) {
				if ($permission->name == $value ['name']) {
					$value ['checked'] = true;
				}
			} );
		}
		return $availablePermissions;
	}
	
	/**
	 * Convert from input array to savable array.
	 * 
	 * @param
	 *        	$permissions
	 * @return array
	 */
	public function preparePermissionsForSave($permissions) {
		$availablePermissions = $this->orderBy('display_name')->get()->toArray();
		$preparedPermissions = array ();
		foreach ( $permissions as $permission => $value ) {
			// If checkbox is selected
			if ($value == '1') {
				// If permission exists
				array_walk ( $availablePermissions, function (&$value) use($permission, &$preparedPermissions) {
					if ($permission == ( int ) $value ['id']) {
						$preparedPermissions [] = $permission;
					}
				} );
			}
		}
		return $preparedPermissions;
	}
}