<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFlagDematerialiseToEform extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eforms', function(Blueprint $table)
		{
			$table->boolean('is_dematerialise')->default(false);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('eforms', function(Blueprint $table)
		{
			$table->dropColumn('is_dematerialise');
		});
	}

}
