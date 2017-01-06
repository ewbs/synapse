<?php
use Illuminate\Database\Migrations\Migration;
class CreateAclOnUsersAndAdministrations extends Migration {
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		// Creation de la table pivot users-administrations
		// On s'en servira pour stocker les éventuelles restrictions d'accès
		// Creation de la table pivot ministres-gouvernements
		Schema::create ( 'administration_user', function ($table) {
			$table->integer ( 'user_id' )->unsigned ()->index ();
			$table->integer ( 'administration_id' )->unsigned ()->index ();
			$table->foreign ( 'user_id' )->references ( 'id' )->on ( 'users' )->onDelete ( 'cascade' );
			$table->foreign ( 'administration_id' )->references ( 'id' )->on ( 'administrations' )->onDelete ( 'cascade' );
		} );
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop ( 'administration_user' );
	}
}
