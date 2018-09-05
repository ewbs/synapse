<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDemarcheNostraThematiquesadmTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create ( 'demarche_nostra_thematiqueadm', function ($table) {
			$table->integer ( 'demarche_id' )->unsigned ()->index ();
			$table->integer ( 'nostra_thematiqueadm_id' )->unsigned ()->index ();
			$table->foreign ( 'demarche_id' )->references ( 'id' )->on ( 'demarches' )->onDelete ( 'cascade' );
			$table->foreign ( 'nostra_thematiqueadm_id' )->references ( 'id' )->on ( 'nostra_thematiquesadm' )->onDelete ( 'cascade' );
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop ( 'demarche_nostra_thematiqueadm' );
	}

}
