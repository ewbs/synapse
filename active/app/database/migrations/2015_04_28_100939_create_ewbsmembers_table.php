<?php
use Illuminate\Database\Migrations\Migration;
class CreateEwbsmembersTable extends Migration {
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		// Create the table
		Schema::create ( 'ewbs_members', function ($table) {
			$table->increments ( 'id' )->unsigned ();
			$table->integer ( 'user_id' )->unsigned ()->index ();
			$table->foreign ( 'user_id' )->references ( 'id' )->on ( 'users' )->onDelete ( 'cascade' );
			$table->string ( 'lastname', 255 );
			$table->string ( 'firstname', 255 );
			$table->string ( 'jobtitle', 255 )->nullable ();
			$table->timestamps ();
			$table->softDeletes ();
		} );
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop ( 'ewbs_members' );
	}
}
