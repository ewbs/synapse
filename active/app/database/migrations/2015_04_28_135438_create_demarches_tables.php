<?php
use Illuminate\Database\Migrations\Migration;
class CreateDemarchesTables extends Migration {
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		// Creation de la table des demarches
		Schema::create ( 'demarches', function ($table) {
			$table->increments ( 'id' )->unsigned ();
			$table->integer ( 'user_id' )->unsigned ()->index ();
			$table->integer ( 'nostra_demarche_id' )->unsigned ()->index ();
			$table->integer ( 'mandatory' )->nullable ();
			$table->string ( 'volume', 2048 )->nullable ();
			$table->integer ( 'ewbs' )->nullable ();
			$table->integer ( 'dematerialized' )->nullable ();
			$table->decimal ( 'gain_potential', 15, 2 )->nullable ();
			$table->decimal ( 'gain_real', 15, 2 )->nullable ();
			$table->integer ( 'eform_usage' )->nullable ();
			$table->text ( 'comment' )->nullable ();
			$table->timestamps ();
			$table->softDeletes ();
			$table->foreign ( 'user_id' )->references ( 'id' )->on ( 'users' );
			$table->foreign ( 'nostra_demarche_id' )->references ( 'id' )->on ( 'nostra_demarches' );
		} );
		
		// Creation de la table pivot demarches-administrations
		Schema::create ( 'administration_demarche', function ($table) {
			$table->integer ( 'demarche_id' )->unsigned ()->index ();
			$table->integer ( 'administration_id' )->unsigned ()->index ();
			$table->foreign ( 'demarche_id' )->references ( 'id' )->on ( 'demarches' )->onDelete ( 'cascade' );
			$table->foreign ( 'administration_id' )->references ( 'id' )->on ( 'administrations' )->onDelete ( 'cascade' );
		} );
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop ( 'administration_demarche' );
		Schema::drop ( 'demarches' );
	}
}
