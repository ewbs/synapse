<?php
use Illuminate\Database\Migrations\Migration;
class CreateAdministrationsTables extends Migration {
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		// Creation de la table des regions
		Schema::create ( 'regions', function ($table) {
			$table->increments ( 'id' )->unsigned ();
			$table->string ( 'name', 255 )->nullable ();
			$table->timestamps ();
			$table->softDeletes ();
		} );
		
		// Creation de la table des administrations
		Schema::create ( 'administrations', function ($table) {
			$table->increments ( 'id' )->unsigned ();
			$table->integer ( 'region_id' )->unsigned ()->index ();
			$table->string ( 'name', 255 )->nullable ();
			$table->timestamps ();
			$table->softDeletes ();
			$table->foreign ( 'region_id' )->references ( 'id' )->on ( 'regions' )->onDelete ( 'cascade' );
		} );
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop ( 'administrations' );
		Schema::drop ( 'regions' );
	}
}
