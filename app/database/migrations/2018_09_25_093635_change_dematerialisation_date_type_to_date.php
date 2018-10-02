<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDematerialisationDateTypeToDate extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('ALTER TABLE eforms DROP COLUMN dematerialisation_date;');
		Schema::table('eforms', function(Blueprint $table)
		{
			$table->date('dematerialisation_date')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('ALTER TABLE eforms DROP COLUMN dematerialisation_date;');
		Schema::table('eforms', function(Blueprint $table)
		{
			$table->string('dematerialisation_date')->nullable();
		});
	}

}
