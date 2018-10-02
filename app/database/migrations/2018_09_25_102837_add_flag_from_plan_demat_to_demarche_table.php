<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFlagFromPlanDematToDemarcheTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('demarches', function(Blueprint $table)
		{
			$table->boolean('from_plan_demat')->default(false);
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
			$table->dropColumn('from_plan_demat');
		});
	}

}
