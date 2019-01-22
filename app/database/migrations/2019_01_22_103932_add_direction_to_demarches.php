<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDirectionToDemarches extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('demarches', function(Blueprint $table)
		{
			$table->string('direction')->nullable();
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
			$table->dropColumn('direction');
		});
	}

}
