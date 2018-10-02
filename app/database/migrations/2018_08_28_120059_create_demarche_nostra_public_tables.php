<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDemarcheNostraPublicTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create ( 'demarche_nostra_public', function ($table) {
			$table->integer ( 'demarche_id' )->unsigned ()->index ();
			$table->integer ( 'nostra_public_id' )->unsigned ()->index ();
			$table->foreign ( 'demarche_id' )->references ( 'id' )->on ( 'demarches' )->onDelete ( 'cascade' );
			$table->foreign ( 'nostra_public_id' )->references ( 'id' )->on ( 'nostra_publics' )->onDelete ( 'cascade' );
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop ( 'idea_nostra_public' );
	}

}