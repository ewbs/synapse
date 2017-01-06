<?php
use Illuminate\Database\Migrations\Migration;
class CreateMinistersTables extends Migration {
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		// Creation de la table des gouvernements
		Schema::create ( 'governements', function ($table) {
			$table->increments ( 'id' )->unsigned ();
			$table->string ( 'name', 255 )->nullable ();
			$table->string ( 'shortname', 15 )->nullable ();
			$table->timestamps ();
			$table->softDeletes ();
		} );
		
		// Creation de la table des ministres
		Schema::create ( 'ministers', function ($table) {
			$table->increments ( 'id' )->unsigned ();
			$table->string ( 'firstname', 255 )->nullable ();
			$table->string ( 'lastname', 255 )->nullable ();
			$table->timestamps ();
			$table->softDeletes ();
		} );
		
		// Creation de la table pivot ministres-gouvernements
		Schema::create ( 'governement_minister', function ($table) {
			
			$table->integer ( 'minister_id' )->unsigned ()->index ();
			$table->integer ( 'governement_id' )->unsigned ()->index ();
			$table->timestamp ( 'start' );
			$table->timestamp ( 'end' );
			$table->text ( 'function' )->nullable ();
			$table->foreign ( 'minister_id' )->references ( 'id' )->on ( 'ministers' );
			$table->foreign ( 'governement_id' )->references ( 'id' )->on ( 'governements' );
		} );
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop ( 'governement_minister' );
		Schema::drop ( 'ministers' );
		Schema::drop ( 'governements' );
	}
}
