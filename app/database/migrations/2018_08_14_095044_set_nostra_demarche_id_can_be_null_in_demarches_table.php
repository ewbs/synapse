<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetNostraDemarcheIdCanBeNullInDemarchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('demarches', function(Blueprint $table)
		{
			DB::statement("ALTER TABLE demarches ALTER COLUMN nostra_demarche_id DROP NOT NULL; ");
			DB::statement("UPDATE demarches SET nostra_demarche_id = NULL WHERE nostra_demarche_id = 0");
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
			DB::statement("UPDATE demarches SET nostra_demarche_id = 0 WHERE nostra_demarche_id IS NULL");
			DB::statement("ALTER TABLE demarches ALTER COLUMN nostra_demarche_id SET NOT NULL; ");
		});
	}

}
