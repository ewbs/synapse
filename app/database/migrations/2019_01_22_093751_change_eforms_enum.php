<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeEformsEnum extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("ALTER TABLE eforms DROP CONSTRAINT eforms_deposable_en_ligne_check");
		DB::statement("ALTER TABLE eforms DROP CONSTRAINT eforms_disponible_en_ligne_check");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
