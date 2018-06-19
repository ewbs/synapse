<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Output\ConsoleOutput;

class ModifyDemarcheTable40 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$output=new ConsoleOutput();
		$output->writeln("Ajout de la colonne de volume à la démarche");

		// et on ajoute la colonne "référence"
		Schema::table('demarches', function(Blueprint $table) {
			$table->enum('volume', ['< 100', '< 500', '< 1.000', '< 10.000', '> 10.000'])->nullable();
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$output=new ConsoleOutput();
		$output->writeln("Supppression de la colonne de volume à la démarche");

		Schema::table('demarches', function(Blueprint $table) {
			$table->dropColumn('volume');
		});
	}

}
