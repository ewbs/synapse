<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdddPersonDeContactToDemarchesTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('demarches', function(Blueprint $table)
		{
			$table->string('personne_de_contact')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('demarches', function(Blueprint $table)
		{
			$table->dropColumn('personne_de_contact');
		});
	}

}
